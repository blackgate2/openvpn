<?php

ini_set("display_errors", "1");
ini_set("error_reporting", E_ERROR);
ini_set("max_execution_time", 3600);


require('/home/forbac/public_html/beta/protected/class.define.conts.php');
require(commonConsts::path_cammon . '/db.class.php' );
require(commonConsts::path_cammon . '/class.common.php' );
include commonConsts::path_admin . '/vars_db.class.php';

$remote_path = '/usr/local/etc/php_scripts_account/protected';
$username = 'root';
$password = 'V1ufP2ob5$';
$s = vars_db::get_hosts();
if (!empty($s)) {

    foreach ($s as $v) {
        echo $v['hostname']."\n";

        $connection = ssh2_connect($v['hostname'], 22);
        ssh2_auth_password($connection, $username, $password);

        ssh2_scp_recv($connection, $remote_path.'/___class.accounts.php', 'class.accounts.php');
    }
}
       