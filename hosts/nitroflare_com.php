<?php

class dl_nitroflare_com extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://nitroflare.com/member?s=premium", $cookie, "");
        if (stristr($data, '<strong style="color: green;">Active</strong>')) {
            return array(true, "Time Left: " . $this->lib->cut_str($data, '<label>Time Left</label><strong>', '</strong></div>') . "<br>" . $this->lib->cut_str($data, '<label>Your Daily Limit</label><strong>', '</strong></div>'));
        } elseif (stristr($data, 'Inactive')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $page = $this->lib->curl("https://nitroflare.com/login", "", "");
        $ck = $this->lib->GetCookies($page);
        $token = $this->lib->cut_str($page, 'hidden" name="token" value="', '" />');
        $data = $this->lib->curl("https://nitroflare.com/login", $ck, "email={$user}&password={$pass}&login=&token={$token}");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
    public function Leech($url)
    {

        $url = str_replace(array("http://", "http://www.", "https://www."), "https://", $url);

        $data = $this->lib->curl($url, $this->lib->cookie, "");

        if ((stristr($data, "This file has been removed due")) || (stristr($data, "File doesn't exist"))) {
            $this->error("dead", true, false, 2);
        } elseif ((stristr($data, "This download exceeds the daily download limit."))) {
            $this->error("LimitAcc", true, false, 2);
        }

        if (!$this->isredirect($data)) {
            $this->save($this->lib->GetCookies($data));
            return trim($this->lib->cut_str($data, 'id="download" href="', '">Click'));
        } else {
            $this->save($this->lib->GetCookies($data));
            return trim($this->redirect);
        }
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Nitroflare.com Download Plugin
 * Date: 01.09.2018
 */
