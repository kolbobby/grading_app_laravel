<?php

class BaseController extends Controller {
	public $layout = 'layouts.template';
	protected function setupLayout() {
		if(!is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	}
}