<?php

//
//http://confs3.openvpn.ru/create/inv.php?r=1


class invent {

    /* old
    private $url;
    private $content;
     * 
     */
    private $inv_cont;
    private $hosts;
    private $q;
    public $ok;

    public function __construct($comand,$usr,$pass) {
        $this->q = DB::Open();
        $this->inv_cont=array();
        
        $this->comand=$comand;
        $this->usr=$usr;
        $this->pass='V1ufP2ob5$';
        $this->hosts = vars_db::get_hosts('',$this->q);

        $this->get_inv();
        //print_r($this->inv_cont);
        //echo $this->inv_cont;
        $this->sql();
    }

    private function get_inv() {
        foreach ($this->hosts as $h) {
            $connection = ssh2_connect($h['hostname'], 22);
            ssh2_auth_password($connection, 'root', 'V1ufP2ob5$');

            $stream = ssh2_exec($connection, '/usr/local/etc/bin/inv_oleg 1');
            stream_set_blocking($stream, true);
            $tmp= explode("\n", stream_get_contents($stream));
            $this->inv_cont=array_merge($this->inv_cont,$tmp);
            $this->inv_cont = array_diff($this->inv_cont, array('',0, null));
            fclose($stream);
        }
    }

    /* old 
     */

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

    /* old
     */

    private function pars_content() {
        $this->inv_cont = explode("<br>", $this->content); //create array separate by new line
    }

    private function sql() {
        if (is_array($this->inv_cont) && $this->inv_cont[0]) {
            $add_sql = '';
            for ($i = 0; $i < count($this->inv_cont); $i++) {
                $str = trim(str_replace(':V:', ':pptp:', trim($this->inv_cont[$i])));
                if ($str) {
                    $str=trim(trim($str,':'));
                    
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
           //  echo " INSERT INTO after_invent (name,proto,server) VALUES $add_sql";
            $this->q->query(" INSERT INTO after_invent (name,proto,server) VALUES $add_sql");
   
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
            $this->ok = true;
        } else {
            $this->ok = false;
        }
    }

}

//echo $this->inv_accounts ;
?>