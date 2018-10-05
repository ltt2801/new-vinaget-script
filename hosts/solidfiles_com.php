<?php

class dl_solidfiles_com extends Download
{

    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));
        if (preg_match('@https?:\/\/s(\d+\.)?sfcdn\.in\/[^"\'><\r\n\t]+@i', $data, $giay)) {
            return trim($giay[0]);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Solidfiles.com Download Plugin
 * Date: 01.09.2018
 */
