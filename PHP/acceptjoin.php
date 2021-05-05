<?php
		//Author:Dan Klein
		//purpose: Allows a fruip ownerr to accept a join request sent by a potential member.
        session_start();
		//error_reporting(-1);
        ini_set("display error",0);
		
		// Grab data and setup PDO
		$pos=$_POST["fruipNum"];
        $groups=array_values($_SESSION['owned']);
		$gName=$groups[$pos];
		$useName=$_POST['useName'];
        $options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES => false, ];

        try{ //create PDO object
			$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
		}
        catch(PDOException $e){
			//check for connection errors
            print"Error!: ".$e->getMessage()."<br/>";
            die();
	} 

        //prepare input for sanatization
        $sql="SELECT usename FROM membership WHERE gName=:gName and useName=:useName and pending=true";
        $statement=$myPDO->prepare($sql);
        $statement->bindValue(':gName',$gName);
	$statement->bindValue(':useName',$useName);
	try{ // attempted SQL statement
			$statement->execute();
        }
        catch(PDOException $e){
		echo $e->getMessage();
	} 
		
        $user=$statement->fetch();
        //$count=0; // not sure  for this
		
        if(!empty($user['usename'])) {	//user actually requested to join this fruip
			//permanently add user to fruip
            $sql="Update  membership set pending=false where gName=:gName and useName=:useName";
			// sanatization
		    $statement=$myPDO->prepare($sql);
			// bind values from php with database columns
            $statement->bindValue(':gName',$gName);
			$statement->bindValue(':useName',$useName);
			$statement->execute();
		} // end if
?>
