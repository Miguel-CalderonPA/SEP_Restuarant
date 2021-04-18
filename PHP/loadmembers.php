<?php

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
                $sql="SELECT usename FROM membership WHERE gName=:gName and pending=false";
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

                                foreach($row as $key){

                                    $sql="SELECT owner FROM fruips WHERE gName=:gName";
                		    $stmt=$myPDO->prepare($sql);
                		    $stmt->bindValue(':gName', $gName);
		                    $stmt->execute();
                		    $checkOwn=$stmt->fetch();

                                        
                                        $tableEntries=$tableEntries."<tr>";
                                        $tableEntries=$tableEntries.'<td>'.$key.'</td>';

                                        //status voting
                                        $tableEntries=$tableEntries.'<td></td>';
										//remove?

                                        if($checkOwn['owner']!=$key){

                                                $tableEntries=$tableEntries."<td><button type='button' name='remove' class='dis' id='remove{$count}' value='{$key}'>Remove</button>";

                                        }
                                        $tableEntries=$tableEntries."</tr>";
                                }
                        }
                        echo $tableEntries;
                }
                else{
                        echo("</p>You haven't joined any fruips yet! Try creating or joining one.</p>");

                }

?>
