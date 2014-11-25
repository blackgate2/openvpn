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

        /*
          CONCAT(a.name,\'<br/><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=32">Config32</a><br/><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=64">Config64</a>\') ,
          IF(of.config!="" AND o.portable="",
          CONCAT(a.name,\'<br/><a href="/downloadConfig.php/?hash=\',of.config,\'">Config</a>\'),
          a.name)  ) as account_conf,
          CONCAT(\'<small>\',u.name,\'<br>\',u.email,\'<br>\',u.icq,\'<br>\',u.jabber,\'<br><span class="red">\',IFNULL(o.notes,\'\'),\'</small></span><br><span class="green">\',IFNULL(uu.name,\'\'),\'</span>\' ) as user,
         */
        $a[0]['url'].=$d['config_data'];
        //$a[0]['name']=$d['account_name'];
        $url = $a[0]['url'];
        //print_r(parent::$msg);
        $str = '';
        $title = $a[0]['title'];
        $a[0]['title'] .=' ' . $d['account_name'];
        $title = $a[0]['title'];
        if ($d['account_name'] && $d['config_data'] && $d['portable']) {
            $a[0]['title'].=' 32bit';
            $a[0]['url'] = $url . '&portable=32';
            $str.=parent::rowButton($a[0], 'portable 32 bit');
            $a[0]['title'] = $title . ' 64bit';
            $a[0]['url'] = $url . '&portable=64';
            $str.=parent::rowButton($a[0], 'portable 64 bit');
        } elseif ($d['account_name'] && $d['config_data'] && !$d['portable']) {
            $str.= parent::rowButton($a[0], '');
        }
        return $str;
    }

    public static function alink_settings($a) {
        $d = $a['data'];
        if ($d['url_by_order_params']) {
            $urls = array_unique(explode('::', $d['url_by_order_params']));

            $str='';

            foreach ($urls as $value) {

                list($a[0]['url'], $a[0]['title']) = explode('||', $value);
                $str.= parent::alink($a[0],'',array());
            }
            return $str;
        }
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