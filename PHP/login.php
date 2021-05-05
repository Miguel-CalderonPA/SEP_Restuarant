<?php
	//Author:Dan Klein
	//Purpose: logs the user into the website
	//error_reporting(-1);
   	//ini_set("display error",1);
	
	// Grab data and setup PDO
	$pwd=$_POST['pwd'];
    	$useName=$_POST['useName'];
    	$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES   => false, ];
    	try{
        	$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
	}
    	catch(PDOException $e){
		print"Error!: ".$e->getMessage()."<br/>";
        	die();
    	}
	//get salt and hash for user
	$sql="SELECT salt,hash,usename FROM users WHERE usename=:useName";
	$stmt=$myPDO->prepare($sql);
    	$stmt->bindValue(':useName',$useName);
	try{
		$stmt->execute();
	}
	catch(PDOException $e){
		"<script>alert('login failed: username or password is invalid');</script>";
	}
	$pass=$stmt->fetch();
	$hashedPass=crypt($pwd, $pass['salt']);
    	if($pass['hash']==$hashedPass){ //salted and hashed pass matches hashed password
		echo "<script>alert('login successful');</script>";
		//create session variables for user
		session_start();
		$_SESSION['useName']=$useName;
		$_SESSION['created']=time();
		$_SESSION['refresh']=time();
		echo ("<script>location.href='../HTML/Fruip/FruipDashboard.html';</script>");
    	} // end if hashedPass
    	else {
		//no user was found with this username or password was wrong
        	echo "<script>alert('login failed: username or password is invalid');</script>";
    	}
?>


