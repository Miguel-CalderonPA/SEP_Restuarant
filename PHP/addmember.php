<?php
	//Author: Dan Klein
	//Purpose: Allows a fruip owner to send an add request to the user
	session_start();
    	//error_reporting(-1);
    	//ini_set("display error",1);
     
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
		//confrim that user is marked to recieve an add request
		$sql="SELECT usename, reqadd, pending, gname  from membership where useName=:useName and gName=:gName";
        	$stmt=$myPDO->prepare($sql);
       		$stmt->bindValue(':gName', $gName);
        	$stmt->bindValue(':useName',$useName);
		
		try{
			$stmt->execute();
		}
		catch(PDOException $e){
			echo"<script>alert('Error: User does not exist');</script>";
		}
		
		$checkIfMember=$stmt->fetch();
        
		if(empty($checkIfMember['gname'])) {
			//if not a member, check if user actually exists
			$sql="SELECT usename from users where useName=:useName";
            		$stmt=$myPDO->prepare($sql);
            		$stmt->bindValue(':useName',$useName);
            		try{
				$stmt->execute();
			}
            		catch(PDOException $e){
                		echo"<script>alert('Error: User does not exist');</script>";
            		}
            
			$checkIfExists=$stmt->fetch();
			
			if(empty($checkIfExists['usename'])){ //user does not exist
				echo"<script>alert('Error: User does not exist');</script>";
			} 
			else{	
				//user exists and is not a member, add them as a member
                		$sql="Insert into membership(useName, gName, pending, reqadd) values(:useName, :gName, true, true)";
                		$stmt=$myPDO->prepare($sql);
                		$stmt->bindValue(':gName', $gName);
                		$stmt->bindValue(':useName',$useName);
                		$stmt->execute();

               			echo("<script>alert('Add request sent successfully!');</script>");
			} // end else
		} // end if 
        	else{
			echo("<script>alert('Error: You have already sent a request to this user or they are already a member!');</script>");
        	}
	} // end if empty fruip 
    	else{ //user is somehow trying to add to a nonexistant fruip
		echo("<script>alert('Error: Fruip does not exist.');</script>");
	} 
?> 
