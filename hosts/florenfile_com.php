<?php

class dl_florenfile_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://florenfile.com/?op=my_account", $cookie, "");
        if (stristr($data, 'Premium account expire')) {
            return array(true, "Until " . strip_tags($this->lib->cut_str($data, 'Premium account expire<br>', '<a')) . "<br>Traffic left: " . strip_tags($this->lib->cut_str($data, 'Traffic available<br>', '</div>')));
        } else if (stristr($data, 'My Account Settings') && !stristr($data, 'Premium account expire')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://florenfile.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=https://florenfile.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, '<h2>File Not Found</h2>')) {
            $this->error("dead", true, false, 2);
        } elseif ($this->isRedirect($data)) {
            return trim($this->redirect);
        } else {
            $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if ($this->isRedirect($data)) {
                return trim($this->redirect);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Florenfile.com Download Plugin
 * Date: 30.05.2020
 */
