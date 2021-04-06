<!DOCTYPE html>
<html lang="en">

<head>
		<meta charset="UTf-8">
		<title> Password Reset  </title>
</head>
<body>
	<?php
	// Constants
	$ourEmail = "ChomperHelp@gmail.com"; $file = "tempTemp.txt"; $mode = "a"; $mode2 = "w";
	#$tickets = fopen($file, $mode) or die ("Unable to open file!"); 
	// headers
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <$strEmail>' . "\r\n";
	$host="host=localhost";
	$user="danserver";
	$pass="AlphaSQ#1";
	$dbName="dbname=postgres";
		//error_reporting(-1);
		//ini_set("display error",1);
		$email=$_POST["email"];
		$pwd =$_POST["pwd"];
		var_dump($email);
		var_dump($pass);
		$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
		
		try {
			$pdo = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);	
		if($pdo) {
			echo "Connected Successfully\n";
			$SELECT=$pdo->query("SELECT email FROM users WHERE email='{$email}'")->fetch();
			
			if($SELECT != false) {
				$link = $email . " A link allowing them to access update page";
				mail($ourEmail, "Reset Password" , $link, $headers);
				#file_put_contents($file, $link.PHP_EOL, FILE_APPEND | LOCK_EX);
				$isThere = true;
			}
			else {
				//echo "An email was sent if there is an associated account";
				echo "<script>alert('Email isnt associated');</script>";
				$isThere = false;
			}
			//echo "<script>alert('Password Reset');</script>";
		}
		}catch(PDOException $e) {
			echo "";
			echo "\nFailed ";
			echo $e->getMessage();
			die();
		}
		$bytes=random_bytes(5);
		$salt=bin2hex($bytes);
		while(!empty($pdo->query("SELECT salt FROM users WHERE salt='{$salt}'")->fetch()))
		{
			$bytes=random_bytes(5);
                	$salt=bin2hex($bytes);
		}
		$hashedPass=crypt($pwd, $salt);
		$sql="UPDATE users SET hash = :hash, salt = :salt WHERE email = :email";
		$stmt=$pdo->prepare($sql);
		$stmt->bindValue(':email', $email);
		$stmt->bindValue(':hash',$hashedPass);
		$stmt->bindValue(':salt',$salt);
		
		try{
			$stmt ->execute();
			if ($isThere) {
				echo "<script>alert('Password Reset');</script>";
			}
		} // end try 
		catch(PDOException $e){
			echo $e->getMessage();
		} // end catch
		
    ?>
	</body>
</html>
