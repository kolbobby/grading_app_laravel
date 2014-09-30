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
				// Send email
				
				
				return Redirect::route('home')
					->with('global', 'Your account has been created! We have sent you an email to activate your account!');
			}
		}
	}
}