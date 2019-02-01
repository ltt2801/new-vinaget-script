<?php

class dl_upfile_vn extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://upfile.vn/user/", $cookie, "");
        if (stristr($data, 'Quản lý tài khoản')) {
            return array(true, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://upfile.vn/", "", "Act=Login&Email=" . rawurlencode($user) . "&Password=" . strtoupper(hash('sha256', hash('sha256', 'UpFile.VN') . rawurlencode($pass))));
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");

        if (stristr($data, 'RẤT TIẾC, FILE BẠN CẦN HIỆN KHÔNG')) {
            $this->error("dead", true, false, 2);
        }

        if (preg_match('/https?:\/\/upfile.vn\/(.*?)\/(.*)/', $url, $match)) {
            $idhash = strtoupper(hash('sha256', trim($match[1]) . '7891'));
            $data = $this->lib->curl($url, $this->lib->cookie, "Token={$idhash}", 0);

            if (empty($data)) {
                $this->error("dead", true, false, 2);
            } else {
                $json = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data), true);
                return trim($json["Link"]);
            }
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Upfile.vn Download Plugin
 * Date: 02.01.2019
 */
