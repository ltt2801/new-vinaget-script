<?php

class dl_nitroflare_com extends Download
{

    public function CheckAcc($cookie)
    {
        preg_match('/user=(.*?); pass=(.*?);/U', $cookie, $match);
        list($user, $pass) = array($match[1], $match[2]);

        $data = $this->lib->curl("https://nitroflare.com/api/v2/getKeyInfo?user={$user}&premiumKey={$pass}", "", "", 0);
        $json = json_decode($data, true);
        if ($json['type'] == 'success') {
            if ($json['result']['expiryDate'] && $json['result']['trafficLeft']) {
                return array(true, "Until " . $json['result']['expiryDate'] . "<br>Bandwidth Left: " . $this->lib->convertmb($json['result']['trafficLeft']) . " / " . $this->lib->convertmb($json['result']['trafficMax']));
            }
            return array(false, "accfree");
        }
        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $cookie = "user={$user}; pass={$pass}";
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        preg_match('/user=(.*?); pass=(.*?);/', $this->lib->cookie, $match);
        list($user, $pass) = array($match[1], $match[2]);

        preg_match('/view\/(.*?)\//', $url, $match);
        $fileCode = trim($match[1]);

        $data = $this->lib->curl("https://nitroflare.com/api/v2/getDownloadLink?user={$user}&premiumKey={$pass}&file={$fileCode}", "", "", 0);
        $json = json_decode($data, true);

        if ($json['type'] == 'error') {
            if (stristr($json['message'], "File doesn't exist")) {
                $this->error("dead", true, false, 2);
            } elseif (stristr($json['message'], "Captcha required")) {
                $this->error("Nitroflare Captcha found. Please bypass captcha first -> <a target=\"blank\" href=\"https://nitroflare.com/api/v2/solveCaptcha?user={$user}\">Click here</a>", true, false);
            } elseif (stristr($json['message'], "limit")) {
                $this->error("LimitAcc", true, false, 2);
            }
        } elseif (isset($json['result']['url'])) {
            return $json['result']['url'];
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Nitroflare.com Download Plugin
 * Date: 21.08.2020
 */
