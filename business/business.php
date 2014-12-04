<?php
	include('../data_access/data_access.php');
	
	class PinMeUp {
		private $da = null;
		
		// Default country code (when using localhost)
		private static $DEFAULT_COUNTRY_CODE = "AT";
		
		// Link to country flag folder
		private static $flags = "../public/images/flags/";
		
		public function __construct() {
			$this->da = new DataAccess();
		}
		
		/**
			Puts together HTML table containing all pins
		*/
		public function getPinHtml() {
			$html = "";
			
			// Table heading
			$html .= "<table border='1' cellpadding='2'>";
			
				$html .= "<tr>";
					$html .= "<th>#</th>";
					$html .= "<th>Latitude</th>";
					$html .= "<th>Longitude</th>";
					$html .= "<th>IP address</th>";
					$html .= "<th>Country</th>";
				$html .= "</tr>";
			
			// Get all pins
			$pins = $this->getPins();
			
			foreach ($pins as $pin) {
				$country = $this->getCountryById($pin['fk_country_id']);
			
				$flag = "<img src='" . $this->getCountryFlagById($pin['fk_country_id']) . "' title='" . $country['short_name'] . "' />";
				
				$html .= "<tr>";
					$html .= "<td> " . $pin['pin_id'] . " </td>";
					$html .= "<td> " . $pin['lat'] . " </td>";
					$html .= "<td> " . $pin['lng'] . " </td>";
					$html .= "<td> " . $pin['ip_address'] . " </td>";
					$html .= "<td> " . $flag . "</td>";
				$html .= "</tr>";
			}
			
			$html .= "</table>";
			
			return $html;
		}
		
		/**
			Reads all pin entries from the database
		*/
		public function getPins() {
			return $this->da->getPins();
		}
		
		/**
			Adds a new pin entry to the database
		*/
		public function addPin($lat, $lng, $ip_address, $country_id) {
			return $this->da->addPin($lat, $lng, $ip_address, $country_id);
		}
		
		/**
			Returns the country id of the specified iso2 country code (e.g. "AT" returns the country code for Austria)
			If the country is not found, the return value is 0
		*/
		public function getCountryIdByCode($code) {
			return $this->da->getCountryIdByCode($code);
		}
		
		/**
			Returns the whole country database entry for the specified country id as an associative array
			If the id is not found, an empty array is returned
		*/
		public function getCountryById($id) {
			return $this->da->getCountryById($id);
		}
		
		/**
			Returns the link to the country flag for the specified country id
			If the id is not found, an empty string is returned
		*/
		public function getCountryFlagById($id) {
			$flag = "";
		
			$country = $this->getCountryById($id);
			
			if (!empty($country)) {
				$flag = self::$flags . $country['iso2'] . '.png';
			}
			
			return $flag;
		}
		
		/**
			Returns the client IP address
		*/
		public function getIpAddress() {
			$ip = "";
		
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			
			return $ip;
		}
		
		/**
			Fetches JSON object containing the country info using an external service
		*/
		private function getIpInfo($ip) {
			return json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"), true);
		}
		
		/**
			Returns the iso2 country code for the specified IP address
		*/
		public function getCountryFromIpAddress($ip) {
			$localhost = array("127.0.0.1", "::1");
			
			// If localhost, default country code is returned
			if (in_array($ip, $localhost)) {
				return self::$DEFAULT_COUNTRY_CODE;
			} else {
				$details = $this->getIpInfo($ip);
			
				return $details['country'];
			}
		}
	}