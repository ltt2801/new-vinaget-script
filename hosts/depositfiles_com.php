<?php

class dl_depositfiles_com extends Download
{
    public function CheckAcc($cookie)
    {
        $domain = $this->lib->cut_str($this->getredirect("https://depositfiles.com/gold/payment_history.php"), "//", "/");
        $data = $this->lib->curl("https://{$domain}/gold/payment_history.php", "lang_current=en;{$cookie}", "");

        if (stristr($data, 'You have Gold access until:')) {
            $checksubscribe = $this->lib->curl("http://{$domain}/gold/payment_subscribe_manage.php", "lang_current=en;{$cookie}", "");
            return array(true, "Until " . $this->lib->cut_str($data, '<div class="access">You have Gold access until: <b>', '</b></div>') . "<br/> " . (strpos($checksubscribe, '>You are subscribed to automatically') ? "You are subscribed" : "You are not subscribed"));
        } elseif (stristr($data, 'Your current status: FREE - member')) {
            return array(false, "accfree");
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://depositfiles.com/login.php?return=%2F", "lang_current=en", "go=1&login=$user&password=$pass");

        return $this->lib->GetCookies($data);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $tachid = explode("/", $url);

        $DFid = $tachid[5];
        if (preg_match("/\/files\/(.*)\/(.+)/i", $url, $id)) {
            $DFid = $id[1];
        } elseif (count($tachid) == 5) {
            $DFid = $tachid[4];
        }

        $data = $this->lib->curl("https://depositfiles.com/api/download/file?file_id={$DFid}&file_password={$pass}", "lang_current=en;" . $this->lib->cookie, "", 0);
        $page = json_decode($data, true);

        if (isset($page['status'])) {
            if ($page['status'] != "OK") {
                if ($page['error'] == "FileIsPasswordProtected") {
                    $this->error("reportpass", true, false);
                }

                if ($page['error'] == "FileDoesNotExist") {
                    $this->error("dead", true, false, 2);
                }

                if ($page['error'] == "FilePasswordIsIncorrect") {
                    $this->error("wrongpass", true, false, 2);
                }

                return $this->error($page['error'], true, false);
            } elseif (isset($page['data']['download_url'])) {
                return str_replace("https://", "http://", $page['data']['download_url']);
            }

        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Depositfiles.com Download Plugin
 * Date: 21.05.2017
 */
