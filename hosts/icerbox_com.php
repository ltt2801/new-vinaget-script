<?php
class dl_icerbox_com extends Download
{

    public function CheckAcc($cookie)
    {
        $token = $this->lib->cut_str($cookie, 'token=', ';');
        $data = $this->lib->curl("https://icerbox.com/api/v1/user/account", "", "", 0, 1, 0, 0, array("Authorization: Bearer {$token}"));
        $json = json_decode($data, true);
        if ($json['data']['has_premium'] == true) {
            return array(true, "Duration: " . $json['data']['package']['duration'] . " days left<br/>Daily Limit: " . $this->lib->convertmb($json['data']['package']['volume']) . "<br/>Bandwidth Left: " . $this->lib->convertmb($json['data']['package']['bandwidth']));
        } elseif ($json['data']['has_premium'] == false) {
            return array(false, "accfree");
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://icerbox.com/api/v1/auth/login", "", '{"email": "' . $user . '", "password": "' . $pass . '"}', 0);
        if (stristr($data, 'status_code":429')) {
            $this->error("Captcha found when login account. Please try again later", true, false);
        }
        elseif (preg_match('/"token":"(.*?)"/', $data, $match)) {
            return "token=" . trim($match[1]) . ';';
        }

        return false;
    }

    public function Leech($url)
    {
        $token = $this->lib->cut_str($this->lib->cookie, 'token=', ';');
        if (preg_match('#^https?://icerbox.com/(folder/)?([\w\d]+)/?(.*)$#', $url, $match)) {
            $id = trim($match[2]);
            $data = $this->lib->curl("https://icerbox.com/api/v1/dl/ticket", "", '{"file": "' . $id . '"}', 0, 1, 0, 0, array("Authorization: Bearer {$token}"));
            $json = json_decode($data, true);
            if (isset($json['status_code'])) {
                $this->error("dead", true, false, 2);
            } elseif (isset($json['url'])) {
                return trim($json['url']);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Icerbox.com Download Plugin
 * Date: 05.10.2018
 */
