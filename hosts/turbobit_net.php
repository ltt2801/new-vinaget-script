<?php

class dl_turbobit_net extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://turbobit.net/?site_version=1&from_mirror=1", $cookie, "");
        if (stristr($data, "HTTP/1.1 307 Temporary Redirect") && $this->isRedirect($data)) {
            $data = $this->lib->curl(trim($this->redirect), $cookie, 0);
        }

        if (stristr($data, 'Turbo access till')) {
            if (stristr($data, '> limit of premium downloads')) {
                return array(true, "LimitAcc");
            } else {
                return array(true, "Until " . $this->lib->cut_str($data, '>Turbo access till ', '</span></a>'));
            }

        } else if (stristr($data, '<u>Turbo Access</u> denied.')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://turbobit.net/login", "user_lang=en", "");
        $cook = $this->lib->GetCookies($data);
        $data = $this->lib->curl("https://turbobit.net/user/login", $cook, "user[login]={$user}&user[pass]={$pass}&user[captcha_type]=&user[captcha_subtype]=&user[submit]=Sign+in&user[memory]=on");
        if (stristr($data, "HTTP/1.1 307 Temporary Redirect") && $this->isRedirect($data)) {
            $this->lib->curl(trim($this->redirect), "user_lang=en", "user[login]={$user}&user[pass]={$pass}&user[captcha_type]=&user[captcha_subtype]=&user[submit]=Sign+in&user[memory]=on");
        }

        $cookie = "user_lang=en;" . $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        if (strpos($url, "/download/free/") == true) {
            $gach = explode('/', $url);
            $url = "https://turbobit.net/{$gach[5]}.html";
        }
        $data = $this->lib->curl($url . '?site_version=1&from_mirror=1', $this->lib->cookie, "");

        if (stristr($data, "HTTP/1.1 307 Temporary Redirect") && $this->isRedirect($data)) {
            $data = $this->lib->curl(trim($this->redirect), $this->lib->cookie, "");
        }
        $this->save($this->lib->GetCookies($data));
        if (stristr($data, 'site is temporarily unavailable') || stristr($data, 'This document was not found in System')) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, 'Please wait, searching file') || stristr($data, 'The file is not avaliable now because of technical problems.')) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, 'You have reached the <a href=\'/user/messages\'>daily</a> limit of premium downloads') || stristr($data, 'You have reached the <a href=\'/user/messages\'>monthly</a> limit of premium downloads')) {
            $this->error("LimitAcc");
        } elseif (stristr($data, '<u>Turbo Access</u> denied')) {
            $this->error("blockAcc", true, false);
        } elseif (preg_match("/<a[^>]+href='(https:\/\/turbobit\.net\/download\/redirect\/[^']+)'[^>]*>\s*<b>Download file<\/b>/i", $data, $match)) {
            $link = trim($match[1]);
            $data = $this->lib->curl($link, $this->lib->cookie, "");
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
 * Turbobit.net Download Plugin
 * Date: 11.11.2020
 */
