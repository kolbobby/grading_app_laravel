<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));

/**
 * Unauthenticated group
 */
Route::group(array('before' => 'guest'), function() {
	/**
	 * CSRF protection group
	 */
	Route::group(array('before' => 'csrf'), function() {
		/**
		 * Create account (POST)
		 */
		Route::post('/account/create', array('as' => 'account-create-post', 'uses' => 'AccountController@postCreate'));
	});

	/**
	 * Create account (GET)
	 */
	Route::get('/account/create', array('as' => 'account-create', 'uses' => 'AccountController@getCreate'));
});