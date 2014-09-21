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
			'latitude'  => 52.48871909,
			'longitude' => - 1.88758850,
			'name'      => 'Innovation Birmingham Campus'
		],
		2 => [
			'latitude'  => 52.4878110,
			'longitude' => - 1.8872666,
			'name'      => 'Gosta Green'
		],
		3 => [
			'latitude'  => 52.487334086,
			'longitude' => - 1.888135671,
			'name'      => 'Sacks Of Potatoes'
		],
		4 => [
			'latitude'  => 52.48678529,
			'longitude' => - 1.88931584,
			'name'      => 'Aston University Entrance'
		],
		5 => [
			'latitude'  => 52.4857660,
			'longitude' => - 1.8900775,
			'name'      => 'Tesco'
		],
		6 => [
			'latitude'  => 52.484090,
			'longitude' => - 1.8898522,
			'name'      => 'Lakeside'
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
		} elseif ($distance < 100 && $distance > 30) {
			return 'hot';
		} else {
			return 'target';
		}
	}

	public function makeCall($phone, $location)
	{
		$client = new Services_Twilio(
			getenv('TWILIO_SID'),
			getenv('TWILIO_TOKEN'),
			"2010-04-01"
		);

		try {
			// Initiate a new outbound call
			$call = $client->account->calls->create(
				getenv('TWILIO_NUMBER'), // The number of the phone initiating the call
				$phone, // The number of the phone receiving call
				getenv('CDN_URL') . "{$location}.xml", // The URL Twilio will request when the call is answered
				['Method' => 'get']
			);
		} catch (Exception $e) {
			echo 'Error: ' . $e->getMessage();
		}

	}

}
