<?php
	//Author:Dan Klein
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
    } // end try
    catch(PDOException $e){
		//check for connection errors
        print"Error!: ".$e->getMessage()."<br/>";
        die();
    } // end catch

    //prepare input for sanatization
    $sql="SELECT usename FROM membership WHERE gName=:gName and useName=:useName";
    $statement=$myPDO->prepare($sql);
    $statement->bindValue(':gName',$gName);
	$statement->bindValue(':useName',$useName);
    
	try{
        $statement->execute();
    } // end try
    catch(PDOException $e){
		echo $e->getMessage();
	} // end catch
    
	$user=$statement->fetch();
    $count=0;
    
	if(!empty($user['usename'])) {
		$sql="Select voting from fruips where gName=:gName";
		$statement=$myPDO->prepare($sql);
        $statement->bindValue(':gName',$gName);
		$statement->execute();
		$checkVotes=$statement->fetch();
		if(!$checkVotes['voting']){
			echo("<script> alert('Fruip is not currently voting, redirecting to dashboard');location.href='FruipDashboard.html';</script>");
		} // end if
	} // end if
	else{
		echo("<script> alert('You do not belong to this fruip! Redirecting to dashboard');location.href='FruipDashboard.html';</script>");
	} // end else
?>
