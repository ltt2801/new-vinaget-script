<?php

class dl_1fichier_com extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://1fichier.com/en/console/abo.pl", $cookie, "");
        if (stristr($data, "offer subscription is valid")) {
            return array(true, "Until " . $this->lib->cut_str($data, '<span style="font-weight:bold">', '</span>'));
        } elseif (stristr($data, ">Identification")) {
            return array(false, "accinvalid");
        }

        return array(false, "accfree");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://1fichier.com/en/login.pl", "", "mail={$user}&pass={$pass}&lt=on&Login=Login");
        $cookie = $this->lib->GetCookies($data);

        return $cookie;
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, "The requested file could not be found")) {
            $this->error("dead", true, false, 2);
        } elseif ($this->isredirect($data)) {
            return trim($this->redirect);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * 1fichier.com Download Plugin
 * Date: 10.09.2018
 */
