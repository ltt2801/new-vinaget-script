<?php

class dl_dropbox_com extends Download
{

    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));

        if (preg_match('@https?:\/\/dl\.dropboxusercontent\.com\/[^"\'><\r\n\t]+@i', $data, $giay)) {
            return trim($giay[0]);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Dropbox.com Download Plugin
 * Date: 01.09.2018
 */
