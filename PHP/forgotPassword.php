<!DOCTYPE html>
<html lang="en">

<head>
		<meta charset="UTf-8">
		<title> Forgot Password Status  </title>
</head>
<body>
	<?php
	// Constants
	$ourEmail = "ChomperHelp@gmail.com"; $file = "tempReset.txt"; $mode = "a"; $mode2 = "w";
	$tickets = fopen($file, $mode) or die ("Unable to open file!"); 
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
		var_dump($email);
		$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
		
		try { // try to connect
			$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
		if($pdo) { // if failed
			echo "Connected Successfully\n";
			$SELECT=$pdo->query("SELECT email FROM users WHERE email='{$email}'")->fetch();
			
			if($SELECT != false) { // if nothing in select
				echo "An email was sent if there is an associated account";
				$link = $email . " A link allowing them to access update page";
				mail($ourEmail, "Reset Password" , $link, $headers);
				file_put_contents($file, $link.PHP_EOL, FILE_APPEND | LOCK_EX); // write to file
			}
			else { // if fail let user know
				echo "An email was sent if there is an associated account";
			}
			echo "<script>alert('An email was sent if there is an associated account');</script>"; 
		}
		}catch(PDOException $e) {
			echo "";
			echo "\nFailed ";
			echo $e->getMessage();
			die();
		}
		
    ?>
	</body>
</html>
