<?php

class dl_k2s_cc extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://k2s.cc/site/profile.html", $cookie, "");
        if (stristr($data, 'Premium expires') && stristr($data, '<strong>Premium</strong>')) {
            return array(true, "Until " . strip_tags($this->lib->cut_str($data, 'Premium expires', '<em>')) . "<br/>Bandwidth Left: " . strip_tags($this->lib->cut_str($data, 'Traffic left today', '</strong>')));
        } else if (stristr($data, '<strong>Free</strong>')) {
            return array(true, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://k2s.cc/login.html", "", "LoginForm[username]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=1&yt0=login");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, "This file is no longer available") && stristr($data, ">Error 404<")) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, "Traffic limit exceed")) {
            $this->error("LimitAcc", true, false);
        } elseif (!$this->isredirect($data)) {
            if (preg_match('/<a href="(.*?)" class="btn-download/', $data, $match)) {
                $id = trim($match[1]);
            }

            $match = $this->lib->curl("https://k2s.cc" . $id, $this->lib->cookie, "");
            if ($this->isredirect($match)) {
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
 * Version: 3.3 LTSB
 * K2s.cc Download Plugin
 * Date: 26.11.2017
 */
