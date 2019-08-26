<?php

class dl_mediafire_com extends Download
{

    public function PreLeech($url)
    {
        if (stristr($url, "sharekey=")) {
            $url = str_replace("http://www.mediafire.com/?sharekey=", "http://www.mediafire.com/folder/", $url);
        }

        if (stristr($url, "/folder/")) {
            $url = explode('/', $url);
            $sharekey = $url[4];
            $data = $this->lib->curl("http://www.mediafire.com/api/folder/get_content.php?r=jyxt&content_type=files&order_by=name&order_direction=asc&version=2.13&folder_key={$sharekey}&response_format=json", "", "", 0);
            if (stristr($data, 'Unknown or invalid FolderKey') || stristr($data, 'Required parameters')) {
                $this->error("dead", true, false, 2);
            } else {
                $quickkey = explode('"quickkey":', $data);
                $maxqk = count($quickkey);
                for ($i = 1; $i < $maxqk; $i++) {
                    preg_match('%"(.+)","filename%U', $quickkey[$i], $code);
                    preg_match('%filename":"(.+)",%U', $quickkey[$i], $filename);
                    preg_match('%size":"(.+)",%U', $quickkey[$i], $filesize);
                    if ($filesize[1] > 1024 * 1024 * 1024) {
                        $size = round($filesize[1] / (1024 * 1024 * 1024), 2) . ' GB';
                    } else {
                        $size = round($filesize[1] / (1024 * 1024), 2) . ' MB';
                    }

                    //$list = "http://www.mediafire.com/download/".$code[1]."/".urlencode($filename[1])."<br/>";
                    $list = "<a href=http://www.mediafire.com/download/{$code[1]}/" . urlencode($filename[1]) . ">http://www.mediafire.com/download/{$code[1]}/" . urlencode($filename[1]) . "</a>  | <font color=blue face=Arial size=2>{$filename[1]}</font> | <font color=green face=Arial size=2>{$size}</font><br>";
                    echo $list;
                }
            }
            exit;
        }
    }

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://www.mediafire.com/myaccount/billinghistory.php", $cookie, "");
        $dt = $this->lib->curl("https://www.mediafire.com/myaccount.php", $cookie, "");
        if (stristr($data, 'Billed on date of purchase') && !stristr($dt, '>Upgrade your Account<')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<div> <div class="lg-txt">', '</div>'));
        } elseif (stristr($data, 'You are not currently and never have been a MediaPro') || stristr($dt, '>Upgrade your Account<')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $page1 = $this->lib->curl("http://www.mediafire.com/", "", "");
        $cookies = $this->lib->GetCookies($page1);
        $page2 = $this->lib->curl("http://www.mediafire.com/dynamic/login.php?popup=1", $cookies, "login_email={$user}&login_pass={$pass}&login_remember=1&submit_login=Log%20in%20to%20MediaFire");
        $cookie = "{$cookies};{$this->lib->GetCookies($page2)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        if (stristr($url, "mediafire.com/?")) {
            $ex = explode("?", $url);
            $url = "http://www.mediafire.com/download/" . $ex[1];
        }
        $url = preg_replace("@https?:\/\/(www\.)?mediafire\.com\/(view|edit|watch|listen|play)@", "http://www.mediafire.com/download", $url);
        list($url, $pass) = $this->linkpassword($url);
        $fileID = $this->exploder('/', $url, 4);
        if ($pass) {
            $data = $this->lib->curl("http://www.mediafire.com/?{$fileID}", $this->lib->cookie, "downloadp={$pass}");
        }

        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, 'Please enter password to unlock this file')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, "error.php")) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            if (preg_match('/kNO = "(http:\/\/.+)";/i', $data, $giay)) {
                return trim($giay[1]);
            }

        } else {
            return trim($this->redirect);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Mediafire.com Download Plugin
 * Date: 01.09.2018
 */
