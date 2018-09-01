<?php

class dl_d_h_st extends Download
{
    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));

        if (preg_match('@https?:\/\/fs(\d+\.)?d\-h\.st\/download\/\d+\/[^"\'><\r\n\t]+@i', $data, $giay)) {
            return trim($giay[0]);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Prefiles.com Download Plugin
 * Date: 25.07.2013
 */
