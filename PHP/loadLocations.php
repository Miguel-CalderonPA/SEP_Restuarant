<?php
	// Author: Miguel Calderon, Dan Klein
	session_start(); // start session
	//purpose: retrieve restaurants from API, store in database, and initiate voting process. Called in fruip management on initiate voting.
	// Grab all data and setuo PDO
	$pos=$_POST["fruipNum"];
   	$groups=array_values($_SESSION['owned']);
    	$gName=$groups[$pos];
    	$useName=$_SESSION['useName'];
    	$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES   => false, ];
	$zip=$_POST['zip'];
	$zip=strval($zip);
	$zipPattern="/^[0-9]{5}$/";
	
	try{
        	//create PDO object
        	$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
    	} // end try
    	catch(PDOException $e){
        	//check for connection errors
        	print"Error!: ".$e->getMessage()."<br/>";
        	die();
    	} // end catch
    	//get group voting status
    	$sql="SELECT gName, voting  FROM fruips WHERE gName=:gName";
    	$stmt=$myPDO->prepare($sql);
    	$stmt->bindValue(':gName',$gName);
    	try{
		$fruips=$stmt->execute();
    	}
    	catch(PDOException $e){
		echo $e->getMessage();
	}
     
	$fruips=$stmt->fetch();
	
	if(!preg_match($zipPattern, $zip)) {
		//zip code is invalid
		echo"<script>alert('Error: zip codes must only contain numbers and be 5 digits long.');</script>";
	}
    	else if(!empty($fruips['gname']))
    	{	//fruip exists
		if(!$fruips['voting']){
			//setting voting entry for fruip to true
			$sql="update fruips set voting=True  where gName=:gName";
			$stmt=$myPDO->prepare($sql);
			$stmt->bindValue(':gName', $gName);
			$stmt->execute();
					
			//deleting the last winning restauraunt from the table
			$sql="delete from places where gname=:gName";
			$stmt=$myPDO->prepare($sql);
			$stmt->bindValue(':gName',$gName);
			$stmt->execute();

			//set up table for restaurants, load restaurants into table, set counts for members, possible order restaurants alphabetically and take that entry
			$locationArr=getXMLArr($zip);
			$count=0;
			
			foreach($locationArr as $placeArr){ //store restaurant 
				$sql="insert into  places(gname, name, image, location) values(:gName,:name,:image,:location)";
				$stmt=$myPDO->prepare($sql);
				$stmt->bindValue(':gName', $gName);
				$stmt->bindValue(':name', $placeArr['name']);
				$stmt->bindValue(':image', $placeArr['photo_reference']);
				$stmt->bindValue(':location', $placeArr['address']);
				$stmt->execute();
				$count++;
			} // end foreach
			//initialize count variables for every user
			$sql="Update membership set currplace={$count}, voted=False, votes=null  WHERE gName=:gName";
			$stmt=$myPDO->prepare($sql);
			$stmt->bindValue(':gName',$gName);
			try{
				$fruips=$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			echo("<script>alert('Voting initiated Successfully!');</script>");
		} // end if not voting
		else{
			//voting already in progress
			echo("<script>alert('Error: Voting is already in progress!');</script>");
		}
	} // end if fruip name not empty
    	else{
		echo("<script>alert('Error: A group with this name does not exist! Please try again.');</script>");
	} 

//-----------Function getXMLArr----------------/

function getXMLArr($zip){
	// Setup for XML file
	$context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
	// The places api information

	$url = "https://maps.googleapis.com/maps/api/place/textsearch/xml?query=restaurants+{$zip}+&radius=500&key=GOOGLE_API_KEY=restaurant";
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
	foreach($xmlObject->result as $location) {
		$locationsAttr["name"] = (string)$location->name;
		$locationsAttr["address"] = (string)$location->formatted_address;
		$locationsAttr["rating"] = (string)$location->rating;
		$locationsAttr["photo_reference"] = (string)$baseUrl . (string)$location->photo->photo_reference . "&key=GOOGLE_API_KEY";
	// Testing Code - to be deleted later
	//echo "<p>The locations name is: " . $locationsAttr['name'] . "</p>";
	//echo "<p>The address is: " . $locationsAttr['address'] . "</p>";
	//echo "<p>The rating is: " . $locationsAttr['rating'] . "</p>";
	//echo "<p>The imageURL is " . $locationsAttr['photo_reference'] . "</p>";
		// push each locations into one large array
		array_push($allLocations, $locationsAttr); 
	} // end foreach locations
// testing - to be deleted
//foreach($allLocations as $current) {
	//var_dump($current);
	//echo "<br><br>";
//}
// Store all locations into session array
//$_SESSION['locations'] = $allLocations;
// testing - to be deleted later
//var_dump($_SESSION['locations']);
//$_SESSION['count']=count($allLocations)-1;

	return $allLocations;
}
?>
