<?php
ob_start();
ob_implicit_flush(TRUE);
ignore_user_abort(0);
if (!ini_get('safe_mode')) set_time_limit(30);
define('vinaget', 'yes');
date_default_timezone_set('Asia/Jakarta');

require_once('class.php');
$obj = new stream_get();

$page = !empty($_GET['page']) ? $_GET['page'] : '';

if ($obj->Deny || !$obj->isadmin() || empty($page)) {
	setcookie('msg', 'proccess.php :: Access Violation');
	header('Location: index.php');
	ob_end_flush();
	exit;
}

$msg = '';
switch ($page) {
	case 'config':
		if (!empty($_POST['config']) && is_array($_POST['config'])) {
			foreach ($_POST['config'] as $ckey => $cval) {
				if ($cval == 'on' || $cval == 'off') $cval = $cval == 'on' ? true : false;
				elseif (is_numeric($cval)) $cval = intval($cval);
				else $cval = $cval;
				$obj->config[$ckey] = $cval;
			}
			$obj->save_json($obj->fileconfig, $obj->config);
			$msg = 'Config Saved!';
		}
		break;

	case 'cookie':
		if (!empty($_POST['type']) && !empty($_POST['cookie'])) {
			$obj->save_cookies($_POST['type'], $_POST['cookie']);
			$msg = $_POST['type'] . 'Cookie Added!';
		} elseif (!empty($_GET['del'])) {
			$obj->save_cookies($_GET['del'], '');
			$msg = $_GET['del'] . 'Cookie Deleted!';
		}
		break;

	case 'account':
		if (!empty($_POST['type']) && !empty($_POST['account'])) {
			if (empty($obj->acc[$_POST['type']])) {
				$obj->acc[$_POST['type']]['max_size'] = $obj->max_size_default;
				$obj->acc[$_POST['type']]['proxy'] = '';
				$obj->acc[$_POST['type']]['direct'] = false;
			}
			$account = explode("\n", $_POST['account']);
			$maxacc = count($account); 
			if (($maxacc == 2 && $account[1] == '') || $maxacc == 1) {
				$_POST['account'] = $account[0];
				$obj->save_account($_POST['type'], $_POST['account']);
			}
			else {
				for ($i=0; $i < $maxacc; $i++) {
					$_POST['account'] = $account[$i];
					$obj->save_account($_POST['type'], $_POST['account']);
				}
			}
			$msg =  count($account). ' Account '.$_POST['type'].' Added!';
		} elseif (isset($_GET['del']) && !empty($_GET['host'])) {
			$acc = $obj->acc[$_GET['host']]['accounts'];
			unset($obj->acc[$_GET['host']]['accounts']);
			foreach ($acc as $key => $val) {
				if ($key == $_GET['del']) continue;
				$obj->acc[$_GET['host']]['accounts'][] = $val;
			}
			$obj->save_json($obj->fileaccount, $obj->acc);
			$msg = $_GET['host'] . 'Account Deleted!';
		}
		break;

	case 'host':
		if (!empty($_POST['host'])) {
			foreach ($_POST['host'] as $key => $val) {
				$obj->acc[$key]['max_size'] = $val['max_size'];
				$obj->acc[$key]['proxy'] = !empty($val['proxy']) ? $val['proxy'] : '';
				$obj->acc[$key]['direct'] = (isset($val['direct']) && $val['direct'] == 'ON' ? true : false);
			}
			ksort($obj->acc);
			$obj->save_json($obj->fileaccount, $obj->acc);
			$msg = 'Host Setting Changed!';
		}
		break;

	default:
		setcookie('msg', 'proccess.php :: Unknown Page Type');
		header('Location: index.php');
		ob_end_flush();
		exit;
}

setcookie('msg', empty($msg) ? '' : $msg);
header('Location: index.php?id=admin&page='.urlencode($page));
ob_end_flush();

?>