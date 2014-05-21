<?php

require_once('../protected/class.define.conts.php');
require('../protected/cammon/db.class.php' );

if (is_numeric($_POST['typeID'])) {
    $q = new query();
    $forms = new forms();
    if ($_POST['typeID'] == 3)
        $sql = 'SELECT s.id,s.name FROM servers s Where s.part_multi="1" and s.iddouble="" and s.idmultidouble=""';
    $d = array(form => 'select', caption => 'Сервера',
        first_val => 'no',
        status => 'X',
        sel_field => 'id, name',
        name => 'order_server_ids',
        value => array(),
        table => 'servers',
        sql => $sql);
    $forms->select($d, false);
    echo $forms->str;
}
?>
