<?php

class ProfileController extends BaseController {
	public function getAccount() {
		$this->layout->title = Auth::user()->email;
		$this->layout->content = View::make('account.view')
			->with('page', $this->loadDynPage());
	}

	public function loadDynPage() {
		$accType = $this->checkAccountType();

		if($accType == 'sc') {
			return View::make('school_counselor.home');
		} else if($accType == 'teacher') {
			return View::make('teacher.home');
		} else if($accType == 'parent') {
			return View::make('parent.home');
		} else {
			return View::make('admin.home');
		}
	}

	public function checkAccountType() {
		$user = Auth::user();

		if($user->school_counselor()->count()) {
			return 'sc';
		} else if($user->teacher()->count()) {
			return 'teacher';
		} else if($user->parent()->count()) {
			return 'parent';
		} else {
			return 'admin';
		}
	}
}