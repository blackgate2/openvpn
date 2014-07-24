<?php
session_start();

/**  константы  */
require('./protected/class.define.conts.php');

require(commonConsts::path_cammon.'/functions.php');
/** header (charset  cash ) */
Expire(); 


require(commonConsts::path_cammon.'/db.class.php');
/**  база  */
$q = DB::Open();

require(commonConsts::path_protect.'/class.navigation.php');
$nav = new navigation();

require(commonConsts::path_cammon.'/msg_array'.$nav->pre.'.php');
//require('./protected/functions_site.php' );


/** классы  */
require(commonConsts::path_cammon.'/vadation.class.php');
require(commonConsts::path_cammon.'/class.auth.php');


/** авторизация  */
require_once(commonConsts::path_modules.'/login.php');

/**  навигация  */
$str_main_menu = $nav->getMainMenu();
$str_left_menu = $nav->getLeftMenu();
$str_bottom_menu = $nav->getBottomMenu();
$is_right=$nav->getRightPart();

/** доступные модули */
$moules=array(
    'user','user_sendpass','user_create','user_active','user_basket','success','fail','result',
    'press','news','user','order','faq'
);

/** подключение доступных модулей  */
if (in_array($nav->page, $moules)) {
    include (commonConsts::path_modules.'/'.$nav->page.'.php');
}elseif ($nav->page != '') {
    include (commonConsts::path_modules.'/page.php');
}else{
    $page_id=7;
    include (commonConsts::path_modules.'/page.php');
}
if ($is_right){
    include (commonConsts::path_modules.'/front_faq.php');
}

include (commonConsts::path_modules.'/user_menu.php');
include(commonConsts::path_templates.'/template.php');