<?php
		// Constants
		$ourEmail = "ChomperHelp@gmail.com"; $file = "tickets.txt"; $mode = "a"; $mode2 = "w";
		$tickets = fopen($file, $mode) or die ("Unable to open file!"); 
		//include CSS Style Sheet
		echo "<link rel='stylesheet' type='text/css' href='../CSS/registerStyle.css' />";
		
		// Error checking
		$boolValid=true; 
		$invalidCt=0;	
		$strErrorMessage=""; 

		// Santitize
        $strFirstName = $_POST["firstname"]; // user firstname
		$strEmail = $_POST["email"]; // user email
		$strMessage = $_POST["message"]; // message for help

		// headers
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <$strEmail>' . "\r\n";
		
		
		date_default_timezone_set("America/New_York");
		$date = date('mdYHis');
		$ID = "Help Ticket #" . $date;
		// Doesn't work on EC2 instance
		mail($ourEmail, $ID, $strMessage, $headers);
		$divider = "-----------------------------------------------------------";
		$totalMessage =$divider . "\nID: " . $ID . "\n Name: " . $strFirstName . "\n Email: ". $strEmail . "\n Problem: " . $strMessage . "\n"; 
		file_put_contents($file, $totalMessage.PHP_EOL, FILE_APPEND | LOCK_EX);


//~~~~~~~~~~~~~~~~~~~~Check Sender Name~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if(validName($strFirstName)) // if valid
		{
		//	echo "<p>VALID Sender Name </p>"; // testing
		} // end if 
		else // if not valid
		{
			$strErrorMessage.="Name must not have numbers (#) or symbols"."+"; // let user know
			$boolValid=false; // set to invalid
			$invalidCt++; // add one to invalid counter
		} // end else




//~~~~~~~~~~~~~~~~~Outputs Errors(if any) or Summary ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		
		if($invalidCt>0) // if errors are above 0 (so if any errors)
		{
			$tempArr = explode("+", $strErrorMessage); // split error message
			$i=1; // set i to 1
			echo "<p>"; // formatting
			foreach($tempArr as $strVal) // for each part
			{
				if($strVal !="") // if not last iteration
				{
				echo "$i: $strVal<br><br>"; // output 
				$i++; // add to i
				}// end inner if 
			} // end foreach
			echo "</p>"; // end formatting 
			echo '<a href="lab5.html"> <-- Return to form </a>'; // if incorrect data send give link
		} // end outer if 
		else // if no errors
		{
			// Output data that could reassure customer or sender
			// Package info
			echo "<head><title> Tickets </title></head>";
			echo "<div class = 'title'><h1> $ID </h1>";
			echo "<h2>Successfully submitted!</h2>";
			echo "<h3> Thank you $strFirstName!</h3>";
			echo "<p>We hope to get back to you soon!.<p>";
	
			
			// A quick timeframe
			echo "<p>Please check your email no earlier than 2 buisness days</p>";
			echo '<a href="../HTML/login.html"> Return to Login</a> <br><br><hr> </div>'; // if correct then they can return to login
		} //end else


//~~~~~~~~~~~~~~~~~ValidName Function~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		function validName(/*in*/$strName)
		{
		/*
		Pre: needs name to check
		Post: returns if name matches expression
		Purpose: to test user data to make sure its formatted correctly
		*/
			$boolvalid=true; // set to true intially 
			$namePattern ="/^[A-Z][a-z]+/"; // expression
			$tempArr = explode(" ", $strName); // find spaces
			
			foreach($tempArr as $strVal) // for each string in string array
			{
				if(!preg_match($namePattern, $strVal)) // if doesn't match
				{
				return false; // not valid 
				} // end if 
			} // end foreach
			return $boolvalid; // only returns true here
		} // end end validName

//~~~~~~~~~~~~~~~~~END PHP~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

?> 