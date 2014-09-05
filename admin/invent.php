<?php

ini_set('max_execution_time', 3600);
//print_r($_POST);
require_once('/home/forbac/public_html/beta/protected/class.define.conts.php');
require_once(commonConsts::path_cammon . '/db.class.php' );
require_once commonConsts::path_protect . '/class.invent.php';

$i = new invent();
if ($i->ok) {
    echo 'ok';
} else {
    
}
    
