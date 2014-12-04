<?php
	/**
		This script evaluates an AJAX post request sent containing the position values sent from the browser.
		After determining the IP address and the country associated with this IP address, a new pin entry is added to the database.
		A JSON object containing a success status and a message is returned to the AJAX call.
		If the execution was not successful, the message contains information about why it was not successful.
	*/

	include('business.php');

	$success = 0;
	$msg = "";

	// Check if it was a post request
	if ($_POST) {
		// Get latitude and longitude vaules from post array
		$lat = isset($_POST['lat']) ? $_POST['lat'] : null;
		$lng = isset($_POST['lng']) ? $_POST['lng'] : null;
		
		if (!empty($lat) AND !empty($lng)) {
			$business = new PinMeUp();
			
			// Get client IP address
			$ip_address = $business->getIpAddress();
			
			// Determine country id from IP address
			$country_code = $business->getCountryFromIpAddress($ip_address);
			$country_id = $business->getCountryIdByCode($country_code);
			
			// Add new pin entry to the database
			if ($business->addPin($lat, $lng, $ip_address, $country_id)) {
				$success = 1;
				$msg = $business->getPinHtml();
			} else {
				$msg = "Add operation not successful";
			}
		} else {
			$msg = "No position data received";
		}
	} else {
		$msg = "No post data received";
	}
	
	// Fill return array
	$return = array();
	
	$return['success'] = $success;
	$return['msg'] = $msg;
	
	// Return JSON object with succes status and message
	print json_encode($return);