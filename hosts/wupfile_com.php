<?php

class dl_wupfile_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://wipfiles.net/?op=my_account", $cookie, "");
        if (stristr($data, "Account Balance") && stristr($data, "Premium expire")) {
            return array(true, "Until " . $this->lib->cut_str($data, "Premium expire&nbsp;&nbsp;", " </span>"));
        } else if (stristr($data, "Account Balance")) {
            return array(false, "accfree");
        }
        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://wupfile.com/login.html", "lang=english", "");
        $cook = $this->lib->GetCookies($data);
        $post = $this->parseForm($this->lib->cut_str($data, '<form id="Login"', '</form>'));
        $post['login'] = $user;
        $post['password'] = $pass;
        $post['redirect'] = "https://wupfile.com/";
        $data = $this->lib->curl("https://wupfile.com/", $cook, $post);
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
            } elseif (preg_match('@https?:\/\/(\w+\.)?wupfile\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $match)) {
                return trim($match[0]);
            }
        }
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, 'File Not Found')) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, 'You have reached the download-limit')) {
            $this->error("LimitAcc", true, false, 2);
        } elseif ($this->isRedirect($data)) {
            return trim($this->redirect);
        } else {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('@https?:\/\/(\w+\.)?wupfile\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $match)) {
                return trim($match[0]);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Wupfile.com Download Plugin
 * Date: 26.05.2020
 */
