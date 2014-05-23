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
class send_fail_actions {

//put your code here

    private static $subj;
    private static $mess;

    public static function get_lock_fial() {
        $q = DB::Open();
        self::$subj = 'fail lock ';
        $s = $q->fetch_data_to_array('SELECT 
                                            `id`, `action`, `comman_line`, `pass`, `return_val`, `datetime_exec`, `order_id`  FROM forbac_openvpn.log_lounch_script l
                                        WHERE 
                                            `action`=\'lock\' 
                                            and return_val=\'0\'
                                            and date(datetime_exec) = date(now())
                                            and id not in (select log_lounch_script_id from log_send_mail);');
        if (!empty($s)) {
            self::$mess.='<table>';
            // print_r($s);

            foreach ($s as $v) {
                self::$mess.='<tr><td>' . implode(' </td><td> ', $v) . '</td></tr>';
                $q->insert('log_send_mail', array('log_lounch_script_id' => $v['id']));
            }
            self::$mess.='</table>';
            return 1;
        }
        return 0;
    }

    public static function check_send() {


        if (self::get_lock_fial())            
            common::to_mail(commonConsts::admin_email, self::$subj, self::$mess);
        //  common::to_mail('oleg@laweb.ru', self::$subj,  self::$mess);
    }

}
