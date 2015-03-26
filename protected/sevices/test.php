<?php
    $server = "usa-wc-silver.openvpn.ru";
    //ip address will work too i.e. 192.168.254.254 just make sure this is your public ip address not private as is the example

    //specify your username
    $username = "root";

    //select port to use for SSH
    $port = "22";

    //command that will be run on server B
    $command = "uptime";

    //form full command with ssh and command, you will need to use links above for auto authentication help
    $cmd_string = "ssh -p ".$port." ".$username."@".$server." ".$command;

    //this will run the above command on server A (localhost of the php file)
    exec($cmd_string, $output);

    //return the output to the browser
    //This will output the uptime for server B on page on server A
    echo '<pre>';
    print_r($output);
    echo '</pre>';