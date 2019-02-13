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
            $cookie = $download->Login($user, $pass);
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
################################## savve account  #############################################################################

}
/*
 * Home page: http://vinaget.us
 * Blog:    http://blog.vinaget.us
 * Script Name: Vinaget
 * Version: 3.2
 * Based on Vinaget 2.7.0
 * Description:
- Vinaget is script generator premium link that allows you to download files instantly and at the best of your Internet speed.
- Vinaget is your personal proxy host protecting your real IP to download files hosted on hosters like RapidShare, megaupload, hotfile...
- You can now download files with full resume support from filehosts using download managers like IDM etc
- Vinaget is a Free Open Source, supported by a growing community.
 * Code LeechViet by VinhNhaTrang
 * Developed by ..:: [H] ::..

 */
