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

		$this->makeCall($user->phone, 1);

		return $this->_returnJsonP($user);
	}

	public function locate()
	{
		$token = $_GET['token'];
		$user  = User::where('token', '=', $token)->firstOrFail();

		$location = [
			'latitude'  => round((float)$_GET['latitude'], 5),
			'longitude' => round((float)$_GET['longitude'], 5)
		];

		$target = $this->_getLocation($user->last_location + 1);

		$distance = $this->vincentyGreatCircleDistance(
			$location['latitude'],
			$location['longitude'],
			$target['latitude'],
			$target['longitude']
		);

		$hotness = $this->calculateHotness($distance);

		if ($hotness == 'target') {
			$next_location = $user->last_location + 1;
			$this->makeCall($user->phone, $next_location);
			$user->last_location = $next_location;
			$user->save();
		}

		return $this->_returnJsonP(
			[
				'destination' => $target['name'],
				'hotness'     => $hotness,
				'distance'    => $distance
			]
		);
	}

}