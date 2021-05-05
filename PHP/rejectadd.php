<?php
	//Author:Dan Klein
	//purpose: allows a user to reject a fruips add attempt
	session_start();
	//error_reporting(-1);
    	ini_set("display error",0);
	
	// Grab data and setup PDO
	$gName=$_POST['gName'];
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

	//prepare input for sanatization, gets username to check if user was added
    	$sql="SELECT usename, gname  FROM membership WHERE gName=:gName and useName=:useName";
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
    	$count=0;
	
	if(!empty($user['gname'])) {
		//user exists, removing add request
		$sql="DELETE from membership where gName=:gName and useName=:useName";
		$statement=$myPDO->prepare($sql);
        	$statement->bindValue(':gName',$gName);
		$statement->bindValue(':useName',$useName);
		$statement->execute();
		echo("<script>alert('{$useName} was found');</script>");
	} // end if
	else {
		echo("<script>alert('{$useName} was not found');</script>");
	}
?>
