<?php

class dl_file_al extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://file.al/?op=my_account", "lang=english;{$cookie}", "");
        if (stristr($data, '<TD>Premium account expire</TD>')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<TD>Premium account expire</TD><TD><b>', '</b>'));
        } else if (stristr($data, '<TD>My affiliate link</TD>') && !stristr($data, '<TD>Premium account expire</TD>')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://file.al/login.html", "lang=english", "op=login&login={$user}&password={$pass}&redirect=https://file.al/login.html");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } else if ($this->isredirect($data)) {
                return trim($this->redirect);
            }

        }
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, '<b>File Not Found</b>') || stristr($data, '<Title>File Not Found</Title>')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isredirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if ($this->isredirect($data)) {
                return trim($this->redirect);
            }

            if (preg_match('/<a href="(.*?)">Click here to download<\/a>/i', $data, $match)) {
                return $match[1];
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
 * File.al Download Plugin
 * Date: 01.09.2018
 */
