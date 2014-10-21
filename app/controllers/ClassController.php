<?php

class ClassController extends BaseController {
	public function getClass($class_id) {
		if($this->checkAccType(Auth::user()) != 'parent') {
			$this->layout->title = SchoolClass::find(RegisteredClass::find($class_id)->class_id)->name;
			$this->layout->content = View::make('account.view')
				->with('accType', $this->checkAccType(Auth::user()))
				->with('page', View::make('class.view')
					->with('class_id', $class_id)
					->with('students', $this->getStudentsInClass($class_id))
					->with('assignments', $this->getAssignmentsInClass($class_id)));
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

	public function getAddAssignment() {
		$this->layout->title = "Add Assignment";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->checkAccType(Auth::user()))
			->with('page', View::make('class.add_assignment'));
	}
	public function postAddAssignment($class_id) {
		$validator = Validator::make(Input::all(),
			array(
				'name' => 'required',
				'type' => 'required',
				'points' => 'required'
			)
		);

		// Checks if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('class-add-assignment')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to add assignment
			$name = Input::get('name');
			$type = Input::get('type');
			$points = Input::get('points');

			$assignment = Assignment::create(array(
				'registered_class_id' => $class_id,
				'name' => $name,
				'type' => $type,
				'points' => $points
			));

			if($assignment) {
				// If assignment is created, return to class page
				return Redirect::route('class-page', array('class_id' => $class_id))
					->with('global', 'Assignment added!');
			}
		}
	}

	public function getRegisteredClassesJson() {
		$term = Input::get('term'); // jQuery UI creates term

		$registered_classes = RegisteredClass::all();
		$classes = [];
		foreach($registered_classes as $registered_class) {
			$school_class = $registered_class->school_class()->first();
			if(strpos(Str::lower($school_class->search_id), Str::lower($term)) !== false) {
				$classes[] = ['value' => $school_class->search_id, 'id' => $registered_class->id];
			}
		}

		return Response::json($classes);
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

	public function getAssignmentsInClass($class_id) {
		$class = RegisteredClass::find($class_id);
		$class_assignments = Assignment::where('registered_class_id', '=', $class->id)->get();
		$assignments = [];

		foreach($class_assignments as $assignment) {
			$assignments[] = $assignment->name;
		}

		return $assignments;
	}

	public function checkValidClass($search) {
		$class = SchoolClass::where('search_id', '=', $search)->first();

		if($class) {
			return true;
		}

		return false;
	}

	public function checkAccType($user) {
		if($user->school_counselor()->count()) return 'sc';
		else if($user->teacher()->count()) return 'teacher';
		else if($user->parent()->count()) return 'parent';
		else return 'admin';
	}
}