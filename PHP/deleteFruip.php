<?php
		// Author: Miguel Calderon with substantial help from Dan Kline
		session_start();
		// Variables
        $useName=$_SESSION["useName"];
		$userFruips=$_SESSION["owned"];	
		$fruipNum = $_POST["fruipNum"];
		$gName = $userFruips[$fruipNum];
		$confirmation = $_POST["confirmDel"];
		//echo("<script>alert($confirmation);</script>"); // testing to be deleted
		// If user doens't cancel deletion process
		if ($confirmation==1) {
			$options = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,\PDO::ATTR_EMULATE_PREPARES => false,];
			try{
				//create PDO object (connect to server)
				$myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);
			} // end try
			catch(PDOException $e){ // error
				//check for connection errors
				print"Error!: ".$e->getMessage()."<br/>";
				die();
			} // end catch
			//prepare input for sanatization
			$sql="SELECT gName FROM fruips WHERE gName=:gName";
			$stmt=$myPDO->prepare($sql);
			$stmt->bindValue(':gName',$gName);
			// Try to perform select 
			try{
				 $fruips=$stmt->execute();
				 echo "execution " . $fruips . " line";
			} // end try
			catch(PDOException $e){ // if failed
				echo $e->getMessage();
			} // end catch
			$fruips=$stmt->fetch(); // fetch to make sure it is there
			if(!empty($fruips['gname'])) { // test ^
				
				// Delete from the members 
				$sql="DELETE FROM membership WHERE gName=:gName";
				$stmt=$myPDO->prepare($sql);
				$stmt->bindValue(':gName',$gName);
				$stmt->execute();
				// Delete from the Fruip
				$sql="DELETE FROM fruips WHERE gName=:gName";
				$stmt=$myPDO->prepare($sql);
				$stmt->bindValue(':gName',$gName);
				$stmt->execute();
				// Let user know and send them a page back
				echo("<script>alert('Fruip deleted successfully! Redirecting to fruips page.');</script>");
				echo ("<script>location.href='../Fruip/FruipDashboard.html';</script>");
			} // end !empty
			else{ // let user know if failed
				echo("<script>alert('Error: Could not delete, does this fruip exist?');</script>");
			} // end else
		} // end if user hit cancel
?>
