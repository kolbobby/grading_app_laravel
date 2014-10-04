<?php

class ProfileController extends BaseController {
	public function getAccount() {
		$this->layout->title = Auth::user()->email;
		$this->layout->content = View::make('account.view')
			->with('accType', $this->checkAccountType())
			->with('page', null)
			->with('data', $this->loadData());
	}

	public function loadData() {
		$data = [];
		$accType = $this->checkAccountType();

		if($accType == 'sc') {
			$counselor = SchoolCounselor::where('user_id', '=', Auth::user()->id)->first();
			$data['students'] = $counselor->students;
		} else if($accType == 'parent') {
			$parent = StudentParent::where('user_id', '=', Auth::user()->id)->first();
			$data['students'] = $parent->students;
		} else {
			$data['students'] = Student::all();
		}

		return $data;
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