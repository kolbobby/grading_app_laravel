<?php

class AdminController extends BaseController {
	private $accType = 'admin';

	public function getReserveEmail() {
		$this->layout->title = "Reserve Email";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('admin.reserve'));
	}
	public function postReserveEmail() {
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|email',
				'type' => 'required'
			)
		);

		// Checks if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('admin-reserve-email')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to reserve email
			$email = Input::get('email');
			$type = Input::get('type');
			if($type == 'school_counselor') $type = 'sc';

			if($this->reserved($email)) {
				return Redirect::route('admin-reserve-email')
					->with('global', 'Email already reserved.');
			} else {
				$reserved = Reserved::create(array(
					'email' => $email,
					'type' => $type
				));

				// Check if reserved email is successfully created
				if($reserved) {
					return Redirect::route('admin-reserve-email')
						->with('global', 'Reserved email has been added!');
				}
			}

			return Redirect::route('admin-reserve-email')
				->with('global', 'Problem adding reserved email.');
		}
	}

	public function getAddStudent() {
		$this->layout->title = "Add Student";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('admin.add_student'));
	}
	public function postAddStudent() {
		$validator = Validator::make(Input::all(),
			array(
				'student_name' => 'required',
				'parent_name' => 'required',
				'sc_name' => 'required'
			)
		);

		// Checks if above requirements are met
		if($validator->fails()) {
			// If not, send back original input with errors
			return Redirect::route('admin-add-student')
				->withErrors($validator)
				->withInput();
		} else {
			// If it passes, attempt to create student
			$student_name = Input::get('student_name');
			$parent_id = Input::get('parent_id');
			$parent_name = Input::get('parent_name');
			$sc_id = Input::get('sc_id');
			$sc_name = Input::get('sc_name');

			// Check for valid parent and school counselor
			if($this->checkValidParent($parent_name) && $this->checkValidSc($sc_name)) {
				// If it passes, attempt to create student
				$student = Student::create(array(
					'name' => $student_name,
					'parent_id' => $parent_id,
					'sc_id' => $sc_id
				));

				if($student) {
					// If student is created, return to add student
					return Redirect::route('admin-add-student')
						->with('global', 'Student successfully added!');
				}
			} else {
				// If not, send back error
				return Redirect::route('admin-add-student')
					->with('global', 'Invalid parent or school counselor name.');
			}

			return Redirect::route('admin-add-student')
				->with('global', 'Problem adding student.');
		}
	}

	public function getParentsJson() {
		$term = Input::get('term'); // jQuery UI creates term

		$users = User::all();
		$parents = [];
		foreach($users as $user) {
			if($user->parent()->count()) {
				if(strpos(Str::lower($user->name), Str::lower($term)) !== false) {
					$parent = StudentParent::where('user_id', '=', $user->id)->first();
					if($parent->count()) {
						$parents[] = ['value' => $user->name, 'id' => $parent->id];
					}
				}
			}
		}

		return Response::json($parents);
	}
	public function getSchoolCounselorsJson() {
		$term = Input::get('term'); // jQuery UI creates term

		$users = User::all();
		$school_counselors = [];
		foreach($users as $user) {
			if($user->school_counselor()->count()) {
				if(strpos(Str::lower($user->name), Str::lower($term)) !== false) {
					$counselor = SchoolCounselor::where('user_id', '=', $user->id)->first();
					if($counselor->count()) {
						$school_counselors[] = ['value' => $user->name, 'id' => $counselor->id];
					}
				}
			}
		}

		return Response::json($school_counselors);
	}

	public function reserved($email) {
		// Looks for reserved email (needed in order to create account)
		$user = Reserved::where('email', '=', $email);

		if($user->count()) {
			return true;
		}

		return false;
	}

	public function checkValidParent($name) {
		$user = User::where('name', '=', $name)->first();

		if($user->parent()->count()) {
			return true;
		}

		return false;
	}
	public function checkValidSc($name) {
		$user = User::where('name', '=', $name)->first();

		if($user->school_counselor()->count()) {
			return true;
		}

		return false;
	}
}