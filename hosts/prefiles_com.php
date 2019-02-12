<?php

class dl_prefiles_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://prefiles.com/", "", "");
        $cok = $this->lib->GetCookies($data);
        $data = $this->lib->curl("https://prefiles.com/my-account", "{$cok}{$cookie}", "");
        if (stristr($data, 'PRO Membership</dt>')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<dd class="small">', '</dd>') . "<br>Storage: " . strip_tags($this->lib->cut_str($data, '<td>Storage</td>', '</td>')) . "<br>Traffic: " . strip_tags($this->lib->cut_str($data, '<td>Traffic Remaining</td>', '</td>')));
        } elseif (stristr($data, '<dt>FREE Account</dt>') && !stristr($data, 'Username')) {
            return array(false, "accfree");
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://prefiles.com/", "", "");
        $cok = $this->lib->GetCookies($data);
        $data = $this->lib->curl("https://prefiles.com/login", $cok, "op=login&token=&rand=&redirect=&login={$user}&password={$pass}");
        $cookie = $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form class="margin-clear"', '</form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            }

            if (preg_match('/href="(.*?)">Click here/i', $data, $match)) {
                return trim($match[1]);
            }

        }
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, '<li class="active">Page 404</li>') || stristr($data, '<li class="active">File not Found!</li>')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isredirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form class="margin-clear"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('/href="(.*?)">Click here/i', $data, $match)) {
                return trim($match[1]);
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
 * Prefiles.com Download Plugin
 * Date: 07.10.2018
 */
