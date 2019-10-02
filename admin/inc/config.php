<?php
// $sessdir = dirname(dirname(__FILE__)).'/session_dir';
// ini_set('session.save_path', $sessdir);
ob_start();
session_start();

error_reporting(E_ALL);
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'littlepr_donate');
define('DB_SERVER_PASSWORD', '!)DnW1^QvKNt');
define('DB_DATABASE', 'littlepr_newlittlepray');
//=======================================================
require_once 'function/functions.php';
require_once 'function/database_table.php';
require_once 'function/connection.php';
//=====================================================
//define image directory
define('UPLOAD_FILES','file/');
//=====================================================
//define('WEB_ROOT', $webRoot);
define('WEB_ROOT','http://littlepray.org/newlittlepray/admin/');
//define('SRV_ROOT', $srvRoot);



//==============================================
$conn = tep_db_connect() or die('Unable to connect to database server!');


?>

