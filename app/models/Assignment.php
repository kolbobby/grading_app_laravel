<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Assignment extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('registered_class_id', 'name', 'type', 'points');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'assignments';
}
