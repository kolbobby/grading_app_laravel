<?php

use GradingApp\Libraries\ClassPeriods\ClassPeriods;
use GradingApp\Libraries\Calendar\Calendar;

class SchoolCounselorController extends BaseController {
	private $accType = 'sc';

	public function getAddSchoolClass() {
		$this->layout->title = "Add School Class";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('school_counselor.add_school_class'));
	}
	public function postAddSchoolClass() {
		$validator = Validator::make(Input::all(),
			array(
				'search_id' => 'required|unique:school_classes',
				'name' => 'required',
				'description' => 'required'
			)
		);

		// Checks if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('sc-add-school-class')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to add school class
			$search_id = Input::get('search_id');
			$name = Input::get('name');
			$description = Input::get('description');

			$school_class = SchoolClass::create(array(
				'search_id' => $search_id,
				'name' => $name,
				'description' => $description
			));

			if($school_class) {
				// Check if school class was added
				return Redirect::route('sc-add-school-class')
					->with('global', 'School class successfully added!');
			}
		}

		return Redirect::route('sc-add-school-class')
			->with('global', 'There was a problem adding the school class.');
	}

	public function getRegisterClass() {
		$this->layout->title = "Register Class";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('school_counselor.register_class'));
	}
	public function postRegisterClass() {
		$validator = Validator::make(Input::all(),
			array(
				'search' => 'required',
				'teacher_name' => 'required',
				'period' => 'required'
			)
		);

		// Check if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('sc-register-class')
				->withErrors($validator)
				->withInput();
		} else {
			// If it does, attempt to register class
			$search_id = Input::get('search_id');
			$search = Input::get('search');
			$teacher_id = Input::get('teacher_id');
			$teacher_name = Input::get('teacher_name');
			$period = Input::get('period');

			// Check for valid class and teacher
			if($this->checkValidClass($search) && $this->checkValidTeacher($teacher_name)) {
				if($this->checkValidRegister($search_id, $teacher_id, $period)) {
					$registered_class = RegisteredClass::create(array(
						'class_id' => $search_id,
						'teacher_id' => $teacher_id,
						'period' => $period
					));

					if($registered_class) {
						// If class is registered, return to register class
						return Redirect::route('sc-register-class')
							->with('global', 'Class successfully registered!');
					}
				} else {
					return Redirect::route('sc-register-class')
						->with('global', 'This class is already registered.');
				}
			} else {
				return Redirect::route('sc-register-class')
					->with('global', 'Invalid class search id or teacher name.');
			}
		}
	}

	public function getSchoolClassesJson() {
		$term = Input::get('term'); // jQuery UI creates term

		$school_classes = SchoolClass::all();
		$classes = [];
		foreach($school_classes as $school_class) {
			if(strpos(Str::lower($school_class->search_id), Str::lower($term)) !== false) {
				$classes[] = ['value' => $school_class->search_id, 'id' => $school_class->id];
			}
		}

		return Response::json($classes);
	}
	public function getTeachersJson() {
		$term = Input::get('term'); // jQuery UI creates term

		$users = User::all();
		$teachers = [];
		foreach($users as $user) {
			if($user->teacher()->count()) {
				if(strpos(Str::lower($user->name), Str::lower($term)) !== false) {
					$teacher = Teacher::where('user_id', '=', $user->id)->first();
					if($teacher->count()) {
						$teachers[] = ['value' => $user->name, 'id' => $teacher->id];
					}
				}
			}
		}

		return Response::json($teachers);
	}

	public function checkValidClass($search) {
		$class = SchoolClass::where('search_id', '=', $search)->first();

		if($class->count()) {
			return true;
		}

		return false;
	}
	public function checkValidTeacher($name) {
		$user = User::where('name', '=', $name)->first();

		if($user->teacher()->count()) {
			return true;
		}

		return false;
	}
	public function checkValidRegister($search_id, $teacher_id, $period) {
		$class = RegisteredClass::where('class_id', '=', $search_id)->where('teacher_id', '=', $teacher_id)->where('period', '=', $period);

		if($class->count()) {
			return false;
		}

		return true;
	}
}