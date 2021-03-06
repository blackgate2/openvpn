<?php


$tables['after_invent_show'] = array(
    'titles' => array('id','Аккаунт', 'Протокол', 'Сервер','Статус'),
    'fields' => array('id','name',    'proto',    'server','status'),
    'table' => 'after_invent',
    'table_view' => 'after_invent_res',
    'where' => '',
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : 'name',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    //'url' => '/admin/index.php?m=main&table=after_invent',
    'id'=>'id',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 0,
    'isSortbl' => 1,
    'isDialog' => 0,
    'isDefaultActions'=>0,
    'nonSortblFields' => array('status'),
    'status_titles' => array('Заблокировать','Выключить блокировку'),
    'status_hints' => array('Включить  аккаунт на блокировку','Выключить  аккаунт на блокировку'),
    'actions_pannel' => array(
        array('title' => 'Сделать инвентаризацию аккаунтов',
            'hint'=>'Это действие найдет все аккаунта на серверах подлежащих блокировке',
            'url' => 'index.php?m=main&table=' . $table,
            'action' => 'invent',
            'css' => 'link_go ui-button-text-only',
            'confirm' => ''),
        array('title' => 'Заблокировать','hint'=>'Включить выбранные аккаунты на блокировку',
            'url' => 'index.php?m=main&table=' . $table.'&statusNEW=1',
            'action' => 'changestatus',
            'css' => 'link_group_anythink ui-button-text-only',
            'confirm' => 'Вы действительно хотите включить все выбранные аккаунты на блокировку?'),
        array('title' => 'Выключить блокировку','hint'=>'Выключить выбранные аккаунты на блокировку',
            'url' => 'index.php?m=main&table=' . $table.'&statusNEW=0',
            'action' => 'changestatus',
            'css' => 'link_group_anythink ui-button-text-only',
            'confirm' => 'Вы действительно хотите включить все выбранные аккаунты на блокировку?'),
        ),

        
);
$tables['params_pages_ids_show'] = array(
    'titles' => array('ID', 'Тип VPN', 'Протокол', 'OS', 'is Portable', 'Страничка','Якорь'),
    'fields' => array('id', 'type',    'proto',    'os', 'portable',    'page','yakor'),
    'fields_sql' => 'c.id,   t.name as type, p.name as proto,  c.os,   c.portable,  pa.name as page,c.yakor',
    'table' => 'params_pages_ids',
    'table_view' => $table_lang . ' c',
    'where' => '
        JOIN types t ON t.id = c.type_id
        JOIN protocols p ON p.id = c.protocol_id 
        LEFT JOIN ' . (($lang) ? $lang . '_pages' : 'pages') . ' pa ON pa.id=c.page_id
        WHERE 1
        ',
    
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : 'c.id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'isDefaultActions'=>1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);


$tables['user_groups_show'] = array(
    'titles' => array('ID', 'Название', 'Кол-во дней по начала рассылки', 'Способ отправки', 'Сообщение'),
    'fields' => array('id', 'name', 'note_period_days', 'note_method', 'mess_id'),
    'fields_sql' => 'g.id, g.name, g.note_period_days, g.note_method, m.mess',
    'table' => 'user_groups',
    'table_view' => $table_lang . ' g',
    'where' => 'LEFT JOIN user_messages m ON m.id = g.mess_id',
    'order' => 'g.name',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'isDialog' => 1,
    'path_foto' => '',
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['user_groups_discount_show'] = array(
    'titles' => array('ID', 'Название'),
    'fields' => array('id', 'name'),
    'fields_sql' => 'ud.id, ud.name',
    'table' => 'user_groups_discount',
    'table_view' => 'user_groups_discount ud',
    'where' => '',
    'order' => 'ud.id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 5,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 0,
    'isSortbl' => 0,
    'field_foto' => '',
    'isDialog' => 0,
    'path_foto' => '',
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>0,
);

$tables['countries_by_order_id_show'] = array(
    'titles' => array('', 'countries'),
    'fields' => array('name'),
    'fields_sql' => 'getCountryByOrderID (' . $id . ') as name',
    'table' => 'countries_by_order_id',
    'table_view' => '',
    'where' => '',
    'group' => '',
    'order' => '',
    'order_dir' => '',
    'defMaxRow' => 999999,
);
$tables['pages_show'] = array(
    'titles' => array('ID', 'Название'),
    'fields' => array('id', 'name'),
    'table' => 'pages',
    'table_view' => $table_lang,
    'where' => '',
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : 'id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'dialog_is_modal' => false,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 0,
    'field_foto' => '',
    'path_foto' => '',
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);


$tables['menu_show'] = array(
    'titles' => array('ID', 'Сортировка', 'Название', 'url', 'Страничка', 'Статус'),
    'fields' => array('id', 'sort', 'name', 'url', 'page', 'status'),
    'fields_sql' => 'm.id,m.sort,m.name,m.url,p.name as page,m.status',
    'table' => 'menu',
    'table_view' => $table_lang . ' m',
    'where' => 'LEFT JOIN ' . (($lang) ? $lang . '_pages' : 'pages') . ' p ON p.id=m.pages_id',
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : 'm.sort',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'isDialog' => 1,
    'path_foto' => '',
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['news_show'] = array(
    'titles' => array('ID', 'Дата', 'Название', 'Картинка'),
    'fields' => array('id', 'date', 'name', 'img'),
    'table' => 'news',
    'table_view' => $table_lang,
    'where' => '',
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : $table_lang . '.id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'cap' => 'Новости',
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '../images/news/',
    'isDialog' => 0,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['faq_show'] = array(
    'titles' => array('ID', 'Гуппа', 'Вопрос', 'Ответ', 'На главной', 'Вес', 'Статус'),
    'fields' => array('id', 'gr_name', 'name', 'name_a', 'is_front', 'weight', 'status'),
    'fields_sql' => 'f.id,  g.name as gr_name,  f.name,     f.name_a,   f.is_front,   f.weight, f.status',
    'table' => 'faq',
    'table_view' => $table_lang . ' f',
    'where' => 'LEFT JOIN ' . (($lang) ? $lang . '_faq_group' : 'faq_group') . ' g ON g.id=f.group_id',
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : 'f.id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 0,
    'field_foto' => '',
    'path_foto' => '',
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['faq_group_show'] = array(
    'titles' => array('ID', 'Группа'),
    'fields' => array('id', 'name'),
    'table' => 'faq_group',
    'table_view' => $table_lang,
    'where' => '',
    'order' => isset($_SESSION[$table_lang]['order']) ? $_SESSION[$table_lang]['order'] : $table_lang . '.id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '',
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['users_show'] = array(
    'titles' => array('ID', 'Login', 'Группа рассылки','Группа скидки', 'ФИО', 'Email', 'ICQ', 'Jabber', 'Тип пользователя', 'Статус', 'Уведомлять по', 'Дата регистрации', 'Заметки'),
    'fields' => array('id', 'login', 'gr',             'gd',            'name', 'email', 'icq', 'jabber', 'tip_user', 'status', 'note_method', 'date_reg', 'notes'),
    'fields_sql'=>'u.id, u.login, g.name as gr, gd.name as gd, u.name,u.email, u.icq, u.jabber, u.tip_user, u.status, u.note_method, u.date_reg, u.notes',
    'table' => 'users',
    'table_view' => 'users u',
    'where' => ''
    . 'LEFT JOIN user_groups g ON g.id=u.group_id '
    . 'LEFT JOIN user_groups_discount gd ON gd.id=u.price_dis_id '
    . 'WHERE 1 ',
    'order' => isset($_SESSION['users']['order']) ? $_SESSION['users']['order'] : 'u.id',
    'order_dir' => ($_SESSION['users']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '',
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
    'actions_pannel' => array(
        
        array('title' => 'Групповая правка',
            'url' => 'dialog.php?m=main&table=users_groups',
            'action' => 'edit',
            'css' => 'link_group_dialog_modal ui-button-text-only',
            'confirm' => ''),
        )
);

$tables['accounts_show'] = array(
    'titles' => array('ID', 'Логин', 'Пароль'),
    'fields' => array('id', 'name', 'pass'),
    'table' => 'accounts',
    'table_view' => 'accounts',
    'where' => '',
    'order' => isset($_SESSION['accounts']['order']) ? $_SESSION['accounts']['order'] : 'name',
    'order_dir' => ($_SESSION['accounts']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);
$tables['countries_show'] = array(
    'titles' => array('ID', 'Название', 'Сокращение', 'Статус'),
    'fields' => array('id', 'name', 'shot_name', 'status'),
    'table' => 'countries',
    'table_view' => 'countries',
    'where' => '',
    'order' => isset($_SESSION['countries']['order']) ? $_SESSION['countries']['order'] : 'countries.id',
    'order_dir' => ($_SESSION['countries']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '',
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['protocols_show'] = array(
    'titles' => array('ID', 'Название', 'Статус'),
    'fields' => array('id', 'name', 'status'),
    'table' => 'protocols',
    'table_view' => 'protocols',
    'where' => '',
    'order' => isset($_SESSION['protocols']['order']) ? $_SESSION['protocols']['order'] : 'protocols.id',
    'order_dir' => ($_SESSION['protocols']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '',
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);
$tables['periods_show'] = array(
    'titles' => array('ID', 'Название', 'Статус'),
    'fields' => array('id', 'name', 'status'),
    'table' => 'periods',
    'table_view' => 'periods',
    'where' => '',
    'order' => isset($_SESSION['periods']['order']) ? $_SESSION['periods']['order'] : 'periods.id',
    'order_dir' => ($_SESSION['periods']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '',
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);

$tables['servers_show'] = array(
    'titles' => array('ID', 'Название', 'Номер', 'ID для Дабла', 'ID для мультиДабла', 'Участие в мульти аккаунте', 'Хост', 'DNS1', 'DNS2', 'Порт', 'IP', 'Страна', 'Хостер', 'Email', 'Способ оплаты', 'Дата начала', 'Дата конца', 'Статус'),
    'fields' => array('id', 'name', 'num_ser', 'iddouble', 'idmultidouble', 'part_multi', 'hostname', 'dns1', 'dns2', 'port', 'ip', 'cname', 'hoster', 'emails', 'payment', 'date_begin', 'date_expire', 'status'),
    'fields_sql' => 's.id,  s.name,   s.num_ser,    s.iddouble,   s.idmultidouble,    s.part_multi,  s.hostname, d1.name as dns1,  d2.name as dns2, s.port, s.ip, c.name as cname, s.hoster, s.emails, s.payment,      s.date_begin,  s.date_expire,   s.status',
    'table' => 'servers',
    'table_view' => 'servers s',
    'where' => '
                  Left Join countries c On c.id=s.country_id
                  Left Join dns d1 On d1.id=s.dns1_id
                  Left Join dns d2 On d2.id=s.dns2_id
                    ',
    'order' => isset($_SESSION['servers']['order']) ? $_SESSION['servers']['order'] : 's.id',
    'order_dir' => ($_SESSION['servers']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'path_foto' => '',
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['prices_show'] = array(
    'titles' => array('ID', 'Период(месяцы)', 'Категория', 'Страна(ы)', 
        'Цена', 'portable', 
        'Цена1', 'portable1', 
        'Цена2', 'portable2', 
        'Цена3', 'portable3', 
        'Цена4', 'portable4', 
        'Цена5', 'portable5', 
        'Статус'),
    'fields' => array('id', 'period', 'pgname', 'countries', 
        'name', 'portable_price', 
        'name1', 'portable_price1', 
        'name2', 'portable_price2', 
        'name3', 'portable_price3', 
        'name4', 'portable_price4', 
        'name5', 'portable_price5', 
        'status'),
    'fields_sql' => 'p.id,
                    p.period,
                    pg.name as pgname,
                    getCountriesByPriceIDs(p.id) as countries,
                    p.name,
                    p.portable_price,
                    p.name1,
                    p.portable_price1,
                    p.name2,
                    p.portable_price2,
                    p.name3,
                    p.portable_price3,
                    p.name4,
                    p.portable_price4,
                    p.name5,
                    p.portable_price5,
                    p.status',
    'table' => 'prices',
    'table_view' => 'prices p',
    'where' => 'JOIN types pg ON pg.id=p.type_id Where 1',
    'order' => isset($_SESSION['prices']['order']) ? $_SESSION['prices']['order'] : 'p.id',
    'order_dir' => ($_SESSION['prices']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 20,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['types_show'] = array(
    'titles' => array('ID', 'Тип VPN', 'Протоколы'),
    'fields' => array('id', 'name', 'prot'),
    'fields_sql' => 'id,name,getProtocolsByIDs(id) as prot',
    'table' => 'types',
    'table_view' => 'types',
    'where' => '',
    'order' => isset($_SESSION['types']['order']) ? $_SESSION['types']['order'] : 'name',
    'order_dir' => ($_SESSION['types']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);
$tables['actions_show'] = array(
    'titles' => array('ID', 'Название'),
    'fields' => array('id', 'name'),
    'table' => 'actions',
    'table_view' => 'actions',
    'where' => '',
    'order' => isset($_SESSION['actions']['order']) ? $_SESSION['actions']['order'] : 'name',
    'order_dir' => ($_SESSION['actions']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 0,
    'isSortbl' => 0,
    'isDialog' => 0,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);
$tables['dns_show'] = array(
    'titles' => array('ID', 'Логин'),
    'fields' => array('id', 'name'),
    'table' => 'dns',
    'table_view' => 'dns',
    'where' => '',
    'order' => isset($_SESSION['dns']['order']) ? $_SESSION['dns']['order'] : 'name',
    'order_dir' => ($_SESSION['dns']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 0,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);
$tables['commads_show'] = array(
    'titles' => array('ID', 'Тип openVPN', 'Действие', 'Команда', 'Протокол'),
    'fields' => array('id', 'type', 'act', 'command', 'protocol'),
    'fields_sql' => 'c.id,     t.name as type,  a.action as act,    c.name as command,  p.name as protocol',
    'table' => 'commads',
    'table_view' => 'commads c',
    'where' => '
        JOIN types t ON t.id = c.type_id
        JOIN actions a ON a.id = c.action_id
        JOIN protocols p ON p.id = c.protocol_id
        ',
    'order' => 'c.id',
    'order_dir' => 'desc',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 0,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);

$tables['config_rules_show'] = array(
    'titles' => array('ID', 'Тип VPN', 'Протокол', 'OS', 'is Portable', 'Папка для конфига', 'Доп. файлы'),
    'fields' => array('id', 'type', 'proto', 'os', 'portable', 'config_folder', 'add_files_folder'),
    'fields_sql' => 'c.id,   t.name as type, p.name as proto,  c.os,   c.portable,  c.config_folder,    c.add_files_folder',
    'table' => 'config_rules',
    'table_view' => 'config_rules c',
    'where' => '
        JOIN types t ON t.id = c.type_id
        JOIN protocols p ON p.id = c.protocol_id Where 1',
    'order' => isset($_SESSION['config_rules']['order']) ? $_SESSION['config_rules']['order'] : 'c.id',
    'order_dir' => ($_SESSION['config_rules']['order_dir']) ? 'desc' : '',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array(),
    'isDefaultActions'=>1,
);

$tables['orders_reponse_show'] = array(
    'titles' => array('Сервер'),
    'fields' => array('name'),
    'fields_sql'=>'getServerActByOrder('.$id.') as name',
    'table' => 'orders_reponse',
    'table_view' => '',
    'where' => '',
    'order' =>  '',
    'order_dir' => '',
    'dialog_url_edit' => '',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 0,
    'isSortbl' => 0,
    'isDialog' => 1,
    'nonSortblFields' => array(),
    'actions_pannel' => ''
);
$tables['orders_user_ext_show'] = array(
    'titles' => array('ext_num','period','user_name','date_create','price'),
    'fields' => array('ext_num','period','user_name','date_create','price'),
    'fields_sql'=>'
        e.ext_num,
        p.name as period,
        u.name as user_name,
        e.date_create,
        e.price',
    
    'table' => 'orders_user_ext',
    'table_view' => 'orders_user_ext_ids e'
    . ' Join users u ON u.id = e.userID '
    . ' Join periods p ON p.id = e.periodID ',
    'where' => 'where e.orderID = '.$id,
    'order' =>  'orderID',
    'order_dir' => 'DESC',
    'dialog_url_edit' => '',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 0,
    'isSortbl' => 0,
    'isDialog' => 1,
    'nonSortblFields' => array(),
    'actions_pannel' => ''
);
$tables['log_mess_show'] = array(
    'titles' => array('ID', 'Тип', 'Invoice number', 'Return Value', 'Сообщение', 'Дата отправки'),
    'fields' => array('id', 'note_method', 'invoice_numbers', 'return_val', 'mess', 'datetime_exec'),
    'table' => 'log_mess',
    'table_view' => 'log_mess',
    'where' => '',
    'order' => isset($_SESSION['log_mess']['order']) ? $_SESSION['log_mess']['order'] : 'id',
    'order_dir' => ($_SESSION['log_mess']['order_dir']) ? 'desc' : '',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);

$tables['log_lounch_script_show'] = array(
    'titles' => array('ID', 'Действие', 'Команда',     'Return Value', 'Дата',          'Сервер', '#Орд.',    '#ОрдID.',  'VPN', 'Дата начала',     'Дата конца',      'Заказчик', 'Цена', 'is Port',   'Период', 'action', 'Протокол', 'Аккоукт', 'OS', 'Дата создания', 'Дата редакции'),
    'fields' => array('id', 'command',  'comman_line', 'return_val',   'datetime_exec', 'server', 'num_order','order_id', 'type', 'datetime_begin', 'datetime_expire', 'user',     'price', 'portable', 'period', 'action', 'protocol', 'account', 'os', 'date_create', 'datetime_edit', 'user_update'),
    'fields_sql' => ' id,  command,    comman_line,   return_val,   datetime_exec, server,  num_order, order_id, type, datetime_begin, datetime_expire, user, price, portable, period, action, protocol, account, os, date_create, datetime_edit,user_update',
    'table' => 'log_lounch_script',
    'table_view' => 'log_lounch_script  ',
    'where' => '',
    'order' => 'id',
    'order_dir' => 'desc',
    'limit' => '300',
    'cap' => 'Лог команд от серверов',
    'dialog_width' => '760',
    'dialog_height' => '460',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 1,
    'isSortbl' => 0,
//   'field_title' => 'id',
//    'path_foto' => '',
    'isDialog' => 0,
    'nonSortblFields' => array('id', 'action', 'comman_line', 'return_val', 'datetime_exec', 'server'),
    'actions_pannel' => array()
);
$tables['activitylog_show'] = array(
    'titles' => array('ID', 'Дата', 'User name', 'Action', 'IP'),
    'fields' => array('id', 'datetime', 'username', 'action', 'ip'),
    'fields_sql' => 'id, date as datetime,  username, action, ip',
    'table' => 'activitylog',
    'table_view' => 'activitylog',
    'where' => '',
    'order' => 'id',
    'order_dir' => 'desc',
    'limit' => '300',
    'cap' => 'Лог пользователей',
    'dialog_width' => '760',
    'dialog_height' => '460',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 1,
    'isSortbl' => 1,
//   'field_title' => 'id',
//    'path_foto' => '',
    'isDialog' => 0,
    'nonSortblFields' => array('id', 'action', 'comman_line', 'return_val', 'datetime_exec', 'server'),
    'actions_pannel' => array()
);
$tables['user_orders_show'] = array(
    'titles' => $msg['user_orders_tab'],
    'fields' => array('button_get_config','alink_settings', 'name', 'datetime_begin', 'datetime_expire', 'type', 'country', 'price', 'select_period', 'button_pay_account'),
    'fields_sql' => 'o.id, 
                    -- CONCAT(
                    -- IF(of.config!="" AND o.portable="1",
                    --    CONCAT(a.name, \'<br><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=32" class="ui-state-default ui-corner-all">Config32</a><br/>
                    --             <a href="/downloadConfig.php/?hash=\',of.config,\'&portable=64" class="ui-state-default ui-corner-all">Config64</a>\') ,
                    --        IF(of.config!="" AND o.portable="", 
                    --           CONCAT(a.name, \'<br><a href="/downloadConfig.php/?hash=\',of.config,\'" class="ui-state-default ui-corner-all">Config</a>\'), 
                    --           "")  ),
                    --           
                    --  \'<br><a href="\',IFNULL( get_url_by_order_params(o.type_id, o.protocol_id, o.portable, o.os),""),\'" class="ui-state-default ui-corner-all">settings</a>\'
                    -- ,o.type_id, o.protocol_id, \'portable\',IFNULL(o.portable,\'0\'), o.os
                    --  
                    -- ) as accountconfig, 
                     o.num_order as name,
                     o.datetime_begin,
                     o.datetime_expire,
                     pi.name as period,
                     CONCAT(\'<strong>\',t.name,\'</strong>: \',
                            p.name,
                            IF(o.portable=1, \', portable\', \'\'),
                            \', \',o.os,
                            IF(o.type_id=1 or o.type_id=2 , getDNSByServerID(o.id) ,  CONCAT(\' <a href="javascript:"title="\',getDNSByServerID(o.id),\'" class="list_to_dialog ui-state-default ui-corner-all">dns</a>\'))
                            ) as type,
                     p.name as protocol,
                     IF(o.type_id=1 or o.type_id=2, getCountryByOrderID(o.id) ,CONCAT(\' <a href="javascript:"title="\',getCountryByOrderID(o.id),\'" class="list_to_dialog ui-state-default ui-corner-all">'.$msg['user_orders_tab'][5].'</a>\')) as country,
                     o.price,
                     o.portable,
                     o.os,
                     os.name as ostatus,
                     o.type_id,
                     o.action_id,
                     of.config as config_data,
                     o.period_id,
                     o.protocol_id,
                     a.name as account_name,
                     IFNULL( '.$nav->pre.'get_url_by_order_params(o.type_id, o.protocol_id, o.portable, o.os),"") as url_by_order_params
                     -- ext.ext_num
                     
                    ',
    'table' => 'user_orders',
    'table_view' => 'orders o',
    'where' => 'JOIN users u ON u.id=o.user_id
                JOIN periods pi ON pi.id=o.period_id
                JOIN types t ON t.id = o.type_id
                JOIN protocols p ON p.id = o.protocol_id
                JOIN actions os ON os.id = o.action_id                      
               
                Left Join order_server_action_ids osids ON osids.orderID = o.id 
                Left Join accounts a ON a.id = osids.accountID
                
                Left Join order_configs of On of.order_id = o.id
                ',
    'order' => 'o.id',
    'group' => 'o.id',
    'url'=>'/user/?table=user_orders',
    'defMaxRow' => 10,
    'order_dir' => 'desc',
    'isCheck' => 1,
    'money_format' => array('price'),
    'add_collumn_alink_settings' => array(
        'user_row::alink_settings' => array(
            array(
                'url' => '',
                'action' => '',
                'hint' => $msg['button_settings_hint'],
                'title' => $msg['button_settings'],
                'ico' => '',
                'css' => 'menu',
                'confirm' => ''
            )

        ),
    ),
    'add_collumn_button_get_config' => array(
        'user_row::button_get_config' => array(
            array(
                'url' => '/downloadConfig.php/?hash=',                
                'action' => '',
                'title' => $msg['button_config'],
                'hint' => $msg['button_config_hint'],
                'ico' => '',
                'css' => 'link_go button_row ui-button-text-only',
                'confirm' => ''
            ),

        ),
    ),
    'add_collumn_select_period' => array(
        'user_row::select_period' => array(
            array(form => 'select', caption => 'Период', first_val => '', status => '0', name => '', 
                value => '2',
                data => array('vars_db::__table', array('table' => 'periods', 'order' => 'id'))
                ),
        ),
    ),
    'add_collumn_button_pay_account' => array(
        'user_row::button_pay_account' => array(
            array(
                'url' => $url,
                'mrh_pass1' => $mrh_pass1,
                'url_params' => array(
                    'MerchantLogin' => $mrh_login,
                    'OutSum' => $out_summ,
                    'InvId' => $inv_id,
                    'shpItem' => $shp_item,
                    'SignatureValue' => $crc,
                    'Description' => $inv_desc2,
                    'Culture' => $culture,
                    'Encoding' => $encoding
                ),
                'action' => '',
                'hint' => '',
                'title' => $msg['user_prolong'],
                'ico' => '',
                'css' => 'ext_pay button_row ui-button-text-only',
                'confirm' => ''
            ),
        )
    ),
    'set_rows_colors' => array(
        'user_row::rowColors' => array(
            // Поступил
            'enrolled' => array(
                'odd' => 'odd_yellow',
                'even' => 'even_yellow',
            ),
            // Поступил c сайта
            'enrolled_site' => array(
                'odd' => 'odd_blue',
                'even' => 'even_blue',
            ),
            // work
            'work' => array(
                'odd' => 'odd_green',
                'even' => 'even_green',
            ),
            // lock 
            'lock' => array(
                'odd' => 'odd_red',
                'even' => 'even_red',
            ),
            // archive
            'archive' => array(
                'odd' => 'odd_gray',
                'even' => 'even_gray',
            ),
        )
    ),
    'actions_pannel' => array(array(
            'title' => $msg['user_prolong_all'],
            'url' => '',
            'name' => 'order_ext_all',
            'css' => 'link_group_anythink ui-button-text-only',
            'confirm' => '')),
    
);

$tables['user_messages_show'] = array(
    'titles' => array('ID', 'Тема', 'Сообщение'),
    'fields' => array('id', 'name', 'mess'),
    'table' => 'user_messages',
    'table_view' => $table,
    'where' => '',
    'order' => isset($_SESSION[$table]['order']) ? $_SESSION[$table]['order'] : $table_lang . '.id',
    'order_dir' => ($_SESSION[$table_lang]['order_dir']) ? 'desc' : '',
    'cap' => 'Тексты сообщений',
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'field_foto' => '',
    'isDialog' => 1,
    'path_foto' => '',
    'nonSortblFields' => array('status'),
    'actions_pannel' => array()
);

$tables['orders_show'] = array(
    'titles' => array('id','Дата прод.','Дата ред.', 'Акк/Конф','Акк в Откл.', 'Заказчик', '№ заказа', 'Дата начала', 'Дата конца', 'Период', 'VPN', 'в Плане', 'Откл./Опл.', 'Цена'),
    'fields' => array('id','ext_date','datetime_edit',  'account_conf','acc_res', 'user', 'name', 'datetime_begin', 'datetime_expire', 'period', 'type', 'server', 'reponse', 'price'),
    'fields_sql' => 'DISTINCT o.id,  
                    
                    ext.date_create as ext_date,                    
                    IF(ext.orderID  IS NOT NULL, 1 ,"") as is_ext,
                    o.datetime_edit,
                    aa.name as acc_res,
                    IF(of.config!="" AND o.portable="1",
                        CONCAT(a.name,\'<br/><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=32">Config32</a><br/><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=64">Config64</a>\') ,
                            IF(of.config!="" AND o.portable="", 
                               CONCAT(a.name,\'<br/><a href="/downloadConfig.php/?hash=\',of.config,\'">Config</a>\'), 
                               a.name)  ) as account_conf,  
                    CONCAT(\'<small>\',u.name,\'<br>\',u.email,\'<br>\',u.icq,\'<br>\',u.jabber,\'<br><span class="red">\',IFNULL(o.notes,\'\'),\'</small></span><br>_________<br><span class="green">\',IFNULL(uu.name,\'\'),\'</span>\' ) as user, 
                    o.num_order as name,
                    o.datetime_begin,
                    o.datetime_expire,
                    pi.name as period,
                    CONCAT(t.name,\'<br>\',p.name,\'<br>\',o.os,\'<br>\', IF(o.portable!="",\'portable\',\'\')) as type,
                    p.name as protocol,
                     CONCAT(\'<small>\',getServerByOrderID(o.id),\'</small>\') as server,
                    IF(osids.orderID,"1","") as is_btn_respons,
                    o.price,
                    o.portable,  
                    o.os,
                    os.name as ostatus',
    'table' => 'orders',
    'table_view' => 'orders o',
    
    'where' => 'JOIN actions os ON os.id = o.action_id
                Left JOIN users u ON u.id=o.user_id
                Left JOIN periods pi ON pi.id=o.period_id
                Left JOIN types t ON t.id = o.type_id
                Left JOIN protocols p ON p.id = o.protocol_id
                
                Left JOIN accounts a ON a.id = o.account_id
                Left JOIN order_configs of On of.order_id = o.id
                Left JOIN order_invoices oi On oi.num_order = o.num_order
                Left Join order_server_action_ids osids ON osids.orderID = o.id 
                Left Join accounts aa ON aa.id = osids.accountID
                Left JOIN orders_user_ext_ids ext ON ext.orderID = o.id
                Left JOIN users uu ON uu.id=o.user_update_id
                ',
    'order' => isset($_SESSION[$table]['order']) ? $_SESSION[$table]['order'] : 'datetime_edit',
    'order_dir' => (!isset($_SESSION[$table]['order_dir'])) ? 'desc' : $_SESSION[$table]['order_dir'],
   // 'group'=>'id',
    'defMaxRow' => 300,
    'isEdit' => 1,
    'isCopy' => 1,
    'isDel' => 1,
    'isCheck' => 1,
    'isNav' => 1,
    'isSortbl' => 1,
    'isDialog' => 1,
    'dialog_url_edit' => '/admin/dialog.php?m=main&table=' . $table,
    'nonSortblFields' => array('status'),
    'isDefaultActions'=>1,
    'actions_pannel' => array(
//        array('title' => 'Заблок.',
//            'url' => 'dialog.php?m=main&table=' . $table,
//            'action' => 'lock_orders',
//            'css' => 'link_group_dialog_modal ui-button-text-only',
//            'confirm' => ''),
        array('title' => 'Изменить',
            'url' => 'dialog.php?m=main&table=orders_params',
            'action' => 'edit',
            'css' => 'link_group_dialog_modal ui-button-text-only',
            'confirm' => ''),
        array('title' => ' + 1мес к date_expire ',
            'url' => 'index.php?m=main&table=' . $table,
            'action' => 'ext_date_expire',
            'css' => 'link_group_anythink ui-button-text-only',
            'confirm' => 'Вы действительно хотите добавить ЦЕЛЫЙ месяц к date_expire?'),
        array('title' => 'Удалить конфиги',
            'url' => 'index.php?m=main&table=' . $table,
            'action' => 'del_configs',
            'css' => 'link_group_anythink ui-button-text-only',
            'confirm' => 'Вы действительно хотите удалить конфиги?'),
        array('title' => 'Удалить отклик',
            'url' => 'index.php?m=main&table=' . $table,
            'action' => 'del_response',
            'css' => 'link_group_anythink ui-button-text-only',
            'confirm' => 'Вы действительно хотите удалить отклик?'),
        array('title' => 'Config filemanager',
            'url' => '' ,
            'action' => '',
            'css' => 'openfilemanger ui-button-text-only',
            'confirm' => ''),
    ),
    'add_collumn_reponse' => array(

        'button1' =>
        array(
            'name'=>'btn_respons',
            'url' => '/admin/dialog.php?m=main&table=orders_reponse',
            'key_val'=>'',
            'action'=>'show',
            'hint' => 'Отклик от серверов по данному заказу',
            'title' => 'Отклик',
            'ico' => ' ui-icon-check',
            'css' => 'link_alert button_row ui-button-icon-only  '
        ),
        'button2' =>
        array(
            'name'=>'btn_respons',
            'url' => '/admin/dialog.php?m=main&table=orders_user_ext',
            'key_val'=>'',
            'name'=>'ext',
            'action'=>'show',
            'hint' => 'Все продления по этому заказу',
            'title' => 'Продление заказа',

            'ico' => ' ui-icon-cart',
            'css' => 'link_alert button_row ui-button-icon-only  '
        ),
       
    ),

    'set_rows_colors' => array(
        'order_row::rowColors' => array(
            // Поступил
            'enrolled' => array(
                'odd' => 'odd_yellow',
                'even' => 'even_yellow',
            ),
            // Поступил c сайта
            'enrolled_site' => array(
                'odd' => 'odd_blue',
                'even' => 'even_blue',
            ),
            // work
            'work' => array(
                'odd' => 'odd_green',
                'even' => 'even_green',
            ),
            // lock 
            'lock' => array(
                'odd' => 'odd_red',
                'even' => 'even_red',
            ),
            // archive
            'archive' => array(
                'odd' => 'odd_gray',
                'even' => 'even_gray',
            ),
        )
    ),

);

$tables['log_orders_befor_show'] = array(
    'titles' => array('id','SQL действие','Дата прод.', 'Дата ред.', 'Акк/Конф','Акк в Откл.', 'Заказчик', '№ заказа', 'Дата начала', 'Дата конца', 'Период', 'VPN', 'в Плане', 'Отклик', 'Цена'),
    'fields' => array('id','log_action','ext_date','datetime_edit',  'account_conf','acc_res', 'user', 'name', 'datetime_begin', 'datetime_expire', 'period', 'type', 'server', 'reponse', 'price'),
    'fields_sql' => 'DISTINCT o.id,  
                    
                    ext.date_create as ext_date,                    
                    IF(ext.orderID  IS NOT NULL, 1 ,"") as is_ext,
                     
                    o.datetime_edit,
                    aa.name as acc_res,
                    IF(of.config!="" AND o.portable="1",
                        CONCAT(a.name,\'<br/><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=32">Config32</a><br/><a href="/downloadConfig.php/?hash=\',of.config,\'&portable=64">Config64</a>\') ,
                            IF(of.config!="" AND o.portable="", 
                               CONCAT(a.name,\'<br/><a href="/downloadConfig.php/?hash=\',of.config,\'">Config</a>\'), 
                               a.name)  ) as account_conf,  
                    CONCAT(\'<small>\',u.name,\'<br>\',u.email,\'<br>\',u.icq,\'<br>\',u.jabber,\'<br><span class="red">\',IFNULL(o.notes,\'\'),\'</small></span><br><span class="green">\',IFNULL(uu.name,\'\'),\'</span>\' ) as user, 
                    o.num_order as name,
                    o.datetime_begin,
                    o.datetime_expire,
                    pi.name as period,
                    CONCAT(t.name,\'<br>\',p.name,\'<br>\',o.os,\'<br>\', IF(o.portable!="",\'portable\',\'\')) as type,
                    p.name as protocol,
                     CONCAT(\'<small>\',getServerByOrderID(o.id),\'</small>\') as server,
                    IF(osids.orderID,"1","") as is_btn_respons,
                    o.price,
                    o.portable,  
                    o.os,
                    os.name as ostatus,
                    o.log_action',
    'table' => 'log_orders_befor',
    'table_view' => 'log_orders_befor o',
    
    'where' => 'JOIN actions os ON os.id = o.action_id
                Left JOIN users u ON u.id=o.user_id
                Left JOIN periods pi ON pi.id=o.period_id
                Left JOIN types t ON t.id = o.type_id
                Left JOIN protocols p ON p.id = o.protocol_id
                
                Left JOIN accounts a ON a.id = o.account_id
                Left JOIN order_configs of On of.order_id = o.id
                Left JOIN order_invoices oi On oi.num_order = o.num_order
                Left JOIN order_server_action_ids osids ON osids.orderID = o.id 
                Left JOIN accounts aa ON aa.id = osids.accountID
                Left JOIN orders_user_ext_ids ext ON ext.orderID = o.id
                Left JOIN users uu ON uu.id=o.user_update_id
                ',
    'order' => isset($_SESSION[$table]['order']) ? $_SESSION[$table]['order'] : 'datetime_edit',
    'order_dir' => (!isset($_SESSION[$table]['order_dir'])) ? 'desc' : $_SESSION[$table]['order_dir'],
   // 'group'=>'id',
    'defMaxRow' => 300,
    'isEdit' => 0,
    'isCopy' => 0,
    'isDel' => 0,
    'isCheck' => 0,
    'isNav' => 0,
    'isSortbl' => 1,
    'isDialog' => 0,
    'dialog_url_edit' => '' ,
    'nonSortblFields' => array('status'),
    'isDefaultActions'=>0,
    'actions_pannel' => array(),
    'set_rows_colors' => array(
        'order_row::rowColors' => array(
            // Поступил
            'enrolled' => array(
                'odd' => 'odd_yellow',
                'even' => 'even_yellow',
            ),
            // Поступил c сайта
            'enrolled_site' => array(
                'odd' => 'odd_blue',
                'even' => 'even_blue',
            ),
            // work
            'work' => array(
                'odd' => 'odd_green',
                'even' => 'even_green',
            ),
            // lock 
            'lock' => array(
                'odd' => 'odd_red',
                'even' => 'even_red',
            ),
            // archive
            'archive' => array(
                'odd' => 'odd_gray',
                'even' => 'even_gray',
            ),
        )
    ),
   );
