<?php

class dl_worldbytez_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://worldbytez.com/?op=my_account", $cookie, "");
        if (stristr($data, 'Premium account expire')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<span class="label label-success">', '</span>'));
        } else if (stristr($data, '<div class="UserHead">')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://worldbytez.com/login.html", "", "", 0);
        $cook = $this->lib->GetCookies($data);
        $post = $this->parseForm($this->lib->cut_str($data, 'name="FL">', '</form>'));
        $post['login'] = $user;
        $post['password'] = $pass;
        $data = $this->lib->curl("https://worldbytez.com/", $cook, $post);
        $cookie = $this->lib->GetCookies($data);
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($this->isRedirect($data) && stristr($this->redirect, "/download")) {
            $cook = $this->lib->GetCookies($data);
            $data = $this->lib->curl($this->redirect, "{$cook}{$this->lib->cookie}", "");
        }

        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } elseif (preg_match('/<a href="(.*?)" class="bbc_url"/', $data, $match)) {
                return trim($match[1]);
            }
        }

        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, '<h2>Oops File Not Found</h2>') || stristr($data, '<b>File Not Found</b>')) {
            $this->error("dead", true, false, 2);
        } elseif ($this->isRedirect($data)) {
            return $this->redirect;
        } else {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('/<a href="(.*?)" class="bbc_url"/', $data, $match)) {
                return trim($match[1]);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Worldbytez_com Download Plugin
 * Date: 30.05.2020
 */
