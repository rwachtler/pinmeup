<?php
/**
 * Created by PhpStorm.
 * User: R.Wachtler
 * Date: 01.12.14
 */?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../public/css/normalize-css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../public/css/main.css">
    <script src="../public/components/jquery/jquery.js"></script>
    <script src="../public/components/bootstrap/bootstrap.js"></script>
</head>

    <body onload="geoLocate()">
    <div class="header row">
        <div class="container col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1>Pin Me Up!</h1>
        </div>
    </div>
    <div class="content row">
        <div class="container">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h1>Facts</h1>
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
            Displays the user position
         */
        function showPosition(position){
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            mapElement.style.height = '400px';
            latlon = new google.maps.LatLng(latitude, longitude);

            var mapOptions = {
                center: latlon,
                zoom: 14,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
            };
            var map = new google.maps.Map(mapElement, mapOptions);
            var marker = new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
        }

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


