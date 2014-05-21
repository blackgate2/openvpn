<?php
session_start();
require('./protected/class.define.conts.php');
require(commonConsts::path_cammon.'/db.class.php' );
require(commonConsts::path_cammon. '/msg_array.php');
require(commonConsts::path_cammon.'/class.auth.php');

$auth= new auth($msg);
//exit($_REQUEST['login'].'-'. $_REQUEST['passwd'].'-'.'user');
if ($auth->login($_REQUEST['login'], $_REQUEST['passwd'],'user')){
    echo 'success';
}
?>