<?php

/**
 * обработака необходимых $_REQUEST и $_SESSION
 * 
 */
/* --------------------------  language -------------------------- */
if (isset($_REQUEST['table']) && $_REQUEST['table'])
    $table = trim($_REQUEST['table']);
if ($_GET['lang']) {
    $_SESSION['lang'] = $_GET['lang'];
}
if ($_SESSION['lang']) {
    $lang = $_SESSION['lang'];
}
if ($_SESSION['lang'] == 'ru') {
    $lang = '';
}


if ($lang == 'en' &&
        !in_array($table, array('users', 'countries', 'periods', 'protocols', 'servers', 'prices',
            'types', 'orders', 'actions', 'accounts', 'commads', 'dns',
            'config_rules', 'log_mess', 'log_lounch_script', 'user_messages', 'after_invent'))) {
    $pref = $lang . '_';
    $table_lang = $pref . $table;
} else {
    $table_lang = $table;
    $pref = '';
}

/* --------------------------  -------------------------- */
$ids = array();
if (isset($_REQUEST['id']) && $_REQUEST['id']) {
    $id = trim($_REQUEST['id']);
    $ids[] = $id;
}
if (isset($_REQUEST['objects_ids']) && trim(trim($_REQUEST['objects_ids']), ',')) {
    //print_r($_REQUEST['objects_ids']);
    $ids = array_merge($ids, array_unique(explode(',', trim(trim($_REQUEST['objects_ids']), ','))));
    $objects_ids = implode(',', $ids);
    //print_r($ids);
    // exit($objects_ids);
}



if (isset($_REQUEST['m']) && $_REQUEST['m'])
    $m = trim($_REQUEST['m']);
if (isset($_REQUEST['action']) && $_REQUEST['action'])
    $action = trim($_REQUEST['action']);

if (isset($_REQUEST['statusNEW']) && $_REQUEST['statusNEW'])
    $statusNEW = trim($_REQUEST['statusNEW']);

if (isset($_REQUEST['order']) && $_REQUEST['order'])
    $_SESSION[$table]['order'] = $_REQUEST['order'];
if (isset($_REQUEST['order_dir']))
    $_SESSION[$table]['order_dir'] = $_REQUEST['order_dir'];
if (isset($_REQUEST['p']))
    $_SESSION[$table]['p'] = $_REQUEST['p'];
if (isset($_REQUEST['maxRow']))
    $_SESSION[$table]['maxRow'] = $_REQUEST['maxRow'];

$statusNEW = $_REQUEST['statusNEW'];

$_filter = $table . '_filter';

if (isset($tables[$_filter])) {
    for ($i = 0; $i < count($tables[$_filter]); $i++) {

        $name = $tables[$_filter][$i]['name'];
        foreach (array($name, 'min_' . $name, 'max_' . $name) as $name) {
            if (isset($_REQUEST[$name])) {
                $_SESSION[$_filter][$name] = $_REQUEST[$name];

                if (preg_match('/min\_/', $name))
                    $tables[$_filter][$i]['value']['min'] = $_SESSION[$_filter][$name];
                elseif (preg_match('/max\_/', $name))
                    $tables[$_filter][$i]['value']['max'] = $_SESSION[$_filter][$name];
                else
                    $tables[$_filter][$i]['value'] = $_SESSION[$_filter][$name];
            }
        }
    }
}

/* иключения */
if (isset($_POST['user_name_filter']) && trim($_POST['user_name_filter']) == '') {
    $_SESSION[$_filter]['user_id_filter'] = '';
    $_SESSION[$_filter]['user_name_filter'] = '';
} elseif (trim($_POST['user_name_filter'])) {
    $_SESSION[$_filter]['user_name_filter'] = trim($_POST['user_name_filter']);
}

if (isset($_POST['account_name_filter']) && trim($_POST['account_name_filter']) == '') {
    $_SESSION[$_filter]['account_id_filter'] = '';
    $_SESSION[$_filter]['account_name_filter'] = '';
} elseif (trim($_POST['account_name_filter'])) {
    $_SESSION[$_filter]['account_name_filter'] = trim($_POST['account_name_filter']);
}


if (isset($_POST['num_order_name_filter']) && trim($_POST['num_order_name_filter']) == '') {
    $_SESSION[$_filter]['num_order_filter'] = '';
    $_SESSION[$_filter]['num_order_name_filter'] = '';
} elseif (trim($_POST['num_order_name_filter'])) {
    $_SESSION[$_filter]['num_order_name_filter'] = trim($_POST['num_order_name_filter']);
}

