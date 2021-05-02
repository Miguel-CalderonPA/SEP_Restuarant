<?php
		//Author:Dan Klein

                session_start();

                //error_reporting(-1);
                ini_set("display error",0);
		$pos=$_POST["fruipNum"];
                $groups=array_values($_SESSION['owned']);
		$gName=$groups[$pos];
		$useName=$_POST['useName'];
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
                $sql="SELECT usename FROM membership WHERE gName=:gName and useName=:useName";
                $statement=$myPDO->prepare($sql);
                $statement->bindValue(':gName',$gName);
		$statement->bindValue(':useName',$useName);
               try{
                      $statement->execute();
                }
                catch(PDOException $e){

                        echo $e->getMessage();

                }
                $user=$statement->fetch();
                $count=0;
                if(!empty($user['usename']))
           	{
										
                    	$sql="Select voting from fruips where gName=:gName";
		    	$statement=$myPDO->prepare($sql);
               	    	$statement->bindValue(':gName',$gName);
			$statement->execute();
			$checkVotes=$statement->fetch();
			if(!$checkVotes['voting']){

				echo("<script> alert('Fruip is not currently voting, redirecting to dashboard');location.href='FruipDashboard.html';</script>");


			}
			

                }
		else{

			echo("<script> alert('You do not belong to this fruip! Redirecting to dashboard');location.href='FruipDashboard.html';</script>");

		}



?>