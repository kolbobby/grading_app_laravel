<?php

use GradingApp\Libraries\ClassPeriods\ClassPeriods;
use GradingApp\Libraries\Calendar\Calendar;

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
		$class_periods = new ClassPeriods();
		$calendar = new Calendar();

		if($accType == 'sc') {
			$counselor = SchoolCounselor::where('user_id', '=', Auth::user()->id)->first();
			$data['students'] = $counselor->students;
		} else if($accType == 'parent') {
			$parent = StudentParent::where('user_id', '=', Auth::user()->id)->first();
			$data['students'] = $parent->students;
		} else if($accType == 'teacher') {
			$teacher = Teacher::where('user_id', '=', Auth::user()->id)->first();
			$data['classes'] = RegisteredClass::where('teacher_id', '=', $teacher->id)->get(); // Work around: $teacher->registered_classes wouldn't work?
		} else {
			$data['students'] = Student::all();
			$data['current_period'] = $class_periods::GetCurrentPeriod();
			$data['current_events'] = $calendar::GetDatesEvents();
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