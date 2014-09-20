<?php namespace GPSpy\Helpers;

class Locations {

	var $locations = [
		1 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'MI-12 (HQ)'
		],
		2 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'location two'
		],
		3 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'Crime Alley'
		],
		4 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'Martini Avenue'
		],
		5 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'Location five'
		],
		6 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'Doctor Evil Lair'
		],
	];

	public function get($id = null)
	{
		if (!$id) {
			return $this->locations;
		}

		if (isset($this->locations[$id])) {
			return $this->locations[$id];
		}

		return [];
	}

}