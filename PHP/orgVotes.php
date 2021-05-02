<?php
	// Author: Miguel Calderon with substantial help from Dan Klein
	// Start session to gather data from Session Array
	// Open post array for data from previous page
	session_start();
	$useName= $_SESSION["useName"];
	$vote=(int)$_POST["vote"];
	$userFruips=$_SESSION["fruips"];
	$fruipNum = $_POST["fruipNum"];	
	$gName = $userFruips[$fruipNum];
	$numVotesLeft = 20; // initalize, should be re-initalized once data returns from database 
	
	// TESTING OUTPUT -- TO BE DELETED
//	if ($vote == 1) // if liked
//		echo("<p>$useName LIKED for Fruip $gName </p>");
//	else // if dislike
//		echo("<p> $useName DISLIKED for Fruip $gName</p>");
	//---------------------------
	
	//extra options to connect to database
	$options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,\PDO::ATTR_EMULATE_PREPARES => false,];
	try{//create PDO object (connect to server)
		$myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);
	} // end try
	catch(PDOException $e){ //check for connection errors
		print"Error!: ".$e->getMessage()."<br/>";
		die();
	} // end catch
			
	/* FOR RESETTING ARRAY--------------------- to be deleted 
		$updateVotesForDelete = null;
		$sql="update membership set votes=:votes where gName=:gName and useName=:useName";
        $stmt=$myPDO->prepare($sql);
        $stmt->bindValue(':gName',$gName);
		$stmt->bindValue(':useName',$useName);
		$stmt->bindValue(':votes', $updateVotesForDelete);
        $stmt->execute();
	//------------------------------*/
		// Create SELECT statement
		$sql="SELECT usename,array_to_json(votes) as votes, voted, currplace FROM membership WHERE gName=:gName and useName=:useName";
		//prepare input for sanatization
        $stmt=$myPDO->prepare($sql); 
		// create associations
        $stmt->bindValue(':gName',$gName); 
		$stmt->bindValue(':useName',$useName);
		
		// Try to perform select 
		try{
			$fruips=$stmt->execute();
		} // end try
		catch(PDOException $e){ // if failed
			echo $e->getMessage();
		} // end catch
		// Grab answer
		$user=$stmt->fetch();
		// Grab if they have already voted
		$voted = $user['voted'];
			
		// TESTING ONLY -- To be deleted
		//if ($voted)
		//	echo "<p> USER NOT DONE VOTING</p>";
		//else
		//	echo "<p> USER STILL VOTING </p>";
		$numVotesLeft = $user['currplace'];
		//echo "<p>Votes Left: $numVotesLeft </p>";
		// --------------------------
		if (!$voted) {
			// clean up JSON from database
			$votes = rtrim($user['votes']);
			$votes=json_decode($votes, true);
			// if first vote
			if($votes == null) {
				// initalize first iteration array
				$newVotes=array(); 
				$newVotes[0] = $vote; // set value
			} // end if
			else {// initalize all other iteration of the array
				$convertedArray = array(); 
				// for each location convert boolean to integer and add to array
				foreach($votes as $places) {
					array_push($convertedArray, (int)$places);
				} // end foreach
				// intialize array for current vote value
				$votes2=array();
				// set current value to first index
				$votes2[0] = $vote;
				// create 1 array from 2 arrays
				$newVotes = array_merge($votes2, $convertedArray);
			} // end else
			// deduct count of restuarants
			$numVotesLeft--;
			//$numVotesLeft = 20; // for resetting
			if ($numVotesLeft < 1) { // when count below # of restuarants
				$voted = 1; // done voting

			} // end if
			else { // if restaurants are still in voting
				$voted=0;
			} // end else
			// select statemtn to update database
			$sql="update membership set currplace=:currplace, votes=:votes, voted=:voted where gName=:gName and useName=:useName";
			// TESTING ONLY -- to be deleted
			//$sql="update membership set currplace=:currplace, voted=:voted where gName=:gName and useName=:useName";
			// prepare input for sanatization
			$stmt=$myPDO->prepare($sql);
			// Create associations
			$stmt->bindValue(':gName',$gName);
			$stmt->bindValue(':useName',$useName);
			$stmt->bindValue(':votes',pgArray($newVotes));
			$stmt->bindValue(':currplace',$numVotesLeft);
			$stmt->bindValue(':voted', $voted);
			// Actually do the work
			$stmt->execute();
			if($numVotesLeft<1){


				$sql="SELECT owner FROM fruips WHERE gName=:gName";
                                $stmt=$myPDO->prepare($sql);
                                $stmt->bindValue(':gName', $gName);
                                $stmt->execute();
                                $checkOwn=$stmt->fetch();
                                if($checkOwn['owner']==$useName){

                                        $sql="Update fruips set voting=False where gName=:gName";
                                        $stmt=$myPDO->prepare($sql);
                                        $stmt->bindValue(':gName', $gName);
                                        $stmt->execute();

                                        $sql="SELECT array_to_json(votes) as votes from membership where gname=:gname and voted=True";
                                        $stmt=$myPDO->prepare($sql);
                                        $stmt->bindValue(':gname', $gName);
                                        $stmt->execute();
                                        $userVotes=$stmt->fetchAll();
                                        $countVotes=0;
                                        $tallyArr=[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
                                        foreach($userVotes as $aUser){
                                                $finishedVotes = rtrim($aUser['votes']);
                                                $finishedVotes=json_decode($finishedVotes, true);
                                                $convertedArray = array();
                                                // for each location convert boolean to integer and add to array
                                                foreach($finishedVotes as $places) {
                                                        array_push($convertedArray, (int)$places);
                                                } // end foreach
                                                while($countVotes<count($convertedArray)){

                                                        $tallyArr[$countVotes]+=$convertedArray[$countVotes];
                                                        $countVotes++;
                                                }
                                                $countVotes=0;
                                        }
                                        $maxVote=max($tallyArr);
                                        $maxIndex=array_search($maxVote, $tallyArr);
					$maxIndex+=1;
                                        echo("<script>alert({$maxIndex});</script>");
                                        $sql="DELETE from places WHERE gname=:gname and name!=(select name from (SELECT name, ROW_NUMBER () OVER (ORDER BY name) as nth from (select name from places where gname=:gname) as names) as allnames where nth={$maxIndex})";
                                        $stmt=$myPDO->prepare($sql);
                                        $stmt->bindValue(':gname', $gName);
                                        $stmt->execute();
                                }



			}
		} // end if not done voting
		else {
		echo "<p> YOU HAVE ALREADY VOTED </p>";	

			

		} // end if done voting
	
//-------------------------Convert JSON Array to PHP Array---------------------------------//
	function pgArray($arr){
		// Initalize Arrays
		$result=array(); 
		// For each item convert characters to proper JSON
		foreach($arr as $item){
			$item=str_replace('"', '\\"', $item);
			// set into array
			$result[]=$item;
		} // end foreach
		// finish conversion
		return '{'.implode(",",$result).'}';
	} // end function
?>
