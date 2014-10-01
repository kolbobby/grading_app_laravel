<?php

class AccountController extends BaseController {
	public function getCreate() {
		$this->layout->title = "Register";
		$this->layout->content = View::make('account.create');
	}
	public function postCreate() {
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|max:255|email|unique:users',
				'password' => 'required|min:5|max:60',
				'confirm_password' => 'required|min:5|max:60|same:password'
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
}