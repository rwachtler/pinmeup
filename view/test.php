<html>
	<head>
		<title>PinMeUp!</title>
	</head>
	
	<body>
		<h1>PinMeUp!</h1>
	
		<?php
			error_reporting(E_ALL);
		
			// Include business logic
			include('../business/business.php');

			$business = new PinMeUp();
			
			$c = $business->getCountryById(15);
			print_r($c);
			
			echo "<img src='" . $business->getCountryFlagById(15) . "' /><br/>";
			
			$business->getIpInfo();
			
			// Get all pins as associative array
			$pins = $business->getPins();
		?>
	</body>
</html>