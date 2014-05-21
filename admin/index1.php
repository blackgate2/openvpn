<?
require( 'start_page.php');
Expire();
require( 'vars_runtime.php');

/* --------------------------  pathes for including other innterfaces  ---------------------------- */
$path_vars = ($_SESSION['auth_tip_user'] == 'admin') ? '' : './' . $_SESSION['auth_tip_user'] . '/';
if (file_exists($path_vars . 'vars.php'))
    require_once($path_vars . 'vars.php');
if (file_exists($path_vars . 'vars_filters.php'))
    require_once( $path_vars . 'vars_filters.php' );
if (file_exists($path_vars . 'vars_show.php'))
    require_once( $path_vars . 'vars_show.php' );
if (file_exists($path_vars . 'vars_edit.php'))
    require_once( $path_vars . 'vars_edit.php' );
if (file_exists($path_vars . 'vars_add.php'))
    require_once( $path_vars . 'vars_add.php' );
/* -------------------------------------------------------------------------------------------------- */


if ($m) {
    include "$m.php";
}



?>