<?php
ini_set("display_errors","1"); 
ini_set("error_reporting", E_ERROR);
ini_set("max_execution_time", 3600);


require('/home/forbac/public_html/beta/protected/class.define.conts.php');
require(commonConsts::path_cammon.'/db.class.php' );
require(commonConsts::path_services.'/class.createConfig.php');



    
$c = new createConfig();
$c->addFilesDir = commonConsts::FolderConfigFiles;
$c->zipParh = commonConsts::FolderZipConfigs;

$c->startAction();

