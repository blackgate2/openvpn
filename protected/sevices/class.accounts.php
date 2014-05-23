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
    protected $_accountPass; /* пара логин пароль создавшегося аккаунта который сгенерил sysCommandLine */
    protected $server;
    private $arrPass = array(); /* пароли для мульти аккаунтов */
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

    private function sysCommandLine($v, $command) {


        if (isset($command[$v['type']][$v['command']][$v['protocol']])) {

            /*  */
            $sysStr = '';
            $sysStr = $command[$v['type']][$v['command']][$v['protocol']];
            $sysStr = str_replace('?account?', $v['account'], $sysStr);
            $sysStr = str_replace('?iddouble?', $v['iddouble'], $sysStr);
            $sysStr = str_replace('?idmultidouble?', $v['idmultidouble'], $sysStr);
            if ($v['type'] == 'Multi' || $v['type'] == 'MultiDouble'){
                $pass= $this->getPass($v['order_id']);
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
            $this->logLaunchScript($v['command'], $sysStr, $retval,$pass, $v['order_id']);
            return $r;
        }
    }

    public function startAction() {
        $this->checkServer();

        $arrCommands = $this->getAllCommands();
        foreach ($this->getOrdersData() as $v) {
            //print_r($v);
            //echo "\n";
            //exit();
            $this->_accountPass = $this->sysCommandLine($v, $arrCommands);
            //echo $this->_accountPass."\n\n";
            //exit();



            /* -- тест ------------------------- */
//            if ($v['action'] == 'unlock') {
//                if ($v['type'] == 'Multi' || $v['type'] == 'MultiDouble')
//                    $this->_accountPass = 'CORP' . $v['order_id'] . ':superpassssssss';
//                else
//                    $this->_accountPass = 'account' . $v['order_id'] . ':superpassssssss';
//            }
            /* ------------------------------ */
            if ($v['account_id']=='' && ($v['command'] == 'unlock' || $v['command'] == 'lock') ) {
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

        $this->q->query('Select id From order_server_action_ids Where orderID=' . $orderID . ' and serverID=' . $serverID . ' and actionID=' . $actionID . '');

        if (!$this->q->numrows()) {
            if ($this->q->query('Insert Into order_server_action_ids Set orderID=' . $orderID . ',serverID=' . $serverID . ',actionID=' . $actionID . ',accountID=' . $accountID)) {
                return $this->q->lastID();
            } else {
                throw new Exception('error  setServerReponse');
            }
        }
    }

    protected function updateOrdersStatus($actionID, $order_id) {
        // echo 'Update orders Set action_id=' . $actionID . ' Where id=' . $order_id;
        $this->q->query('Update orders Set action_id=' . $actionID . ' Where id=' . $order_id);
    }

    private function logLaunchScript($action, $command_line, $retval,$pass, $order_id) {
        if (!$this->q->query('Insert Into log_lounch_script (action,comman_line,return_val,pass,order_id)
            Values ("' . $action . '","' . $command_line . '","' . $retval . '","' . $pass . '",' . $order_id . ')')) {
            throw new Exception;
        }
    }

    public function getOrdersData() {

        $sql = 'SELECT ac.name as account,
                       ac.id as account_id,
                       o.id as order_id,
                       p.name as protocol,
                       pg.name as type,
                       
                       s.id as server_id,s.name as server,
                       s.iddouble,
                       s.idmultidouble,
                       
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
                Join servers s On 
                        s.id = osids.serverID  
                        ' . (($this->hostname) ? 'and s.hostname = "' . $this->hostname . '"' : '') . '
         

                Where os.action = "create" or os.action = "unlock"  or os.action = "lock" 
                and  NOT EXISTS (Select * From order_server_action_ids osa 
                                  Where osa.orderID = o.id  and osa.serverID = s.id )
                Having action IS NOT NULL
                ';
        // echo $sql;
        return $this->q->fetch_data_to_array($sql);
    }

}