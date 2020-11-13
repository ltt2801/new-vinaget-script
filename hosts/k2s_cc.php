<?php

class dl_k2s_cc extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://keep2share.cc/api/v2/accountInfo", "", "{{$cookie}}", 0);
        $json = @json_decode($data, true);
        if (isset($json["account_expires"]) && !$json["account_expires"]) {
            return array(true, "accfree");
        } elseif (isset($json["available_traffic"])) {
            return array(true, "Until " . $this->lib->convert_time($json["account_expires"] - time()) . "<br/>Traffic Left Today: " . $this->lib->convertmb($json["available_traffic"]));
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $post = array(
            "username" => $user,
            "password" => $pass,
            "re_captcha_challenge" => isset($_REQUEST["recaptcha_challenge_field"]) ? $_REQUEST["recaptcha_challenge_field"] : "",
            "re_captcha_response" => isset($_REQUEST["recaptcha_response_field"]) ? $_REQUEST["recaptcha_response_field"] : "",
        );

        $data = $this->lib->curl("https://keep2share.cc/api/v2/login", "", json_encode($post), 0);
        if (preg_match('/"message":"(.*?)"/', $data, $mess)) {
            if (stristr($mess[1], "captcha")) {
                $data = $this->lib->curl("https://keep2share.cc/api/v2/requestReCaptcha", "", "true=false", 0);
                $json = @json_decode($data, true);
                $this->error("captcha url code '{$json['challenge']}' url '{$json['captcha_url']}'", true, true);
            }
            $this->error($mess[1], true, true);
        }

        $cookie = "";
        if (preg_match("/\"auth_token\":\"(.*?)\"/", $data, $match)) {
            $cookie = "\"auth_token\":\"{$match[1]}\"";
        }

        return array(false, $cookie);
    }

    public function Leech($url)
    {
        if (preg_match('/file\/([a-zA-Z0-9]+)/', $url, $match)) {
            $fileid = trim($match[1]);
            $data = $this->lib->curl("https://keep2share.cc/api/v2/getUrl", "", "{{$this->lib->cookie}, \"file_id\":\"{$fileid}\"}", 0);
            $json = @json_decode($data, true);

            if ($json["code"] == 200) {
                return trim($json['url']);
            } elseif ($json["errorCode"] == 20 || $json["errorCode"] == 21 || $json["errorCode"] == 22) {
                $this->error("dead", true, false, 2);
            } elseif ($json["errorCode"] == 2) {
                $this->error("LimitAcc", true, false);
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * K2s.cc Download Plugin
 * Date: 11.11.2020
 */
