<?php
//require_once('./protected/class.define.conts.php');
//require_once('./protected/cammon/db.class.php' );
//require_once './protected/class.accounts.php';


require('/usr/local/etc/php_scripts_account/protected/class.define.conts.php');
require('/usr/local/etc/php_scripts_account/protected/cammon/db.class.php' );
require('/usr/local/etc/php_scripts_account/protected/class.accounts.php');


$a = new accounts('de-gold.openvpn.ru');
$a->startAction();
