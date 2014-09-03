<?php

require(commonConsts::path_admin . '/vars_show.php');
require(commonConsts::path_cammon . '/strDate.class.php' );
require(commonConsts::path_cammon . '/forms.class.php' );
require(commonConsts::path_cammon . '/show_from_db.class.php' );
require commonConsts::path_modules . '/robokassa.params.php';
require(commonConsts::path_protect . '/class.user_row.php' );
require(commonConsts::path_admin . '/vars_db.class.php' );
require(commonConsts::path_cammon . '/class.common.php');
require commonConsts::path_modules . '/success.php';
require(commonConsts::path_admin . '/vars_runtime.php');

require(commonConsts::path_protect . '/class.alink.php');


//$a = new alink($p);

if ($is_login) {


    $msg['checkAll']='';
    $show = new show_from_db($msg,$tables['user_orders_show']);
    
    
    $sql = 'Select ' . $show->obj['fields_sql'] .
            ' From ' . $show->obj['table_view'] . '  
        ' . $show->obj['where'] . ' 
         WHERE   u.id=' . $_SESSION['auth_user_id'] . '
        ' . (($show->obj['group']) ? 'Group by ' . $show->obj['group'] : '') . ' 
        ' . (($show->obj['order']) ? 'Order by ' . $show->obj['order'] : '') . ' ' . $show->obj['order_dir'];

    //echo $sql;
    $show->dataAll = $q->fetch_data_to_array($sql);

    foreach ($show->dataAll as $v) {
        $show->dataAll['total']['price']+=0;
    }

    $content['css'] = '
        <link type="text/css" href="/css/table.css" rel="stylesheet" /> 
        <link type="text/css" href="/css/users.css" rel="stylesheet" />';
    $content['content_page'].='
    <div id="dialog_modal" title=""></div>
    <div id="dialog_alert" title=""></div>
    <script type="text/javascript" src="/js/user.js"></script>
    ';
    $content['js']='<script> var lang = "'.$nav->lang.'" </script>';
    $content['name'] = $msg['user_orders'];
    if (!empty($show->dataAll)) {
        if (is_numeric($_GET['InvId'])) {
            $show->setEditID(array($_GET['InvId']));
        }
        $content['content_page'].=($msg_alert)?'<script>
            $("#dialog_alert").dialog({
                title: "!",
                height: 100
            });
            $("#dialog_alert").html("<p>'.$msg_alert.'</p>");
            $("#dialog_alert").dialog(\'open\');
            setTimeout(function() {
            window.location = "/user"
            }, 1500);
            </script>':'';
        $content['content_page'].='<p class="alert" ></p><div class="actions">' . $show->actions_buttons() . '</div>';
        $content['content_page'].=$show->show();
    } else {
        $content['content_page'].=$msg['user_no_orders'];
    }
}
?>