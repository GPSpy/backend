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

	var $locations = [
		1 => [
			'latitude'  => 1,
			'longitude' => 1,
			'name'      => 'MI-12 (HQ)'
		],
		2 => [
			'latitude'  => 52.488903,
			'longitude' => - 1.8870747,
			'name'      => 'Martini Avenue'
		],
		3 => [
			'latitude'  => 52.488643,
			'longitude' => - 1.8865992,
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

	protected function _getLocation($id = null)
	{
		if (!$id) {
			return $this->locations;
		}

		if (isset($this->locations[$id])) {
			return $this->locations[$id];
		}

		return [];
	}

	/**
	 * Calculates the great-circle distance between two points, with
	 * the Vincenty formula.
	 *
	 * @param float $latitudeFrom  Latitude of start point in [deg decimal]
	 * @param float $longitudeFrom Longitude of start point in [deg decimal]
	 * @param float $latitudeTo    Latitude of target point in [deg decimal]
	 * @param float $longitudeTo   Longitude of target point in [deg decimal]
	 * @param int   $earthRadius   Mean earth radius in [m]
	 *
	 * @return float Distance between points in [m] (same as earthRadius)
	 */
	public function vincentyGreatCircleDistance(
		$latitudeFrom,
		$longitudeFrom,
		$latitudeTo,
		$longitudeTo,
		$earthRadius = 6371000
	) {
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo   = deg2rad($latitudeTo);
		$lonTo   = deg2rad($longitudeTo);

		$lonDelta = $lonTo - $lonFrom;
		$a        = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b        = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		$angle = atan2(sqrt($a), $b);

		return $angle * $earthRadius;
	}

	public function calculateHotness($distance)
	{
		if ($distance > 250) {
			return 'cold';
		} elseif ($distance < 250 && $distance > 100) {
			return 'warm';
		} elseif ($distance < 100 && $distance > 20) {
			return 'hot';
		} else {
			return 'target';
		}
	}

}
