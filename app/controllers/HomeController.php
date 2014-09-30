<?php

class HomeController extends BaseController {
	public function index() {
		/*Mail::send('emails.auth.test', array('name' => 'Bobby'), function($message) {
			$message->to('kolbobby@gmail.com', 'Bobby Koller')->subject('Testing Email');
		});*/

		$this->layout->title = "Home";
		$this->layout->content = View::make('home');
	}
}