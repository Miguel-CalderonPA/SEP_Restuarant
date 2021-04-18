<?php
		session_start();
                //error_reporting(-1);
                //ini_set("display error",1);
                $gName=$_POST['fruipName'];
                $useName=$_SESSION['useName'];
                $options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
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
		$sql="SELECT gName FROM fruips WHERE gName=:gName";
		$stmt=$myPDO->prepare($sql);
		$stmt->bindValue(':gName',$gName);
               try{
			 $stmt->execute();
		}
		catch(PDOException $e){

			echo $e->getMessage();

		}
		$fruips=$stmt->fetch();
                if(empty($fruips['gname']))
                {
			
			$sql="Insert into fruips(gName, owner) values(:gName, :useName)";
			$stmt=$myPDO->prepare($sql);
			$stmt->bindValue(':gName', $gName);
			$stmt->bindValue(':useName',$useName);
			$stmt->execute();
			$sql="Insert into membership(useName, gName, pending) values(:useName, :gName, false)";
                        $stmt=$myPDO->prepare($sql);
                        $stmt->bindValue(':gName', $gName);
                        $stmt->bindValue(':useName',$useName);
                        $stmt->execute();

			echo("<script>alert('Fruip created successfully! Redirecting to fruips page.');</script>");
			echo ("<script>location.href='../Fruip/FruipDashboard.html';</script>");



                }
		else{
			echo("<script>alert('Error: A fruip already exists with this name! Please try again.');</script>");


		}


?>


