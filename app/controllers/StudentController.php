<?php

class StudentController extends BaseController {
	public function getStudent($student_id) {
		if($this->checkAccType(Auth::user()) != 'parent' && $this->checkAccType(Auth::user()) != 'teacher') {
			$this->layout->title = Student::find($student_id)->name;
			$this->layout->content = View::make('account.view')
				->with('accType', $this->checkAccType(Auth::user()))
				->with('page', View::make('student.view')
					->with('accType', $this->checkAccType(Auth::user()))
					->with('student_id', $student_id));
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

	public function postAddClassToSchedule($student_id) {
		$validator = Validator::make(Input::all(),
			array(
				'registered_class' => 'required'
			)
		);

		// Check if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('student-page', array('student_id' => $student_id))
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to add class to student schedule
			$class_id = Input::get('registered_class_id');

			$student_class = StudentClass::create(array(
				'registered_class_id' => $class_id,
				'student_id' => $student_id
			));

			if($student_class) {
				// Check if class was added to student schedule
				return Redirect::route('student-page', array('student_id' => $student_id))
					->with('global', 'Class added to student schedule!');
			}
		}

		return Redirect::route('student-page', array('student_id' => $student_id))
			->with('global', 'Problem adding class to student schedule.');
	}

	public function getRegisteredClassesJson() {
		$term = Input::get('term'); // jQuery UI creates term

		$registered_classes = RegisteredClass::all();
		$classes = [];
		foreach($registered_classes as $registered_class) {
			$school_class = $registered_class->school_class()->first();
			if(strpos(Str::lower($school_class->search_id), Str::lower($term)) !== false) {
				$classes[] = ['value' => $school_class->search_id . ' - Period ' . $registered_class->period, 'id' => $registered_class->id];
			}
		}

		return Response::json($classes);
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