<?php
/**  формируем фильтер */





$tables['params_pages_ids_show']['where'].=(is_array($_SESSION[$_filter]['type_id_filter'])&& $_SESSION[$_filter]['type_id_filter'][0]!='') ? ' AND  c.type_id IN (' .implode(',', $_SESSION[$_filter]['type_id_filter']) .')': '';
$tables['params_pages_ids_show']['where'].=(is_array($_SESSION[$_filter]['protocol_id_filter'])&& $_SESSION[$_filter]['protocol_id_filter'][0]!='') ? ' AND  c.protocol_id IN (' .implode(',', $_SESSION[$_filter]['protocol_id_filter']) .')': '';
$tables['params_pages_ids_show']['where'].=($_SESSION[$_filter]['portable_filter']!='all') ? ' AND  c.portable="' .$_SESSION[$_filter]['portable_filter'].'"' : '';
$tables['params_pages_ids_show']['where'].=($_SESSION[$_filter]['os_filter']) ? ' AND  c.os="' .$_SESSION[$_filter]['os_filter'].'"' : '';
echo $tables['params_pages_ids_show']['where'];