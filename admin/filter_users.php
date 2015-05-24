<?php

$tables['users_show']['where'].=($_SESSION[$_filter]['text_filter']) ? 
        ' and ('
        . ' (u.login LIKE "%' . $_SESSION[$_filter]['text_filter'] . '%")'
        . 'OR (u.name LIKE "%' . $_SESSION[$_filter]['text_filter'] . '%")'
        . 'OR (u.email LIKE "%' . $_SESSION[$_filter]['text_filter'] . '%")'
        . 'OR (u.icq LIKE "%' . $_SESSION[$_filter]['text_filter'] . '%")'
        . 'OR (u.jabber LIKE "%' . $_SESSION[$_filter]['text_filter'] . '%")'
        . 'OR (u.skype LIKE "%' . $_SESSION[$_filter]['text_filter'] . '%")'
        . ')' 
        : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['status_filter'] == '1' ) ? ' and u.status = "1" ' : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['status_filter'] == '2') ? ' and u.status = ""' : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['tip_user_filter']) ? ' and u.tip_user = "' . $_SESSION[$_filter]['tip_user_filter'] . '"' : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['group_id_filter']) ? ' and u.group_id = ' . $_SESSION[$_filter]['group_id_filter'] : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['group_dis_id_filter']) ? ' and u.price_dis_id= ' . $_SESSION[$_filter]['group_dis_id_filter'] : '';

