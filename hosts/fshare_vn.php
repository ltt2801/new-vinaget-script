<?php

class dl_fshare_vn extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://www.fshare.vn/account/profile", $cookie, "");
        if (stristr($data, 'Mã Tài Khoản</td>') && !stristr($data, '<td>Free</td>') && stristr($data, 'Hạn dùng</td>')) {
            return array(true, "Until " . $this->lib->cut_str($this->lib->cut_str($data, 'Hạn dùng</td>', '</tr>'), '<td>', '</td>'));
        } elseif (stristr($data, '<td>Free</td>')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $page = $this->lib->curl("https://www.fshare.vn/site/login", "", "");
        $_csrf_app = trim($this->lib->cut_str($page, 'name="_csrf-app" value="', '"'));
        $data = $this->lib->curl("https://www.fshare.vn/site/login", $this->lib->GetCookies($page), "_csrf-app={$_csrf_app}&LoginForm%5Bemail%5D=" . urlencode($user) . "&LoginForm%5Bpassword%5D=" . urlencode($pass) . "&LoginForm%5BrememberMe%5D=0");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }

    public function Leech($url)
    {
        $url = str_replace('http://', 'https://', $url);
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        $token = $this->lib->GetCookies($data);

        $_csrf_app = $this->lib->cut_str($data, 'name="_csrf-app" value="', '"');
        $linkcode = $this->lib->cut_str($data, 'name="linkcode" value="', '"');
        $csrf_token = $this->lib->cut_str($data, 'csrf-token" content="', '"');

        if ($pass) {
            $k = 0;
            while ($k < 5) {
                $page = $this->lib->curl($url, $this->lib->cookie . $token, "_csrf-app={$_csrf_app}&DownloadPasswordForm%5Bpassword%5D=" . urlencode($pass), 1);
                if (stristr($page, "DownloadPasswordForm[password]")) {
                    $this->error("wrongpass", true, false);
                    return false;
                } elseif (preg_match('@https?:\/\/download-?(\w+\.)?fshare\.vn\/dl\/[^"\'><\r\n\t]+@i', $$page, $match)) {
                    return trim($match[0]);
                } else {
                    $page = $this->lib->curl('https://www.fshare.vn/download/get', $this->lib->cookie . $token, "_csrf-app={$_csrf_app}&linkcode={$linkcode}&withFcode5=0&fcode5=", 0);
                    $json = json_decode($page, true);

                    if ($json['errors']['linkcode'][0]) {
                        $this->error($json['errors']['linkcode'][0], true, false);
                    } elseif ($json["url"]) {
                        return $json["url"];
                    }
                }
            }
        }

        if (stristr($data, 'message-error')) {
            $this->lib->curl("{$this->lib->self}?id=check&rand=" . time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
            $this->error("blockAcc", true, false);
        } elseif (stristr($page, "form-signup")) {
            $this->lib->curl("{$this->lib->self}?id=check&rand=" . time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
            $this->error("cookieinvalid", true, false);
        } elseif (stristr($data, '<span>Free</span>')) {
            $this->error("accfree", true, false);
        } elseif (stristr($data, "<h2>Tập tin của bạn yêu cầu không tồn tại</h2>")) {
            $this->error("dead", true, false, 2);
        } elseif (stristr($data, "DownloadPasswordForm[password]")) {
            $this->error("reportpass", true, false);
        } elseif (preg_match('@https?:\/\/download-?(\w+\.)?fshare\.vn\/dl\/[^"\'><\r\n\t]+@i', $data, $match)) {
            return trim($match[0]);
        } else {
            $k = 0;
            while ($k < 5) {
                $page = $this->lib->curl('https://www.fshare.vn/download/get', $this->lib->cookie . $token, "_csrf-app={$_csrf_app}&linkcode={$linkcode}&withFcode5=0&fcode5=", 0);
                $json = json_decode($page, true);

                if ($json['errors']['linkcode'][0]) {
                    $this->error($json['errors']['linkcode'][0], true, false);
                } elseif ($json["url"]) {
                    return $json["url"];
                }
            }
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Fshare.vn Download Plugin
 * Date: 31.01.2018
 */
