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
class accounts {

    protected $q; /* class для работы с db */
    protected $_accountPass; /* пара логин пароль создавшегося аккаунта который сгенерил sys_line */
    private $hostname;

    public function __construct($hostname) {
        $this->q = DB::Open();
        $this->hostname = $hostname;
    }

    //create random password with 8 alphanumerical characters
    private function createPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

    private function getPass($order_id) {
        $a = $this->getAccountMulti($order_id);
        if ($a['pass']) {
            return $a['pass'];
        } else {
            return $this->createPassword();
        }
    }

    protected function sys_line($v, $command,$i) {


        if (isset($command[$v['type']][$v['command']][$v['protocol']])) {

            /*  */
            $sysStr = '';
            $sysStr = $command[$v['type']][$v['command']][$v['protocol']];
            $sysStr = str_replace('?account?', $v['account'], $sysStr);
            $sysStr = str_replace('?iddouble?', $v['iddouble'], $sysStr);
            $sysStr = str_replace('?idmultidouble?', $v['idmultidouble'], $sysStr);
            if ($v['type'] == 'Multi' || $v['type'] == 'MultiDouble') {
                $pass = $this->getPass($v['order_id']);
                $sysStr = str_replace('?pass?', $pass, $sysStr);
            }
            //echo $sysStr."\n";
            $r = system($sysStr, $retval);
            //exit($sysStr);
            //echo "\n\n" . ' order_id:' . $v['order_id'] . ' type:' . $v['type'] . ' command:' . $v['command'] . ' protocol:' . $v['protocol'] . ' $retval:' . $sysStr . "\n";
            /* -- командная строка вернула ошибку ------------------------- */
            if ($r == 'FULL') {
                $v['command'] = 'full';
                $v['action_id'] = 6;
            }
            $this->logLaunchScript($sysStr, $r, $pass, $v,false,$i);
            return $r;
        }
    }

    protected function sys_line_inv($v) {

        $sysStr = '/usr/local/etc/bin/kb ' . $v['account'] . (($v['protocol'] == 'pptp') ? ' ' . $v['protocol'] : '');
        $r = system($sysStr, $retval);
        $this->logLaunchScript($sysStr, $r, '', $v,true,$i);
    }

    protected function logLaunchScript($command_line, $retval, $pass, $v, $is_inv = false,$i) {
        if (!$is_inv) {
            if (!$this->q->query('Insert Into log_lounch_script 
                       (command,                      comman_line,             return_val,        pass,               server,                order_id,                 type,                  datetime_begin,                  datetime_expire,                 num_order,                 user,                price,                portable,                  period,                 action,                protocol,                 account,                 os,                 date_create,                 datetime_edit,                user_update)
                Values ("' . $v['command'] . '","' . $command_line . '", "' . $retval . '", "' . $pass . '", "'.$i.'- ' .$this->hostname.' '. $v['server'].'", ' . $v['order_id'] . ',  "' . $v['type'] . '",  "' . $v['datetime_begin'] . '",  "' . $v['datetime_expire'] . '",  ' . $v['num_order'] . ',  "' . $v['user'] . '", ' . $v['price'] . ', "' . $v['portable'] . '",  "' . $v['period'] . '", "' . $v['action'] . '", "' . $v['protocol'] . '", "' . $v['account'] . '", "' . $v['os'] . '", "' . $v['date_create'] . '", "' . $v['datetime_edit'] . '", "' . $v['user_update'] . '")')) {
                throw new Exception;
            }
        }else{
            
            
            if (!$this->q->query('Insert Into log_lounch_script 
                       (command,                      comman_line,             return_val,       server)
                Values ("' . $v['command'] . '","' . $command_line . '", "' . $retval . '", "'.$i.'- ' .$this->hostname.' '. $v['server'].'")')) {
                throw new Exception;
            }            
        }
    }

    public function startAction() {
        $this->checkServer();

        /*
         * действия для заказов
        */
        $arrCommands = $this->getAllCommands();
        foreach ($this->get_orders_data() as $v) {
            //print_r($v);
            //echo "\n";
            //exit();
            $this->_accountPass = $this->sys_line($v, $arrCommands,$i);

            if ($v['account_id'] == '' && ($v['command'] == 'unlock' || $v['command'] == 'lock')) {
                continue;
            }
            if ($v['order_id']) {
                $this->q->begin();
                if ($v['command'] == 'create')
                    $account = $this->setAccount();
                else {
                    $account['id'] = $v['account_id'];
                }
                //print_r($v);
                //exit ($account['id']);
                $this->setServerReponse($v['order_id'], $v['server_id'], $v['action_id'], $account['id']);
                if ($this->compareNumServers($v['action_id'], $v['order_id'])) {
                    if ($account['id']) {
                        $this->setAccountOrder($v['order_id'], $account['id']);
                    }
                    $this->updateOrdersStatus($v['action_id'], $v['order_id']);
                }
                $this->q->commit();
            }
            $i++;
        } 
        /*
         * действия для рузультата инвенторизации
         */
        foreach ($this->get_data_inv() as $v) {

            $this->sys_line_inv($v);
        }
    }

    /*
     * сравниваем 
     */

    private function compareNumServers($actionID, $orderID) {
        //exit('Select compareNumSevers ('.$actionID .','.  $orderID . ') as res');
        $this->q->query('Select compareNumSevers (' . $actionID . ',' . $orderID . ') as res');
        $d = $this->q->getrow();
        if ($d['res']) {
            return true;
        } else {
            return false;
        }
    }

    private function getAllCommands() {
        $this->q->query('Select 
                            t.name as type, 
                            a.action, 
                            p.name as protocol,
                            c.name as command 
                        From commads c 
                        JOIN types t ON t.id = c.type_id 
                        JOIN actions a ON a.id = c.action_id 
                        JOIN protocols p ON p.id = c.protocol_id');
        $data = array();
        while ($d = $this->q->getrow()) {
            $data[$d['type']][$d['action']][$d['protocol']] = $d['command'];
        }
        return $data;
    }

    private function checkServer() {
        //echo $this->hostname;
        $this->q->qry('Select name From servers Where hostname="?"', $this->hostname);
        if (!$this->q->numrows()) {
            echo "Hostname server is wrong";
            exit();
        }
    }

    private function setAccount() {
        $login = '';
        $pass = '';
        if ($this->_accountPass) {
            list($login, $pass) = explode(':', $this->_accountPass);


            $res = $this->q->fetch_data_to_array('Select a.id From accounts a 
                             JOIN orders o ON o.account_id = a.id 
                             JOIN order_server_ids os ON os.orderID = o.id
                             JOIN servers s ON s.id = os.serverID 
						' . (($this->hostname) ? 'and s.hostname = "' . $this->hostname . '"' : '') . '
                             Where a.name="' . $login . '"');
            if (is_array($res[0]))
                foreach ($res as $d) {
                    $this->q->del('accounts', 'Where id=' . $d['id']);
                }
            if ($this->q->query('Insert Into accounts (name,pass) Values ("' . $login . '","' . $pass . '")')) {
                $account_id = $this->q->lastID();
                return array('id' => $account_id, 'login' => $login, 'pass' => $pass);
            } else {
                throw new Exception('error: Insert Account');
            }
        } else {
            throw new Exception('error: not exist _accountPass');
        }
    }

    private function setAccountOrder($order_id, $account_id) {
        if ($account_id) {
            $this->q->query('Update orders Set account_id=' . $account_id . '  Where id=' . $order_id);
        }
    }

    private function getAccountMulti($order_id) {
        $this->q->query('Select a.id,a.name,a.pass 
                            From accounts a
                         Join order_server_action_ids osa ON 
                            osa.accountID = a.id
                            and osa.orderID = ' . $order_id . ' Limit 1');

        if ($this->q->numrows() > 0) {
            return $this->q->getrow();
        } else {
            return false;
        }
    }

    private function setServerReponse($orderID, $serverID, $actionID, $accountID) {
        
        $this->q->query('Delete From order_server_action_ids Where orderID=' . $orderID . ' and serverID=' . $serverID );
        $this->q->query('Insert Into order_server_action_ids Set orderID=' . $orderID . ',serverID=' . $serverID . ',actionID=' . $actionID . ',accountID=' . $accountID);
        return $this->q->lastID();
    }

    protected function updateOrdersStatus($actionID, $order_id) {
        // echo 'Update orders Set action_id=' . $actionID . ' Where id=' . $order_id;
        $this->q->query('Update orders Set action_id=' . $actionID . ' Where id=' . $order_id);
    }

    public function get_orders_data() {

        $sql = 'SELECT 
                       ac.id as account_id,
                       o.id as order_id,
                       o.datetime_begin,
                       o.datetime_expire,
                       o.num_order,
                       u.name as user,
                       o.price,
                       o.ammount,
                       o.portable,
                       pi.name as period,
                       p.name as protocol,
                       pg.name as type,
                       ac.name as account,
                       o.os,
                       s.id as server_id,
                       s.name as server,
                       s.iddouble,
                       s.idmultidouble,
                       o.date_create,
                       o.datetime_edit,
                       uu.name as user_update,
                       
                (CASE
                    WHEN os.action="create" THEN "create"
                    WHEN (os.action="unlock" and o.datetime_expire < now())  THEN "lock"
                    WHEN (os.action="lock" and o.datetime_expire > now())  THEN "unlock"
                 END ) as command,
                 
                (CASE
                    WHEN os.id=1 THEN 2
                    WHEN (os.id=2 and o.datetime_expire < now())  THEN 3
                    WHEN (os.id=3 and o.datetime_expire > now())  THEN 2
                 END ) as action_id,
                (CASE
                    WHEN os.action="create" THEN "unlock"
                    WHEN (os.action="unlock" and o.datetime_expire < now())  THEN "lock"
                    WHEN (os.action="lock" and o.datetime_expire > now())  THEN "unlock"
                 END ) as action
                 
                FROM orders o
                Join types pg On pg.id=o.type_id
                Join protocols p On p.id=o.protocol_id
                Join actions os On os.id=o.action_id
                Join order_server_ids osids On 
                        osids.orderID = o.id
                Left Join accounts ac ON ac.id = o.account_id
                Left JOIN users u ON u.id=o.user_id
                Left JOIN users uu ON uu.id=o.user_update_id
                Left JOIN periods pi ON pi.id=o.period_id
                Join servers s On 
                        s.id = osids.serverID  
                        ' . (($this->hostname) ? 'and s.hostname = \'' . $this->hostname . '\'' : '') . '
         

                Where (os.action = "create" or os.action = "unlock"  or os.action = "lock")
                and  ( NOT EXISTS(Select osa.orderID From order_server_action_ids osa 
                                  Where osa.orderID = o.id  and osa.serverID = s.id)
                       or (o.datetime_expire < now() and  os.action = "lock")
                      )
                Having action IS NOT NULL
                ';
        // echo $sql;
        return $this->q->fetch_data_to_array($sql);
    }

    public function get_data_inv() {

        $sql = 'SELECT 
                      i.`name` as account,
                      i.`server` ,
                      i.proto as protocol,
                      \'lock\' as command
                FROM after_invent_res i
                JOIN servers s On 
                        s.`name` = i.server
                        ' . (($this->hostname) ? 'and s.hostname = \'' . $this->hostname . '\'' : '') . '
                Where i.status =\'1\'
                ';
        $r = $this->q->fetch_data_to_array($sql);
        $this->q->query('Update  after_invent_res i SET i.status =\'\'
                WHERE i.server IN 
                    (SELECT s.name FROM servers s  ' . (($this->hostname) ? ' Where s.hostname = \'' . $this->hostname . '\'' : '') . ')');

        return $r;
    }

}
