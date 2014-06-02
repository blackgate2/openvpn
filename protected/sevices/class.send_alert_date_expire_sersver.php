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
class send_alert_date_expire_sersver {

//put your code here

    private static $subj;
    private static $mess;

    public static function get_exire_hosts() {
        
        self::$subj = 'date expire of the payment server';
        
        $s = vars_db::get_hosts('and date_expire <=DATE_ADD(now(),INTERVAL -2 day)');
        //print_r($s);echo 'ddd';

        if (!empty($s)) {
            self::$mess.='<table>';
            // print_r($s);
            foreach ($s as $v) {
                self::$mess.='<tr><td>' . implode(' </td><td> ', array_values($v)) . '</td></tr>';
            }
            
            self::$mess.='</table>';
            //echo self::$mess;
            return 1;
            echo self::$mess;
        }
        return 0;
    }

    public static function send() {


        if (self::get_exire_hosts())            
            common::to_mail(commonConsts::admin_email, self::$subj, self::$mess);
       //   common::to_mail('oleg@laweb.ru', self::$subj,  self::$mess);
    }

}
