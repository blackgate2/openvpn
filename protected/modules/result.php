<?php

if (isset($_REQUEST["InvId"]) && isset($_REQUEST["InvId"]) && isset($_REQUEST["shpItem"]) && isset($_REQUEST["SignatureValue"])) {
    require commonConsts::path_modules . '/robokassa.params.php';

// чтение параметров
// read parameters
    $out_summ = $_REQUEST["OutSum"];
    $inv_id = $_REQUEST["InvId"];
    $shp_item = $_REQUEST["shpItem"];
    $crc = $_REQUEST["SignatureValue"];

    $crc = strtoupper($crc);

    $my_crc1 = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:shpItem=1"));
    $my_crc_order_ext = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:shpItem=order_ext"));
    if ($my_crc1 != $crc && $my_crc_order_ext != $crc) {
        require(commonConsts::path_admin . '/vars_show.php');
        require(commonConsts::path_cammon . '/strDate.class.php' );
        require(commonConsts::path_cammon . '/forms.class.php' );
        require(commonConsts::path_cammon . '/show_from_db.class.php' );
        require(commonConsts::path_protect . '/class.user_row.php' );
        require(commonConsts::path_admin . '/vars_db.class.php' );
        require(commonConsts::path_cammon . '/class.common.php');

        include 'success_fix.php';
        echo "OK$inv_id\n";
    } else {

        echo "bad sign\n";
    }
    exit();
}



