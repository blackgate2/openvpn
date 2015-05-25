<?php

require(commonConsts::path_protect.'/class.order_form.php');

//$_SESSION['auth_user_id'];

if ($_REQUEST['biletik']) {
        
    if ($is_login) {
        //print_r($_REQUEST);
        if ($_REQUEST['order_id']){
            require(commonConsts::path_modules.'/user_basket.php');
        }else{            
            $_SESSION['basket']=$_REQUEST;
            require(commonConsts::path_modules.'/user_basket.php');
        }
    } else {
       // $content['content_page'].=
        require(commonConsts::path_modules.'/user_create.php');
    }
} else {
    require_once(commonConsts::path_cammon.'/forms.class.php');
    $order_form = new order_form($msg);
    $q->query('Select name,content_page From '.$nav->pre.'pages Where id=' . commonConsts::page_id_new_order);
    $content = $q->getrow();
    $content['css'] = '
        <style>
            table#order_tbl {
                width: 850px;
            }
            table#order_tbl td#td1{
                width: 150px;
            }
            input.amount,
            input.price,
            input#total{width: 60px;text-align: right;}
            input#total{background: #ff5a81; font-size: 110%;color: #ffffff; font-weight: bolder; padding: 4px;}
            .list_countries{
                font-weight: normal;
            }
        </style>';

    $content['js'] = ' <script src="/js/order_calc.js"></script>';
    $content['content_page'].= $msg_alert;
    $content['content_page'].= $order_form->order_form_HTML();
}