<?php
// Author Dan Klein, Miguel Calderon
    $options = [\PDO::ATTR_ERRMODE=> \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES   => false,
]; // configuration for sql
    try{
        //create PDO object
        $myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options); // login
    } // end try 
    catch(PDOException $e){
		//check for connection errors
        print"Error!: ".$e->getMessage()."<br/>"; // show user the error
        die(); // end process
    } // end catch
	//prepare input for sanatization
    $sql="SELECT gname, owner  FROM fruips";
    $statement=$myPDO->prepare($sql); // santitize on backend
    try{ // try to do sql statement
        $statement->execute();
    } // end try
    catch(PDOException $e){ // if sql fails
		echo $e->getMessage(); // report
	} // end catch
	$fruips=$statement->fetchAll();
    if(!empty($fruips)) { // if fruips exist
		$tableEntries="";
		foreach($fruips as $row){ // for each fruip
            $sql="SELECT owner FROM fruips WHERE gName=:gName"; // grab the owner of each fruip
            $stmt=$myPDO->prepare($sql); // santitize on backend
            $stmt->bindValue(':gName', $row['gname']); // allow value to be recogniziable 
            $stmt->execute(); // do the sql 
			$checkOwn=$stmt->fetch(); // get results
			$tableEntries=$tableEntries."<tr>"; // output start of table row
			$tableEntries=$tableEntries.'<td>'.$row['gname'].'</td>'; // output fruip name 
			$tableEntries=$tableEntries.'<td>'.$row['owner'].'</td>'; // output owner name
			var_dump($row); // testing
			$tableEntries=$tableEntries."</tr>"; // end table row
		} // end foreach
		echo $tableEntries; // send table to html page
    } // end if
    else{
        echo("</p> No fruips on Chomper! </p>"); // let user know there isn't any fruips that exist
	} // end else

/* ------------------------------------- not sure if we need this here 
function checkSession($pathToLogin){
	if(!isset($_SESSION['useName']))
	{
		echo("<script> alert('You are not logged in, redirecting to login page');location.href='{$pathToLogin}';</script>");
	}
	else{
		if(!isset($_SESSION['created'])){
			$_SESSION['created']=time();
		}
		else if(time()-$_SESSION['created']<14400){
			
			if(time()-$_SESSION['refresh']>3600){
				session_regenerate_id(true);
				$_SESSION['refresh']=time();
				$_SESSION['created']=time();
			}
		}
		else{
			session_unset();
			session_destroy();
			echo("<script> alert('Logging out due to extended inactivity');location.href='{$pathToLogin}';</script>");
		}
	}

} */
?>
