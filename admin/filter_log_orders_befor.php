<?php
/**  формируем фильтер */
echo $_SESSION[$_filter]['log_action_filter'];



$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['server_ids_filter'][0]) ?    (' JOIN order_server_ids oss On oss.orderID = o.id  AND  oss.serverID IN (' .implode(',', $_SESSION[$_filter]['server_ids_filter']) .')'):'';


$date1 = ($_SESSION[$_filter]['min_datetime_expire_filter']) ? strDate::DateToSql($_SESSION[$_filter]['min_datetime_expire_filter']) : date('Y-m-d', strtotime('-3 month'));
$date2 = ($_SESSION[$_filter]['max_datetime_expire_filter']) ? strDate::DateToSql($_SESSION[$_filter]['max_datetime_expire_filter']) : date('Y-m-d', strtotime('1 year'));

$tables['log_orders_befor_show']['where'].=' WHERE 1 ';
$tables['log_orders_befor_show']['where'].=' AND (o.datetime_expire BETWEEN \''.$date1.'\' AND \''.$date2.'\'  OR o.datetime_begin =\'0000-00-00 00:00:00\' OR o.datetime_expire =\'0000-00-00 00:00:00\')';

$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['log_action_filter'])? ' AND  o.log_action = \''.$_SESSION[$_filter]['log_action_filter'].'\'': '';


$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['user_id_filter'])? ' AND  o.user_id = '.$_SESSION[$_filter]['user_id_filter']: '';

$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['account_id_filter']) ? ' AND  o.account_id = '. $_SESSION[$_filter]['account_id_filter']: '';

$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['num_order_filter']) ? ' AND  o.id = '. trim($_SESSION[$_filter]['num_order_filter']): '';
$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['num_order_name_filter']) ? ' AND  o.num_order = '. trim($_SESSION[$_filter]['num_order_name_filter']): '';

$tables['log_orders_befor_show']['where'].=(is_array($_SESSION[$_filter]['type_filter'])&& $_SESSION[$_filter]['type_filter'][0]!='') ? ' AND  o.type_id IN (' .implode(',', $_SESSION[$_filter]['type_filter']) .')': '';
$tables['log_orders_befor_show']['where'].=(is_array($_SESSION[$_filter]['period_filter'])&& $_SESSION[$_filter]['period_filter'][0]!='') ? ' AND  o.period_id IN (' .implode(',', $_SESSION[$_filter]['period_filter']) .')': '';

$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['os_filter']) ? ' AND  o.os="' .$_SESSION[$_filter]['os_filter'].'"' : '';
$tables['log_orders_befor_show']['where'].=(is_array($_SESSION[$_filter]['actions_filter'])&& $_SESSION[$_filter]['actions_filter'][0]!='') ? ' AND  o.action_id IN (' .implode(',', $_SESSION[$_filter]['actions_filter']) .')': '';

$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['is_respons']=='yes') ? ' AND EXISTS (SELECT * FROM order_server_action_ids osa  WHERE osa.orderID = o.id  )' : '';
$tables['log_orders_befor_show']['where'].=($_SESSION[$_filter]['is_respons']==='no') ? ' AND NOT EXISTS (SELECT * FROM order_server_action_ids osa  WHERE osa.orderID = o.id  )' : '';

//echo $tables['log_orders_befor_show']['where'];