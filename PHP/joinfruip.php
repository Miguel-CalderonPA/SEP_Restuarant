<?php
	//Author:Dan Klein
	//Purpose: Allows user to send join requests to fruips
    	session_start();
    	//error_reporting(-1);
    	//ini_set("display error",1);
	
	// Grab data and setup PDO
    	$gName=$_POST['fruipName'];
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
    	$sql="SELECT gName FROM fruips WHERE gName=:gName";
    	$stmt=$myPDO->prepare($sql);
    	$stmt->bindValue(':gName',$gName);
    	try{
		$fruips=$stmt->execute();
	}
    	catch(PDOException $e){
		echo $e->getMessage();
	}
	$fruips=$stmt->fetch();
    
	if(!empty($fruips['gname'])) {
		//fruip exists, check membership status
		$sql="SELECT gName from membership where useName=:useName and gName=:gName";
        	$stmt=$myPDO->prepare($sql);
        	$stmt->bindValue(':gName', $gName);
        	$stmt->bindValue(':useName',$useName);
        	$stmt->execute();

		$checkIfMember=$stmt->fetch();

		if(empty($checkIfMember['gname'])) {
			//user is not a member or has sent a join request already
			$sql="Insert into membership(useName, gName, pending) values(:useName, :gName, true)";
            		$stmt=$myPDO->prepare($sql);
            		$stmt->bindValue(':gName', $gName);
            		$stmt->bindValue(':useName',$useName);
            		$stmt->execute();
			echo("<script>alert('Join request sent successfully!');</script>");
                            
        	} // end if empty fruip name
        	else{
			//user is a member
			echo("<script>alert('Error: You have already sent a request to this fruip or are already a member!');</script>");
        	}
    	} // end if not empty fruip
    	else{
		echo("<script>alert('Error: A fruip with this name does not exist! Please try again.');</script>");
	}
?>                

