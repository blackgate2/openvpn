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
class user_message {

    //private $users; //array('name'=>, 'method'=>, 'contact'=>)
    private $q;
    private $icq_class;
    private static $jabber_class;
    private $action_id;
    private $days;
    private $subj;
    private $mess;
    private $subjTempl;
    private $messTempl;
    private $admin_too;
    private $ret_val;
    

    public function __construct($user_mess_id) {
        $this->q = DB::Open();
        $this->setSubjMess($user_mess_id);
        $this->admin_too = array();
    }

    public function setDays($n = 1) {
        if (is_numeric($n)) {
            $this->days = $n;
        } else {
            $this->days = 1;
        }
    }

    public function setActionID($a = 2) {
        if (is_numeric($a)) {
            $this->action_id = $a;
        } else {
            $this->action_id = 2;
        }
    }

    public function sending() {
        $up = $this->get_users_params();

        foreach ($up as $v) {
            //$this->mess=$this->parse($v)
            $this->ret_val='';
            $this->invoice_number($v[0]);

            if ($v[0]['note_method'] == 'email')
                $this->constructMessHTML($v);
            else
                $this->constructMessText($v);

            //exit($this->mess);
            echo $this->mess . "\r\n\r\n";


            $this->send_mess($v[0]);
            $this->logMess($v[0]['note_method'], $v[0]['invoice_number'], $this->ret_val, $this->subj . "\r\n" . $this->mess);
            if (!empty($this->admin_too)) {
                $this->admin_too['note_method'] = $v[0]['note_method'];
                $this->send_mess($this->admin_too);
            }
            
        }
    }

    public function set_admin_too($a) {
        if (is_array($a))
            $this->admin_too = $a;
        return $this->admin_too;
    }

    private function constructMessText($u) {

        $this->subj = $this->subjTempl;
        $this->mess = ' ';
        $fields = array('num_order', 'type_openVPN', 'protocol', 'portable', 'price');


        $this->mess.='' . $this->parse($u[0]) . '' . "\r\n";
        $line = '------------------------------------------------' . "\r\n";
        $this->mess.=$line;

        $this->mess.='' . implode('|', $fields) . '' . "\r\n";

        $sum_price = 0;
        foreach ($u as $up) {
            $this->mess.=$line;
            foreach ($up as $k => $v) {
                if (in_array($k, $fields)) {
                    $this->mess.= $v . $this->calcSimpols($k, $v) . '|';
                    if ($k == 'price')
                        $sum_price+=$v;
                }
            }
            $this->mess = trim($this->mess, ' | ');
            $this->mess.="\r\n";
        }
        $this->mess.=$line;
        $this->mess.='                   total payment ($USD): ' . number_format($sum_price, 2, '.', ' ') . "\r\n";
    }

    private function calcSimpols($k, $v) {
        $count_add = strlen($k) - strlen($v);
        $str = '';
        if ($count_add > 0)
            for ($i = 0; $i < $count_add; $i++) {
                $str.=' ';
            }
        return $str;
    }

    private function invoice_number(&$u) {

        foreach ($u as $k => $v) {
            if ($k == 'num_order') {
                $str.=$v . ',';
            }
        }
        $u['invoice_number'] = trim($str, ',');
        //return $str;
    }

    private function constructMessHTML($u) {

        $this->subj = $this->subjTempl;
        $this->mess = '<table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid black;"> ';
        $fields = array('num_order', 'type_openVPN', 'protocol', 'portable', 'price');
        $this->mess.='<caption> ' . $this->parse($u[0]) . '</caption>' . "\r\n";
        $this->mess.='<tr><th> ' . implode(' </th><th> ', $fields) . ' </th></tr>' . "\r\n";
        $sum_price = 0;
        foreach ($u as $up) {
            $this->mess.='<tr>';
            foreach ($up as $k => $v) {
                if (in_array($k, $fields)) {
                    $this->mess.='<td> ' . $v . ' </td>';
                    if ($k == 'price')
                        $sum_price+=$v;
                }
            }
            $this->mess.='</tr>' . "\r\n";
        }
        $this->mess.='<tr><td colspan="4" align = "right">total payment($USD):</td><td> ' . number_format($sum_price, 2, '.', ' ') . '</td></tr>' . "\r\n";
        $this->mess.='</table>';
        //echo $this->mess;
    }

    private function setSubjMess($id) {
        $sql = 'Select name,mess From user_messages Where id=' . $id;
        $d = $this->q->get_fetch_data($sql);
        $this->subjTempl = $d['name'];
        $this->messTempl = $d['mess'];
    }

    private function parse($v) {
        $str = $this->messTempl;
        foreach ($v as $key => $value) {
            $str = str_replace('?' . $key . '?', $value, $str);
        }
        return $str;
    }

    public function get_users_params() {

        $sql = 'SELECT 
                    u.id,u.name,u.note_method,u.email,u.icq,u.jabber,
                    o.num_order,t.name as type_openVPN, p.name as protocol,if(o.portable!=\'\',\'yes\',\'no \') as portable,
                    DATE_FORMAT(o.datetime_expire,\'%d.%m.%Y\') as date_expire,o.price
                FROM orders  o 
                JOIN users u ON u.id = o.user_id
                JOIN types t On t.id=o.type_id
                JOIN protocols p On p.id=o.protocol_id
                Where  
                DATE(DATE_ADD(NOW(), INTERVAL + ' . $this->days . ' day)) =  DATE(o.datetime_expire) AND o.action_id  =' . $this->action_id . '
                Order by u.id,o.num_order';
        //  exit($sql);

        $u = array();
        foreach ($this->q->fetch_data_to_array($sql) as $v) {
            $u[$v['id']][] = $v;
        }
        return $u;
    }

    public function send_mess($v) {

        switch ($v['note_method']) {
            case 'email':
                $this->to_mail($v);
                break;
            case 'icq':
                $this->to_icq($v);
                break;
            case 'jabber':
                $this->to_jabber($v);
                break;
        }
    }

    private function to_mail($u) {
        // print_r($u);
        //send_mail($u['email'], commonConsts::admin_name, commonConsts::admin_email, $this->subj, $this->mess);
        $from = "From: " . commonConsts::admin_name . " <" . commonConsts::admin_name . ">\r\n";
        $replay = "Reply-To: " . commonConsts::admin_email . "\r\n";
        $params = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\n";
        $header = $from . $replay . $params;

        if (@mail($u['email'], $this->subj, $this->mess, $header)) {
            $this->ret_val = 1;
        } else {
            $this->ret_val = 0;
        }
        
    }

    private function to_icq($u) {
        if ($this->icq_class === NULL) {
            include_once(commonConsts::path_services.'/WebIcqLite.class.php');
            define('UIN', commonConsts::admin_icq_uin);
            define('PASSWORD', commonConsts::admin_icq_pass);
            $this->icq_class = new WebIcqLite();
        }

        if ($this->icq_class->connect(UIN, PASSWORD)) {

            if (!$this->icq_class->send_message($u['icq'], $this->mess)) {
                //if(!$icq->send_message('736491', 'Привет из php скрипта!!!')){

                $this->ret_val = $this->icq_class->error . '___';
            } else {
                $this->ret_val = 1;
            }
            $this->icq_class->disconnect();
        } else {
            $this->ret_val = $this->icq_class->error . ' connect';
        }
        sleep(600);
    }

    private function to_jabber($u) {
        if ($this->jabber_class === NULL) {
            include_once(commonConsts::path_services.'/jabber/xmpp.class.php');
            //echo $u['jabber'];
            $webi_conf['user'] = commonConsts::admin_jabber_user;
            $webi_conf['pass'] = commonConsts::admin_jabber_pass;
            $webi_conf['host'] = commonConsts::admin_jabber_host;
            $webi_conf['port'] = commonConsts::admin_jabber_port;
            $webi_conf['domain'] = commonConsts::admin_jabber_domain;
            $webi_conf['logtxt'] = false; // ведение лога false | true
            $webi_conf['log_file_name'] = commonConsts::path_services.'/jabber/loggerxmpp.log'; // файл лога
            $webi_conf['tls_off'] = 1; // принудительное отключение шифрования. 1 - выключено, 0 - включено, если сервер поддерживает
            $this->jabber_class = new XMPP($webi_conf);
            $this->jabber_class->connect(); // установка соединения...
            $this->jabber_class->sendStatus('text status', 'chat', 3); // установка статуса         
        }

        $this->jabber_class->sendMessage($u['jabber'], $this->subj . "\r\n" . $this->mess); // отправка сообщения
        if ($this->jabber_class->isConnected)
            $this->ret_val=1;
        else
            $this->ret_val=0;
        
    }

    private function logMess($note_method, $invoice_numbers, $return_val, $mess) {
        if (!$this->q->query('Insert Into log_mess (note_method,invoice_numbers,return_val,mess)
            Values ("' . $note_method . '",
                    "' . $invoice_numbers . '",
                    "' . $return_val . '",
                    "' .  mysql_real_escape_string(trim($mess)) . '")')) {
            throw new Exception;
        }
    }

}

?>
