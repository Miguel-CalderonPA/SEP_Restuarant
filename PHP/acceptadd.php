,<?php
		//Author:Dan Klein
		//Purpose: Allow a user to accept an add request sent by a fruip owner, adding them to the group
        session_start();
        //error_reporting(-1);
        ini_set("display error",0);
		
		// grab data and setup PDO
		$gName=$_POST['gName'];
		$useName=$_SESSION['useName'];
        $options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES => false, ];

        try{ //create PDO object
			$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
        }
		catch(PDOException $e){ //check for connection errors
			print"Error!: ".$e->getMessage()."<br/>";
			die();
        }

        //prepare input for sanatization
        $sql="SELECT usename FROM membership WHERE gName=:gName and useName=:useName and reqadd=True";
		// sanatization
        $statement=$myPDO->prepare($sql);
		// bind values from php with database columns
        $statement->bindValue(':gName',$gName);
		$statement->bindValue(':useName',$useName);
        try{ // attempt SQL statement
			$statement->execute();
        }
        catch(PDOException $e){
			echo $e->getMessage();
		}
		
        $user=$statement->fetch();
       // $count=0; // not sure what this is
		
		 //user was actually added to this fruip, nothing was modified in front end post request. 
		if(!empty($user['usename'])) {
			//update membership table to add user
            $sql="Update membership set pending=false, reqadd=false  where gName=:gName and useName=:useName";
			// sanatization
		    $statement=$myPDO->prepare($sql);
			// bind values from php with database columns
            $statement->bindValue(':gName',$gName);
			$statement->bindValue(':useName',$useName);
			$statement->execute();
        } // end if 
?>
