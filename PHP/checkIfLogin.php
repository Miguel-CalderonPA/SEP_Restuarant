<?php

session_start();
checkSession('../login.html');




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
