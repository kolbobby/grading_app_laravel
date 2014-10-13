<?php

class StudentController extends BaseController {
	public function getStudent($student_id) {
		if($this->checkAccType(Auth::user()) != 'parent' && $this->checkAccType(Auth::user()) != 'teacher') {
			$this->layout->title = Student::find($student_id)->name;
			$this->layout->content = View::make('account.view')
				->with('accType', $this->checkAccType(Auth::user()))
				->with('page', View::make('student.view')
					->with('accType', $this->checkAccType(Auth::user()))
					->with('student_id', $student_id)
					->with('classes', $this->getStudentClasses($student_id)));
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

			$class = StudentClass::where('student_id', '=', $student_id)->where('registered_class_id', '=', $class_id)->first();

			// Check if class is already on student's schedule
			if($class) {
				// If so, send message
				return Redirect::route('student-page', array('student_id' => $student_id))
					->with('global', 'Class is already on student\'s schedule.');
			} else {
				// If not, create student class

				$create_class = true;
				$registered_class_period = RegisteredClass::where('id', '=', $class_id)->first()->period;
				$student_classes = StudentClass::where('student_id', '=', $student_id)->get();

				foreach($student_classes as $stu_class) {
					if($stu_class->registered_class()->first()->period == $registered_class_period) $create_class = false;
				}

				// Check if period is already taken
				if($create_class) {
					// If not, create student class
					$student_class = StudentClass::create(array(
						'registered_class_id' => $class_id,
						'student_id' => $student_id
					));

					if($student_class) {
						// Check if class was added to student schedule
						return Redirect::route('student-page', array('student_id' => $student_id))
							->with('global', 'Class added to student schedule!');
					}
				} else {
					// If so, send back message
					return Redirect::route('student-page', array('student_id' => $student_id))
						->with('global', 'Student already has class scheduled for this period.');
				}
			}
		}

		return Redirect::route('student-page', array('student_id' => $student_id))
			->with('global', 'Problem adding class to student schedule.');
	}

	public function getStudentClasses($student_id) {
		$student_classes = StudentClass::where('student_id', '=', $student_id)->get();
		$classes = [];

		foreach($student_classes as $class) {
			$classes[] = $class->registered_class()->first()->school_class()->first()->name;
		}

		return $classes;
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