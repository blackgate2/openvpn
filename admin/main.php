<?php

require(commonConsts::path_cammon . '/vadation.class.php');
require(commonConsts::path_cammon . '/msg_array.php');


require(commonConsts::path_cammon . '/fileupload.class.php');
require(commonConsts::path_cammon . '/forms.class.php');
require(commonConsts::path_cammon . '/db_object.class.php' );
require(commonConsts::path_cammon . '/show_from_db.class.php' );
require(commonConsts::path_protect . '/order_row.class.php' );
require('vars_db.class.php');
require('actions.class.php');




$o = new db_object($q, $tables[$table], $table_lang, $id);
$show = new show_from_db($msg);
if ($table == 'orders')
    $a = new actions($ids, $q);

/** ---------------------------- инвенторизация  аккаунтов ------------------------------------ */
if ($action == 'invent') {
    require_once commonConsts::path_protect . '/class.invent.php';

    $i = new invent('/usr/local/etc/bin/inv_oleg 1','root', 'V1ufP2ob5$');
    if ($i->ok) {
        $msg_alert .= '<br><br>' . ok('Заказы которых нет в активных аккаунтах:');
    } else {
        $msg_alert .= '<br><br>' . ok('ничего не найдено');
    }
    $action = 'show';
}
/** ----------------------------- в лочим родительский заказ при копировании    ---------------------------------------- */
if ($action == 'insert' && $table == 'orders' && $_POST['is_lock_parent']) {
    if ($a->lock_by_date_exp()) {
        $msg_alert = ok('заблокировали родительский заказ');
    } else {
        $msg_alert = error('Error DB');
    }
}
/** -----------------------------  лочим  выбранне заказы   ---------------------------------------- */
if ($table == 'orders' && $action == 'lock_orders' && $objects_ids != '') {
    if ($a->lock_by_date_exp()) {
        $msg_alert = ok('Заказы заблокируются через минуту');
    } else {
        $msg_alert = error('Error DB');
    }
}


//exit("$action == 'ext_date_expire' && $table == 'orders' && $objects_ids");
/** ----------------------------- в ордерах добавляем к дате datetime_expire   ---------------------------------------- */
if ($action == 'ext_date_expire' && $table == 'orders' && $objects_ids != '') {

    if ($a->inc_date_exp()) {
        $action = 'show';
        $msg_alert = ok('Добавлен 1 месяц к выделенным заказам');
    } else {
        $msg_alert = error('Error DB');
    }
}

/** ----------------------------- удаляем отклики по акшину  ---------------------------------------- */
if ($action == 'del_response' && $table == 'orders' && $objects_ids != '') {
    $q->query('Call deleteOrdersServersActionsIds ("' . $objects_ids . '") ');
    $q->qry('Update orders SET action_id=3,user_update_id =' . $_SESSION['auth_user_id'] . ' Where id IN (?)', $objects_ids);
    $a->delete_cofigs();
    $action = 'show';
    $msg_alert = ok('Отклики удалены');
}
/** ----------------------------- удаляем конфиги по акшину  ---------------------------------------- */
if ($action == 'del_configs' && $table == 'orders' && $objects_ids != '') {
    $a->delete_cofigs();
    $action = 'show';
    $msg_alert = ok('Конфиги удалены');
}

/** ----------------------------- смена статуса  ---------------------------------------- */
if ($action == 'changestatus' && isset($statusNEW) && $table && ($id || $objects_ids)) {

    if ($table == 'users' && $id)
        $q->qry('Update ? SET status=\'?\', user_update_id =' . $_SESSION['auth_user_id'] . ' Where id=?', $tables[$table . '_show']['table_view'], $statusNEW, $id);
    elseif ($table && $id)
        $q->qry('Update ? SET status=\'?\' Where id=?', $tables[$table . '_show']['table_view'], $statusNEW, $id);
    elseif ($table && $objects_ids)
        $q->qry('Update ? SET status=\'?\' Where id IN (?)', $tables[$table . '_show']['table_view'], $statusNEW, $objects_ids);

    $action = 'show';
    $edit_id = $id;
    $msg_alert = ok('Статус изменен');
}
/*
  /** ------------------------------ post ------------------------------------------------------------------- */
if ($action == 'del') {
    if ($id != '')
        $condition = 'Where id = ' . $id;
    elseif ($objects_ids != '')
        $condition = "Where id IN ($objects_ids)";


    /* ---------------- -- удаляем файл ------------------ */
    if (isset($delete_filefoto))
        deletefile($delete_filefoto);
    /* ---------------- -- удаляем связи ------------------ */
//    if ($ids && $table == 'orders') {
//        $q->query('Call `deleteOrdersAccountIds`("' . $ids . '") ');
//    }
    if ($ids && $table == 'price') {
        $q->query('Call `deletePriceCountryIds`("' . $ids . '") ');
    }
    if ($ids && $table == 'types') {
        $q->query('Call `deleteTypeProtocolIds`("' . $ids . '") ');
    }
    if ($ids && $table == 'orders') {
        $q->query('Call `deleteOrdersByID`("' . $ids . '") ');
    }
    if ($condition != '') {
        $q->del($table_lang, $condition);
        $msg_alert = ok($msg['rows_deleted']);
        unset($id);
    }
    $action = 'show';
}
/** ----------------------------- удаляем конфиги при редакции заказа  ---------------------------------------- */
if ($action == 'update' && $table == 'orders' && $a->check_order_for_del_cofig($arr_fields_vals)) {
    $a->delete_cofigs();
    $action = 'show';
    $msg_alert = ok('Конфиги удалены');
}


if ($action == 'update' || $action == 'insert') {
    $arr_fields_vals = $o->post_data_form();
    //    print_r($arr_fields_vals);
    // exit('gg');
    if ($table == 'orders') {
        $arr_fields_vals['user_update_id'] = $_SESSION['auth_user_id'];
    }




    /** ----------------------------- новый пароль  ---------------------------------------- */
    if ($table == 'users' && $arr_fields_vals['passwd'])
        $arr_fields_vals['passwd'] = hesh_pass($arr_fields_vals['passwd'], commonConsts::sol_pass1, commonConsts::sol_pass2);

    /** ----------------------------- общий апдейт  ---------------------------------------- */
    if ($action == 'update' && $objects_ids != '') {
        $condition = 'Where id IN (' . $objects_ids . ')';
        $q->update($table_lang, $arr_fields_vals, $condition);
        $msg_alert = ok($msg['row_saved']);
    } elseif ($action == 'update' && is_numeric($id)) {
        $condition = "Where id =  " . $id;
        $q->update($table_lang, $arr_fields_vals, $condition);
        $msg_alert = ok($msg['row_saved']);
    } elseif ($action == 'insert') {

        $q->insert($table_lang, $arr_fields_vals);
        $msg_alert.= ok($msg['row_added']);
        $id = $q->maxid($table_lang);
    }

    /** ----------------------------- связи  ---------------------------------------- */
    if ($table == 'user_groups_discount' && is_array($_POST['user_ids']) && is_numeric($id)) {
        $q->query('Call insertUpdateUserGroupDiscount (' . $id . ', "' . implode(',', $_POST['user_ids']) . '") ');
    }
    if ($table == 'prices' && is_array($_POST['price_country_ids']) && is_numeric($id)) {
        $q->query('Call insertUpdatePriceCountiesIds (' . $id . ', "' . implode(',', $_POST['price_country_ids']) . '") ');
    }
    if ($table == 'types' && is_array($_POST['type_protocol_ids']) && is_numeric($id)) {
        $q->query('Call insertUpdateTypeProtocolIds (' . $id . ', "' . implode(',', $_POST['type_protocol_ids']) . '") ');
    }
    if ($table == 'orders' && $_POST['action_id'] == '1' && is_array($_POST['order_server_ids']) && is_numeric($id)) {
        /* если action enroled: меняем заказа меняем акк, сервера и убиваем конфиги
          тое по сути перегенериваем заказ */

        $q->begin();
        $q->query('Call insertUpdateOrderServersIds (' . $id . ', "' . implode(',', $_POST['order_server_ids']) . '") ');
        if ($action == 'update')
            $q->query('Call deleteOrdersServersActionsIds ("' . $id . '") ');
        $q->commit();
    } elseif ($table == 'orders' && $action == 'update' && $_POST['action_id'] == '3') {
        $q->query('Call deleteOrdersServersActionsIds ("' . $id . '") ');
    }
    if ($table == 'orders' && is_numeric($id))
        $q->query('Update orders Set  datetime_edit=CURRENT_TIMESTAMP Where id = ' . $id);

    $msg_alert .= error($q->msg_alert);




    $edit_id = $id;
    unset($id);
    $action = 'show';
}


/** ------------------------------------ show   ---------------------------------------- */
if ($action == 'show') {
    $table_show = $table . '_' . $action;

    /** ---------------------------- подключаемы фильтры ------------------------------------ */
    if (file_exists('filter_' . $table . '.php')) {
        include('filter_' . $table . '.php');
        include('filter_form.php');
    }


    /** ---------------------------- отображение таблички ------------------------------------ */
    include 'show_table.php';
}




/** --------------------  отрисовка формы -------------------------------------------- */
if (in_array($action, array('edit', 'copy', 'add'))) {
    include 'form.php';
}
?>