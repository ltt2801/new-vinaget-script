<?php

class dl_mp3_zing_vn extends Download
{

    public function FreeLeech($url)
    {
        if (stristr($url, "http://mp3.zing.vn")) {
            $url = str_replace("http://mp3.zing.vn", "http://m.mp3.zing.vn", $url);
        }

        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));
        if (!preg_match('@https?:\/\/m\.mp3\.zing\.vn\/xml\/song\/[^"\'><\r\n\t]+@i', $data, $giay)) {
            $this->error("Cannot get XML", true, false, 2);
        } else {
            $data = $this->lib->curl($giay[0], $this->lib->cookie, "", 0);

            $js = json_decode($data, true);

            $link = $js['data'][0]['source'];

            $link = str_replace("I=", "Y=", $link);

            $this->lib->reserved['filename'] = $js['data'][0]['title'] . ".mp3";

            return trim($link);
        }
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Mp3.zing.vn Download Plugin
 * Date: 01.09.2018
 */
