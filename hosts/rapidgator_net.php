<?php

class dl_rapidgator_net extends Download
{
    public function CheckAcc($cookie)
    {
        $session_id = $this->lib->cut_str($cookie, "session_id=", ";");

        $data = $this->lib->curl("https://rapidgator.net/api/user/info?sid=" . $session_id, "", "", 0);
        $json = json_decode($data, true);

        if ($json['response_status'] == "401") {
            return array(false, "accinvalid");
        } else {
            if (isset($json["response"]["expire_date"]) && isset($json["response"]["traffic_left"])) {
                $str = "";

                if ($json["response"]["expire_date"] > 0) {
                    $str .= "Until " . date("Y-m-d", $json["response"]["expire_date"]);
                } else {
                    $str .= "No time expired";
                }

                if ($json["response"]["traffic_left"] > 0) {
                    $str .= "<br>Bandwidth left: " . $this->lib->convertmb($json["response"]["traffic_left"]);
                } else {
                    $str .= "<br>No traffic left";
                }

                return array(true, $str);
            } else {
                return array(false, "accfree");
            }
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://rapidgator.net/api/user/login?username=" . urlencode($user) . "&password=" . urlencode($pass), "", "", 0);
        $json = json_decode($data, true);
        $session_id = $json["response"]["session_id"];
        $cookie = "session_id={$session_id};";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        $session_id = $this->lib->cut_str($this->lib->cookie, "session_id=", ";");

        $data = $this->lib->curl("https://rapidgator.net/api/file/download?sid=" . $session_id . "&url=" . urlencode($url), "", "", 0);
        $json = json_decode($data, true);

        if ($json["response_status"] == "404") {
            $this->error("dead", true, false, 2);
        }

        if ($json["response_status"] == "401") {
            $this->error("LimitAcc");
        }

        if ($json["response_status"] == "200" && isset($json["response"]["url"])) {
            return trim($json["response"]["url"]);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Rapidgator.net Download Plugin
 * Date: 04.10.2017
 */
