<?php
error_reporting(E_ALL);
define('vinaget', 'yes');
include "class.php";
function check_account($host, $account)
{
    global $obj;
    if (empty($obj->acc[$host]['accounts'])) {
        return false;
    }

    foreach ($obj->acc[$host]['accounts'] as $value) {
        if ($account == $value) {
            return true;
        }
    }

    return false;
}

if (!empty($_POST["accounts"])) {
    $obj = new stream_get();
    $type = $_POST['type'];
    $_POST["accounts"] = str_replace(" ", "", $_POST["accounts"]);
    $account = trim($_POST['accounts']);
    $donate = false;
    if (check_account($type, $account)) {
        die("false duplicate");
    }

    require_once 'hosts/' . $obj->list_host[$type]['file'];
    $download = new $obj->list_host[$type]['class']($obj, $type);
    if ($download->lib->acc[$download->site]['proxy'] != "") {
        $download->lib->proxy = $download->lib->acc[$download->site]['proxy'];
    }

    if (method_exists($download, "CheckAcc")) {
        if (strpos($account, ":")) {
            list($user, $pass) = explode(':', $account);
            $cook = $download->Login($user, $pass);
            $cookie = $cook[1];
        } else {
            $cookie = $account;
        }

        $status = $download->CheckAcc($cookie);
        if ($status[0]) {
            echo "true";
            $update = true;
            if (empty($obj->acc[$type])) {
                $obj->acc[$type]['max_size'] = $obj->max_size_default;
                $obj->acc[$type]['proxy'] = "";
                $obj->acc[$type]['direct'] = false;
            }
            $obj->acc[$type]['accounts'][] = $account;
            $download->save($cookie);
        } else {
            echo "false {$status[1]}";
            $update = false;
        }
    } else {
        echo "false plugin fail";
        $update = false;
    }
################################## save account  #############################################################################
    if ($update == true && is_array($obj->acc) && count($obj->acc) > 0) {
        $obj->save_json($obj->fileaccount, $obj->acc);
    }
################################## save account  #############################################################################

}