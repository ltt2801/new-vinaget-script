<?php

class dl_hitfile_net extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://app.hitfile.net/api/user/info", $cookie, "", 0);
        $json = json_decode($data, true);

        if (isset($json['premium']['status'])) {
            if ($json['premium']['status'] === 'active' && isset($json['premium']['expiredAt'])) {
                return array(true, "Until " . $json['premium']['expiredAt']);
            } elseif ($json['premium']['status'] === 'inactive') {
                return array(false, "accfree");
            }
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://app.hitfile.net/api/auth/login", "user_lang=en", "email=" . urlencode($user) . "&password=" . urlencode($pass) . "&captcha=true");
        $cookie = "user_lang=en;" . $this->lib->GetCookies($data);
        return array(true, $cookie);
    }

    public function Leech($link)
    {
        if (preg_match('/hitfile\.net\/([a-zA-Z0-9]+)/', $link, $match)) {
            $fileId = $match[1];
            $jsonRequest = json_encode(array(
                "fileId" => $fileId,
                "referrer" => null,
                "site" => null
            ));

            $data = $this->lib->curl("https://app.hitfile.net/api/download/info", $this->lib->cookie, $jsonRequest, 0, 1);
            $json = json_decode($data, true);

            if (isset($json['error_name']) && $json['error_name'] === 'file_is_not_available_for_download') {
                $this->error("dead", true, false, 2);
            } elseif (isset($json['downloadUrls']) && is_array($json['downloadUrls']) && count($json['downloadUrls']) > 0) {
                return trim($json['downloadUrls'][0]);
            } elseif (isset($json['error'])) {
                $this->error("dead", true, false, 2);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Hitfile.net Download Plugin
 * Date: 19.11.2025
 */
