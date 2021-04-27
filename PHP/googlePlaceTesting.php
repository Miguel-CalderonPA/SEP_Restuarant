<?php
namespace GooglePlace;
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/googleplace/src/Request.php";
//var_dump($path);
include_once($path);
$path = $_SERVER['DOCUMENT_ROOT'];
$path .= "/efs-mount-point/sampledir/googleplace/src/Services/Nearby.php";
//var_dump($path);
include_once($path);
//include "../googleplace/src/Request.php";
//include "../googleplace/src/Services/Nearby.php";
$absPath = realpath("../googleplace/src/Request.php");
//echo $absPath;
//var_dump($absPath);
\googleplace\Request::$api_key = 'AIzaSyCVTXoxNH-OCRPWsxQB9jQHLbr_MFiarw8';

//echo "HERE /n";
/*Haven't test throughly yet - Miguel */
$rankBy = new \GooglePlace\Services\Nearby([
            'location' => '42.6641,-73.7857', // college of saint rose 
            'rankby' => 'distance',
            'type' => 'bank'
        ]
    );
    $rankBy->places(); // it will return \Collection each contains a object of GooglePlace\Service\Place
    /* Google Return 60 places divide by 20 each request.
     To get next 20 result you have to call nextPage method.
     */ 
     print_r($rankBy->nextPage()); // it will return \GooglePlace\Response

?>