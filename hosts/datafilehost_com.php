<?php

class dl_datafilehost_com extends Download
{
    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));

        if (preg_match('/a href=\'(http:\/\/www\.datafilehost\.com\/get\.php\?file\=[^\']+)/i', $data, $dl)) {
            return trim($dl[1]);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Datafilehost.com Download Plugin
 * Date: 01.09.2018
 */
