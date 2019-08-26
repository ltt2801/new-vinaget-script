<?php

class dl_fastshare_cz extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://fastshare.cz/user", "lang=en;{$cookie}", "");
        preg_match('/<td class="data_cell">(\d+\.\d+) GB &nbsp;&nbsp;&nbsp;/i', $data, $credit);
        if ($credit[1] == 0) {
            return array(false, "accfree");
        } else if ($credit[1] > 0) {
            return array(true, "Credit " . $this->lib->cut_str($this->lib->cut_str($data, '>Credit', '>Direct download'), '<td class="data_cell">', '&nbsp;&nbsp;&nbsp'));
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://fastshare.cz/sql.php", "lang=en", "login={$user}&heslo={$pass}");
        $cookie = "lang=en;{$this->lib->GetCookies($data)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, '>The file  has been deleted at request of its copyright owner.')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            if (preg_match('/href="(http:\/\/data\d+\.fastshare\.cz\/download\.php.+)">/i', $data, $giay)) {
                return trim($giay[1]);
            }

        } else {
            return trim($this->redirect);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Fastshare.cz Download Plugin
 * Date: 01.09.2018
 */
