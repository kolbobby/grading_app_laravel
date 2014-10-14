<?php

class ClassController extends BaseController {
	public function getClass($class_id) {
		if($this->checkAccType(Auth::user()) != 'parent') {
			$this->layout->title = SchoolClass::find(RegisteredClass::find($class_id)->class_id)->name;
			$this->layout->content = View::make('account.view')
				->with('accType', $this->checkAccType(Auth::user()))
				->with('page', View::make('class.view'));
		} else {
			return Redirect::route('account-page');
		}
	}

	public function checkAccType($user) {
		if($user->school_counselor()->count()) return 'sc';
		else if($user->teacher()->count()) return 'teacher';
		else if($user->parent()->count()) return 'parent';
		else return 'admin';
	}
}