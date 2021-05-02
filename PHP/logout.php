<?php
	//Author: Dan Klein
	session_start();
	if(isset($_SESSION['useName'])){
		session_unset();
		session_destroy();
	}
?>
