<?php

namespace GradingApp\ClassPeriods;

use Config;
use Carbon\Carbon;

class ClassPeriods {
	// Class durations
	private static $fd_class = 40,
				   $hd_class = 29;

	// Between classes
	private static $fd_between = 6.5,
				   $hd_between = 4.5;

	// Required
	private static $current_time,
				   $day_start_time,
				   $current_period;

	private static $number_of_periods = 9; // CHANGE BASED ON SHCOOL

	private static $half_day = 1; // half-day boolean; whether or not current day is half-day

	public function __construct() {
		self::SetCurrentTime(Carbon::now(Config::get('app.timezone')));
		self::SetDayStart(Carbon::createFromTime(8, 0, 0, Config::get('app.timezone'))); // (hours, minutes, second, timezone) DEFAULT: 08:00:00
	
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