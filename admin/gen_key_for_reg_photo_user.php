<?


$result = @mysql_query('SELECT * FROM bilets_reg_users');
if (!$result) {
    mysql_query("CREATE TABLE `bilets_reg_users` (
                `id` int(15) NOT NULL AUTO_INCREMENT,
                `bilet` varchar(32) DEFAULT '',
                `status` enum('1','') NOT NULL DEFAULT '1',
                PRIMARY KEY (`id`),
                UNIQUE KEY `bilet_UNIQUE` (`bilet`)
              ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;") or die(mysql_error());
    echo " so I created one!";
}
for ($i = 0; $i < 9999999; $i++) {
    //$rand = mt_rand(0, 32);
    $code = md5($i . time() . 'opa');
    mysql_query('INSERT INTO bilets_reg_users (bilet)  VALUES ("' . $code . '")');
}
echo 'ok<br>';


$result = @mysql_query("SHOW FULL PROCESSLIST");
while ($row = mysql_fetch_array($result)) {
    $process_id = $row["Id"];
    if ($row["Time"] > 200) {
        $sql = "KILL $process_id";
        mysql_query($sql);
    }
}
?>
