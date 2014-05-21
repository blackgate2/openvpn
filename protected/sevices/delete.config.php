<?php
ini_set("display_errors","1"); 
ini_set("error_reporting", E_ERROR);
ini_set("max_execution_time", 3600);

require_once('/home/forbac/public_html/beta/protected/class.define.conts.php');
require_once(commonConsts::path_cammon.'/db.class.php' );
require_once(commonConsts::path_services.'/class.deleteConfig.php');

//require_once('protected/class.define.conts.php');
//require_once('protected/cammon/db.class.php' );
//require_once('protected/class.deleteConfig.php');
    
$d = new deleteConfig();   
$d->startAction();



?>