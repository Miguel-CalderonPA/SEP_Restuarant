<?php


$url = 'https://maps.googleapis.com/maps/api/place/search/xml?location=42.6641,-73.7857&radius=500&sensor=false&key=AIzaSyCVTXoxNH-OCRPWsxQB9jQHLbr_MFiarw8&types=restaurant';
$xmlData = file_get_contents($url);
$xmlObject = simplexml_load_string($xmlData) or die("SimpleXML fails, isn't defined");// HOW IS THIS DISABLED
//echo $json;


var_dump(ini_get('allow_url_fopen')); 


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