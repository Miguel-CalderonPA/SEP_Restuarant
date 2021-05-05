<?php
	//Author: Dan Klein
	//Purpose: destroys the session when a logged in user goes to the login page, therby loging out the user
	session_start();
	if(isset($_SESSION['useName'])){
		session_unset();
		session_destroy();
	}
?>
