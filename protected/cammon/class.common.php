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
class common {

    const MONEY_DELIMETR = '.';

    public static function to_money_format($value, $zero = true, $c_num = 2) {
        return (is_null($value) || ($value === '')) ? '' : number_format($value, $c_num, self::MONEY_DELIMETR, ' ');
    }
    public static function to_numder_format($value) {
        return (is_null($value) || ($value === '')) ? '' : str_replace(' ', '',$value);
    }
    public static function redirect_without_action($action, $new_url = '') {
        if ($new_url)
            $url = $new_url;
        else
            $url = str_replace('&action=' . $action, '', $_SERVER['REQUEST_URI']);
        self::urldirect($url);
    }

    public static function urldirect($request) {
        header('Location: ' . $request);
        exit();
    }

    public static function unesc($str) {
        $str = str_replace('"', "&quot;", $str);
        //$str=str_replace("'","&rsquo;",$str);
        //$str=str_replace('\\','\\ ',$str);
        return $str;
    }

    public static function to_mail($email, $subj, $mess) {
        // print_r($u);
        //send_mail($u['email'], commonConsts::admin_name, commonConsts::admin_email, $this->subj, $this->mess);
        $from = "From: " . commonConsts::admin_name . " <" . commonConsts::admin_name . ">\r\n";
        $replay = "Reply-To: " . commonConsts::admin_email . "\r\n";
        $params = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
        $header = $from . $replay . $params;

        mail($email, $subj, $mess, $header);
        
        
    }
    

}

?>
