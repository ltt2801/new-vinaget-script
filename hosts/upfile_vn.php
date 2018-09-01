<?php

class dl_upfile_vn extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://upfile.vn/payments/index.html", $cookie, "");
        if (stristr($data, 'Chuyển về tài khoản Free:')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, 'Chuyển về tài khoản Free:', '</tr>'), '<td>', '</td>'));
        } elseif (stristr($data, 'Loại Tài Khoản:') && !stristr($data, 'Chuyển về tài khoản Free')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://upfile.vn/login.html", "", "loginUsername={$user}&loginPassword={$pass}&submitme=1&submit=Đăng%20nhập");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }

    public function Leech($url)
    {
        return trim($url);
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Upfile.vn Download Plugin
 * Date: 01.09.2018
 */
