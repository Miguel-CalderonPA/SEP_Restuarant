<?php

		session_start();		

                //error_reporting(-1);
                ini_set("display error",0);
                $useName=$_SESSION['useName'];
                $options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
	
		$ownedFruips=[];
	
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
                $sql="SELECT gname FROM membership WHERE usename=:useName";
                $statement=$myPDO->prepare($sql);
                $statement->bindValue(':useName',$useName);
               try{
                      $statement->execute();
                }
                catch(PDOException $e){

                        echo $e->getMessage();

                }
		$fruips=$statement->fetchAll();
		$count=0;
                if(!empty($fruips))
                {
			$tableEntries="";
			foreach($fruips as $row){

				foreach($row as $key){

					

                       			$sql="SELECT owner FROM fruips WHERE gName=:gName";
                        		$stmt=$myPDO->prepare($sql);
                        		$stmt->bindValue(':gName', $key);
                       			$stmt->execute();
					$checkOwn=$stmt->fetch();
					$tableEntries=$tableEntries."<tr>";
					$tableEntries=$tableEntries.'<td>'.$key.'</td>';
					
					//currently voting
					$tableEntries=$tableEntries.'<td></td>';


					//voting status
					$tableEntries=$tableEntries.'<td></td>';

					//results
					$tableEntries=$tableEntries.'<td></td>';

					//manage
					
					if($checkOwn['owner']==$useName){

						$tableEntries=$tableEntries."<td><button type='button' name='manage' id='manage'  value='{$count}'>Manage</button></td>";
						array_push($ownedFruips, $key);
						$count++;

					}
					$tableEntries=$tableEntries."</tr>";
				}
			}
			$_SESSION['owned']=$ownedFruips;
			echo $tableEntries;
                }
                else{
                        echo("</p>You haven't joined any fruips yet! Try creating or joining one.</p>");

                }

?>


