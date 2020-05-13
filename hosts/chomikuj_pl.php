<?php

class dl_chomikuj_pl extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://chomikuj.pl/", $cookie, "");
        if (stristr($data, '<div style="margin-top:2px;margin-bottom:5px;">')) {
            return array(true, "Traffic left: " . $this->lib->cut_str($data, 'title="Transfer" rel="nofollow"><strong>', '</strong>'));
        } else if (stristr($data, 'id="topbarTransfer"')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://chomikuj.pl/", "", "");
        $cook = $this->lib->GetCookies($data);
        $post = $this->parseForm($this->lib->cut_str($data, '<form action=""', '</form>'));
        $post['Login'] = $user;
        $post['Password'] = $pass;
        $data = $this->lib->curl("http://chomikuj.pl/action/Login/TopBarLogin", $cook, $post);
        $cookie = $this->lib->GetCookies($data) . $cook;
        $cookie = $this->lib->TrimCookies($cookie, ["ChomikSession", "RememberMe"]);
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, '<h2 class="marked">Nie znaleziono</h2>')) {
            $this->error("dead", true, false, 2);
        } else {
            $cookie = $this->lib->GetCookies($data);
            $post = $this->parseForm($this->lib->cut_str($data, '<div id="content">', '</form>'));
            $data = $this->lib->curl("http://chomikuj.pl/action/License/DownloadContext", $this->lib->cookie . $cookie, $post, 0);
            $json = @json_decode($data, true);
            if (isset($json['Content'])) {
                $post2 = $this->parseForm($json['Content']);
                $post2 = array_merge($post2, $post);
                $data = $this->lib->curl("http://chomikuj.pl/action/License/DownloadWarningAccept", $this->lib->cookie . $cookie, $post2, 0);
                $json = @json_decode($data, true);
                if (isset($json['redirectUrl'])) {
                    return trim($json['redirectUrl']);
                }
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Chomikuj.pl Download Plugin
 * Date: 14.05.2020
 */
