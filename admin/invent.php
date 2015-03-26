<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 3600);
//print_r($_POST);
require_once('/home/forbac/public_html/test/protected/class.define.conts.php');

require_once(commonConsts::path_cammon . '/db.class.php' );
require_once commonConsts::path_admin . '/vars_db.class.php';
require_once commonConsts::path_protect . '/class.invent.php';


new invent('/usr/local/etc/bin/inv_oleg 1','root', 'V1ufP2ob5$');

    
