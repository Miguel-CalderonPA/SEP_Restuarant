<?php
                session_start();
                //error_reporting(-1);
                //ini_set("display error",1);
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
               	if(!empty($fruips['gname']))
                {
			

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
                        if(empty($checkIfMember['gname']))
                        {
				if(empty($checkIfMember['usename'])){
					echo"<script>alert('Error: User does not exist');</script>";
			    	}
				else{

				
                            		$sql="Insert into membership(useName, gName, pending, reqadd) values(:useName, :gName, true, true)";
                            		$stmt=$myPDO->prepare($sql);
                            		$stmt->bindValue(':gName', $gName);
                            		$stmt->bindValue(':useName',$useName);
                            		$stmt->execute();

                           		echo("<script>alert('Add request sent successfully!');</script>");
				}
                            
                        }
                        else{
                            echo("<script>alert('Error: You have already sent a request to this user or they are already a member!');</script>");
                        }


                }
                else{
                        echo("<script>alert('Error: Fruip does not exist.');</script>");


                }
?>                

