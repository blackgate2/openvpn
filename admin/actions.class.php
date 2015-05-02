<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of actions
 *
 * @author oleg
 */
class actions {

    private $ids;
    private $q;
    private $objects_ids;

    public function __construct($ids = array(), $q) {
        $this->q = $q;
        $this->ids = $ids;
        $this->objects_ids = implode(',', $this->ids) ;
    }

    public function check_order_for_del_cofig($params_order) {
        
        //echo $params_order['account_id'].'<br><br>'.$params_order['os'].'<br><br>'.$params_order['portable'].'<br><br>';        
        //print_r($params_order);
        //exit(('SELECT account_id,os,portable From orders WHERE id = ' . $this->ids[0]));
        
        $d=$this->q->get_fetch_data('SELECT account_id, os, portable From orders WHERE id = ' . $this->ids[0]);
      
        if ($d['account_id']!=$params_order['account_id']){
            return false;
        }
         
        if ($d['os'] ==$params_order['os'] &&  $d['portable'] ==$params_order['portable']){
            return false; 
        }
        return true;
       
    }

    public function inc_date_exp() {
        $this->q->begin();
        $this->q->query('Call deleteOrdersServersActionsIds ("' . $this->objects_ids . '") ');
        $this->q->query('Update orders SET action_id = 3, datetime_expire = DATE_ADD(datetime_expire,INTERVAL 1 MONTH),user_update_id ='.$_SESSION['auth_user_id'].' Where id IN (' . $this->objects_ids . ')');
        return $this->q->commit();
    }
    
    public function lock_by_date_exp() {
        //echo 'Update orders SET action_id = 2, datetime_expire = DATE_ADD(now(),INTERVAL -1 day) Where id IN (' . $this->objects_ids . ')';
        $this->q->begin();
        $this->q->query('Update orders SET action_id = 2, datetime_expire = DATE_ADD(now(),INTERVAL -1 day) Where id IN (' . $this->objects_ids . ')');
        return $this->q->commit();
    }

    public function lock_by_date_exp_after_copy($interval='30 MINUTE') {
        //echo 'Update orders SET action_id = 2, datetime_expire = DATE_ADD(now(),INTERVAL -1 day) Where id IN (' . $this->objects_ids . ')';
        $this->q->begin();
        $this->q->query('Update orders SET action_id = 2, datetime_expire = DATE_ADD(now(),INTERVAL '.$interval.') Where id IN (' . $this->objects_ids . ')');
        return $this->q->commit();
    }

    public function set_date_exp($date) {
        //$this->q->begin();
        //$this->q->query('Call deleteOrdersServersActionsIds ("' . $this->objects_ids . '") ');
        echo'Update orders SET datetime_expire = \''.$date.'\') Where id IN (' . $this->objects_ids . ')';
        //return $this->q->commit();
    }

    public function delete_cofigs() {
        if ($this->objects_ids) {
            $this->q->query('DELETE FROM order_configs Where order_id IN (' . $this->objects_ids . ')');
            foreach ($this->ids as $order_id) {
                foreach (array(commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '.zip',
            commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '32.zip',
            commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '64.zip') as $value) {
                    if (file_exists($value))
                        unlink($value);
                }
            }
        }
    }

    public function __set($key, $value) {
        $this->$key = $value;
    }

    public function __get($key) {
        return $this->$key;
    }

}
