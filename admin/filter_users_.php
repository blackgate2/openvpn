<?

$tables['users_show']['where'].=($_SESSION[$_filter]['fio_filter']) ? ' and  (users.fio LIKE "%' . $_SESSION[$_filter]['fio_filter'] . '%")' : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['status_filter'] == '1' ) ? ' and users.status = "1" ' : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['status_filter'] == '2') ? ' and users.status = ""' : '';
$tables['users_show']['where'].=($_SESSION[$_filter]['tip_user_filter']) ? ' and users.tip_user = "' . $_SESSION[$_filter]['tip_user_filter'] . '"' : '';


//$show = new show_fromdb();
$forms = new forms();
$forms->fields = $tables['users_filter'];
$forms->nameform = 'form_filter';
$forms->hiddens = array('action' => 'show', 'post_form' => '1', 'm' => $m, 'table' => $tables['users_show']['table'], 'order' => $tables['users_show']['order'], 'order_dir' => $tables['users_show']['order_dir'], 'p' => $p, 'maxRow' => $maxRow);
$forms->is_form_In_dialog = 0;
$forms->print_form_site();

echo $forms->str;
?>