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
                         $fruips=$stmt->execute();
                }
                catch(PDOException $e){

                        echo $e->getMessage();

                }
		$fruips=$stmt->fetch();
		var_dump($fruips);
               	if(!empty($fruips['gname']))
                {


                        $sql="SELECT gName from membership where useName=:useName and gName=:gName";
                        $stmt=$myPDO->prepare($sql);
                        $stmt->bindValue(':gName', $gName);
                        $stmt->bindValue(':useName',$useName);
                        $stmt->execute();
			$checkIfMember=$stmt->fetch();
                        if(empty($checkIfMember['gname']))
                        {

                            $sql="Insert into membership(useName, gName, pending) values(:useName, :gName, true)";
                            $stmt=$myPDO->prepare($sql);
                            $stmt->bindValue(':gName', $gName);
                            $stmt->bindValue(':useName',$useName);
                            $stmt->execute();

                            echo("<script>alert('Join request sent successfully!');</script>");
                            
                        }
                        else{
                            echo("<script>alert('Error: You have already sent a request to this fruip or are already a member!');</script>");
                        }


                }
                else{
                        echo("<script>alert('Error: A fruip with this name does not exist! Please try again.');</script>");


                }
?>                

