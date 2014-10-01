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
			$password = Input::get('password');

			// Activation code
			$code = str_random(60);

			// Create user
			$user = User::create(array(
				'email' => $email,
				'password' => Hash::make($password),
				'code' => $code,
				'active' => 0
			));

			// Check if user is successfully created
			if($user) {
				Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code), 'email' => $email), function($message) use ($user) {
					$message->to($user->email, $user->email)->subject('Activate Account');
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
}