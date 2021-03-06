<?php

if (is_array($tables[$table . '_' . $action])) {
    $_table = $table . '_' . $action;
} else {
    $_table = $table;
}
/** ------------------------------------ начиняем нужный объект данными  из базы  ---------------------------------------- */
if ($id )
    $o->get_data_obj();



/* если указан модуль перед формированием формы подключаем его */
if ($tables[$table . '_df']['dialog_inc_modul']) {
    include ($tables[$table . '_df']['dialog_inc_modul']);
}
$forms = new forms($msg);
//$forms->fields = $o->obj;

$key=(isset($tables[$table . '_df']['table']))?$tables[$table . '_df']['table']:$table;

$forms->__set('fields',  $o->obj);
$forms->__set('nameform', 'form_' . $key);
$forms->set_dialog_params(
        array(
            'dialog_name' => $tables[$key . '_df']['dialog_name'],
            'dialog_is_modal' => $tables[$key . '_df']['dialog_is_modal'],
            'dialog_title' => $tables[$key . '_df']['dialog_title'],
            'dialog_title_field' => $tables[$key . '_df']['dialog_title_field'],
            'dialog_width' => $tables[$key . '_df']['dialog_width'],
            'dialog_height' => $tables[$key . '_df']['dialog_height']
));
$forms->__set('url', $tables[$key . '_df']['url']);
$forms->__set('count_view_col', $tables[$key . '_df']['count_view_col']);

/* если отправка ajax*/
if ($tables[$key . '_df']['ajax_tag_respons']) {
    $forms->set_ajax_sent_form($tables[$key . '_df']['ajax_tag_respons']);
}

if ($tables[$key . '_df']['autosubmit']) {
    $forms->__set('autosubmit', true);
}


$def_hiddens = array(
    'action' => ($action == 'edit') ? 'update' : 'insert',
    'm' => $m,
    'table' => $key,
    'order' => $tables[$key . '_show']['order'],
    'order_dir' => $tables[$key . '_show']['order_dir'],
    'objects_ids' => $objects_ids,
    'id' => $id);

/* если нужны хидены можно добавить  */
if ( is_array($tables[$key . '_df']['add_hidden_values'])){
    $def_hiddens = array_merge($def_hiddens,$tables[$key . '_df']['add_hidden_values']);
}
$forms->__set('hiddens', $def_hiddens);
$forms->__set('edit_id', $id);

/* если без диалога включаем редактор */
if (!$tables[$key . '_show']['isDialog']) {
    echo displayHeadlineAdm($tables[$key . '_show']['cap']);
    echo forms::back();
    include 'editor_tiny_mce_vars_new.html';
    $forms->is_form_In_dialog = 0;
}
/* для фильтров */
if ($tables[$key . '_df']['dialog_form']=='filter') {
    $forms->print_form_filter($tables[$key . '_df']['dialog_form_br']);
}else{
    $forms->print_form_site();
}
if ($tables[$key . '_df']['dialog_template_form']) {
    include ($tables[$key . '_df']['dialog_template_form']);
} else {
    include commonConsts::path_templates . '/form.php';
}


?>