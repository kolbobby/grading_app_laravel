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
 * Authenticated group
 */
Route::group(array('before' => 'auth'), function() {
	/**
	 * CSRF protection group
	 */
	Route::group(array('before' => 'csrf'), function() {
		/**
		 * Change password (POST)
		 */
		Route::post('/account/change_password', array('as' => 'account-change-password-post', 'uses' => 'AccountController@postChangePassword'));
	});

	/**
	 * Account page (GET)
	 */
	Route::get('/account', array('as' => 'account-page', 'uses' => 'ProfileController@getAccount'));

	/**
	 * Admin group
	 */
	Route::group(array('before' => 'admin'), function() {
		/**
		 * CSRF protection group
		 */
		Route::group(array('before' => 'csrf'), function() {
			/**
			 * Reserve email (POST)
			 */
			Route::post('/account/admin/reserve_email', array('as' => 'admin-reserve-email-post', 'uses' => 'AdminController@postReserveEmail'));
		
			/**
			 * Add student (POST)
			 */
			Route::post('/account/admin/add_student', array('as' => 'admin-add-student-post', 'uses' => 'AdminController@postAddStudent'));
		});

		/**
		 * Reserve email (GET)
		 */
		Route::get('/account/admin/reserve_email', array('as' => 'admin-reserve-email', 'uses' => 'AdminController@getReserveEmail'));
	
		/**
		 * Add student (GET)
		 */
		Route::get('/account/admin/add_student', array('as' => 'admin-add-student', 'uses' => 'AdminController@getAddStudent'));
	
		/**
		 * Get parents (JSON GET)
		 */
		Route::get('/account/admin/get_parents_json', array('uses' => 'AdminController@getParentsJson'));
		/**
		 * Get school counselors (JSON GET)
		 */
		Route::get('/account/admin/get_school_counselors_json', array('uses' => 'AdminController@getSchoolCounselorsJson'));
	});

	/**
	 * Change password (GET)
	 */
	Route::get('/account/change_password', array('as' => 'account-change-password', 'uses' => 'AccountController@getChangePassword'));

	/**
	 * Sign out (GET)
	 */
	Route::get('/account/signout', array('as' => 'account-sign-out', 'uses' => 'AccountController@getSignOut'));
});

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

		/**
		 * Sign in (POST)
		 */
		Route::post('/account/signin', array('as' => 'account-sign-in-post', 'uses' => 'AccountController@postSignIn'));

		/**
		 * Forgot password (POST)
		 */
		Route::post('/account/forgot_password', array('as' => 'account-forgot-password-post', 'uses' => 'AccountController@postForgotPassword'));
	});

	/**
	 * Forgot password (GET)
	 */
	Route::get('/account/forgot_password', array('as' => 'account-forgot-password', 'uses' => 'AccountController@getForgotPassword'));

	/**
	 * Recover password (GET)
	 */
	Route::get('/account/recover/{code}', array('as' => 'account-recover', 'uses' => 'AccountController@getRecover'));

	/**
	 * Sign in (GET)
	 */
	Route::get('/account/signin', array('as' => 'account-sign-in', 'uses' => 'AccountController@getSignIn'));

	/**
	 * Create account (GET)
	 */
	Route::get('/account/create', array('as' => 'account-create', 'uses' => 'AccountController@getCreate'));

	/**
	 * Activate account (GET)
	 */
	Route::get('/account/activate/{code}', array('as' => 'account-activate', 'uses' => 'AccountController@getActivate'));
});