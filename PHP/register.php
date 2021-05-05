
<!DOCTYPE html>
<html lang="en">

<head>
		<meta charset="UTf-8">
		<title> Regisration Status  </title>
</head>
<body>
	
<?php
    //redirect to login -- done
	//Author:Dan Klein
	//Purpose: checks the users inputs from the registration page, and if they are all valid with a unique email and username creates a new user account
	
	// Grab data and setup PDO
	$fName=$_POST['fName'];
	$lName=$_POST['lName'];
	$email=$_POST['email'];
	$pwd=$_POST['pwd'];
	$rPwd=$_POST['rPwd'];	
    	$useName=$_POST['useName'];
	//regex patterns for verifying user input
	$emailRegex="/[a-zA-Z0-9]+@[a-zA-Z0-9]+(\.[a-zA-Z]+)+/";
	$useNamePattern="/^[a-zA-Z0-9]{1,20}$/";
	$pwdPattern="/^([\d\s\bA-Za-z]+){8,}$/";
	$namePattern="/^([A-Za-z]+){2,20}$/";
	
	$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES => false,];
    	try{
		$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);		
	} 
	catch(PDOException $e){
		print"Error!: ".$e->getMessage()."<br/>";
		die();
	} 
	
	$bytes=random_bytes(5);
	$salt=bin2hex($bytes);
	
	//generate unique salt
	while(!empty($myPDO->query("SELECT salt FROM users WHERE salt='{$salt}'")->fetch())) {
		$bytes=random_bytes(5);
        $salt=bin2hex($bytes);
	}
	//hash and salt password	
	$hashedPass=crypt($pwd, $salt);
	
	if(!preg_match($emailRegex, $email)) { //email not valid
		echo "<script>alert('Error: invalid email address. Please enter a valid email address');</script>";
	} 
	else if(!preg_match($useNamePattern, $useName)) { //usename not valid
		echo "<script>alert('Error: invalid username. Usernames cannot contain white space or special characters and cannot be more than 20 characters long.');</script>";
	}
	else if(!preg_match($pwdPattern, $pwd)) { //password not valid
		echo "<script>alert('Error: Invalid password length. Password must be at least 8 characters and cannot contain unicode characters.');</script>";
	} 
	else if(!preg_match($namePattern, $fName)) { //first name is not valid
		echo "<script>alert('Error: Invalid first name. First name cannot contain white space, special characters, or digits and must be 2 to 20 letters long.');</script>";
	} 
	else if(!preg_match($namePattern, $lName)) { //last name is not valid
		echo"<script>alert('Error: Invalid last name. Last name cannot contain white space, special characters, or digits and must be 2 to 20 letters long.');</script>";
	}
	else if($rPwd!=$pwd){
		//password does not match retype password
		echo"<script>alert('Error: Passwords do not match.');</script>";

	}
	else{
		//sanatize inputs, attempt to create user account.
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
			echo "<script>alert('Regisration Successful');</script>";
			echo ("<script>location.href='login.html';</script>"); 
		}catch(PDOException $e){
			if($e->getCode()==23505){
				//email or password already in use
				if(strpos($e->getMessage(),"email")) {
					echo "Error: an account with this email already exists.";
				} 
				else if(strpos($e->getMessage(), "usename")) {
					echo "Error: username {$useName} is already in use.";
				} 
				else {
					echo $e->getMessage();
				} 			
			} // end if error code
		} // end catch 
        
		//https://varunver.wordpress.com/2016/06/03/centos-7-install-php-and-postgres/
	} // end else everything okay
?>
	</body>
</html>
