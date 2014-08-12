<?php

//print_r($_POST);

include_once 'inv.php';
if (is_array($inv_cont) && !empty($inv_cont)) {
    for ($i = 0; $i < count($inv_cont); $i++) {
        $str = trim(str_replace(':V:', ':pptp:', trim($inv_cont[$i])));
        if ($str) {

            $add_sql .= '("' . str_replace(':', '","', $str) . '"),';
        }
    }
    $add_sql = trim($add_sql, ',');


    $q->begin();
    $q->query("Delete from after_invent;");
    $q->query("Delete from after_invent_res;");
//    $q->query("create table after_invent_res  (
//                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//                `name` varchar(12), 
//                `proto` varchar(10), 
//                `server` varchar(10),  
//                `status` enum('1','') DEFAULT '',
//                 PRIMARY KEY (`id`),
//                 KEY `nps_ix` (`name`,`proto`,`server`)
//                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;) Engine = MyISAM");
    $q->query(" INSERT INTO after_invent (name,proto,server) VALUES $add_sql");
    $q->query('
                    INSERT INTO after_invent_res (name,proto,server)
                    SELECT t.name,t.proto,t.server
                    FROM after_invent t 
                    LEFT JOIN ( SELECT a.name as account, p.name as proto, s.name as server,
                                     o.account_id,o.protocol_id,os.serverID
                                FROM orders o
                                    
                                    JOIN order_server_ids os ON os.orderID = o.id AND o.action_id=2
                                    JOIN servers s ON s.id = os.serverID    
                                    JOIN accounts a ON a.id = o.account_id
                                    JOIN protocols p ON p.id = o.protocol_id
                                                                
                                ) ot					
                         ON ot.account=t.name and ot.proto=t.proto and ot.server=t.server
                    WHERE ot.account_id IS NULL and ot.protocol_id  IS NULL and ot.serverID  IS NULL
                    GROUP by name
               
                ');
    $q->commit();
    $msg_alert .= '<br><br>' . ok('Заказы которых нет в активных аккаунтах:');
} else {
    $msg_alert .= '<br><br>' . ok('ничего не найдено');
}
    
