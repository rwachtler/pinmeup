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
			
			/*
			// SQL query
			$sql = 'SELECT * FROM pins WHERE 1=$1';
			
			// Prepare statement and bind parameters
			$stmt = $this->connection->prepare($sql);
			
			// Example: bind_param for 1 integer value: "bind_param('i', $i)" - bind_param for 3 strings and 1 double: "bind_param('sssd', $a, $b, $c, $d)"
			if (!$stmt->bind_param('i', 1)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			// Execute statement
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			
			$pins = $stmt->get_result();
			*/
			
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
			$sql = 'INSERT INTO pins (lat, lng, ip_address, fk_country) VALUES (?, ?, ?, ?)';
			
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
			Adds a new country to the database.
		*/
		public function addCountry($name) {
			$sql = 'INSERT INTO countries (name) VALUES (?)';
			
			$stmt = $this->connection->prepare($sql);
			
			if (!$stmt->bind_param('s', $name)) {
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
			Searches the database for a country with the specified name.
			If it finds one, the id of the country is returned, otherwise 0.
		*/
		public function getCountryByName($name) {
			$country_id = 0;
		
			$sql = 'SELECT id FROM countries WHERE name LIKE ?';
			
			$stmt = $this->connection->prepare($sql);
			
			if (!$stmt->bind_param('s', $name)) {
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
				$row = $result->fetch_assoc();
			
				$country_id = $row['id'];
			}
			
			return $country_id;
		}
	}