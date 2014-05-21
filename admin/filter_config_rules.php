<?php
/**  формируем фильтер */


$tables['config_rules_show']['where'].=(is_array($_SESSION[$_filter]['type_filter'])&& $_SESSION[$_filter]['type_filter'][0]!='') ? ' AND  c.type_id IN (' .implode(',', $_SESSION[$_filter]['type_filter']) .')': '';
$tables['config_rules_show']['where'].=($_SESSION[$_filter]['os_filter']) ? ' AND  c.os="' .$_SESSION[$_filter]['os_filter'].'"' : '';
$tables['config_rules_show']['where'].=($_SESSION[$_filter]['portable_filter'] &&  $_SESSION[$_filter]['portable_filter']!='all') ? ' AND  c.portable=' .$_SESSION[$_filter]['portable_filter'].'' : '';
