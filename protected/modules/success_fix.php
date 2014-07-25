<?php

/* --------------------------------------- покупка ----------------------------  */
if ($shp_item == 1) {
    $q->qry('Select id From order_invoices Where num_order =0 AND id = ?', $inv_id);
    // print_r($q->numrows());
    //  exit();
    require(commonConsts::path_protect . '/class.order_post.php');
    $order = new order_post($msg);
    if ($q->numrows() && $order->checkSpam()) {
        $order_invoices = $order->inserOrder();
        if ($order_invoices) {
            $q->update('order_invoices', array('num_order' => $order_invoices), ' Where id = ' . $inv_id);
        }

        $msg_alert = ($order->msg_error) ? $order->msg_error : $msg['order_payment_ok'];
    }

    /* формируем письмецо */
    /**  табличка    */
    $show = new show_from_db($msg);
    $show->obj = $tables['user_orders_show'];
    $sql = 'Select ' . $show->obj['fields_sql'] . ' ,u.name as uname From ' . $show->obj['table_view'] . '  
                    ' . $show->obj['where'] . '
                      JOIN order_invoices inv ON inv.num_order = o.num_order and inv.id = ' . $inv_id . '
                    ' . (($show->obj['group']) ? 'Group by ' . $show->obj['group'] : '') . ' 
                    ' . (($show->obj['order']) ? 'Order by ' . $show->obj['order'] : '') . ' ' . $show->obj['order_dir'];
    //echo $sql;
    $show->obj['titles'][12] = 'user';
    $show->obj['fields'][12] = 'uname';
    unset($show->obj['add_rows_form']);

    $show->dataAll = $q->fetch_data_to_array($sql);
    $mail_body = $show->show();
    //echo $mail_body;
    send_mail(commonConsts::admin_email, commonConsts::admin_email,  commonConsts::admin_name, 'new order', $mail_body);
    //common::urldirect('/user');
/* --------------------------------------- подление ----------------------------  */
} else {
    /* $inv_id здесь ext_num orders_user_ext_ids */
    $q->begin();
    $d = $q->fetch_data_to_array('
                    SELECT p.for_sql,ext.orderID,ext.periodID,ext.price,o.datetime_expire
                    FROM orders_user_ext_ids ext
                    JOIN orders o ON o.id = ext.orderID
                    JOIN periods p ON p.id = ext.periodID
                    Where ext.ext_num=' . $inv_id);

    if (!empty($d)) {


        foreach ($d as $v) {
            $date1 = new DateTime("now");
            $date2 = new DateTime($v['datetime_expire']);
            $interval = ($date1 > $date2) ? 'now()' : '\'' . $v['datetime_expire'] . '\'';



            $q->qry('Call deleteOrdersServersActionsIds (?) ', $v['orderID']);
            $q->qry('UPDATE orders 
                            SET 
                               datetime_expire = DATE_ADD(' . $interval . ',INTERVAL ?),
                               datetime_edit = CURRENT_TIMESTAMP,
                               period_id = ?,
                               action_id = 3,
                               price = ?
                            WHERE id =?', $v['for_sql'], $v['periodID'], $v['price'], $v['orderID']);
        }
    }
    $q->commit();

    $show = new show_from_db($msg);
    $show->obj = $tables['user_orders_show'];

    $sql = 'Select ' . $show->obj['fields_sql'] . ' ,u.name as uname From ' . $show->obj['table_view'] . '  
                    ' . $show->obj['where'] . '
                      JOIN orders_user_ext_ids ext ON ext.orderID = o.id and ext.ext_num = ' . $inv_id . '
                    ' . (($show->obj['group']) ? 'Group by ' . $show->obj['group'] : '') . ' 
                    ' . (($show->obj['order']) ? 'Order by ' . $show->obj['order'] : '') . ' ' . $show->obj['order_dir'];
    //echo $sql;
    $show->obj['titles'][12] = 'user';
    $show->obj['fields'][12] = 'uname';
    unset($show->obj['add_rows_form']);
    // print_r($show->obj );
    // exit();
    $show->dataAll = $q->fetch_data_to_array($sql);


    $mail_body = $show->show();

    send_mail(commonConsts::admin_email, commonConsts::admin_email, commonConsts::admin_name, 'order_ext', $mail_body);
    $msg_alert = $msg['order_ext_payment_ok'];
    //common::urldirect('/user');
}
