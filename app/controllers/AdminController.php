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

	public function getAdjustTimings() {
		$this->layout->title = "Adjust Timings";
		$this->layout->content = View::make('account.view')
			->with('accType', $this->accType)
			->with('page', View::make('admin.adjust_timings')
				->with('data', $this->loadTimingsData()));
	}
	public function postAdjustTimings() {
		$validator = Validator::make(Input::all(),
			array(
				'day_start_hours' => 'required',
				'day_start_minutes' => 'required',
				'day_start_seconds' => 'required',
				'fd_class_duration' => 'required',
				'fd_between_time' => 'required',
				'hd_class_duration' => 'required',
				'hd_between_time' => 'required'
			)
		);

		if($validator->fails()) {
			return Redirect::route('admin-adjust-timings')
				->withErrors($validator)
				->withInput();
		} else {
			$day_start_hours = Input::get('day_start_hours');
			$day_start_minutes = Input::get('day_start_minutes');
			$day_start_seconds = Input::get('day_start_seconds');

			$fd_class_duration = Input::get('fd_class_duration');
			$fd_between_time = Input::get('fd_between_time');

			$hd_class_duration = Input::get('hd_class_duration');
			$hd_between_time = Input::get('hd_between_time');

			if(!$this->checkValidDayStartHours($day_start_hours)) {
				return Redirect::route('admin-adjust-timings')
					->with('global', 'Day start hours must be less than or equal to 24 and greater than or equal to 0.');
			}
			if(!$this->checkValidDayStartMinutes($day_start_minutes)) {
				return Redirect::route('admin-adjust-timings')
					->with('global', 'Day start minutes must be less than or equal to 59 and greater than or equal to 0.');
			}
			if(!$this->checkValidDayStartSeconds($day_start_seconds)) {
				return Redirect::route('admin-adjust-timings')
					->with('global', 'Day start seconds must be less than or equal to 59 and greater than or equal to 0.');
			}

			DB::table('application_settings')->where('name', '=', 'day_start_time')->update(array('value' => $day_start_hours . ':' . $day_start_minutes . ':' . $day_start_seconds));
			DB::table('application_settings')->where('name', '=', 'fd_class_duration')->update(array('value' => $fd_class_duration));
			DB::table('application_settings')->where('name', '=', 'fd_between_time')->update(array('value' => $fd_between_time));
			DB::table('application_settings')->where('name', '=', 'hd_class_duration')->update(array('value' => $hd_class_duration));
			DB::table('application_settings')->where('name', '=', 'hd_between_time')->update(array('value' => $hd_between_time));

			return Redirect::route('admin-adjust-timings')
				->with('global', 'Timing settings have been saved!');
		}
	}
	public function loadTimingsData() {
		$data = [];
		$day_start_time = DB::table('application_settings')->where('name', '=', 'day_start_time')->first()->value;
		sscanf($day_start_time, '%d:%d:%d', $day_start_hours, $day_start_minutes, $day_start_seconds);

		$data['day_start_hours'] = $day_start_hours;
		$data['day_start_minutes'] = $day_start_minutes;
		$data['day_start_seconds'] = $day_start_seconds;

		$data['fd_class_duration'] = DB::table('application_settings')->where('name', '=', 'fd_class_duration')->first()->value;
		$data['fd_between_time'] = DB::table('application_settings')->where('name', '=', 'fd_between_time')->first()->value;

		$data['hd_class_duration'] = DB::table('application_settings')->where('name', '=', 'hd_class_duration')->first()->value;
		$data['hd_between_time'] = DB::table('application_settings')->where('name', '=', 'hd_between_time')->first()->value;

		return $data;
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

		if($user) {
			if($user->count()) {
				return true;
			}
		}

		return false;
	}

	public function checkValidDayStartHours($hours) {
		if($hours <= 24 && $hours >= 0) {
			return true;
		}

		return false;
	}
	public function checkValidDayStartMinutes($minutes) {
		if($minutes <= 59 && $minutes >= 0) {
			return true;
		}

		return false;
	}
	public function checkValidDayStartSeconds($seconds) {
		if($seconds <= 59 && $seconds >= 0) {
			return true;
		}

		return false;
	}

	public function checkValidParent($name) {
		$user = User::where('name', '=', $name)->first();

		if($user) {
			if($user->parent()->count()) {
				return true;
			}
		}

		return false;
	}
	public function checkValidSc($name) {
		$user = User::where('name', '=', $name)->first();

		if($user) {
			if($user->school_counselor()->count()) {
				return true;
			}
		}

		return false;
	}
}