<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vars_db
 *
 * @author Oleg
 */
class vars_db {
    
    
    public static function delete_configs($ids) {
        $q = DB::Open();
        $q->query('DELETE FROM order_configs Where order_id IN (' . implode(',', $ids) . ')');
        foreach ($ids as $order_id) {
            foreach (array(commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '.zip',
                            commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '32.zip',
                            commonConsts::FolderZipConfigs . '/' . md5($order_id . commonConsts::sol_pass1) . '64.zip') as $value) {
                if (file_exists($value))
                    unlink($value);
            }
        }
    }
    public static function order_users_filter($where='',$limit=100) {
        $q = DB::Open();
        //exit('Select id,CONCAT(name ,".", login ,".", icq ,".",  jabber ,".", email) as name From users '.$where.' Order by name,icq '.(($limit)?'Limit '.$limit:''));
        return $q->fetch_data_to_array('Select id,CONCAT(name ,".", login ,".", icq ,".",  jabber ,".", email) as name From users '.$where.' Order by name,icq '.(($limit)?'Limit '.$limit:''));
    }
    public static function types() {
        $q = DB::Open();
        return $q->fetch_data_to_array('Select id,name From types  ');
    }
    public static function periods() {
        $q = DB::Open();
        return $q->fetch_data_to_array('Select id,name From periods  ');
    }
    public static function actions() {
        $q = DB::Open();
        return $q->fetch_data_to_array('Select id,name From actions Order by id');
    }
    public function get_hosts($add_where='') {
        $q = DB::Open();
        return $q->fetch_data_to_array('Select `name`, `hostname`,  `hoster`, `emails`, `payment`, `date_begin`, `date_expire`, `comments` From servers s Where s.status=\'1\' and iddouble =\'\' and idmultidouble=\'\'  '.$add_where);
    }
    public function users() {
        $q = DB::Open();
        return $q->fetch_data_to_accoc_array('Select id, `name`From users Where status=\'1\' Order by name');
    }
    public static function __table($p) {
        $q = DB::Open();
        if (!isset($p['fields']))
            $p['fields']='id,name';
        if (!isset($p['order']))
            $p['order']='name';
        if (isset($p['where']) && $p['where'])
            $p['where']='Where '.$p['where'];
        return $q->fetch_data_to_array('Select '.$p['fields'].' From '.$p['table'].' '.$p['where'].' Order by '.$p['order']);
    }
    public static function __selected_ids($p) {
        $q = DB::Open();

        if ($p['where'])
            return $q->fetch_data_to_accoc_array('Select '.$p['field'].' From '.$p['table'].' Where  '.$p['where'],'',$p['field']); 
        else
            return array();
        //exit();
    }
}

?>