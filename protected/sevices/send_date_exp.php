<?php

ini_set("display_errors","1"); 
ini_set("error_reporting", E_ERROR);
ini_set("max_execution_time", 3600);


require('/home/forbac/public_html/beta/protected/class.define.conts.php');
require(commonConsts::path_cammon.'/db.class.php' );
require(commonConsts::path_cammon.'/class.common.php' );
require(commonConsts::path_cammon.'/strDate.class.php' );
require(commonConsts::path_services.'/class.send_date_exp.php');


send_date_exp::check_send();