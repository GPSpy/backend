<?php

use \GPSpy\Helpers\Locations;

class UsersController extends \BaseController {

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function register()
	{
		$user = new User();

		$user->token         = str_random(40);
		$user->name          = $_GET['name'];
		$user->phone         = $_GET['phone'];
		$user->last_location = 1;
		$user->save();

		return $this->_returnJsonP($user);
	}

	public function locate()
	{
		$token = $_GET['token'];
		$user  = User::where('token', '=', $token)->firstOrFail();

		$location = [
			'latitude'  => (float)$_GET['latitude'],
			'longitude' => (float)$_GET['longitude']
		];

		$target = $this->_getLocation($user->last_location + 1);

		$distance = $this->vincentyGreatCircleDistance(
			$location['latitude'],
			$location['longitude'],
			$target['latitude'],
			$target['longitude']
		);

		$hotness = 'debug';

		return $this->_returnJsonP(
			[
				'hotness'  => $hotness,
				'distance' => $distance
			]
		);
	}

}