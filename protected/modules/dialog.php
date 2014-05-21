<?php
require(commonConsts::path_admin.'/vars_runtime.php');
require_once(commonConsts::path_admin.'/vars_show.php' );
require(commonConsts::path_cammon.'/show_from_db.class.php');

$show = new show_from_db($msg);
$show->set_url('/dialog/?table=' . $table );
$show->obj = $tables[$table . '_show'];

if(is_array($ids))$show->setEditID($ids);
elseif($edit_id)$show->setEditID(array($edit_id));


$sql = 'Select ' . ((isset($show->obj['fields_sql'])) ? $show->obj['fields_sql'] : implode(',', $show->obj['fields'])) . '
             ' . (($show->obj['table_view']) ? 'From ' . $show->obj['table_view'] : '') . '
             ' . $show->obj['where'] . ' 
            ' . (($show->obj['group']) ? 'Group by ' . $show->obj['group'] : '') . ' 
            ' . (($show->obj['order']) ? 'Order by ' . $show->obj['order'] : '') . ' ' . $show->obj['order_dir'] . ' 
            ' . (($show->obj['limit']) ? 'Limit ' . $show->obj['limit'] : '');
//exit('<pre>'.$sql.'<pre>');

$show->dataAll = $q->fetch_data_to_array($sql);

/* ------------- default action (add,del row) ------------ */
if (is_array($show->obj['actions_pannel'])) {
    array_push($show->obj['actions_pannel'], array('title' => $msg['add'],
        'url' => (($show->obj['isDialog']) ? 'dialog.php?m=main' : 'index.php?m=main'),
        'table' => $table,
        'action' => 'add',
        'ico' => '',
        'css' => (($show->obj['isDialog']) ? 'link_dialog_modal' : 'link_go') . ' ui-button-text-only',
        'confirm' => ''), array('title' => $msg['dell'],
        'url' => 'index.php?m=main',
        'table' => $table,
        'action' => 'del',
        'ico' => '',
        'css' => 'link_group_anythink ui-button-text-only',
        'confirm' => $msg['confirm_del']));

    $show->str_filters = '<div class="actions">' . $show->actions_buttons() . '</div>';
}


if ($msg_alert)
    echo $msg_alert;
echo $show->show();

?>