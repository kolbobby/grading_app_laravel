<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Student extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'students';

	// Gets user ownership
	public function user() {
		return $this->belongsTo('User');
	}

	// Gets parent ownership
	public function parent() {
		return $this->belongsTo('Parent', 'parent_id');
	}

	// Gets school counselor ownership
	public function school_counselor() {
		return $this->belongsTo('SchoolCounselor', 'sc_id');
	}
}
