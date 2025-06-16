<?php

class dl_1fichier_com extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://1fichier.com/console/abo.pl", $cookie, "");
        if (stristr($data, "subscription is valid until")) {
            return array(true, "Until " . $this->lib->cut_str($data, '<span style="font-weight:bold">', '</span>'));
        } elseif (stristr($data, ">Identification")) {
            return array(false, "accinvalid");
        }

        return array(false, "accfree");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://1fichier.com/login.pl", "", "mail={$user}&pass={$pass}&lt=on&Login=Login");
        $cookie = $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, 'Premium status must not be used on professional services')) {
            $this->error("blockIP", true, false);
        } elseif (stristr($data, "The requested file could not be found")) {
            $this->error("dead", true, false, 2);
        } elseif ($this->isRedirect($data)) {
            return trim($this->redirect);
        } elseif (preg_match('/<form accept-charset="UTF-8"[^>]*action="([^"]+)"/i', $data, $matches)) {
            $urlDownload = trim($matches[1]);
            $data = $this->lib->curl($urlDownload, $this->lib->cookie, "did=0");
            if ($this->isRedirect($data)) {
                return trim($this->redirect);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * 1fichier.com Download Plugin
 * Date: 06.05.2025
 */
