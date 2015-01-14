<?php

if ($is_login) {

    include 'robokassa.params.php';
    include(commonConsts::path_cammon . '/strDate.class.php' );
    include(commonConsts::path_cammon . '/class.common.php');
    include(commonConsts::path_cammon . '/show_from_db.class.php' );
    include(commonConsts::path_cammon . '/forms.class.php' );
    /* ------------------    формирование корзинки     -------------------- */
    $obj = array(
        'titles' => $msg['order_fields'],
        'fields' => array('type', 'country', 'period', 'portable', 'os', 'protocol', 'amount', 'price'),
        'defMaxRow' => 300,
        'isEdit' => 0,
        'isCopy' => 0,
        'isDel' => 0,
        'isCheck' => 0,
        'isNav' => 0,
        'isSortbl' => 0,
        'isDialog' => 0,
        'isDefaultActions' => 0,
        'nonSortblFields' => array('status'),
        'money_format' => array('price'),
        'total_row' => array('price'),
    );

    $show = new show_from_db($msg, $obj);
    $basket = array();
    $p = array('period', 'type', 'protocol', 'country');
    foreach ($p as $v) {
        //echo 'Select id,name From '.(($v=='country')?'countries':$v.'s')."\n";
        $dd[$v] = $q->fetch_data_to_accoc_array('Select id,name From ' . (($v == 'country') ? 'countries' : $v . 's'));
    }

    foreach ($_SESSION['basket']['type'] as $i)
        foreach ($_SESSION['basket'] as $f => $v) {
            if (in_array($f, $show->obj['fields']))
                if (in_array($f, $p)) {
                    $basket[$i][$f] = $dd[$f][$v[$i]];
                    if (in_array($f, array('period', 'type', 'country'))) {
                        $basket[$i][$f . '_id'] = $v[$i];
                    }
                } else {
                    $basket[$i][$f] = $v[$i];
                }
        }
    //print_r($basket);
    $total = 0;
    foreach ($basket as $i => $v) {
        $d = $q->get_fetch_data('Select
                        ' . (($v['portable']) ? ' IF(p.portable_price <> 0, p.portable_price,p.name)' : 'p.name') . '  as price
                    From prices p
                    ' . (($v['type_id'] == 1) ? 'Join price_country_ids i On i.priceID=p.id and i.countryID=' . $v['country_id'] : '') . '
                    Where p.type_id=' . $v['type_id'] . ' and p.period_id=' . $v['period_id'] . '
                    Limit 1 ');
        $total+=$d['price'];
        $basket[$i]['price'] = $d['price'];
    }
    //$basket['total']['price']=$total;

    /* ------------------    сумма платежа     -------------------- */
    $out_summ = $total;

    /* ------------------    создание ID платежа     -------------------- */
    $q->insert('order_invoices', array('num_order' => NULL));
    $inv_id = $q->lastID();


    /* ------------------    форма отправки робо кассы     -------------------- */
    // формирование подписи
    // generate signature
    $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:shpItem=$shp_item");
    // ----------- 
    $msg['submit_button'] = $msg['basket_submit_button'];
    $forms = new forms($msg);
    $forms->__set('is_form_In_dialog', 0);
    $forms->nameform = 'form_paymant';
    $forms->url = $url_post;
    $forms->hiddens = array(
        'MerchantLogin' => $mrh_login,
        'OutSum' => $out_summ,
        'InvId' => $inv_id,
        'shpItem' => $shp_item,
        'SignatureValue' => $crc,
        'Description' => $inv_desc2,
        'Culture' => $culture,
        'Encoding' => $encoding
    );

    $forms->print_form_site();



    /* ------------------    отрисовка корзины     -------------------- */
    $content['name'] = $msg['basket_name'];
    //print_r($basket);
    $show->dataAll = $basket;
    if (!empty($show->dataAll)) {

        $content['content_page'].='
 <link type="text/css" href="/css/table.css" rel="stylesheet" />
         <style>
        #tables,form#form_paymant{
            width:800px;
        }        
        #tables th#title6 {
            width:100px;
        }
        
        div.submit_holder{
           
            float:right;
        }
        </style>
' . $show->show() . '';
        $content['content_page'] .= $forms->str;
    } else {
        $content['content_page'].=$msg['basket_order_not_exists'];
    }
}
?>