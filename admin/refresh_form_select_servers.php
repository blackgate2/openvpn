<?php

require('../protected/cammon/functions.php');
require('../protected/class.define.conts.php');
require('../protected/cammon/db.class.php' );
require('../protected/cammon/forms.class.php' );
require('vars_runtime.php');
require('vars.php');
require('vars_db.class.php');
require( 'start_page.php');

switch ($_REQUEST['type_id']) {
    case 1:
        $tables['orders'][8]['data']=array('vars_db::__table',array('table'=>'servers','where'=>' status="1" AND idmultidouble=\'\' and iddouble=\'\''));
        $select_all=false;
        break;
    case 2:
        $tables['orders'][8]['data']=array('vars_db::__table',array('table'=>'servers','where'=>' status="1" AND iddouble<>\'\''));
        $select_all=false;
        break;
    case 3:
        $tables['orders'][8]['data']=array('vars_db::__table',array('table'=>'servers','where'=>' status="1" AND iddouble=\'\' and part_multi<>\'\''));
        $select_all=true;
        break;
    case 4:
        $tables['orders'][8]['data']=array('vars_db::__table',array('table'=>'servers','where'=>' status="1" AND idmultidouble<>\'\''));
        $select_all=true;
        break;
}
echo forms::select($tables['orders'][8],false,$select_all);

?>