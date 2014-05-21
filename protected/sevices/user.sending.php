<?php
ini_set("max_execution_time", 129600);
//header('Content-Type: text/html; charset=utf-8');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('/home/forbac/public_html/beta/protected/class.define.conts.php');
require_once(commonConsts::path_cammon.'/db.class.php' );
require_once(commonConsts::path_services.'/class.user.message.php');


//require_once('./protected/class.define.conts.php');
//require_once('./protected/cammon/db.class.php' );
//require_once './protected/class.user.message.php';

$um = new user_message(1);
$um->setActionID(2);


$um->set_admin_too(array('email'=>'support@openvpn.ru',
                        'icq'=>'244436',
                        'jabber'=>'openvpn@jabber.ru'));
/* ------------------------------- 3 days before the lock ------------------*/
$um->setDays(3);
$um->sending();
/* ------------------------------- 2 days before the lock------------------*/

$um->setDays(2);
$um->sending();

/* ------------------------------- 1 days before the lock------------------*/
$um->setDays(1);
$um->sending();

///* ------------------------------- test ------------------*/
//$um->set_admin_too(array('email'=>'oleg@laweb.ru',
//                        'icq'=>'108122655',
//                        'jabber'=>'openvpn@jabber.ru'));
//
//
//$um->setDays(60);
//$um->sending();
