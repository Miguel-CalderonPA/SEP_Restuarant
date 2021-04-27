1<?php

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/googleplace3/src/PlacesApi.php";
include_once($path);

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/GuzzleHttp/src/Client.php";
include_once($path);

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/GuzzleHttp/Psr7/src/Utils.php";
include_once($path);

use SKAgarwal\GoogleApi\PlacesApi;

$googlePlaces = new PlacesApi('AIzaSyCVTXoxNH-OCRPWsxQB9jQHLbr_MFiarw8'); # line 1
$response = $googlePlaces->placeAutocomplete('Albany New York'); # line 2
?>