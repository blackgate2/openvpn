<?php

session_start();
/** константы */
require('./protected/class.define.conts.php');

require(commonConsts::path_cammon . '/functions.php');
/** header (charset  cash ) */
Expire();

require(commonConsts::path_cammon . '/db.class.php');
/**  база  */
$q = DB::Open();

/**  локализация  */
require(commonConsts::path_cammon . '/msg_array' . $_SESSION['pre'] . '.php');
//require('./protected/functions_site.php' );


require_once(commonConsts::path_cammon . '/show_from_db.class.php' );
require_once(commonConsts::path_admin . '/vars_runtime.php');
require_once(commonConsts::path_admin . '/vars_show.php');
require_once(commonConsts::path_cammon . '/msg_array.php');


$show = new show_from_db($msg);
$show->is_filter = false;
$show->sortImgUrl = '/images';

$show->obj = $tables[$table . '_show'];
$sql = 'Select ' . $show->obj['fields_sql'] .'
       
        ' . (($show->obj['table_view']) ? 'From ' . $show->obj['table_view'] : '') . ' 
        ' . $show->obj['where'] . ' 
        ' . (($show->obj['group']) ? 'Group by ' . $show->obj['group'] : '') . ' 
        ' . (($show->obj['order']) ? 'Order by ' . $show->obj['order'] : '') . ' ' . $show->obj['order_dir'];

echo $sql;
$show->dataAll = $q->fetch_data_to_array($sql);
if (!empty($show->dataAll)) {
    echo $show->show();
} else {
    echo $msg['not_found'];
}
?>