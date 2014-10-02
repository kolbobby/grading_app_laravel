<?php

class ProfileController extends BaseController {
	public function getAccount() {
		$this->layout->title = Auth::user()->email;
		$this->layout->content = View::make('account.view');
	}
}