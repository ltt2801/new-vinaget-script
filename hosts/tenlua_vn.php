<?php

class dl_tenlua_vn extends Download
{

    public function CheckAcc($cookie)
    {
        $cookie2 = trim($cookie);
        $cookie2 = str_replace("=", "", $cookie2);
        $cookie2 = str_replace(";", "", $cookie2);
        $data = $this->lib->curl("http://api.tenlua.vn/?sid=" . $cookie2, '', '[{"a":"user_info"}]', 0);
        if ($data == "-500") {
            return array(false, "accinvalid");
        } else {
            $json = json_decode($data, true);
            if ($json[0]['utype'] <= 2) {
                return array(false, "accfree");
            } else {
                return array(true, "Ngày hết hạn GOLD: " . $json[0]['endGold']);
            }

        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl('http://api.tenlua.vn/', '', '[{"a":"user_login","user":"' . $user . '","password":"' . $pass . '","permanent":false}]', 0);
        $cookie = json_decode($data, true);
        $cookie = " " . $cookie[0];
        return $cookie;
    }

    public function Leech($url)
    {
        $gach = explode('/', $url);
        $ze = explode('-', $gach[3]);
        $id = $ze[count($ze, COUNT_RECURSIVE) - 1];
        if ($id == "download") {
            $id = $gach[4];
        }

        $seqno = mt_rand();
        $cookie2 = trim($this->lib->cookie);
        $cookie2 = str_replace("=", "", $cookie2);
        $cookie2 = str_replace(";", "", $cookie2);
        $data = $this->lib->curl('http://api.tenlua.vn/?sid=' . $cookie2, '', '[{"a":"filemanager_builddownload_getinfo","n":"' . $id . '","r":' . $seqno . '}]', 0);
        $content = json_decode($data, true);
        if (isset($content[0]["reqlink"])) {
            return false;
        } elseif ($content[0]["type"] == "none") {
            $this->error("dead", true, false, 2);
        } elseif ($content[0]["type"] == "folder") {
            $this->error("Not Support Folder Link", true, false, 2);
        } else {
            $link = $content[0]["dlink"];
            $data = $this->lib->curl($link, "", "");
            if ($this->isredirect($data)) {
                return trim($this->redirect);
            } else {
                $this->error("Can Not Stream This Link", true, false, 2);
            }

        }
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Tenlua.vn Download Plugin
 * Date: 01.09.2018
 */
