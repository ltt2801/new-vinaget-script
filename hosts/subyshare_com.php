<?php

class dl_subyshare_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://subyshare.com/account/profile", $cookie, "");
        if (stristr($data, 'PREMIUM User') && stristr($data, 'Expiration date')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, 'Expiration date</label>', '</div>'), 'class="form-control-static">', '</p>') . "<br>Traffic: " . $this->lib->cut_str($this->lib->cut_str($data, 'Traffic</label>', '</div>'), 'class="form-control-static">', '</p>'));
        } else if (stristr($data, 'REGISTERED User')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://subyshare.com/", "lang=english", "op=login&login={$user}&password={$pass}");
        if (stristr($data, 'Your IP is banned')) {
            die('Your IP is banned, cannot login');
        }

        $cookie = "lang=english; {$this->lib->GetCookies($data)}";
        return $cookie;
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } elseif ($this->isredirect($data)) {
                return trim($this->redirect);
            }

        }
        if (stristr($data, 'File Not Found</title>') || stristr($data, '<h3>The file you\'re looking for is not here</h3>')) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, '<small>Password:</small> <input class="bttn')) {
            $this->error("reportpass", true, false);
        } elseif (!$this->isredirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if ($this->isredirect($data)) {
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
 * Subyshare.com Download Plugin
 * Date: 15.11.2017
 */
