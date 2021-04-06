<!DOCTYPE html>
<html lang="en">

<head>
		<meta charset="UTf-8">
		<title> Regisration Status  </title>
</head>
<body>
	<?php
        	
		//error_reporting(-1);
		//ini_set("display error",1);
       		$fName=$_POST['fName'];
		$lName=$_POST['lName'];
		$email=$_POST['email'];
        	$pwd=$_POST['pwd'];
        	$useName=$_POST['useName'];
		$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
        	try{
			//$con= pg_connect("host=localhost dbname=postgres user=postgres password=");
			$myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);		
		}
		catch(PDOException $e){
		print"Error!: ".$e->getMessage()."<br/>";
		die();
		}
		$bytes=random_bytes(5);
		$salt=bin2hex($bytes);
		while(!empty($myPDO->query("SELECT salt FROM users WHERE salt='{$salt}'")->fetch()))
		{
			$bytes=random_bytes(5);
                	$salt=bin2hex($bytes);
		}
		

		$hashedPass=crypt($pwd, $salt);
		//	echo 'success';
		//}
		//else{
		//	echo'fail';
		//}
		$sql="insert into users(fName, lName, email, useName, hash, salt) values(:fName,:lName,:email,:useName,:hash,:salt)";
		$stmt=$myPDO->prepare($sql);
		$stmt->bindValue(':fName',$fName);
		$stmt ->bindValue(':lName',$lName);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':useName',$useName);
		$stmt->bindValue(':hash',$hashedPass);
		$stmt->bindValue(':salt',$salt);
		
		try{
			$stmt ->execute();
		}catch(PDOException $e){
			if($e->getCode()==23505){
				if(strpos($e->getMessage(),"email")!==false)
				{
					echo "Error: an account with this email already exists.";
				}
				else if(strpos($e->getMessage(), "usename")!==false)
				{
					echo "Error: username {$useName} is already in use.";
				}
				else
				{
					echo $e->getMessage();
				}			
			}
		} 
		$stmt2= $myPDO->query("SELECT * FROM users");
		echo $stmt2 ->fetch();
        
	//https://varunver.wordpress.com/2016/06/03/centos-7-install-php-and-postgres/
    ?>
	</body>
</html>
