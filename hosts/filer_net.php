<?php

class dl_filer_net extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl('https://filer.net/profile', $cookie, '');
        if (strstr($data, 'Account status') && stristr($data, '<th>Traffic</th>')) {
            return array(true, 'Until ' . $this->lib->cut_str($data, 'valid until ', '.') . '<br>Traffic left: ' . strip_tags($this->lib->cut_str($data, '<th>Traffic</th>', '</tr>')));
        } elseif (strstr($data, 'Account status')) {
            return array(true, 'accfree');
        }
        return array(false, 'accinvalid');
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://filer.net/locale/en", "", "");
        $cook = $this->lib->GetCookies($data);
        $data = $this->lib->curl("https://filer.net/login", $cook, "");
        $post = $this->parseForm($this->lib->cut_str($data, '<form action="/login_check"', '</form>'));
        $post['_username'] = $user;
        $post['_password'] = $pass;
        $data = $this->lib->curl("https://filer.net/login_check", $cook, $post);
        $cookie = $this->lib->GetCookies($data);
        return array(true, $cookie);
    }

    public function Leech($link)
    {
        $data = $this->lib->curl($link, $this->lib->cookie, "");
        if (stristr($data, 'Datei nicht mehr vorhanden')) {
            $this->error("dead", true, false, 2);
        } elseif ($this->isRedirect($data)) {
            return trim($this->redirect);
        } elseif (preg_match('/href="(.*?)">Get download<\/a>/', $data, $match)) {
            $data = $this->lib->curl("https://filer.net" . trim($match[1]), $this->lib->cookie, "");
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
 * Filer.net Download Plugin
 * Date: 26.05.2020
 */
