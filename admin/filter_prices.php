<?php

$tables['prices_show']['where'].=(is_array($_SESSION[$_filter]['type_filter'])&& $_SESSION[$_filter]['type_filter'][0]!='') ? ' AND  p.type_id IN (' .implode(',', $_SESSION[$_filter]['type_filter']) .')': '';
$tables['prices_show']['where'].=(is_array($_SESSION[$_filter]['period_filter'])&& $_SESSION[$_filter]['period_filter'][0]!='') ? ' AND  p.period_id IN (' .implode(',', $_SESSION[$_filter]['period_filter']) .')': '';

