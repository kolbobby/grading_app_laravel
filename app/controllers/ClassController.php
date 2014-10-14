<?php

class ClassController extends BaseController {
	public function getClass($class_id) {
		if($this->checkAccType(Auth::user()) != 'parent') {
			$this->layout->title = SchoolClass::find(RegisteredClass::find($class_id)->class_id)->name;
			$this->layout->content = View::make('account.view')
				->with('accType', $this->checkAccType(Auth::user()))
				->with('page', View::make('class.view')
					->with('class_id', $class_id)
					->with('students', $this->getStudentsInClass($class_id)));
		} else {
			return Redirect::route('account-page');
		}
	}

	public function getStudentClassGradePage($student_id, $class_id) {
		$this->layout->title = Student::find($student_id)->name . '\'s grades for ' . SchoolClass::find(RegisteredClass::find($class_id)->class_id)->name;
		$this->layout->content = View::make('account.view')
			->with('accType', $this->checkAccType(Auth::user()))
			->with('page', View::make('class.student_grades'));
	}

	public function getStudentsInClass($class_id) {
		$class = RegisteredClass::find($class_id);
		$student_classes = StudentClass::where('registered_class_id', '=', $class->id)->get();
		$students = [];

		foreach($student_classes as $student_class) {
			$students[] = $student_class->student;
		}

		return $students;
	}

	public function checkAccType($user) {
		if($user->school_counselor()->count()) return 'sc';
		else if($user->teacher()->count()) return 'teacher';
		else if($user->parent()->count()) return 'parent';
		else return 'admin';
	}
}