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
class createConfig {

    public $addFilesDir;
    public $zipParh;
    public $q;

    public function __construct() {
        $this->q = DB::Open();
    }

    private function templateConfig($template, $proto, $host, $port) {
        $template = str_replace('?proto?', $proto, $template);
        $template = str_replace('?host?', $host, $template);
        $template = str_replace('?port?', $port, $template);
        return $template;
    }

    private function addFilesToZip(&$zip, $addFilesPath, $startDir) {
        $dirHandle = opendir($addFilesPath);
        while (false !== ($fname = readdir($dirHandle))) {
            if ($fname != '.' && $fname != '..') {

                $file = $addFilesPath . '/' . $fname;

                $dfInArch = str_replace($startDir . '/', '', $file);
                //echo $file,' --- ', $dfInArch.'<br>';

                if (is_dir($file)) {
                    $lastDir = $fname;
                    $zip->addEmptyDir($dfInArch);

                    self::addFilesToZip($zip, $file, $startDir);
                } else {


                    $zip->addFile($file, $dfInArch);
                }
            }
        }
    }

    public function createConfigArchiv($FileZip, $file_config_tmp, $a, $login, $pass, $protocol, $template_config, $addFilesPath, $configFolder, $type, $ip, $os) {
        //phpinfo();


        $zip = new ZipArchive;

        if ($zip->open($FileZip, ZipArchive::CREATE) === true) {
            /**
             * добавляем файлы в архив все файлы и папки из $addFilesDir для конкретного типа конфига
             */
            if ($addFilesPath) {
                $this->addFilesToZip($zip, $addFilesPath, $addFilesPath);
            }
            /**
             * добавляем конфиги
             */
            if ($template_config && is_array($a)) {
                foreach ($a as $v) {

//                    if ($portabl == '' && $os == 'win' && !in_array($v['type'], array('Multi','MultiDouble'))) 
//                        $fileConfig = 'vpn.conf';
//                    else

                    if ($file_config_tmp) {
                        $fileConfig = str_replace('type', $type, $file_config_tmp);
                        $fileConfig = str_replace('server', $v['server'], $fileConfig);
                        $fileConfig = str_replace('protocol', $protocol, $fileConfig);
                    } else {
                        $fileConfig = 'conf.txt';
                    }

                    $fileConfig = (($configFolder) ? $configFolder . '/' : '') . $fileConfig;
                   // echo $fileConfig."\n";
                    $zip->addFromString($fileConfig, $this->templateConfig($template_config, $protocol, $v['hostname'], $v['port']));
                }
            }

            /*
             * добавляем pass.txt
             */
            $filePass = ($configFolder) ? $configFolder . '/pass.txt' : 'pass.txt';
            $zip->addFromString($filePass, $this->createFilePass($login, $pass, $type, $ip, $protocol));

            $zip->close();
        } else {
            echo 'Can\'t create Zip archive';
        }
    }

    private function createFilePass($login, $pass, $type, $ip, $protocol) {
        //echo $type. "\r\n" .$ip. "\r\n" .$protocol;
        return $login . "\r\n" . $pass . ((in_array($type, array('Single', 'Double')) && $protocol == 'pptp') ? "\r\n" . $ip : '');
    }

    public function zipFileName($order_id) {
        return md5($order_id . commonConsts::sol_pass1);
    }

    private function getRulesCreateConfig() {
        $this->q->query('Select 
                            t.name as type, 
                            p.name as protocol, 
                            c.os, 
                            c.portable, 
                            c.config_folder, 
                            c.add_files_folder,
                            c.config_temlplate,
                            c.ext_conf_file
                        From config_rules c 
                        JOIN types t ON t.id = c.type_id 
                        JOIN protocols p ON p.id = c.protocol_id 
                        ');
        $data = array();
        while ($d = $this->q->getrow()) {
            $data[$d['type']][$d['os']][$d['protocol']][$d['portable']] = array(
                'config_folder' => $d['config_folder'],
                'add_files_folder' => $d['add_files_folder'],
                'config_temlplate' => $d['config_temlplate'],
                'ext_conf_file' => $d['ext_conf_file']
            );
        }
        return $data;
    }

    public function startAction() {
        $r = $this->getRulesCreateConfig();




        $data = $this->getOrdersData();
        foreach ($data as $v) {

            /* если портабл хуярим срузу 2 конфига 
             */
            if ($v['portable']) {
                $arrPortables = array('32', '64');
            } else {
                $arrPortables = array('');
            }


            //echo '---',$v['type'],'---',$v['os'],'---',$v['protocol'],'---',$v['portable']."\n";


            foreach ($arrPortables as $portabl) {

                if (!$portabl) {
                    $ruleConf = $r[$v['type']][$v['os']][$v['protocol']][0];
                } else {
                    $ruleConf = $r[$v['type']][$v['os']][$v['protocol']][1];
                }
                //print_r($ruleConf);exit();
                $add_files_folder = '';
                if ($ruleConf['add_files_folder']) {
                    $add_files_folder = $this->addFilesDir . '/' . $ruleConf['add_files_folder'];
                }
                // echo "dd";

                if (is_array($ruleConf)) {


                    $zipFile = $this->zipParh . '/' . $this->zipFileName($v['order_id']) . $portabl . '.zip';

                    // echo $zipFile."\n";
                    // exit();
                    if (file_exists($zipFile))
                        unlink($zipFile);

                    //echo $v['ip']. "--\r\n" .$v['protocol']. "--\r\n" .$v['os'];


                    $this->createConfigArchiv($zipFile, $ruleConf['ext_conf_file'], $v['data_config'], $v['account'], $v['pass'], $v['protocol'], $ruleConf['config_temlplate'], $add_files_folder, $ruleConf['config_folder'], $v['type'], $v['ip'], $v['os']);
                    $this->updateOrders($this->zipFileName($v['order_id']), $v['order_id']);
                }
            }
        }
    }

    protected function updateOrders($fileConfig, $order_id) {
        $this->q->begin();
        $this->q->query('Delete From order_configs Where order_id=' . $order_id);
        $this->q->query('Insert Into order_configs (order_id,config) Values (' . $order_id . ',"' . $fileConfig . '")');
        $this->q->commit();
    }

    public function getOrdersData() {
        $data = array();
        $sql = 'SELECT o.id as order_id,
                       p.name as protocol,
                       pg.name as type,
                       o.os,
                       o.portable,
                       a.name as account,a.pass as pass,
                       s.name as server,s.hostname,s.port,s.ip
			
                FROM orders o
                Join types pg On pg.id=o.type_id
                Join protocols p On p.id=o.protocol_id
                Join actions os On os.id=o.action_id
                Join order_server_action_ids osids 
						On osids.orderID = o.id 						
                Join servers s On s.id = osids.serverID
                Join accounts a On a.id = osids.accountID
                Left Join order_configs of On of.order_id = o.id
		Where os.action = "unlock" and of.config IS NULL and a.name IS NOT NULL
                
                ';
        //echo $sql;
        $data = $this->q->fetch_data_to_array($sql);

        $a = array();

        foreach ($data as $v) {
            $a[$v['order_id']][] = array(
                'server' => $v['server'],
                'port' => $v['port'],
                'hostname' => $v['hostname']);
        }
        $r = array();
        foreach ($data as $d) {
            if (!array_key_exists($d['order_id'], $r)) {
                $r[$d['order_id']] = array(
                    'order_id' => $d['order_id'],
                    'os' => $d['os'],
                    'ip' => $d['ip'],
                    'portable' => $d['portable'],
                    'protocol' => $d['protocol'],
                    'type' => $d['type'],
                    'account' => $d['account'],
                    'pass' => $d['pass'],
                    'account' => $d['account'],
                    'data_config' => $a[$d['order_id']]
                );
            }
        }
        return $r;
    }

}

?>