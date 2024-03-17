<?php

class dl_fboom_me extends Download
{
    public function CheckAcc($cook)
    {
        $data = $this->lib->curl("https://api.fboom.me/v1/users/me", $cook, "", 0, 1, "https://fboom.me/profile");
        if (!$data || stristr($data, "Unauthorized")) {
            return array(false, "accinvalid");
        }
        $json = @json_decode($data, true);
        if (isset($json["accountType"])) {
            if ($json["accountType"] == "premium") {
                $data = $this->lib->curl("https://api.fboom.me/v1/users/me/statistic", $cook, "", 0, 1, "https://fboom.me/profile");
                $json_quota = @json_decode($data, true);
                $quota = "";
                if (isset($json_quota["dailyTraffic"])) {{
                    $quota = "<br/>Daily Traffic used: " . $this->lib->convertmb($json_quota["dailyTraffic"]["used"]) . " / " . $this->lib->convertmb($json_quota["dailyTraffic"]["total"]);
                }}
                if (isset($json["isLifetime"]) && $json["isLifetime"]) {
                    return array(true, "Lifetime Subscription" . $quota);
                }
                return array(true, "Until " . $this->lib->convert_time($json["expires"]) . $quota);
            }
            return array(false, "accfree");
        }
        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $json = array();
        $json["client_id"]  = "fb_web_app";
        $json["client_secret"] = "3Zc7urWyORW3HsHX67NMTVnb";
        if (isset($_REQUEST["captcha"]) && $_REQUEST["captcha"] == "reload" && isset($_REQUEST["captcha_cookie"])) {
            $cook = trim($_REQUEST["captcha_cookie"]);
            $data = $this->lib->curl("https://api.fboom.me/v1/users/me/captcha?v=" . mt_rand(), $cook, "", 0);
            $image_html = "<div style=\"display: block; background-color: #fff\">" . $data . "</div>";
            $this->error("captcha image code '$cook' url '" . $image_html . "'", true, true);
        }
        if (!isset($_REQUEST["captcha_code"])) {
            $json["grant_type"] = "client_credentials";
            $data = $this->lib->curl("https://api.fboom.me/v1/auth/token", "", json_encode($json), 1, 1);
            if (!stristr($data, "access_token")) {
                if (stristr($data, "captcha_need_wait")) {
                    $this->error("Sorry, to many captcha login requests, you were banned for 30 minutes", true, false);
                }
                $this->error("blockIP", true, false);
            }
            $cook = $this->lib->GetCookies($data);
        } else {
            $cook = trim($_REQUEST["captcha_cookie"]);
            $json["captchaType"] = "classic";
            $json["captchaValue"] = trim($_REQUEST["captcha_code"]);
        }

        $json["csrfToken"] = "65cc69550197e";
        $json["grant_type"] = "password";
        $json["username"] = $user;
        $json["password"] = $pass;
        $data = $this->lib->curl("https://api.fboom.me/v1/auth/token", $cook, json_encode($json), 1, 1);
        if (preg_match('/"message":"(.*?)"/', $data, $mess)) {
            if (stristr($data, "captcha_required")) {
                $data = $this->lib->curl("https://api.fboom.me/v1/users/me/captcha?v=" . mt_rand(), $cook, "", 0);
                $image_html = "<div style=\"display: block; background-color: #fff\">" . $data . "</div>";
                $this->error("captcha image code '$cook' url '" . $image_html . "'", true, true);
            }
            $this->error($mess[1], true, true);
        } else if (stristr($data, "Invalid credentials")) {
            return array(false, "");
        }
        $cookie = $this->lib->GetCookies($data);
        return array(true, $cookie);
    }

    public function Leech($url)
    {
        if (preg_match('/\/file\/(.*?)\//', $url, $match)) {
            $id = trim($match[1]);
            $data = $this->lib->curl("https://api.fboom.me/v1/files/" . $id . "/download?referer=", $this->lib->cookie, "", 0);
            $json = @json_decode($data, true);
            if (isset($json["downloadUrl"])) {
                return trim($json["downloadUrl"]);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Fboom.me Download Plugin
 * Date: 17.03.2024
 */
