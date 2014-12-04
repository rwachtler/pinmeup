<?php
	class DataAccess {
		
		private $connection;
		
		public function __construct() {
			// settings to get access to the database
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dbname = "pinmeup";
		
			// Establish connection
			$this->connection = new mysqli($servername, $username, $password, $dbname);
			
			// Check connection
			if ($this->connection->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			
			// Set charset to utf8
			if (!$this->connection->set_charset("utf8")) {
				die("Error loading character set utf8: " . $this->connection->error);
			}
		}
		
		/**
			Returns all pins from the database in an associative array.
		*/
		public function getPins() {
			$pins = array();
			
			$sql = 'SELECT * FROM pins';
			
			$result = $this->connection->query($sql);
			
			if (!$result) {
				echo $this->connection->error;
			}
			
			while ($row = $result->fetch_assoc()) {
				$pins[] = $row;
			}
			
			return $pins;
		}
		
		/**
			Adds a new pin to the database
		*/
		public function addPin($lat, $lng, $ip_address, $country_id) {
			$sql = 'INSERT INTO pins (lat, lng, ip_address, fk_country_id) VALUES (?, ?, ?, ?)';
			
			$stmt = $this->connection->prepare($sql);
			
			if (!$stmt->bind_param('ddsi', $lat, $lng, $ip_address, $country_id)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				
				return false;
			}
			
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				
				return false;
			}
			
			return true;
		}
		
		/**
			Returns the country with the specified id from the database as an associative array.
			If the country is not found, an empty array is returned.
		*/
		public function getCountryById($id) {
			$country = array();
			
			$sql = 'SELECT * FROM countries WHERE country_id LIKE ?';
			
			$stmt = $this->connection->prepare($sql);
			
			if (!$stmt->bind_param('i', $id)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				
				return false;
			}
			
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				
				return false;
			}
			
			$result = $stmt->get_result();
			
			// There should only be one country with the same name in the database
			if ($result->num_rows == 1) {
				$country = $result->fetch_assoc();
			}
			
			return $country;
		}
		
		/**
			Searches the database for a country with the specified iso2 code.
			If it finds one, the id of the country is returned, otherwise 0.
		*/
		public function getCountryIdByCode($code) {
			$country_id = 0;
		
			$sql = 'SELECT country_id FROM countries WHERE iso2 LIKE ?';
			
			$stmt = $this->connection->prepare($sql);
			
			if (!$stmt->bind_param('s', $code)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			$result = $stmt->get_result();
			
			// There should only be one country with the same name in the database
			if ($result->num_rows == 1) {
				$row = $result->fetch_assoc();
			
				$country_id = $row['country_id'];
			}
			
			return $country_id;
		}
	}