<?php
		session_start();
        $gName=$_POST['fruipName'];
        $useName=$_SESSION['useName'];
		$options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,\PDO::ATTR_EMULATE_PREPARES => false,];
        try{
            //create PDO object
            $myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);
        }
        catch(PDOException $e){
			//check for connection errors
            print"Error!: ".$e->getMessage()."<br/>";
            die();
		}
		//prepare input for sanatization
		$sql="SELECT gName, owner FROM fruips WHERE gName=:gName";
		$stmt=$myPDO->prepare($sql);
		$stmt->bindValue(':gName',$gName);
		$stmt->bindValue(':owner',$owner);
        try{
			 $fruips=$stmt->execute();
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
        if((!empty($fruips['gName'])) && ($owner == $usename)) {
			
			
			$sql="DELETE FROM membership WHERE gName=:gName";
            $stmt=$myPDO->prepare($sql);
            $stmt->execute();
			
			$sql="DELETE FROM fruips WHERE gName=:gName";
			$stmt=$myPDO->prepare($sql);
			$stmt->execute();
			
			echo("<script>alert('Fruip deleted successfully! Redirecting to fruips page.');</script>");
			echo ("<script>location.href='../Fruip/FruipDashboard.html';</script>");
        }
		else{
			echo("<script>alert('Error: Could not delete, are you the owner of this fruip?');</script>");
		}
?>


