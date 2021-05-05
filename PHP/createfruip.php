<?php
	//Author:Dan Klein
	session_start();
    //error_reporting(-1);
    //ini_set("display error",1);
	
	// Grab data and setup SQL
    	$gName=$_POST['fruipName'];
    	$useName=$_SESSION['useName'];
	$namePattern="/^[a-zA-Z0-9]{1,20}$/";
    	$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES => false, ];
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
		$stmt->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
	} 
	
	// Grab the data (Fruips)
	$fruips=$stmt->fetch();
	
	// Check for proper Fruip name
	if(!preg_match($namePattern, $gName)) {
		echo("<script>alert('Error: Fruip names can not contain special characters or whitespace, and cannot be more than 20 characters long.');</script>");
	} 
    	else if(empty($fruips['gname'])) { // if proper name and doesn't exist
		// Add Fruip to the database
		$sql="Insert into fruips(gName, owner) values(:gName, :useName)";
		// sanatization
		$stmt=$myPDO->prepare($sql);
		// bind values from php with database columns
		$stmt->bindValue(':gName', $gName);
		$stmt->bindValue(':useName',$useName);
		$stmt->execute(); // do SQL statement
		// Add Owner to the member table of fruip
		$sql="Insert into membership(useName, gName, pending) values(:useName, :gName, false)";
        	$stmt=$myPDO->prepare($sql);
        	$stmt->bindValue(':gName', $gName);
        	$stmt->bindValue(':useName',$useName);
        $stmt->execute(); // do SQL statement
		// Let user know of success
		echo("<script>alert('Fruip created successfully! Redirecting to fruips page.');</script>");
		echo ("<script>location.href='../Fruip/FruipDashboard.html';</script>");
	} // end else
	else{ // if proper name, but already exists
		echo("<script>alert('Error: A fruip already exists with this name! Please try again.');</script>");
	} // end else
?>


