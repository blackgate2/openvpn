<?php

if (isset($_REQUEST["InvId"]) && isset($_REQUEST["InvId"]) && isset($_REQUEST["shpItem"]) && isset($_REQUEST["SignatureValue"])) {
    include commonConsts::path_modules . '/robokassa.params.php';

// чтение параметров
// read parameters
    $out_summ = $_REQUEST["OutSum"];
    $inv_id = $_REQUEST["InvId"];
    $shp_item = $_REQUEST["shpItem"];
    $crc = $_REQUEST["SignatureValue"];

    $crc = strtoupper($crc);
//echo "$out_summ:$inv_id:$mrh_pass1:shpItem=$shp_item";

    $my_crc1 = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:shpItem=1"));
    $my_crc_order_ext = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:shpItem=order_ext"));
    // exit($my_crc_order_ext);
// проверка корректности подписи
// check signature
    if ($my_crc1 != $crc && $my_crc_order_ext != $crc) {
        $content['content_page'] .= $msg['order_bad_sign'];
        //exit();
    } else {
        include 'success_fix.php';
    }
    
}

