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
class alert {
    static function error($str){
        return '<p class="msg_error">'.$str.'</p>';
    }
    static function ok($str){
        return '<p class="msg_ok">'.$str.'</p>';
    }
}
?>
