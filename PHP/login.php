<?php

                //error_reporting(-1);
                //ini_set("display error",1);
                $pwd=$_POST['pwd'];
                $useName=$_POST['useName'];
                $options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
                try{
                        //$con= pg_connect("host=localhost dbname=postgres user=postgres password=");
                        $myPDO = new PDO('pgsql:host=localhost;dbname=postgres','danserver','AlphaSQ#1', $options);
                }
                catch(PDOException $e){
                print"Error!: ".$e->getMessage()."<br/>";
                die();
                }
                $pass=$myPDO->query("SELECT salt,hash FROM users WHERE usename='{$useName}'")->fetch();
                if(empty($pass['salt']))
                {


                }

                $hashedPass=crypt($pwd, $pass['salt']);
               // $pass= $myPDO->query("SELECT case when (SELECT hash from users where usename='{$useName}')='{$hash$
                if($pass['hash']==$hashedPass){
                        echo "<script>alert('login successful');</script>";
                        //header("Location: ../HTML/Fruip/FruipDashboard.html");
                        session_start();
			$_SESSION['useName']=$useName;
			$_SESSION['created']=time();
			$_SESSION['refresh']=time();
			echo ("<script>location.href='../HTML/Fruip/FruipDashboard.html';</script>");
                }
                else
                {
                        echo "<script>alert('login failed: username or password is invalid');</script>";
                }

?>


