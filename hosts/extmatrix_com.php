<?php

class dl_extmatrix_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://www.extmatrix.com/", $cookie, "");

        if (stristr($data, '<b>Premium Member</b>')) {
            return array(true, "Until " . strip_tags($this->lib->cut_str($data, 'Premium End:</td>', '</td>')));
        } else if (stristr($data, '<td>Free Member</td>')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://www.extmatrix.com/login.php", "", "user={$user}&pass={$pass}&submit=Login&task=dologin&return=http://www.extmatrix.com/members/myfiles.php");
        $cookie = $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $data = $this->passRedirect($this->lib->curl($url, $this->lib->cookie, ""), $this->lib->cookie);
        if (stristr($data, 'An error occurred')) {
            $this->error("An error occurred. Please try again later.", true, false, 2);
        } elseif (stristr($data, 'The file you have requested does not exists.')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            if (preg_match('/<a id=\'jd_support\' href="(.*?)"><\/a>/', $data, $link)) {
                return trim($link[1]);
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
 * Extmatrix.com Download Plugin
 * Date: 22.05.2017
 */
