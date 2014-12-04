<?php
/**
 * Created by PhpStorm.
 * User: R.Wachtler
 * Date: 01.12.14
 */?>
<!DOCTYPE html>
<html>
<head>
    <title>Pin Me Up!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../public/css/normalize-css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../public/css/main.css">
    <script src="../public/components/jquery/jquery.js"></script>
    <script src="../public/components/bootstrap/bootstrap.js"></script>
</head>

    <body>
    <div class="header row">
        <div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1>Pin Me Up!</h1>
        </div>
    </div>
    <div class="content row">
        <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h1>Facts</h1>
				
				<div class="row">
					<div class="table-responsive" id="facts">
						<?php
							include('../business/business.php');
						
							$business = new PinMeUp();
							
							echo $business->getPinHtml();
						?>

					</div>
				</div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h1>Map</h1>
                <div class="row">
                    <div id="map" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="container">
        <p class="pull-right">&copy; 2014 Ramis Wachtler | Michael Stifter | Mario Kurzweil</p>
    </footer>
    </body><!--./body-->
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script>
        var mapElement = document.getElementById("map");
        var mapExists = false;
        var mapGlob;
        /*
            Checks for ability of geolocation feature
                true - call showPosition function
                error - call showError function
            not supported - notify user
        */
        function geoLocate(){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            }
            else{
                mapElement.innerHTML = "Awww snap! Your browser does not support the geolocation feature!";
            }
        }

        /*
            Set interval for refreshing data
        */
        window.setInterval(function(){geoLocate();},3000);
        window.setInterval(function(){updateMapPins();},3000);
        /*
            Displays the user position
         */
        function showPosition(position){
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

			// send AJAX request to save position in the database
			var pos = { 'lat': latitude, 'lng': longitude };
			$.post('../business/ajax.position.php', pos, function (data) {
				// Parse JSON response
				var resp = $.parseJSON(data);
				
				var success = resp.success;
				var msg = resp.msg;
				
				// If operation was successful, new HTML content is displayed, otherwise the error message is shown
				if (success == 1) {
					$('#facts').html(msg);
				} else {
					alert("An error occurred on the server side: " + msg);
				}
			} );
			
            mapElement.style.height = '400px';
            latlon = new google.maps.LatLng(latitude, longitude);

            var mapOptions = {
                center: latlon,
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
            };
            if(!mapExists){
                var map = new google.maps.Map(mapElement, mapOptions);
                mapGlob = map;
                mapExists = true;
            }

        }
        /*
            Sends a GET request and loads the data from markers.xml
        */
        function updateMapPins(){
            req1 = new XMLHttpRequest;
            req1.onreadystatechange = loadPins;
            req1.open("GET","../public/xml/markers.xml");
            req1.send(null);
        }
        /*
           Loads pins from the XML-Response and displays them on the map
           if request was successful
         */
        function loadPins(){
            var longitude = "";
            var latitude = "";
            if(req1.status == 200 && req1.readyState == 4){
                domDocument = req1.responseXML;
                markerData = domDocument.getElementsByTagName("marker");

                for(i = 0; i < markerData.length; i++){
                    longitude = markerData[i].getAttribute("lng");
                    latitude = markerData[i].getAttribute("lat");
                    latlon = new google.maps.LatLng(latitude, longitude);
                    var marker = new google.maps.Marker({position:latlon,map:mapGlob,title:"You are here!"});
                    marker.setMap(mapGlob);
                }
            }
        }
        /*
            Error handling
         */
        function showError(error){
            switch(error.code){
                case error.PERMISSION_DENIED:
                    mapElement.innerHTML = "Denied the request for Geolocation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    mapElement.innerHTML = "Location information is not available.";
                    break;
                case error.TIMEOUT:
                    mapElement.innerHTML = "The request timed out.";
                    break;
                case error.UNKNOWN_ERR:
                    mapElement.innerHTML = "An unknown error occurred.";
                    break;
            }
        }

    </script>
</html>


