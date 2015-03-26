<?php
$connection = ssh2_connect('usa-wc-silver.openvpn.ru', 22);
ssh2_auth_password($connection, 'root', 'V1ufP2ob5$');

$stream = ssh2_exec($connection, '/usr/local/etc/bin/inv 1');
stream_set_blocking($stream, true);

echo "Output: " . stream_get_contents($stream);
fclose($stream); 
?>