<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class StudentClass extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('registered_class_id', 'student_id');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'student_classes';

	public function registered_class() {
		return $this->belongsTo('RegisteredClass', 'registered_class_id');
	}
}
