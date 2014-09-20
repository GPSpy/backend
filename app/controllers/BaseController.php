<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->layout = View::make($this->layout);
		}
	}

	protected function _returnJsonP($array)
	{
		return Response::json($array)
			->setCallback(Input::get('callback'));
	}
}
