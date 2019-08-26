<?php

class dl_katfile_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://katfile.com/?op=my_account", "lang=english;{$cookie}", "");
        if (stristr($data, 'Premium account expire')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<TD>Premium account expire</TD><TD><b>', '</b>'));
        } else if (stristr($data, 'My affiliate link') && !stristr($data, 'Premium account expire')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://katfile.com/", "lang=english", "op=login&login={$user}&password={$pass}&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form', '</form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } elseif (preg_match('@https?:\/\/www\d+\.katfile.com\/d\/[^\'\"\s\t<>\r\n]+@i', $data, $link)) {
                return trim(str_replace('https', 'http', $link[0]));
            }

        }
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, '<Title>File Not Found</Title>')) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, 'Your IP is blacklisted')) {
            $this->error("blockIP", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            $this->error("Please enable direct download in katfile account", true, false, 2);
        } else {
            return trim(str_replace('https', 'http', trim($this->redirect)));
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Katfile.com Download Plugin
 * Date: 31.01.2018
 */
