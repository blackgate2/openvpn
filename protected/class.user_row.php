<?php

class user_row extends show_from_db {

    static function rowColors($a) {

        $inx = $a['data']['ostatus'];
        if ($inx) {/* registered */
            parent::set_class_odd($a[$inx]['odd']);
            parent::set_class_even($a[$inx]['even']);
        }
    }

    public static function button_get_config($a) {

        parent::$url = '';

        $d = $a['data'];


        if ($d['config_data'] && $d['portable'])
            return parent::rowButton($a[0], $d['config_data'], 'hash');
        if ($d['config_data'] && !$d['portable'])
            return parent::rowButton($a[0], $d['config_data'], 'hash');
    }

    public static function button_pay_account($a) {
        parent::$url = '';
        $d = $a['data'];
        /** формирование url        
          foreach ($a[0]['url_params'] as $key => $value) {
          if ($key=='InvId')
          $a[0]['url_params'][$key]=$d['ext_num'];
          if ($key=='shpItem')
          $a[0]['url_params'][$key]='order_ext';
          if ($key=='OutSum')
          $a[0]['url_params'][$key]=$d['price'];
          if ($key=='SignatureValue')
          $a[0]['url_params'][$key]=md5($a[0]['url_params']['MerchantLogin'].':'.$a[0]['url_params']['OutSum'].':'.$a[0]['url_params']['InvId'].':'.$a[0]['mrh_pass1'].':shpItem='.$a[0]['url_params']['shpItem']);

          $url.='&'.$key.'='.$a[0]['url_params'][$key];
          }
          $a[0]['url'].='?'.trim($url,'&');
         */
        $a[0]['url'] = '';
        $a[0]['name'] = 'ext_pay_button_' . $d['id'];


        return parent::rowButton($a[0]);
    }

    public static function select_period($a) {
        $d = $a['data'];
        // if ($d['period_id']==3){


        $a[0]['first_val'] = 'no';
        $a[0]['value'] = $d['period_id'];
        $a[0]['name'] = 'ext_pay_select_' . $d['id'];
        //    print_r($a[0]);
        // exit();
        // }
        return forms::select($a[0]);
    }

}

?>