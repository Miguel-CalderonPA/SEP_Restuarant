<?php
	//Author:Dan Klein
	//Purpose: Allows a fruip owner to reject a join request from a potential member or remove an existing member
	session_start();
	//error_reporting(-1);
    	ini_set("display error",0);
	
	// Grab data and setup PDO
	$pos=$_POST["fruipNum"];
    	$groups=array_values($_SESSION['owned']);
	$gName=$groups[$pos];
	$useName=$_POST['useName'];
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
	$sql="SELECT usename FROM membership WHERE gName=:gName and useName=:useName";
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
		//user exists as member, remove from fruip
		$sql="DELETE from membership where gName=:gName and useName=:useName";
		$statement=$myPDO->prepare($sql);
        	$statement->bindValue(':gName',$gName);
		$statement->bindValue(':useName',$useName);
		$statement->execute();
		echo("<script>alert('{$useName} was removed');</script>");
    	} // end if
	else {
		echo("<script>alert('{$useName} was not found');</script>");
	}
?>
