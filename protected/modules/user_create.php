<?php

require_once(commonConsts::path_cammon.'/forms.class.php');
require_once(commonConsts::path_cammon.'/class.alert.php');
$t = array(
    array(form => 'text', caption => $msg['user_login'], status => 'X', name => 'login', value => ''),
    array(form => 'password', caption => $msg['user_passwd'], status => 'X', name => 'passwd', value => ''),
    array(form => 'password', caption => $msg['user_passwd_re'], status => 'X', name => 'passwd_re', value => ''),
    array(form => 'text', caption => $msg['user_name'], status => 'X', name => 'name', value => ''),
    array(form => 'text', caption => $msg['user_email'], status => 'X', name => 'email', value => ''),
    array(form => 'text', caption => $msg['user_jabber'], status => '0', name => 'jabber', value => ''),
    array(form => 'text', caption => $msg['user_icq'], status => '0', name => 'icq', value => ''),
        // array(form => 'text', caption => '', status => '0', name => 'aim', value => ''),
);



if ($_POST['post'] == 'writing') {

    // ------------------------------ проверки каждого поля при регистрации ------------------------------ //
    require_once(commonConsts::path_protect.'/class.uservalid.php');
    
    $valid = new validCreateUser($msg);
    $errMsg=   $valid->getErrMsg();

    if ($errMsg=='') {
        
        $passwd=  hesh_pass($_POST['passwd'], commonConsts::sol_pass1,commonConsts::sol_pass2);
        
        /** деактивируем билетик */
        $q->qry('Update bilets_reg_users Set status="" Where bilet="?"', $_POST['biletik']);

        /** формируем код для активации */
        $activ_cod = md5(commonConsts::sol_pass2 . time() . commonConsts::sol_pass2);

        /** формируем ссылку */
        $alink = '<a href="' . commonConsts::url . '/user_active/?alink=' . $activ_cod . '">'. commonConsts::url . '/user_active/?alink=' . $activ_cod .'</a>';

        /** отсылаем на почту ссылочку для активации пользователя  */
        $body_mail = str_replace('<name>', $_POST['name'], $msg['reg_email_body']);
        $body_mail = str_replace('<alink>', $alink, $body_mail);
        
        send_mail($_POST['email'],  commonConsts::admin_email, commonConsts::admin_name, $msg['reg_email_suject'], $body_mail);

        /** пишем регистрационные данные */
        $q->qry('Insert Into users (login,passwd,name,email,icq,bilet) Values("?","?","?","?","?","?")', $_POST['login'], $passwd, $_POST['name'], $_POST['email'], $_POST['icq'], $activ_cod);

        /** редиректим страничку */
        $url = commonConsts::url.'/user_create/'.$nav->lang.'/?postis=1';
        redirect($url);
    } else {
        $content['content_page'] = $errMsg ;
    }
}

if (!$_GET['postis']) {


    $q->query('Select bilet From bilets_reg_users Where status="1"  Limit 1');
    $d = $q->getrow();
    $msg['submit_button'] = $msg['user_create_submit'];
    $forms = new forms($msg);
    $forms->fields = $t;
    $forms->nameform = 'theform';
    $forms->hiddens = array('post' => 'writing', 'biletik' => $d['bilet']);
    $forms->is_form_In_dialog = 0;
    $forms->print_form_site();
    $content['name'] =  $msg['user_create'];

    $content['content_page'] .= $forms->str;
} else {
    // -------------- после inserta ----------
    $q->query('Select name,content_page From '.$nav->pre.'pages Where id=' . commonConsts::page_id_after_reg);
    $content = $q->getrow();
    //$content['content_page'] .=forms::back('', $msg['back']);
}
?>