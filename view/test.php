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
			
			// Get all pins as associative array
			$pins = $business->getPins();
			
			print_r($pins);
		?>
	</body>
</html>