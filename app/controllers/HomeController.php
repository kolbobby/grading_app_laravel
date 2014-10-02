<?php

class HomeController extends BaseController {
	public function index() {
		if(Auth::check()) {
			return Redirect::route('account-page');
		} else {
			return Redirect::route('account-sign-in');
		}
	}
}