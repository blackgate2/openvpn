<?php
require_once('../class.define.conts.php');
require_once('../cammon/db.class.php' );
require_once './class.accounts.php';

//require('/usr/local/etc/php_scripts_account/protected/class.define.conts.php');
//require('/usr/local/etc/php_scripts_account/protected/cammon/db.class.php' );
//require('/usr/local/etc/php_scripts_account/protected/class.accounts.php');


$a = new accounts('lux1.openvpn.ru');
$a->startAction();
