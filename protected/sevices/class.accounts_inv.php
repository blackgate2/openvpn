<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('class.accounts.php');
/**
 * Description of class
 *
 * @author Oleg
 */
class accountsInv extends accounts {




    public function startAction() {
        

        
        foreach ($this->getOrdersData() as $v) {
            
            $this->_accountPass = $this->sysCommandLine($v, $arrCommands);
            
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



    public function getOrdersData() {

        $sql = 'SELECT 
                      `name` as account,
                      `server` ,
                      proto as protocol,
                      \'lock\' as command
                FROM after_invent_res i
                JOIN servers s On 
                        s.name = i.name
                        ' . (($this->hostname) ? 'and s.hostname = "' . $this->hostname . '"' : '') . '
                Where i.status =\'1\')
                ';
        
        return $this->q->fetch_data_to_array($sql);
    }

}