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
		$user = new User();

		$user->token = str_random(40);
		$user->name  = $_GET['name'];
		$user->phone = $_GET['phone'];
		$user->save();

		return $this->_returnJsonP($user);

	}

}