<?php

session_start();
ini_set('zlib.output_compression', 'Off');
ini_set("display_errors", "1");
ini_set("error_reporting", E_ERROR);
require('./protected/class.define.conts.php');

/** классы  */
require(commonConsts::path_cammon.'/vadation.class.php');

if (valid::isMD5($_GET['hash']))
    $fname =  $_GET['hash'] ;
else 
    exit("not format");

//if (!isset($_SESSION['login']) || !is_numeric($_GET['order_id']) || !valid::isMD5($fname)) {
//    echo commonConsts::accessDenied;
//    exit;
//}
if ($_GET['portable'] && ($_GET['portable']=='32' || $_GET['portable']=='64' ))
    $fname.=$_GET['portable'];

$fname.='.zip';
//exit($fname);
$file =  commonConsts::FolderZipConfigs .'/'. $fname;
//$file = 'Z:/home/openvpn/www' . commonConsts::FolderZipConfigs .'/'. $fname;

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $fname);
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    @readfile($file);
} else {
    echo commonConsts::fileNotFound;
}
exit;
?>