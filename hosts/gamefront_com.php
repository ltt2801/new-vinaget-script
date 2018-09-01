<?php

class dl_gamefront_com extends Download
{

    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));
        $lik1 = $this->lib->cut_str($this->lib->cut_str($data, '<div class="action">', 'id="downloadLink">'), '<a href="', '" class="downloadNow');
        $lik2 = $this->lib->curl($lik1, $this->lib->cookie, "");

        if ($giay = $this->lib->cut_str($lik2, '<p>Your download will begin in a few seconds.<br />If it does not, <a href="', '">click here</a>.</p>')) {
            return trim($giay);
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Gamefront.com Download Plugin
 * Date: 01.09.2018
 */
