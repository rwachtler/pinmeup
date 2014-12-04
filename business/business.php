<?php
	include('../data_access/data_access.php');
	
	class PinMeUp {
		private $da = null;
		
		// Link to country flag folder
		private static $flags = "../public/images/flags/";
		
		public function __construct() {
			$this->da = new DataAccess();
		}
		
		public function getPins() {
			return $this->da->getPins();
		}
		
		public function addPin() {
			$ip_address = $this->getIpAddress();
			$lat = 25.1234;
			$lng = 99.6521;
			$country_id = $this->getCountryFromIpAddress($ip_address);
			
			$this->da->addPin($lat, $lng, $ip_address, $country_id);
		}
		
		public function getCountryIdByName($name) {
			return $this->da->getCountryIdByName($name);
		}
		
		public function getCountryById($id) {
			return $this->da->getCountryById($id);
		}
		
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
			Fetches JSON object about the country info
		*/
		private function getIpInfo($ip) {
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

			return $details;
		}
		
		/**
			Returns the iso2 country code for the specified IP address
		*/
		public function getCountryFromIpAddress($ip) {
			$details = $this->getIpInfo($ip);
			
			return $details['country'];
		}
	}