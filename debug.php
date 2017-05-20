<?php
ob_start();
ob_implicit_flush(TRUE);
ignore_user_abort(0);
if (!ini_get('safe_mode')) set_time_limit(30);
define('vinaget', 'yes');
date_default_timezone_set('Asia/Jakarta');

require_once('class.php');
$obj = new stream_get();

// This debug code should be available only to admins.
if ($obj->Deny || !$obj->isadmin()) {
	setcookie('msg', 'debug.php :: Access Violation');
	header('Location: index.php');
	ob_end_flush();
	exit;
} elseif (!empty($_REQUEST['link'])) {
	if (!empty($_REQUEST['proxy'])) $obj->proxy = $_REQUEST['proxy'];
	echo '<pre>'.htmlspecialchars($obj->curl($_REQUEST['link'], (!empty($_REQUEST['cookie']) ? $_REQUEST['cookie'] : 0), (!empty($_REQUEST['post']) ? $_REQUEST['post'] : 0))) . '</pre>';
} else {
	echo '<center><br /><br /><br /><br /><br /><h2>DEBUG RESULT</h2></center>';
}

ob_end_flush();

?>