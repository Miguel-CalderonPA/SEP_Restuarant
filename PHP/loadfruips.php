<?php

		session_start();		

                //error_reporting(-1);
		checkSession('../login.html');
                ini_set("display error",0);
                $useName=$_SESSION['useName'];
                $options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
	
		$ownedFruips=[];
		$allFruips=[];
	
                try{
                        //create PDO object
                        $myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);
                }
                catch(PDOException $e){
                        //check for connection errors
                        print"Error!: ".$e->getMessage()."<br/>";
                        die();
                }

                //prepare input for sanatization
                $sql="SELECT gname, reqadd  FROM membership WHERE usename=:useName and (pending=false or reqadd=true)";
                $statement=$myPDO->prepare($sql);
                $statement->bindValue(':useName',$useName);
               try{
                      $statement->execute();
                }
                catch(PDOException $e){

                        echo $e->getMessage();

                }
		$fruips=$statement->fetchAll();
		$countOwned=0;
		$countAll=0;
                if(!empty($fruips))
                {
			$tableEntries="";
			foreach($fruips as $row){

					


                       			$sql="SELECT owner, voting FROM fruips WHERE gName=:gName";
                        		$stmt=$myPDO->prepare($sql);
                        		$stmt->bindValue(':gName', $row['gname']);
                       			$stmt->execute();
					$checkOwn=$stmt->fetch();

					$tableEntries=$tableEntries."<tr>";
					$tableEntries=$tableEntries.'<td>'.$row['gname'].'</td>';
					
					//currently voting
					if($checkOwn['voting'])
					{
						$sql="SELECT count(useName) from membership where gname=:gName and currplace>0";
                                        	$stmt=$myPDO->prepare($sql);
						$stmt->bindValue(':gName', $row['gname']);
                                        	$stmt->execute();
                                        	$currVotes=$stmt->fetch();
						$currVotes=$currVotes['count'];
					
						$sql="SELECT count(useName) from membership where gname=:gName";
                                        	$stmt=$myPDO->prepare($sql);
						$stmt->bindValue(':gName', $row['gname']);
                                        	$stmt->execute();
                                        	$total=$stmt->fetch();
						$total=$total['count'];

						$tableEntries=$tableEntries."<td>{$currVotes}/{$total} votes remaining</td>";


					}
					else
					{
						$tableEntries=$tableEntries.'<td>Not voting</td>';

					}
					if($row['reqadd']){
						//will need to alter this to make it not alterable, maybe store add requests in diff session array
						$tableEntries=$tableEntries."<td>Added:<button type='button' name='appJoin' class='appJoin'  id='appJoin{$count}' value='{$row['gname']}'>Accept</button>";
                            			$tableEntries=$tableEntries."<button type='button' name='disJoin' class='disJoin' id='disJoin{$count}' value='{$row['gname']}'>Decline</button></td>";
						//results
                                        	$tableEntries=$tableEntries.'<td>N/A</td>';


					}
					else{



					
					
					//voting status
						if($checkOwn['voting'])
                                        	{
							$tableEntries=$tableEntries."<td><button type='button' name='vote' id='vote{$countAll}' onClick='voteFunction(this.value)'  value='{$countAll}'>Vote</button></td>";
							$tableEntries=$tableEntries.'<td>N/A</td>';


                                        	}
						else{ //voting is not currently ongoing
							$tableEntries=$tableEntries."<td>N/A</td>";
								
							//results: Checks for places connected to fruip, if there only exists one it is a winner and the results button should be displayed
							$sql="select count(name) as name from places where gname=:gName";
							$stmt=$myPDO->prepare($sql);
							$stmt->bindValue(':gName',$row['gname']);
							$stmt->execute();
							$getPlaces=$stmt->fetch();
							if($getPlaces['name']==1){

								 $tableEntries=$tableEntries."<td><button type='button' name='result' id='result{$countAll}' onClick='resultsFunction(this.value)'  value='{$countAll}'>Results</button></td>";


							}
							else{

								 $tableEntries=$tableEntries.'<td>N/A</td>';

							}


						}
					}
					if($checkOwn['owner']==$useName){ //user is owner, give them access to management page

						$tableEntries=$tableEntries."<td><button type='button' name='manage' id='manage{$countOwned}' onClick='manageFunction(this.value)'  value='{$countOwned}'>Manage</button></td>";
						array_push($ownedFruips, $row['gname']);
						$countOwned++;

					}
					$tableEntries=$tableEntries."</tr>";
					array_push($allFruips, $row['gname']);
					$countAll++;
				}
			$_SESSION['owned']=$ownedFruips;
			$_SESSION['fruips']=$allFruips;
			echo $tableEntries;
                }
                else{
                        echo("</p>You haven't joined any fruips yet! Try creating or joining one.</p>");

                }


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

}
?>


