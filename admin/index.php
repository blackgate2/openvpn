<?php
require( 'start_page.php');
Expire();
/**  константы  */

require(commonConsts::path_cammon. '/class.common.php');
require(commonConsts::path_cammon. '/strDate.class.php');
require( 'vars_runtime.php');
/* --------------------------  pathes for including other innterfaces  ---------------------------- */
$path_vars = ($_SESSION['auth_tip_user'] == 'admin') ? '' : './' . $_SESSION['auth_tip_user'] . '/';
if (file_exists($path_vars . 'vars.php'))
    require($path_vars . 'vars.php');
if (file_exists($path_vars . 'vars_filters.php'))
    require( $path_vars . 'vars_filters.php' );
if (file_exists($path_vars . 'vars_show.php'))
    require( $path_vars . 'vars_show.php' );
if (file_exists($path_vars . 'vars_edit.php'))
    require( $path_vars . 'vars_edit.php' );
if (file_exists($path_vars . 'vars_add.php'))
    require( $path_vars . 'vars_add.php' );

/* -------------------------------------------------------------------------------------------------- */
require( 'vars_runtime.php');



echo displayHeaderAdmin($msg);

if ($m) {
    require "$m.php";
} else {
    require 'begin_' . $lang . '.html';
}

echo displayFooterAdmin();


?>