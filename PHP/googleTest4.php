<?php
// Author: Miguel Calderon
session_start(); // start session

// Setup for XML file
$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
// The places api information
$url = 'https://maps.googleapis.com/maps/api/place/search/xml?location=42.6641,-73.7857&radius=500&sensor=false&key={API-KEY}&types=restaurant';
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/xml?query=restaurants+12205+&radius=5&key={API-KEY}&types=restaurant';
// Grab the contents of the file, with no base path and using prebuilt context resource
$xmlData = file_get_contents($url, false, $context);
// grab the XML data
$xmlObject = simplexml_load_string($xmlData) or die("SimpleXML fails, isn't defined");
// Setup array for all locations
$allLocations=[];
// Setup size for an image
$maxWidth = "400";
$maxHeight = "200";
// Setup the baseURL for photo reference
$baseUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=" . $maxWidth . "&maxheight" . $maxHeight . "&photoreference=";
// for each locations store the name, address, rating and a photo of locations
//var_dump($xmlObject);
var_dump(sizeof($xmlObject->result));
$numLocations = sizeof($xmlObject->result);
foreach($xmlObject->result as $location) {
	$locationsAttr["name"] = (string)$location->name;
	$locationsAttr["address"] = (string)$location->formatted_address;
	$locationsAttr["rating"] = (string)$location->rating;
	$locationsAttr["photo_reference"] = (string)$baseUrl . (string)$location->photo->photo_reference . "&key={API-KEY}";
	// Testing Code - to be deleted later
	echo "<p>The location's name is: " . $locationsAttr['name'] . "</p>";
	echo "<p>The address is: " . $locationsAttr['address'] . "</p>";
	echo "<p>The rating is: " . $locationsAttr['rating'] . "</p>";
	echo "<p>The imageURL is " . $locationsAttr['photo_reference'] . "</p>";
	// push each locations into one large array
	array_push($allLocations, $locationsAttr); 
} // end foreach locations
// testing - to be deleted
foreach($allLocations as $current) {
	var_dump($current);
	echo "<br><br>";
}
// Store all locations into session array
$_SESSION['locations'] = $allLocations;
// testing - to be deleted later
var_dump($_SESSION['locations']);

// GET IMAGE https://maps.googleleap
/*
$uRL = "https://maps.googleapis.com/maps/api/place/search/xml?location=42.6641,-73.7857&radius=500&sensor=false&key={API-KEY}&types=restaurant";
//var_dump($uRL);
$xML= file_get_contents($uRL) or die("Failed to Load");
//var_dump($xML);
$parser= new XMLParser($xML)or die("Failed to Load");
$parser->Parse();
var_dump($parser);	
foreach($parser->PlaceSearchResponse->result as $result) { };
*/
//var_dump($result); }
//	$name = $result->name;
//}

// name, address, rating, price level
/*
$url = 'https://maps.googleapis.com/maps/api/place/search/xml?location=42.6641,-73.7857&radius=500&sensor=false&key={API-KEY}&types=restaurant';
$xmlData = file_get_contents($url);
$xmlObject = simplexml_load_string($xmlData) or die("SimpleXML fails, isn't defined");// HOW IS THIS DISABLED
//echo $json;

*/
//var_dump(ini_get('allow_url_fopen')); 


/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$xml = curl_exec($ch);
curl_close($ch);

$cont = produce_XML_object_tree($xml);
var_dump($cont);
/*
$json_a = json_decode($json, true); //json decoder 
echo $json_a['results'][0]['geometry']['location']['lat']; 
echo $json_a['results'][0]['geometry']['location']['lng']





//$jsonContent = json_decode($json, true);
//var_dump($jsonContent);
//$jsonContent = explode("result", $str);
//var_dump($jsonContent);
//foreach ($jsonContent['results'] as $result) {
//	var_dump($result);
    // now you have the $result array that contains the location of the place
    // and the name ($result['formatted_address'], $result['name']) and other data.

//}
$place = $jsonContent['result']['name'];
//echo $place;
$jsonContent['result']['formatted_phone_number'];
$jsonContent['result']['formatted_address'];
$jsonContent['result']['website'];
*/

?>
