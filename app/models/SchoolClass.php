<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SchoolClass extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('search_id', 'name', 'description');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'school_classes';

	public function registered_classes() {
		return $this->hasMany('RegisteredClass', 'class_id');
	}
}
