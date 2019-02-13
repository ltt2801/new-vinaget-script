<?php

class dl_filespace_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://www.filespace.com/?op=my_account", $cookie, "");
        if (stristr($data, 'Premium account expire')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<TD><b>', '</b>'));
        } else if (stristr($data, 'Account status') && !stristr($data, 'Premium account expire')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://filespace.com/?op=login&login={$user}&password={$pass}&redirect=", "lang=english;", "");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, '>File not found.<')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isredirect($data)) {
            $page = $this->lib->cut_str($data, '<div style="text-align:center;width:728px;">', '<div id="planlinks" width="100%"');
            preg_match('%input type="hidden" name="rand2" value="(.*?)%U', $page, $rand2);
            $post = $this->parseForm($page, '<Form name="F1"', '<div id="captcha">');
            $post['rand2'] = $rand2[1];
            if ($pass) {
                $post['password'] = $pass;
            } elseif (stristr($page, "bold;\">Password:<")) {
                $this->error("reportpass", true, false);
            }

            $data = $this->lib->curl($url . '?accounttype=premium', $this->lib->cookie, $post);
            if ($this->isredirect($data)) {
                return trim($this->redirect);
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
 * Filespace.com Download Plugin
 * Date: 01.09.2018
 */
