<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author Oleg
 */
class send_date_exp {

//put your code here

    private static $subj;
    private static $mess;

    public static function get_valid_date($inver_val) {
        $q = DB::Open();
        
        $s = $q->fetch_data_to_array('SELECT o.id, os.name as action, u.name as fio, o.num_order,DATE(o.datetime_begin) as begin, DATE(o.datetime_expire) as expire
                                        FROM orders o                                        
                                        JOIN actions os ON os.id = o.action_id
                                        Left JOIN users u ON u.id=o.user_id
                                        Left JOIN accounts a ON a.id = o.account_id
                                        WHERE DATE(o.datetime_expire) > DATE(DATE_ADD(NOW(), INTERVAL + '.$inver_val.')) ');
        if (!empty($s)) {
            self::$mess.='<table>';
            // print_r($s);
            foreach ($s as $v) {
               // echo strDate::Date($v[3]).'   '.strDate::Date($v[4])."\n";
                $v['begin']=strDate::Date($v['begin']);
                $v['expire']=strDate::Date($v['expire']);
                self::$mess.='<tr><td>' . implode(' </td><td> ', $v) . '</td></tr>';                
            }
            self::$mess.='</table>';
            return 1;
        }
        return 0;
    }

    public static function check_send($inver_val='2 YEAR') {
        self::$subj = 'Date expire is doubtful large';
        if (self::get_valid_date($inver_val))            
            common::to_mail(commonConsts::admin_email, self::$subj, self::$mess);
          //common::to_mail('oleg@laweb.ru', self::$subj,  self::$mess);
    }

}
