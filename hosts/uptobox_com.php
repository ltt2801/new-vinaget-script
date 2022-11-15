<?php

class dl_uptobox_com extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://uptobox.com/?op=my_account", "lang=english;{$cookie}", "");
        if (stristr($data, '<div id="myaccount">')) {
            if (stristr($data, 'Premium member')) {
                return array(true, "Until " . preg_replace('/\s+/', ' ', $this->lib->cut_str($data, "data-tippy-content=\"Expires on", "at")));
            }
            return array(false, "accfree");
        }
        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://uptobox.com/?op=login&referer=homepage", "lang=english", "login={$user}&password={$pass}&redirect=");
        if (stristr($data, 'log in from a different country')) {
            $this->error("Account uptobox block login from another country", false, false);
            return false;
        }
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
            } elseif (preg_match('@https?:\/\/www\d+\.uptobox.com\/d\/[^\'\"\s\t<>\r\n]+@i', $data, $link)) {
                return trim(str_replace('https', 'http', $link[0]));
            }

        }

        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, 'The file was deleted by its owner') || stristr($data, 'Page not found / La page')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('@https?:\/\/www\d+\.uptobox.com\/d\/[^\'\"\s\t<>\r\n]+@i', $data, $link)) {
                return trim(str_replace('https', 'http', $link[0]));
            }

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
 * Uptobox.com Download Plugin
 * Date: 15.11.2022
 */
