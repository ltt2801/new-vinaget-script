<?php

class dl_ryushare_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://ryushare.com/?op=my_account", "lang=english;{$cookie}", "");
        if (stristr($data, 'Premium account expire:')) {
            return array(true, "Until " . $this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>', '</b></TD>'));
        } else if (stristr($data, '<a href="http://ryushare.com/premium.python">Upgrade to premium</a>')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://ryushare.com/", "lang=english", "op=login&login={$user}&password={$pass}&loginFormSubmit=Login&redirect=http://ryushare.com/");
        $cookie = "lang=english;" . $this->lib->GetCookies($data);
        
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } elseif (stristr($data, '>Error happened when generating Download Link.<')) {
                $this->error("Error happened when generating Download Link", true, false);
            } elseif (preg_match('@http:\/\/(\w+\.)?ryushare\.com(:\d+)?\/files\/dl\/[^"\'<>\r\n\t]+@i', $data, $giay)) {
                return trim($giay[0]);
            }

        }
        if (stristr($data, '<input type="password" name="password" class="myForm">')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, '>Error happened when generating Download Link.<')) {
            $this->error("Error happened when generating Download Link", true, false);
        } elseif (stristr($data, '403 Forbidden')) {
            $this->error("blockIP", true, false);
        } elseif (stristr($data, 'You have reached the download-limit')) {
            $this->error("LimitAcc", true, false);
        } elseif (stristr($data, 'This server is in maintenance mode.')) {
            $this->error("Ryushare Under Maintenance", true, false);
        } elseif (stristr($data, '>File Not Found<') || stristr($data, '>404 - Not Found<')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isredirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, '>Error happened when generating Download Link.<')) {
                $this->error("Error happened when generating Download Link", true, false);
            } elseif (preg_match('@https?:\/\/(\w+\.)?ryushare\.com(:\d+)?\/files\/dl\/[^"\'<>\r\n\t]+@i', $data, $giay)) {
                return trim($giay[0]);
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
 * Ryushare.com Download Plugin
 * Date: 01.09.2018
 */
