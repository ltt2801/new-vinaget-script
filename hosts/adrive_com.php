<?php

class dl_adrive_com extends Download
{
    public function FreeLeech($url)
    {
        $url = preg_replace("@https?:\/\/(www\.)?adrive\.com@", "http://www.adrive.com", $url);
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));

        if (stristr($data, 'The file is password protected')) {
            $this->error("notsupportpass", true, false);
        } elseif (stristr($data, 'Not Found') || stristr($data, 'The file you are trying to access is no longer available publicly') || stristr($data, 'Public File Busy')) {
            $this->error("dead", true, false, 2);
        } elseif (preg_match('%click <a href="(http:.+adrive.com.+)">here%U', $data, $match)) {
            return trim($match[1]);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Adrive.com Download Plugin
 * Date: 10.09.2017
 */
