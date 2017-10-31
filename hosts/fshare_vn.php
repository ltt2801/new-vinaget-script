<?php

class dl_fshare_vn extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://www.fshare.vn/account/infoaccount", $cookie, "");
        if (stristr($data, 'member">VIP</a>') && stristr($data, '<dt>Hạn dùng</dt>')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, '<dt>Hạn dùng</dt>', '<dl class="money">'), '<dd>', '</dd>'));
        } elseif (stristr($data, 'Tổng file upload:') && stristr($data, 'member">VIP</a>')) {
            return array(true, "Account is lifetime!!!");
        } elseif (stristr($data, '>PROMO</dd>') && stristr($data, '<dt>Hạn dùng</dt>')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, '<dt>Hạn dùng</dt>', '<dt>Fxu hiện có</dt>'), '<dd>', '</dd>'));
        } elseif (stristr($data, '>PROMO PLUS</dd>') && stristr($data, '<dt>Hạn dùng</dt>')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, '<dt>Hạn dùng</dt>', '<dt>Fxu hiện có</dt>'), '<dd>', '</dd>'));
        } elseif (stristr($data, 'FREE')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $page = $this->lib->curl("https://www.fshare.vn/login", "", "");
        $token = $this->lib->cut_str($page, 'hidden" value="', '"');
        $data = $this->lib->curl("https://www.fshare.vn/login", $this->lib->GetCookies($page), "fs_csrf={$token}&LoginForm[email]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=0&yt0=%C4%90%C4%83ng+nh%E1%BA%ADp");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }

    public function Leech($url)
    {
        $url = str_replace('http://', 'https://', $url);
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (preg_match('/<input type="hidden" value="(.*?)" name="fs_csrf"/', $data, $match)) {
            $token = $match[1];
        }
        if (preg_match('/id="DownloadForm_linkcode" type="hidden" value="(.*?)"/', $data, $match)) {
            $linkcode = $match[1];
        }

        if ($pass) {
            $k = 0;
            while ($k < 5) {
                $page = $this->lib->curl('https://www.fshare.vn/download/get', $this->lib->cookie, "fs_csrf={$token}&DownloadForm%5Bpwd%5D=" . urlencode($pass) . "&DownloadForm%5Blinkcode%5D={$linkcode}&ajax=download-form", 0, 0, $url);
                $json = json_decode($page, true);
                if ($json['DownloadForm_pwd']) {
                    $this->error("reportpass", true, false);
                }

                if ($json["url"]) {
                    return $json["url"];
                }

            }
        }
        if (stristr($data, 'message-error')) {
            $this->lib->curl("{$this->lib->self}?id=check&rand=" . time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
            $this->error("blockAcc", true, false);
        } elseif (stristr($page, "signup")) {
            $this->lib->curl("{$this->lib->self}?id=check&rand=" . time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
            $this->error("cookieinvalid", true, false);
        } elseif (stristr($data, '>FREE<')) {
            $this->error("accfree", true, false);
        } elseif (stristr($data, ">404<")) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, "filepwd-form")) {
            $this->error("reportpass", true, false);
        } elseif (preg_match('@https?:\/\/download-?(\w+\.)?fshare\.vn\/dl\/[^"\'><\r\n\t]+@i', $data, $match)) {
            return trim($match[0]);
        } else {
            $k = 0;
            while ($k < 5) {
                $page = $this->lib->curl('https://www.fshare.vn/download/get', $this->lib->cookie, "fs_csrf={$token}&DownloadForm%5Bpwd%5D=&DownloadForm%5Blinkcode%5D={$linkcode}&ajax=download-form", 0, 0, $url);
                $json = json_decode($page, true);
                if ($json["url"]) {
                    return $json["url"];
                }

            }
        }
        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT❤
 * Version: 3.3 LTSB
 * Depositfiles.com Download Plugin
 * Date: 29.10.2017
 */
?>