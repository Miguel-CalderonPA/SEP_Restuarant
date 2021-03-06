<?php
	//Author:Dan Klein
	//Purpose: gets the result of a fruip that has finished voting
	session_start();
	error_reporting(-1);
        ini_set("display error",-1);
	// Grab data and set PDO
	$pos=$_POST["fruipNum"];
        $groups=array_values($_SESSION['fruips']);
	$gName=$groups[$pos];
	$useName=$_SESSION['useName'];
        $options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES   => false, ];

	try{
        //create PDO object
		$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
        }
        catch(PDOException $e){
		//check for connection errors
		print"Error!: ".$e->getMessage()."<br/>";
            	die();
        }

        //prepare input for sanatization
        $sql="SELECT usename, voted, currplace FROM membership WHERE gName=:gName and useName=:useName";
        $statement=$myPDO->prepare($sql);
        $statement->bindValue(':gName',$gName);
		$statement->bindValue(':useName',$useName);
        try{
		$statement->execute();
        } 
        catch(PDOException $e){
		echo $e->getMessage();
	}
	$user=$statement->fetch();
            
	if(!empty($user['usename'])) {
		//user exists and is a member of the fruip
		$sql="Select voting from fruips where gName=:gName";
	    	$statement=$myPDO->prepare($sql);
           	$statement->bindValue(':gName',$gName);
		$statement->execute();
		$checkVotes=$statement->fetch();

		if($checkVotes['voting']){
			//Fruip is voting, no results to show, redirect user
			echo("<script> alert('Fruip is currently voting, redirecting to dashboard');location.href='FruipDashboard.html';</script>");
		}
		else {
			//Fruip has voted, get result place
			$count=$user['currplace'];
			$sql="Select  *  from  places where gname=:gName";
			$statement=$myPDO->prepare($sql);
			$statement->bindValue(':gName',$gName);
			$statement->execute();
			$getPlaces=$statement->fetch();
			if(!empty($getPlaces["name"])){
				//
				$image=$getPlaces["image"];
				$name=$getPlaces["name"];
				$adr=$getPlaces["location"];
				echo"<div id='div1' name='div1'  ondrop='drop(event)' ondragover='allowDrop(event)'>".
				"<img class='res' src='{$image}' alt='' draggable='true' ondragstart='drag(event)' id='drag1' width='88' height='31'>".
				"</div><div id='div2' ondrop='drop(event)' ondragover='allowDrop(event)'></div>".
				"<div class='profile'><div class='name'>{$name}</div> <div class='local'>".
				"<i class='fas fa-map-marker-alt'></i><span>{$adr}</span> </div></div>";
			} // end if not empty
		} // end else if not voting
	} // end if user not empty
	else{
		echo("<script> alert('You do not belong to this fruip! Redirecting to dashboard');location.href='FruipDashboard.html';</script>");
	}


?>
