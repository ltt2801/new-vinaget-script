<?php

class dl_filextras_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://filextras.com/?op=my_account", "lang=english;{$cookie}", "");
        $traffic_available = "";
        if (stristr($data, '<span>Traffic available</span>')) {
            $traffic = $this->lib->cut_str($data, 'traffic position-relative">', '</div>');
            if (preg_match('/<sup>\s*(MB)\s*<\/sup>\s*(\d+)/i', $traffic, $matches)) {
                $traffic_available = '<br/>Traffic available: ' . $matches[2] . ' ' . $matches[1];
            }
        }
        if (stristr($data, 'Premium Pro account expire')) {
            $time = $this->lib->cut_str($data, 'Premium Pro account expire:', '</div>');
            return array(true, "Until " . $this->lib->cut_str($time, '<b>', '</b>')  . $traffic_available);
        } elseif (stristr($data, 'Premium account expire')) {
            $time = $this->lib->cut_str($data, 'Premium account expire:', '</div>');
            return array(true, "Until " . $this->lib->cut_str($data, '<b>', '</b>') . $traffic_available);
        } elseif (stristr($data, 'My affiliate link')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://filextras.com/", "lang=english", "op=login&login={$user}&password={$pass}&redirect=");
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
            } elseif (preg_match('@(?:https?:)?//[a-z0-9\-\.]+\.filextras\.com(?::\d+)?/d/[^\'\"\s<>\r\n]+@i', $data, $link)) {
                return "https:" . trim($link[0]);
            }
        }
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, '<Title>File Not Found</Title>')) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, 'Your IP is blacklisted')) {
            $this->error("blockIP", true, false, 2);
        } elseif (stristr($data, 'reached the download-limit')) {
            $this->error($this->lib->cut_str($data, '<div class="panel-body">', '</div>'), true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('@(?:https?:)?//[a-z0-9\-\.]+\.filextras\.com(?::\d+)?/d/[^\'\"\s<>\r\n]+@i', $data, $link)) {
                return "https:" . trim($link[0]);
            } else {
                $this->error("Please enable direct download in filextras account", true, false, 2);
            }
        } else {
            return $this->redirect;
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Filextras.com Download Plugin
 * Date: 16.06.2025
 */