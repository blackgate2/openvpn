<?php

session_start();
require('./protected/class.define.conts.php');
require(commonConsts::path_cammon . '/db.class.php');
require(commonConsts::path_cammon . '/msg_array' . $_SESSION['pre'] . '.php');
require(commonConsts::path_cammon . '/forms.class.php');
require commonConsts::path_modules . '/robokassa.params.php';
require(commonConsts::path_cammon . '/class.auth.php');
require(commonConsts::path_protect . '/class.orders_sum.php');
require(commonConsts::path_cammon . '/class.common.php');
//'typeID:'+i+' country: '+country+' period'+period+' portable:'+portable+' amount:'+amount


$auth = new auth($msg);
//exit($_SESSION['login']);
if ($auth->login($_SESSION['login'], $_SESSION['passwd'], 'user')) {

    $q = DB::Open();
    $q->begin();
    $q->query('Select numExtOrder() as n');
    $d = $q->getrow();
    $num_ext = $d['n'];
    /* посчет суммы */
    
    
    if (is_numeric($_REQUEST['orderID']) && is_numeric($_REQUEST['periodID'])) {
        $dataOut = array($_REQUEST['orderID'] => $_REQUEST['periodID']);
        $price_order = new orders_sum($dataOut);
        $price = $price_order->sum_order();
        $OutSum = $price;
        $q->del('orders_user_ext_ids', 'WHERE orderID =' . $_REQUEST['orderID']);
        $q->qry('INSERT INTO orders_user_ext_ids (ext_num,orderID,periodID,userID,price) VALUES (?,?,?,?,?)',$num_ext, $_REQUEST['orderID'], $_REQUEST['periodID'], $_SESSION['auth_user_id'],$price);
        //exit("INSERT INTO orders_user_ext_ids (ext_num,orderID,periodID,userID) VALUES (".$num_ext.",".$_REQUEST['orderID'].",".$_REQUEST['periodID'].",".$_SESSION['auth_user_id'].")");
    } elseif ($_REQUEST['order_params']) {
        $dataOut = array();
        $d = json_decode($_REQUEST['order_params']);
        foreach ($d as $v) {
            if (is_numeric($v->oid) && is_numeric($v->pid)) {
                $price_order = new orders_sum(array($v->oid => $v->pid));
                $price = $price_order->sum_order();
                $dataOut[$v->oid] = $v->pid;
                $q->del('orders_user_ext_ids', 'WHERE orderID =' . $v->oid);
                $q->qry('INSERT INTO orders_user_ext_ids (ext_num,orderID,periodID,userID,price) VALUES (?,?,?,?,?)',$num_ext, $v->oid, $v->pid, $_SESSION['auth_user_id'],$price);
            }
        }
        $sum_order = new orders_sum($dataOut);
        $OutSum = $sum_order->sum_order();
    }
    $q->commit();
    

    //print_r($dataOut);
    //    exit($OutSum);
    /* формируем url */

    $crc = md5($mrh_login . ':' . $OutSum . ':' . $num_ext . ':' . $mrh_pass1 . ':shpItem=order_ext');
    $url_params = array(
        'url' => $url,
        'MerchantLogin' => $mrh_login,
        'OutSum' => $OutSum,
        'InvId' => $num_ext,
        'shpItem' => 'order_ext',
        'SignatureValue' => $crc,
        'Desc' => $inv_desc2,
        'Culture' => $culture,
        'Encoding' => $encoding
    );

    //echo $url;

    $json = json_encode($url_params);
    echo $json;
}