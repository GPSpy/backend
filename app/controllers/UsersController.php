<?php

class UsersController extends \BaseController {

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function store()
	{
		return $this->_returnJsonP($_GET);
	}

}