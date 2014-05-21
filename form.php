<?php //

//require('/var/opt/vitinvest/htdocs/common.php');
//require(MY_ROOT_PDO . '/class.db.php');
//
//require( 'vars_runtime.php');
//require( 'strDate.class.php');
//require( 'class.common.php');
//require( 'vars_db.class.php');
//require( 'vars.php');
//require( 'vars_dialogs.php');
//
//require( 'forms.class.php');
//require( 'msg_array.php');
//
//
//
///** ------------------------------------ начиняем нужный объект данными  из базы  ---------------------------------------- */
//if ($id && $action == 'edit') {
//    require( 'DBObject.class.php');
//    require( 'vars_show.php');
//    $o = new DBObject();
//    $o->obj = $tables[$table];
//    $tables[$table] = $o->get_data_obj(
//            $tables[$table . '_show']['fields_sql'], 
//            $tables[$table . '_show']['table_view'], 
//            $tables[$table . '_show']['id'] . '=' . $id);
//   // print_r($tables[$table] );
//}
//
//
//
///* если указа модуль перед формированием формы подключаем его */
//if ($tables[$table . '_df']['dialog_inc_modul']) {
//    include ($tables[$table . '_df']['dialog_inc_modul']);
//}
////echo $table;
////print_r($tables[$tables[$table . '_df']['table']]);
//
//$forms = new forms($msg);
//
//$forms->__set('fields', $tables[$tables[$table . '_df']['table']]);
//$forms->__set('nameform', 'form_' . $tables[$table . '_df']['table']);
//$forms->set_dialog_params(
//        array(
//            'dialog_name' => $tables[$table . '_df']['dialog_name'],
//            'dialog_is_modal' => $tables[$table . '_df']['dialog_is_modal'],
//            'dialog_title' => $tables[$table . '_df']['cap'],
//            'dialog_width' => $tables[$table . '_df']['dialog_width'],
//            'dialog_height' => $tables[$table . '_df']['dialog_height']
//));
//$forms->__set('url', $tables[$table . '_df']['url']);
//$forms->__set('count_view_col', $tables[$table . '_df']['count_view_col']);
//
//if ($tables[$table . '_df']['ajax_tag_respons']) {
//    $forms->set_ajax_sent_form($tables[$table . '_df']['ajax_tag_respons'], $tables[$table . '_df']['is_ajax_alert']);
//}
//if ($tables[$table . '_df']['autosubmit']) {
//    $forms->__set('autosubmit', $tables[$table . '_show']['autosubmit']);
//}
//$forms->__set('hiddens', array(
//    'action' => $action,
//    'table' => $tables[$table . '_df']['table'],
//    'id' => $id));
//
//$forms->__set('edit_id', $id);
//$forms->print_form_site();
//$content['page'].= $forms->str;
//if ($tables[$table . '_df']['dialog_template_form']) {
//    include ($tables[$table . '_df']['dialog_template_form']);
//} else {
//    include MY_ROOT_TEMPLATES . '/form.php';
//}
?>