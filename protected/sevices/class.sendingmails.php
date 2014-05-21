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
class sendingMails {
//put your code here
    public $mail_params;
    public $mail_subj;
    public $mail_body;

    
    public function sendingMails($subj, $body) {
        if (is_array($this->mail_params)) {
            for ($i = 0; $i < count($this->mail_params); $i++) {
                if ($this->mail_params[$i]['to'] && $this->mail_params[$i]['form'])
                    send_mail($this->mail_params[$i]['to'], $this->mail_params[$i]['form'], $this->mail_params[$i]['form_name'], $subj, $body);
            }
        }
    }
}
?>
