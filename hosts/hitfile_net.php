<?php

class dl_hitfile_net extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://hitfile.net/", $cookie, "");
        if (stristr($data, '<span class="premium-date">') && stristr($data, "<b>free</b>")) {
            return array(true, "accfree");
        } elseif (stristr($data, '<span class="premium-date">') && stristr($data, "<b>premium</b>")) {
            return array(true, "Until " . $this->lib->cut_str($data, "href='/premium'>", "</a>"));
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://hitfile.net/login", "user_lang=en", "");
        $cook = $this->lib->GetCookies($data);
        $data = $this->lib->curl("https://hitfile.net/user/login", $cook, "user%5Blogin%5D=" . urlencode($user) . "&user%5Bpass%5D=" . urlencode($pass) . "&user%5Bsubmit%5D=Log+in&user%5Bmemory%5D=on");
        $cookie = "user_lang=en;" . $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    public function Leech($link)
    {
        $data = $this->lib->curl($link, "user_lang=en;{$this->lib->cookie}", "");
        if (stristr($data, 'File was deleted or not found')) {
            $this->error("dead", true, false, 2);
        } elseif (preg_match("/<h1><a href='(.*?)'><b>Download/", $data, $match)) {
            return trim($match[1]);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Hitfile.net Download Plugin
 * Date: 11.11.2020
 */
