<?php

class AdminController extends BaseController {
	private $accType = 'admin';

	public function getReserveEmails() {
		$this->layout->title = "Reserve Emails";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('admin.reserve'));
	}
}