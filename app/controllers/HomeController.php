<?php

class HomeController extends BaseController {
	public function index() {
		$this->layout->title = "Home";
		$this->layout->content = View::make('home');
	}
}