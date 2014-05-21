<?php

class validCreateUser {

    private $errMsg;
    private $q;
    private $msg;

    public function __construct($localize) {
        $this->errMsg = '';
        $this->q= DB::Open();
        $this->msg=$localize;
    }

    private function getExistField($v) {
        $this->q->qry('Select * From '.$v['table'].' Where '.$v['field'].' = "?" ', $v['value']);
        if ($this->q->numrows()) {
            return true;
        } else {
            return false;
        }
    }

    public function getErrMsg() {

        // ------------------------------  на Spam Bot ------------------------------ //
        $this->q->qry('Select bilet From bilets_reg_users Where  status="1" and bilet = "?" ', $_POST['biletik']);

        if (!$this->q->numrows()) {
            $this->errMsg .= alert::error($this->msg['reg_error_spam_bot']);
        }

        // ------------------------------ длинна логина ------------------------------ //
        if (!valid::checkLength($_POST['login'], 20)) {
            $this->errMsg .= alert::error($this->msg['valid_form_login_len']);
        }
        // ------------------------------ на символы ------------------------------ //
        if (!valid::isAlphaNumeric($_POST['login'])) {
            $this->errMsg .= alert::error($this->msg['valid_form_login']);
        }
        // ------------------------------ на существование логина в базе ------------------------------ //
        if ($this->getExistField(array('table'=>'users', 'field'=>'login', 'value' => $_POST['login'] ))) {
            $this->errMsg .= alert::error($this->msg['reg_error_login_exist']);
        }
        // ------------------------------ на существование email в базе ------------------------------ //
        if ($this->getExistField(array('table'=>'users', 'field'=>'email', 'value' => $_POST['email'] ))) {
            $this->errMsg .= alert::error($this->msg['reg_error_email_exist']);
        }
        // ------------------------------ длинна пароля  ------------------------------ //
        if (!valid::checkLength($_POST['passwd'],  16,6)) {
            $this->errMsg .= alert::error($this->msg['valid_form_pass']);
        }
        // ------------------------------ совпадение паролей  ------------------------------ //
        if (!valid::compare($_POST['passwd'], $_POST['passwd_re'])) {
            $this->errMsg .= alert::error($this->msg['valid_form_pass_re']);
        }
        // ------------------------------ длинна имени  ------------------------------ //
        if (!valid::checkLength($_POST['name'],  255,1)) {
            $this->errMsg .= alert::error($this->msg['valid_form_name']);
        }
        // ------------------------------ email ------------------------------ //
        if (!valid::isEmail($_POST['email'])) {
            $this->errMsg .= alert::error($this->msg['valid_form_email']);
        }
        return $this->errMsg;
    }

}