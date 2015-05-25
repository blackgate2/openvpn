<?php
session_start();

if (is_numeric($_POST['typeID']) && is_numeric($_POST['period']) && is_numeric($_POST['amount'])) {
    require('./protected/class.define.conts.php');
    require(commonConsts::path_cammon.'/db.class.php' );
    require(commonConsts::path_admin . '/vars_db.class.php' );
//'typeID:'+i+' country: '+country+' period'+period+' portable:'+portable+' amount:'+amount

    
    $price_dis_id = vars_db::user_group_discount();
    //exit($price_dis_id.'');
    $q = DB::Open();
    $q->qry('Select
                IFNULL(p.name'.$price_dis_id.',p.name) as price,
                IFNULL(p.portable_price'.$price_dis_id.',p.portable_price) as portable_price

            From prices p
               
               ' . (($_POST['typeID'] == 1  && is_numeric($_POST['countryID'])) ? 'Join price_country_ids i On i.priceID=p.id and i.countryID=' . $_POST['countryID'] : '') . '
               
            Where p.type_id=? and p.period_id= ? Limit 1',$_POST['typeID'],$_POST['period']);
    
    $d = $q->getrow();
//    print_r($d);
//    while($d = $q->getrow()){
//        $return_val.='price: '.$d['price'].' portable_price:'.$d['portable_price'].'<br>';
//    }
    if ($_POST['portable'] && $d['portable_price'] != '0.00') {
        $return_val = $d['portable_price'];
    } else {
        $return_val = $d['price'];
    }
    // echo number_format(($return_val*$_POST['amount']), 2, '.', ' ');
    echo intval($return_val * $_POST['amount']);
}
