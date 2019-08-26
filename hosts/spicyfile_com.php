<?php

class dl_spicyfile_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://spicyfile.com/?op=payments", "lang=english;{$cookie}", "");
        if (stristr($data, '<b>Premium account expire:</b><br>')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<b>Premium account expire:</b><br>', '<br><br>'));
        } else if (stristr($data, 'Upgrade to premium</a>')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://spicyfile.com/login.html", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://spicyfile.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } elseif (preg_match('@https?:\/\/(\w+\.)?spicyfile\.com(:\d+)?\/files\/\d+\/[^"\'><\r\n\t]+@i', $data, $giay)) {
                return trim($giay[0]);
            }

        }
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, 'The file was deleted by its owner')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('@https?:\/\/(\w+\.)?spicyfile\.com(:\d+)?\/files\/\d+\/[^"\'><\r\n\t]+@i', $data, $giay)) {
                return trim($giay[0]);
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
 * Spicyfile.com Download Plugin
 * Date: 01.09.2018
 */
