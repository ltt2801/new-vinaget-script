<?php

class dl_tune_pk extends Download
{

    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, "", "");
        $this->save($this->lib->GetCookies($data));
        if (stristr($data, '<li>Video does not exist </li>')) {
            $this->error("dead", true, false, 2);
        }

        if (stristr($data, 'var hq_video_file =')) {
            return trim($this->lib->cut_str($data, "var hq_video_file = '", "'"));
        }

        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Tune.pk Download Plugin
 * Date: 01.09.2018
 */
