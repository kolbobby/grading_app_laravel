<?php

class AccountController extends BaseController {
	public function getSignIn() {
		$this->layout->title = "Sign In";
		$this->layout->content = View::make('account.signin');
	}
	public function postSignIn() {
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|email',
				'password' => 'required'
			)
		);

		// Checks if the above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('account-sign-in')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to sign in
			$remember = (Input::has('remember')) ? true : false;

			$auth = Auth::attempt(array(
				'email' => Input::get('email'),
				'password' => Input::get('password'),
				'active' => 1
			), $remember);

			// Checks if a user exists with above credentials
			if($auth) {
				// If passes, sign user in, and redirect to home page
				return Redirect::intended('/');
			} else {
				// If not, redirect with error
				return Redirect::route('account-sign-in')
					->with('global', 'Email/Password wrong, or account not activatd.');
			}
		}

		// Redirect with error
		return Redirect::route('account-sign-in')
			->with('global', 'There was a problem signing you in.');
	}
	public function getSignOut() {
		// Signs user out
		Auth::logout();
		return Redirect::route('home');
	}

	public function getCreate() {
		$this->layout->title = "Register";
		$this->layout->content = View::make('account.create');
	}
	public function postCreate() {
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|max:255|email|unique:users',
				'name' => 'required',
				'password' => 'required|min:5|max:60',
				'confirm_password' => 'required|same:password'
			)
		);

		// Checks if the above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('account-create')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to create account
			$email = Input::get('email');
			$name = Input::get('name');
			$password = Input::get('password');

			if($this->reserved($email)) {
				// Activation code
				$code = str_random(60);

				// Create user
				$user = User::create(array(
					'email' => $email,
					'name' => $name,
					'password' => Hash::make($password),
					'code' => $code,
					'active' => 0
				));

				// Check if user is successfully created
				if($user) {
					if($this->createReservedType($user->email)) {
						// Send email with activation link
						Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code), 'name' => $name), function($message) use ($user) {
							$message->to($user->email, $user->name)->subject('Activate Account');
						});
						
						// Return to home page with message stating the account has been created
						return Redirect::route('home')
							->with('global', 'Your account has been created! We have sent you an email to activate your account!');
					}

					return Redirect::route('home')
						->with('global', 'Specified account could not be created.');
				}
			}

			return Redirect::route('home')
				->with('global', 'There is no information about your email in our databases.');
		}
	}

	public function getActivate($code) {
		// Looks for user above activation code provided
		$user = User::where('code', '=', $code)->where('active', '=', 0);

		if($user->count()) {
			// If user exists, attempt to activate account
			$user = $user->first();

			// Update user to active state and clear code
			$user->active = 1;
			$user->code = '';

			if($user->save()) {
				// If account is activated, redirect to home page with success message
				return Redirect::route('home')
					->with('global', 'Activated! You can now sign in!');
			}
		}

		return Redirect::route('home')
			->with('global', 'We could not activate your account. Try again later.');
	}

	public function getChangePassword() {
		$this->layout->title = "Change Password";
		$this->layout->content = View::make('account.password');
	}
	public function postChangePassword() {
		$validator = Validator::make(Input::all(),
			array(
				'old_password' => 'required',
				'new_password' => 'required|min:5|max:60',
				'confirm_new_password' => 'required|same:new_password'
			)
		);

		// Checks if the above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('account-change-password')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to change password
			$user = User::find(Auth::user()->id);

			$old_password = Input::get('old_password');
			$new_password = Input::get('new_password');

			// Check if old password entered matches account password
			if(Hash::check($old_password, $user->getAuthPassword())) {
				// If it matches, hash new password, and save account
				$user->password = Hash::make($new_password);
				
				if($user->save()) {
					return Redirect::route('home')
						->with('global', 'Your password has been changed!');
				}
			} else {
				// If not, send back with error
				return Redirect::route('account-change-password')
					->with('global', 'Your old password is incorrect.');
			}
		}

		return Redirect::route('account-change-password')
			->with('global', 'Your password could not be changed.');
	}

	public function getForgotPassword() {
		$this->layout->title = "Forgot Password";
		$this->layout->content = View::make('account.forgot');
	}
	public function postForgotPassword() {
		$validator = Validator::make(array('email' => Input::get('email')),
			array(
				'email' => 'required|email'
			)
		);

		// Checks if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('account-forgot-password')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to retrieve forgotten password
			$user = User::where('email', '=', Input::get('email'));

			if($user->count()) {
				// If account is found, generate a new code and password
				$user = $user->first();

				// Generate a new code and password
				$code = str_random(60);
				$password = str_random(10);

				$user->code = $code;
				$user->password_temp = Hash::make($password);

				if($user->save()) {
					// Send email with new password and activation link
					Mail::send('emails.auth.forgot', array('link' => URL::route('account-recover', $code), 'email' => $user->email, 'password' => $password), function($message) use ($user) {
						$message->to($user->email, $user->email)->subject('New Password');
					});

					return Redirect::route('home')
						->with('global', 'We have sent you new password by email.');
				}
			}
		}

		return Redirect::route('account-forgot-password')
			->with('global', 'Could not request new password.');
	}

	public function getRecover($code) {
		// Attempt to recover account
		$user = User::where('code', '=', $code)->where('password_temp', '!=', '');

		if($user->count()) {
			// Set password to password_temp, clear password_temp and code after activation
			$user = $user->first();

			$user->password = $user->password_temp;
			$user->password_temp = '';
			$user->code = '';

			if($user->save()) {
				// Let user know to change password once logged in
				return Redirect::route('home')
					->with('global', 'Your account has been recovered, and you can use your new password to sign in. Please change your password once you sign in.');
			}
		}

		return Redirect::route('home')
			->with('global', 'Could not recover your account.');
	}

	public function reserved($email) {
		// Looks for reserved email (needed in order to create account)
		$user = Reserved::where('email', '=', $email);

		if($user->count()) {
			return true;
		}

		return false;
	}
	public function createReservedType($email) {
		// Creates an account based on what type it is (admin, school counselor, teacher, or parent)
		$reserved = Reserved::where('email', '=', $email)->first();

		if($reserved->type != 'admin') {
			// Makes sure account type is not admin (admins are standard users with no specific type)
			$user = User::where('email', '=', $email)->first();
			if($reserved->type == 'sc') {
				// Creates new school counselor account (adds reference to user)
				$sc = new SchoolCounselor();

				if($user->school_counselor()->save($sc)) {
					return true;
				}
			}
			if($reserved->type == 'teacher') {
				// Creates new teacher account (adds reference to user)
				$teacher = new Teacher();

				if($user->teacher()->save($teacher)) {
					return true;
				}
			}
			if($reserved->type == 'parent') {
				// Creates new parent account (adds reference to user)
				$parent = new StudentParent();

				if($user->parent()->save($parent)) {
					return true;
				}
			}

			return false;
		}

		return true;
	}
}