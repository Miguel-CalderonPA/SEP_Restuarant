<?php
    session_start();
	//error_reporting(-1);
    //ini_set("display error",1);
    
	// Grab data and setup PDO
	$pos=$_POST["fruipNum"];
	$zip=$_POST["zip"];
	//$radius=$_POST["radius"];
	$groups=array_values($_SESSION['owned']);
    $gName=$groups[$pos];
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
    $sql="SELECT gName FROM fruips WHERE gName=:gName";
    $stmt=$myPDO->prepare($sql);
    $stmt->bindValue(':gName',$gName);
    try{
		$fruips=$stmt->execute();
    } // end try
    catch(PDOException $e){
		echo $e->getMessage();
	} // end catch
	
	$fruips=$stmt->fetch();
    
	if(!empty($fruips['gname'])) {
		//$meters=floor($radius*1609.34);
		$sql="update fruips set voting=True, zip=:zip  where gName=:gName";
        $stmt=$myPDO->prepare($sql);
        $stmt->bindValue(':gName', $gName);
		$stmt->bindValue(':zip', $zip);
		//$stmt->bindValue(':radius', $meters);
		$stmt->execute();
		
		$checkIfMember=$stmt->fetch();
        
		echo("<script>alert('Voting initiated successfully!');</script>");
    } // end fruip name not empty
	else{
		echo("<script>alert('Error: A group with this name does not exist! Please try again.');</script>");
	} // end else
?>                

