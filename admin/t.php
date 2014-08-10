<?php 

//phpinfo();

$host = 'usa-il.openvpn.ru'; 
$port = 22; 
$waitTimeoutInSeconds = 1; 
if($fp = fsockopen($host,$port,$errCode,$errStr,$waitTimeoutInSeconds)){   
    echo 'It worked '; 
} else {
   echo 'It didn\'t work';
} 
fclose($fp);
