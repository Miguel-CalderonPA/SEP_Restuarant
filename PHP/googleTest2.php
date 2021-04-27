<?php

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/googleplace2/src/GooglePlaces.php";
include_once($path);

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/googleplace2/src/GooglePlacesClient.php";
include_once($path);


$google_places = new joshtronic\GooglePlaces('AIzaSyCVTXoxNH-OCRPWsxQB9jQHLbr_MFiarw8');

$google_places->location = array(42.6641,-73.7857);
$google_places->rankby   = 'distance';
$google_places->types    = 'restaurant'; // Requires keyword, name or types
$results                 = $google_places->nearbySearch();
echo $results[0]
?>