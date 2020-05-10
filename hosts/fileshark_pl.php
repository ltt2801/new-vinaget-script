<?php

class dl_fileshark_pl extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://fileshark.pl/en/panel", $cookie, "", 0, 0, 0, 0, [':authority:' => "fileshark.pl"]);
        if (stristr($data, '<strong>Premium')) {
            return array(true, "Until " . $this->lib->cut_str($data, '">(till ', ')</span>') . "<br>Traffic used: " . strip_tags($this->lib->cut_str($data, "Downloaded today</p>", "</div>")));
        } else if (stristr($data, '<p class="type-account">')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://fileshark.pl/en/zaloguj", "", "");
        $cook = $this->lib->GetCookies($data);
        $post = $this->parseForm($this->lib->cut_str($data, '<form action="/login_check"', '</form>'));
        $post['_username'] = $user;
        $post['_password'] = $pass;
        $data = $this->lib->curl("https://fileshark.pl/login_check", $cook, $post);
        $cookie = "hl=en; {$this->lib->GetCookies($data)}";
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, '404 Nie znaleziono strony') || stristr($data, 'Nie znaleziono pliku w serwisie.')) {
            $this->error("dead", true, false, 2);
        } elseif ($this->isRedirect($data)) {
            return trim($this->redirect);
        } elseif (preg_match('/<a href="(.*?)" class="btn-upload-premium"/', $data, $match)) {
            $data = $this->lib->curl("https://fileshark.pl" . trim($match[1]), $this->lib->cookie, "");
            if ($this->isRedirect($data)) return trim($this->redirect);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Fileshark.pl Download Plugin
 * Date: 10.05.2020
 */
