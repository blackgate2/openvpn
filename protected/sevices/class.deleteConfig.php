<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of createConfig
 *
 * @author Oleg
 */
class deleteConfig {

    
    private $q;

    public function __construct() {
        $this->q = DB::Open();
    }

    public function deleteConfigs($_ids) {
        if (is_array($_ids) && $_ids[0]) {
            foreach ($_ids as $order_id) {
                foreach (array(commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '.zip',
                               commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '32.zip',
                               commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '64.zip') as $value) {
                    if (file_exists($value))
                        unlink($value);
                }
            }
            $this->q->query('DELETE FROM order_configs Where order_id IN (' . implode(',', $_ids) . ')');
        }
    }

    public function startAction() {
        $this->deleteConfigs($this->getOrdersData());
    }

    public function getOrdersData() {
        $sql = 'SELECT o.id 
                FROM orders o
                Join actions os On os.id=o.action_id
                Left Join order_configs of On of.order_id = o.id
		Where os.action = "lock" and of.config IS NOT NULL 
                
                ';
        //echo $sql;
        return $this->q->fetch_data_to_accoc_array($sql,'','id');
    }

}

?>