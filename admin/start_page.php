<?php
session_start();
require_once('functions_admin.php');
require_once('../protected/class.define.conts.php');
require_once(commonConsts::path_cammon. '/functions.php');
require_once(commonConsts::path_cammon. '/db.class.php' );

if (isset($_GET['exit'])) {
    session_destroy();
    log_off();
}


if ((isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) || (isset($_SESSION['login']) && isset($_SESSION['passwd']))) {
    
    $login = (isset($_SERVER['PHP_AUTH_USER'])) ? $_SERVER['PHP_AUTH_USER'] : $_SESSION['login'];
    $passwd = hesh_pass(((isset($_SERVER['PHP_AUTH_PW'])) ? $_SERVER['PHP_AUTH_PW'] : $_SESSION['passwd']),commonConsts::sol_pass1,commonConsts::sol_pass2);
    //echo $login.' '.$passwd;
    $q = DB::Open();

    $auth_query = "
        SELECT u.id,u.name,u.tip_user,u.login,u.passwd FROM users u 
                   WHERE tip_user = 'admin' AND login='?' AND passwd = '?'" ;
    
    if ($q->qry($auth_query,$login, $passwd)) {
        $numrows = $q->numrows();
        $row = $q->getrow() ;
    }
    if ($numrows != 1) {
        log_off();
    } elseif (!isset($_SESSION['login']) && !isset($_SESSION['passwd']) &&$row['login'] == $login && $row['passwd'] == $passwd) {
        $_SESSION['login'] = $login;
        $_SESSION['passwd'] = $passwd;
        $_SESSION['auth_user_id'] = $row['id'];
        $_SESSION['auth_user_fio'] = $row['name'];
        $_SESSION['auth_tip_user'] = $row['tip_user'];
        log_in();
    } elseif (isset($_SESSION['login']) && isset($_SESSION['passwd']) &&
            $row['login'] == $login && $row['passwd'] == $passwd) {
        
        if ($_SERVER['REQUEST_URI'] == '/admin/')
            log_in();
    } else {
        
        log_off();
    }
} else {
    log_off();
}
//----------------

