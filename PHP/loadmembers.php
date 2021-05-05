<?php
	//author:Dan Klein
	//Purpose: loads the members table in fruip management.
	session_start();
	checkSession('../login.html');
	//error_reporting(-1);
    	ini_set("display error",0);
	$pos=$_POST["fruipNum"];
    	$groups=array_values($_SESSION['owned']);
	$gName=$groups[$pos];
    	$options = [ \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC, \PDO::ATTR_EMULATE_PREPARES   => false, ];

	try{
        	//create PDO object
        	$myPDO = new PDO('pgsql:host=localhost;dbname=DB_NAME','user','pass', $options);
    	}
	catch(PDOException $e){
        	//check for connection errors
        	print"Error!: ".$e->getMessage()."<br/>";
        	die();
    	} // end catch
	//prepare input for sanatization, get members for given fruip, voted status, and remaining votes 
    	$sql="SELECT usename, voted, currplace FROM membership WHERE gName=:gName and pending=false";
    	$statement=$myPDO->prepare($sql);
    	$statement->bindValue(':gName',$gName);
   	try{
		$statement->execute();
    	} // end try
    	catch(PDOException $e){
		echo $e->getMessage();
	} // end catch
    
	$users=$statement->fetchAll();
    	$count=0;
    	if(!empty($users)) {
		//fruip has members
		$tableEntries="";
		//get user, check if they are the owner
              	$sql="SELECT owner FROM fruips WHERE gName=:gName";
                $stmt=$myPDO->prepare($sql);
                $stmt->bindValue(':gName', $gName);
                $stmt->execute();
                $checkOwn=$stmt->fetch();
        	foreach($users as $row){
			//go through each member, display name and status
			$tableEntries=$tableEntries."<tr>";
                	$tableEntries=$tableEntries.'<td>'.$row['usename'].'</td>';
                	//status voting
			if($row['voted']){
                		$tableEntries=$tableEntries."<td>Voted</td>";
			}
			else{
				$tableEntries=$tableEntries."<td>{$row['currplace']} Votes remaining</td>";
			}
			if($checkOwn['owner']!=$row['usename']){
					//user is owner
					$tableEntries=$tableEntries."<td><button type='button' name='remove' class='dis' id='remove{$count}' value='{$row['usename']}'>Remove</button>";
			}
                	$tableEntries=$tableEntries."</tr>";
       		} // end outer foreach
        	echo $tableEntries;
    	} // end if not empty users 
	else{
        	echo("</p>You haven't joined any fruips yet! Try creating or joining one.</p>");
	}

//-----------Function CheckSession----------------/

function checkSession($pathToLogin){
    if(!isset($_SESSION['useName'])) {
        echo("<script> alert('You are not logged in, redirecting to login page');location.href='{$pathToLogin}';</script>");
    } // end if
    else {
		if(!isset($_SESSION['created'])){
			$_SESSION['created']=time();
        } // end if
        else if(time()-$_SESSION['created']<14400){
			if(time()-$_SESSION['refresh']>3600){
				session_regenerate_id(true);
				$_SESSION['refresh']=time();
				$_SESSION['created']=time();
			} // end if time needs refresh
        } // end if time is created
        else{ 
			session_unset();
            session_destroy();
			echo("<script> alert('Logging out due to extended inactivity');location.href='{$pathToLogin}';</script>");
		} // end else destroy session time
    } // end else 
} // end function
?>
