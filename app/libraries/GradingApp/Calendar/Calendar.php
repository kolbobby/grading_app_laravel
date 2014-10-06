<?php

namespace GradingApp\Libraries\Calendar;

use DB;
use Carbon\Carbon;
use GradingApp\Models\SchoolEvent;

class Calendar {
	private static $dates_events;

	public function __construct() {
		self::SetDatesEvents();
	}

	public static function GetDatesEvents() {
		return self::$dates_events;
	}

	public static function SetDatesEvents() {
		$events = SchoolEvent::all();
		$school_events = [];
		foreach($events as $event) {
			$date = Carbon::createFromTimeStamp(strtotime($event->date));
			if(Carbon::now()->isToday($date)) $school_events[] = $event;
		}

		self::$dates_events = $school_events;
	}
}