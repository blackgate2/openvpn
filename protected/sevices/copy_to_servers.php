<?php
/*
   sshpass -p "xwjERUPf" rsync -avz  forbac@185.23.16.146:/home/forbac/www/test/protected/sevices/class.accounts.php /usr/local/etc/php_scripts_account/protected/class.accounts.php
 * 
   scp class.accounts.php root@usa-il.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@usa-wc-silver.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@cz-cas.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@de-gold.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@prx.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@bg-silver.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@tr.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@lux1.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
   scp class.accounts.php root@uk-bronze.openvpn.ru:/usr/local/etc/php_scripts_account/protected/class.accounts.php
 *  */

ini_set("display_errors", "1");
ini_set("error_reporting", E_ERROR);
ini_set("max_execution_time", 3600);


require('/home/forbac/public_html/beta/protected/class.define.conts.php');
require(commonConsts::path_cammon . '/db.class.php' );
require(commonConsts::path_cammon . '/class.common.php' );
include commonConsts::path_admin . '/vars_db.class.php';


include('phpseclib0.3.7/Net/SFTP.php');

$file_name = 'class.accounts.php';
$remote_directory = '/usr/local/etc/php_scripts_account/protected';
$username = 'root';
$password = 'V1ufP2ob5$';
$s = vars_db::get_hosts();
if (!empty($s)) {

    foreach ($s as $v) {
        echo $v['hostname'] . "\n";
        $sftp = new Net_SFTP($v['hostname']);
        if (!$sftp->login($username, $password)) {
            exit('Login Failed');
        }

        $success = $sftp->put($remote_directory.'/_'. $file_name, './' . $file, NET_SFTP_LOCAL_FILE);
    }
}