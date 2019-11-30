<?php

class dl_4share_vn extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl('https://4share.vn/member', $cookie, '');

        if (stristr($data, 'còn <b>0</b> ngày sử dụng')) {
            return array(true, 'accfree');
        } elseif (stristr($data, '>Ngày hết hạn: <')) {
            return array(true, 'Until ' . $this->lib->cut_str($data, 'Ngày hết hạn: <b>', '</b> (còn') . '<br/> Traffic available: ' . $this->lib->cut_str($data, 'download <strong>', '</strong>') . ' of ' . $this->lib->cut_str($data, '/Tổng số: <strong>', '</strong>'));
        }

        return array(false, 'accinvalid');
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://4share.vn/a_p_i/public-common/login", "", "username={$user}&password={$pass}");
        $cookie = $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    public function Leech($link)
    {
        list($url, $pass) = $this->linkpassword($link);
        $page = $this->lib->curl($link, $this->lib->cookie, '');

        if ($pass) {
            $page = $this->lib->curl($url, $this->lib->cookie, "password_download_input={$pass}");
        }

        if (stristr($page, 'Bạn đợi ít phút để download file này!')) {
            $this->error('Bạn đợi ít phút để download file này!', true, false);
        } elseif (stristr($page, 'File is deleted?') || stristr($page, 'File không tồn tại?')) {
            $this->error('File is deleted? (' . $this->lib->cut_str($page, 'File is deleted? (', ')<') . ')', true, false, 2);
        } elseif (stristr($page, "File này có password, bạn nãy nhập password để download")) {
            $this->error("reportpass", true, false);
        } elseif (preg_match('@https?:\/\/sv\d+\.4share\.vn\/\d+\/\?info=[^\'\r\n]+@i', $page, $dlink)) {
            return trim($dlink[0]);
        }

        $this->lib->save_cookies($this->site, '');

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * 4share.vn Download Plugin
 * Date: 31.10.2017
 */
