<?php

namespace GradingApp\Libraries\ClassPeriods;

use DB;
use Config;
use Carbon\Carbon;
use GradingApp\Libraries\Calendar\Calendar;

class ClassPeriods {
	// Class durations
	private static $fd_class,
				   $hd_class;

	// Between classes
	private static $fd_between,
				   $hd_between;

	// Required
	private static $current_time,
				   $day_start_time,
				   $current_period;

	private static $number_of_periods;

	private static $half_day = 0; // half-day boolean; whether or not current day is half-day

	public function __construct() {
		// Set database variables
		self::$fd_class = (int) DB::table('application_settings')->where('name', '=', 'fd_class_duration')->first()->value;
		self::$hd_class = (int) DB::table('application_settings')->where('name', '=', 'hd_class_duration')->first()->value;

		self::$fd_between = (int) DB::table('application_settings')->where('name', '=', 'fd_between_time')->first()->value;
		self::$hd_between = (int) DB::table('application_settings')->where('name', '=', 'hd_between_time')->first()->value;

		self::$number_of_periods = (int) DB::table('application_settings')->where('name', '=', 'number_of_periods')->first()->value;

		// Check for half day in calendar; set half day based on events
		$calendar = new Calendar();
		$dates_events = $calendar::GetDatesEvents();
		foreach($dates_events as $event) {
			if(self::$half_day) break;
			self::$half_day = $event->half_day;
		}

		// Set application variables
		self::SetCurrentTime(Carbon::now(Config::get('app.timezone')));
		sscanf(DB::table('application_settings')->where('name', '=', 'day_start_time')->first()->value, '%d:%d:%d', $day_start_hours, $day_start_minutes, $day_start_seconds);
		self::SetDayStart(Carbon::createFromTime($day_start_hours, $day_start_minutes, $day_start_seconds, Config::get('app.timezone'))); // (hours, minutes, second, timezone) DEFAULT: 08:00:00
	
		self::CurrentPeriod();
	}

	// Calculates current period
	public static function CurrentPeriod() {
		$dif = self::GetCurrentTime()->diffInMinutes(self::GetDayStart());
		if($dif >= 0) {
			if(!self::GetHalfDay()) {
				if($dif <= (self::$number_of_periods * self::$fd_class) + ((self::$number_of_periods - 1) * self::$fd_between)) {
					self::SetCurrentPeriod((int) (($dif - ((((int) ($dif / self::$fd_class)) - 1) * self::$fd_between)) / self::$fd_class) + 1); // Gets beginning of current period
				} else {
					self::SetCurrentPeriod('School\'s over for the day.');
				}
			} else {
				if($dif <= (self::$number_of_periods * self::$hd_class) + ((self::$number_of_periods - 1) * self::$hd_between)) {
					self::SetCurrentPeriod((int) (($dif - ((((int) ($dif / self::$hd_class)) - 1) * self::$hd_between)) / self::$hd_class) + 1); // Gets beginning of current period
				} else {
					self::SetCurrentPeriod('School\'s over for the day.');
				}
			}
		}
	}
	
	public static function GetHalfDay() {
		return self::$half_day;
	}
	
	public static function SetHalfDay($value) {
		self::$half_day = $value;
	}

	public static function GetDayStart() {
		return self::$day_start_time;
	}

	public static function SetDayStart($value) {
		self::$day_start_time = $value;
	}

	public static function GetCurrentTime() {
		return self::$current_time;
	}

	public static function SetCurrentTime($value) {
		self::$current_time = $value;
	}

	public static function GetCurrentPeriod() {
		return self::$current_period;
	}

	public static function SetCurrentPeriod($value) {
		self::$current_period = $value;
	}
}