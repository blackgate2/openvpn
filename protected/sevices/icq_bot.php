<?php

    include('./protected/WebIcqLite.class.php');

    define('UIN', 586534216);
    define('PASSWORD', 'j8*pe5%w');

    $icq = new WebIcqLite();
    if($icq->connect(UIN, PASSWORD)){

        if(!$icq->send_message('108122655', 'Привет из php скрипта 123 !!!')){
        //if(!$icq->send_message('736491', 'Привет из php скрипта!!!')){

            echo $icq->error.'___';

        }else{

            echo 'Message sent';

        }

        $icq->disconnect();

    }else{

        echo $icq->error.' connect';

    }

?>