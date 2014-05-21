<?

require(commonConsts::path_cammon.'/forms.class.php');
require(commonConsts::path_cammon.'/class.alert.php');
$t = array(
    array(form => 'text', caption => $msg['user_sendpass_form_field'], status => 'X', name => 'email', value => '')
        // array(form => 'text', caption => '', status => '0', name => 'aim', value => ''),
);



if ($_POST['post'] == 'seeend') {
    $errMsg = '';
    /** проверка на валидность email */
    if (!valid::isEmail($_POST['email'])) {
        $errMsg.=alert::error($msg['valid_form_email']);
    }

    /** достаем name,login,passwd  пользователя */
    $q->qry('Select name,login From users Where status=\'1\' and tip_user=\'user\' and email="?"', $_POST['email']);
    if (!$q->numrows()) {
        $errMsg.=alert::error($msg['reg_forgot_email']);
    }
    if ($errMsg == '') {
        $d = $q->getrow();
        
        /** меняем пароль потому как текущий пароль пользователя лежит в md5*/
        
        $md5_passwd = $auth->passwordreset();
        $passwd = $auth->get_pass();

        /** апдейтим пароль */
        $q->update('users', array('passwd' => $md5_passwd), 'Where email="' . $_POST['email'] . '"');


        /** тельце письма */
        $body_mail = str_replace('<name>', $d['name'], $msg['send_pass_body']);
        $body_mail = str_replace('<login>', $d['login'], $body_mail);
        $body_mail = str_replace('<passwd>', $passwd, $body_mail);
        
        /** отсылаем на почту доступ для пользователя  */
        send_mail($_POST['email'], commonConsts::admin_email,commonConsts::admin_name,  $msg['send_pass_suject'], $body_mail);

        /** редиректим страничку во избежании f5 */
        $url = commonConsts::url.'/user_sendpass/'.$nav->lang.'/?postis=1';
        redirect($url);
    } else {
        $content['content_page'] = $errMsg;
    }
}

if (!$_GET['postis']) {
    
    $q->query('Select bilet From bilets_reg_users Where status="1"  Limit 1');
    $d = $q->getrow();
    $msg['submit_button'] = $msg['user_sendpass_submit'];
    $forms = new forms($msg);
    $forms->fields = $t;
    $forms->nameform = 'theform';
    $forms->hiddens = array('post' => 'seeend', 'biletik' => $d['bilet']);
    $forms->is_form_In_dialog = 0;
    $forms->print_form_site();
    $content['name'] = $msg['user_sendpass'];
    $content['content_page'] .= $forms->str;
} else {
    // -------------- после inserta ----------
    $q->query('Select name,content_page From '.$nav->pre.'pages Where id=' . commonConsts::page_id_after_sendpass);
    $content = $q->getrow();
    //forms::set_msg($msg) ;
    //$content['content_page'] .= forms::back();
}