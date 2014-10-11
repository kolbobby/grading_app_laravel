<?php

class StudentController extends BaseController {
	public function getStudent($student_id) {
		if($this->checkAccType(Auth::user()) != 'parent' && $this->checkAccType(Auth::user()) != 'teacher') {
			$this->layout->title = Student::find($student_id)->name;
			$this->layout->content = View::make('account.view')
				->with('accType', $this->checkAccType(Auth::user()))
				->with('page', View::make('student.view'));
		} else {
			if($this->checkAccType(Auth::user()) != 'teacher' && $this->checkValidParent(Auth::user(), $student_id)) {
				$this->layout->title = Student::find($student_id)->name;
				$this->layout->content = View::make('account.view')
					->with('accType', $this->checkAccType(Auth::user()))
					->with('page', View::make('student.view'));
			} else {
				return Redirect::route('account-page');
			}
		}
	}

	public function checkAccType($user) {
		if($user->school_counselor()->count()) return 'sc';
		else if($user->teacher()->count()) return 'teacher';
		else if($user->parent()->count()) return 'parent';
		else return 'admin';
	}

	public function checkValidParent($user, $student_id) {
		$parent = $user->parent;
		$students = $parent->students;

		foreach($students as $student) {
			if($student->id == $student_id) return true;
		}

		return false;
	}
}