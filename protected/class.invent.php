<?php

//
//http://confs3.openvpn.ru/create/inv.php?r=1


class invent {

    private $url;
    private $content;
    private $inv_cont;
    private $q;
    public $ok;

    public function __construct() {
        $this->q = DB::Open();
        $this->url = 'http://support:Tp0VkeSc4@confs3.openvpn.ru/create/inv.php?r=1';
        $this->get_content();
        $this->pars_content();
        $this->sql();
    }

    private function get_content() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        ob_start();

        curl_exec($ch);
        curl_close($ch);
        $this->content = ob_get_contents();

        ob_end_clean();
    }

    private function pars_content() {
        $this->inv_cont = explode("<br>", $this->content); //create array separate by new line
    }

    private function sql() {
        if (is_array($this->inv_cont) && $this->inv_cont[0]) {
            $add_sql = '';
            for ($i = 0; $i < count($this->inv_cont); $i++) {
                $str = trim(str_replace(':V:', ':pptp:', trim($this->inv_cont[$i])));
                if ($str) {

                    $add_sql .= '("' . str_replace(':', '","', $str) . '"),';
                }
            }
            $add_sql = trim($add_sql, ',');
            $this->q->begin();
            $this->q->query("Delete from after_invent;");
            $this->q->query("Delete from after_invent_res;");
//    $this->q->query("create table after_invent_res  (
//                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
//                `name` varchar(12), 
//                `proto` varchar(10), 
//                `server` varchar(10),  
//                `status` enum('1','') DEFAULT '',
//                 PRIMARY KEY (`id`),
//                 KEY `nps_ix` (`name`,`proto`,`server`)
//                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;) Engine = MyISAM");
            $this->q->query(" INSERT INTO after_invent (name,proto,server) VALUES $add_sql");
//    echo " INSERT INTO after_invent (name,proto,server) VALUES $add_sql";
            $this->q->query('
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
            $this->q->commit();
            $this->ok= true;
        } else {
            $this->ok=false;
        }
    }

}

//echo $this->inv_accounts ;
?>