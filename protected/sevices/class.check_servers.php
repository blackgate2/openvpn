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
class check_servers {

//put your code here
    
    public static $port = 22;
    public static $waitTimeoutInSeconds = 1;
    

    public static function send($host) {

        if ($fp = fsockopen($host, self::$port, $errCode, $errStr, self::$waitTimeoutInSeconds)) {
            return true;
        } else {
            return false;
        }
        fclose($fp);
    }


    
    public static function check() {
        $s = vars_db::get_hosts();
        foreach ($s as $v) {
            $subj= "host ".$v['hostname'];
            
            if(!self::send($v['hostname'])){
                $mess= "Don't respons ".$v['hostname']." on port ".self::$port;
                common::to_mail(commonConsts::admin_email, $subj, $mess);
                //echo;
            }else{
//                $mess= "respons ".$v['hostname']." on port ".self::$port;
//                common::to_mail(commonConsts::admin_email, $subj, $mess);
//                echo "\n work ".commonConsts::admin_email.' '.$v['hostname'];
            }
        }
    }

}


