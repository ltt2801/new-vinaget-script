<?php

class dl_k2s_cc extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://keep2share.cc/api/v2/accountinfo", "", "{{$cookie}}", 0);
        $json = @json_decode($data, true);
        if (isset($json["account_expires"]) && !$json["account_expires"]) {
            return array(true, "accfree");
        } elseif (isset($json["available_traffic"])) {
            return array(true, "Until " . $this->lib->convert_time($json["account_expires"] - time()) . "<br/>Traffic Left Today: " . $this->lib->convertmb($json["available_traffic"]));
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://keep2share.cc/api/v2/login", "", "{\"username\":\"{$user}\",\"password\":\"{$pass}\"}", 0);
        if (preg_match('/"message":"(.*?)"/', $data, $mess)) {
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
        if (preg_match('/file\/(.*)/', $url, $match)) {
            $fileid = trim($match[1]);
            $data = $this->lib->curl("http://keep2share.cc/api/v2/geturl", "", "{{$this->lib->cookie}, \"file_id\":\"{$fileid}\"}", 0);
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
 * Date: 11.02.2019
 */
