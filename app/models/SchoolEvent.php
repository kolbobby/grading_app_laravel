<?php

namespace GradingApp\Models;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SchoolEvent extends \Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('date', 'value', 'half_day', 'holiday');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';
}
