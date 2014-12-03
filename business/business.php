<?php
	include('../data_access/data_access.php');
	
	class PinMeUp {
		private $da = null;
		
		public function __construct() {
			$this->da = new DataAccess();
		}
		
		public function getPins() {
			return $this->da->getPins();
		}
		
		public function addPin() {
			$lat = 25.1234;
			$lng = 99.6521;
			$ip_address = "192.168.10.24";
			$country_id = 1;
			
			$this->da->addPin($lat, $lng, $ip_address, $country_id);
		}
		
		public function getCountryByName($name) {
			return $this->da->getCountryByName($name);
		}
	}