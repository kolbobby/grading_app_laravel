<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class RegisteredClass extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('class_id', 'teacher_id', 'period');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'registered_classes';

	public function school_class() {
		return $this->belongsTo('SchoolClass', 'class_id');
	}

	public function teacher() {
		return $this->belongsTo('Teacher', 'teacher_id');
	}

	public function student_classes() {
		return $this->hasMany('StudentClass', 'registered_class_id');
	}
}
