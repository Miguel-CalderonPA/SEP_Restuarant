<?php
		//Author:Dan Klein

                session_start();

                //error_reporting(-1);
                ini_set("display error",0);
		$pos=$_POST["fruipNum"];
                $groups=array_values($_SESSION['owned']);
		$gName=$groups[$pos];
                $options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];


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
                $sql="SELECT usename FROM membership WHERE gName=:gName and pending=true";
                $statement=$myPDO->prepare($sql);
                $statement->bindValue(':gName',$gName);
               try{
                      $statement->execute();
                }
                catch(PDOException $e){

                        echo $e->getMessage();

                }
                $users=$statement->fetchAll();
                $count=0;
                if(!empty($users))
           	{
										
                        $tableEntries="";
                        foreach($users as $row){



                                        
                            $tableEntries=$tableEntries."<tr>";
                            $tableEntries=$tableEntries.'<td>'.$row['usename'].'</td>';
				//approve/dissaprove

                            $tableEntries=$tableEntries."<td><button type='button' name='approve' class='approve'  id='app{$count}' value='{$row['usename']}'>Approve</button>";
			    $tableEntries=$tableEntries."<button type='button' name='disapprove' class='dis' id='dis{$count}' value='{$row['usename']}'>Disapprove</button></td>";
                            $tableEntries=$tableEntries."</tr>";
                        }
                    
                        echo $tableEntries;
                }
                else{
                        echo("</p>Your fruip has no join requests.</p>");

                }



?>
