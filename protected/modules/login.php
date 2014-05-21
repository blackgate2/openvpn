<?php

$auth= new auth($msg);

if (isset($_REQUEST['logout']) ){
    $auth->logout();
    redirect('/');
}
if (isset($_REQUEST['login']) && !$auth->compare_sess($_REQUEST['login'])){
    $auth->logout();
}


if($auth->login($_SESSION['login'], $_SESSION['passwd'],'user')){
    $str_user_menu.=' :: ';
    $str_user_menu.=($nav->page=='user')?'
                    <span class=" menu ui-state-default ui-corner-all ui-state-hover">'.$msg['your_orders'].'</span>':'
                    <a href="/user" class="ui-state-default ui-corner-all">'.$msg['your_orders'].'</a>';
   $str_user_menu.=' :: <a href="/?logout=1" class="ui-state-default ui-corner-all">'.$msg['logout']. '</a>';
    $str_login=$auth->userInfo($str_user_menu);
    $is_login=true;
}else{
    $str_login=$auth->loginForm();
    $is_login=false;
}
?>