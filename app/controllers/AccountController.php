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

		if($validator->fails()) {
			return Redirect::route('account-sign-in')
				->withErrors($validator)
				->withInput();
		} else {
			$remember = (Input::has('remember')) ? true : false;

			$auth = Auth::attempt(array(
				'email' => Input::get('email'),
				'password' => Input::get('password'),
				'active' => 1
			), $remember);

			if($auth) {
				return Redirect::intended('/');
			} else {
				return Redirect::route('account-sign-in')
					->with('global', 'Email/Password wrong, or account not activatd.');
			}
		}

		return Redirect::route('account-sign-in')
			->with('global', 'There was a problem signing you in.');
	}
	public function getSignOut() {
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

		if($validator->fails()) {
			return Redirect::route('account-create')
				->withErrors($validator)
				->withInput();
		} else {
			$email = Input::get('email');
			$name = Input::get('name');
			$password = Input::get('password');

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
				Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code), 'name' => $name), function($message) use ($user) {
					$message->to($user->email, $user->name)->subject('Activate Account');
				});
				
				return Redirect::route('home')
					->with('global', 'Your account has been created! We have sent you an email to activate your account!');
			}
		}
	}

	public function getActivate($code) {
		$user = User::where('code', '=', $code)->where('active', '=', 0);

		if($user->count()) {
			$user = $user->first();

			// Update user to active state and clear code
			$user->active = 1;
			$user->code = '';

			if($user->save()) {
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

		if($validator->fails()) {
			return Redirect::route('account-change-password')
				->withErrors($validator)
				->withInput();
		} else {
			$user = User::find(Auth::user()->id);

			$old_password = Input::get('old_password');
			$new_password = Input::get('new_password');

			if(Hash::check($old_password, $user->getAuthPassword())) {
				$user->password = Hash::make($new_password);
				
				if($user->save()) {
					return Redirect::route('home')
						->with('global', 'Your password has been changed!');
				}
			} else {
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

		if($validator->fails()) {
			return Redirect::route('account-forgot-password')
				->withErrors($validator)
				->withInput();
		} else {
			$user = User::where('email', '=', Input::get('email'));

			if($user->count()) {
				$user = $user->first();

				// Generate a new code and password
				$code = str_random(60);
				$password = str_random(10);

				$user->code = $code;
				$user->password_temp = Hash::make($password);

				if($user->save()) {
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
		$user = User::where('code', '=', $code)->where('password_temp', '!=', '');

		if($user->count()) {
			$user = $user->first();

			$user->password = $user->password_temp;
			$user->password_temp = '';
			$user->code = '';

			if($user->save()) {
				return Redirect::route('home')
					->with('global', 'Your account has been recovered, and you can use your new password to sign in. Please change your password once you sign in.');
			}
		}

		return Redirect::route('home')
			->with('global', 'Could not recover your account.');
	}
}