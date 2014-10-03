<?php

class AdminController extends BaseController {
	private $accType = 'admin';

	public function getReserveEmail() {
		$this->layout->title = "Reserve Email";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('admin.reserve'));
	}
	public function postReserveEmail() {
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|email',
				'type' => 'required'
			)
		);

		// Checks if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('admin-reserve-email')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to reserve email
			$email = Input::get('email');
			$type = Input::get('type');

			if($this->reserved($email)) {
				return Redirect::route('admin-reserve-email')
					->with('global', 'Email already reserved.');
			} else {
				$reserved = Reserved::create(array(
					'email' => $email,
					'type' => $type
				));

				// Check if reserved email is successfully created
				if($reserved) {
					return Redirect::route('admin-reserve-email')
						->with('global', 'Reserved email has been added!');
				}
			}

			return Redirect::route('admin-reserve-email')
				->with('global', 'Problem adding reserved email.');
		}
	}

	public function reserved($email) {
		// Looks for reserved email (needed in order to create account)
		$user = Reserved::where('email', '=', $email);

		if($user->count()) {
			return true;
		}

		return false;
	}
}