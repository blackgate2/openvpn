<?php
$tables['config_rules_filter'] = array(
    array(form => 'select', 
          caption => 'Тип VPN', 
          status => '0', 
          name => 'type_filter',
          value => array(), 
          data => 'vars_db::types'),
    array(form => 'select_simple', caption => 'OS', status => '0', name => 'os_filter', value => $_SESSION[$_filter]['os_filter'], values => array(''=>'', 'win'=>'Win', 'mac'=>'Mac')),
    array(form => 'select_simple', caption => 'is Portable', status => '0', name => 'portable_filter', value => $_SESSION[$_filter]['portable_filter'], values => array('1'=>'Portable', '0'=>'not Portable','all'=>'Все')),
    
);
$tables['prices_filter'] = array(
    array(form => 'select', 
          caption => 'Тип VPN', 
          status => '0', 
          name => 'type_filter',
          value => array(), 
          data => 'vars_db::types'),
    array(form => 'select', caption => 'Период', status => '0', name => 'period_filter', value => array(), data => 'vars_db::periods'),
);
$tables['orders_filter'] = array(

   // array(form => 'select', caption => 'Номер заказа', status => '0', name => 'num_order_filter', value => array(), sql => 'Select num_order as id,num_order as name From orders Group by num_order Order by num_order desc'),
    /*
    array(form => 'select', 
          caption => 'Заказчик',  
           status => '0', 
           name => 'user_id_filter', 
           value => (is_array($_SESSION[$_filter]['user_id_filter']))?$_SESSION[$_filter]['user_id_filter']:array(), 
           data => 'vars_db::order_users_filter'),
    */
    array(form => 'select', 
          caption => 'Тип VPN', 
          status => '0', 
          name => 'type_filter', 
          value => (is_array($_SESSION[$_filter]['type_filter']))?$_SESSION[$_filter]['type_filter']:array(), 
          data => 'vars_db::types'),
    array(form => 'select', caption => 'Период', status => '0', name => 'period_filter', 
        value => (is_array($_SESSION[$_filter]['period_filter']))?$_SESSION[$_filter]['period_filter']:array(),   
        data => 'vars_db::periods'),
    array(form => 'select', caption => 'Сервер(а)', status => '0', name => 'server_ids_filter', 
        value => (is_array($_SESSION[$_filter]['server_ids_filter']))?$_SESSION[$_filter]['server_ids_filter']:array(), 
           // get_value=>array('vars_db::__selected_ids',array('table'=>'order_server_ids', 'field'=>'serverID', 'where'=>'orderID='.$id )),
            data=>array('vars_db::__table',array('table'=>'servers','order'=>'name'))),
    array(form => 'select', caption => 'Статус', status => '0', name => 'actions_filter', 
         value => (is_array($_SESSION[$_filter]['actions_filter']))?$_SESSION[$_filter]['actions_filter']:array(), 
        data => 'vars_db::actions'),
    array(form => 'select_simple', caption => 'OS', status => '0', name => 'os_filter', value => $_SESSION[$_filter]['os_filter'], values => array(''=>'', 'win'=>'Win', 'mac'=>'Mac')),
    
    array(form => 'date_range',
        caption => 'Границы даты конца',
        status => '0',
        name => 'datetime_expire_filter',
        format => 'dd.mm.yy',
        
        value => array(
            'min' => ($_SESSION[$_filter]['min_datetime_expire_filter']) ? $_SESSION[$_filter]['min_datetime_expire_filter'] : strDate::Date(date('Y-m-d', strtotime('-3 month'))),
            'max' => ($_SESSION[$_filter]['max_datetime_expire_filter']) ? $_SESSION[$_filter]['max_datetime_expire_filter'] : strDate::Date(date('Y-m-d', strtotime('2 year')))),
    ),
    array(form => 'select_simple', caption => 'Оклик', status => '0', name => 'is_respons', 
        value => $_SESSION[$_filter]['os_filter'], 
        values => array(''=>'', 'yes'=>'есть', 'no'=>'нет')),
    //array(form => 'select', caption => 'Страна', status => '0', name => 'country_filter', value => array(), table => 'countries'),
    //array(form => 'select', caption => 'Сервер', status => '0', name => 'server_filter', value => array(), table => 'servers'),
    
    array(form => 'checkbox', caption => 'Инвентаризация', status => '0', name => 'inv_filter', value => 1, checked => 0),
    
    array(form => 'autocomplete',
        placeholder => 'Заказчик',
        status => '0',
        edit_name => 'user_name_filter',
        name => 'user_id_filter',
        id => 'user_id_filter',
        value => $_SESSION[$_filter]['user_id_filter'],
        title => $_SESSION[$_filter]['user_name_filter'],
        maxRows => 300,
        minLength => 2,
        event_select => ' ',
        get_val_title => '',
        ajax_url => 'dialog.php?m=ajax_respons_users',
    ),
    //array(form => 'checkbox', caption => 'Оклик', status => '0', name => 'is_respons', value => 1, checked => $_SESSION[$_filter]['is_respons']),
    //array(form => 'checkbox', caption => 'не Оклик', status => '0', name => 'not_is_respons', value => 1, checked => $_SESSION[$_filter]['not_is_respons']),
    );
?>