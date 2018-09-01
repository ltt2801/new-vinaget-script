<?php

class dl_sendspace_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://www.sendspace.com/mysendspace/myindex.html", "{$cookie}", "");
        if (stristr($data, 'account needs to be renewed')) {
            return array(true, "Until " . $this->lib->cut_str($data, 'account needs to be renewed in', '</li>') . "<br/> Traffic available: " . $this->lib->cut_str($data, '<li>You have ', 'available bandwidth</li>'));
        } else if (stristr($data, 'http://www.sendspace.com/login.html?logout=1') && !stristr($data, 'account needs to be renewed')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://www.sendspace.com/login.html", "", "remember=on&action=login&submit=login&username={$user}&password={$pass}");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post['filepassword'] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            return trim($this->lib->cut_str($data, 'id="download_button" href="', '" onclick='));
        }
        if (stristr($data, "Sorry, the file you requested is not available.")) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, "This file has been password protected")) {
            $this->error("reportpass", true, false);
        } elseif (!$this->isredirect($data)) {
            return trim($this->lib->cut_str($data, 'id="download_button" href="', '" onclick='));
        } else {
            return trim($this->redirect);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Sendspace.com Download Plugin
 * Date: 01.09.2018
 */
