<?php

class dl_filefox_cc extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://filefox.cc/profile", $cookie, "");
        if (stristr($data, 'Premium account expires')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, 'Premium account expires', '</div>'), '<a href="/premium">', '</a>'));
        } else if (stristr($data, 'Username / Email') && !stristr($data, 'Premium account expires')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://filefox.cc/login", "", "email={$user}&password={$pass}&op=login");
        $cookie = $this->lib->GetCookies($data);
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (!stristr($data, 'file-name') && !stristr($data, 'Download File')) {
            $this->error("dead", true, false, 2);
        } else {
            $post = $this->parseForm($this->lib->cut_str($data, 'name="F1">', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('/<a class="btn btn-default" href="(.*?)">/', $data, $match)) {
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
 * Filefox.cc Download Plugin
 * Date: 30.05.2020
 */
