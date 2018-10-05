<?php

class dl_uppit_com extends Download
{

    public function FreeLeech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        $this->save($this->lib->GetCookies($data));
        if (stristr($data, 'The file was deleted by its owner')) {
            $this->error("dead", true, false, 2);
        } else {
            $post = array(
                'op' => 'download1',
                'usr_login' => 'admin',
                'id' => $this->lib->cut_str($data, 'id" value="', '">'),
                'fname' => $this->lib->cut_str($data, 'fname" value="', '">'),
                'referer' => 'http://uppit.com',
                'method_free' => ' Generate Link ',
            );
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('/a href="(http:\/\/srv\d+\.uppcdn\.com\/dl\/.+)" onClick/i', $data, $redir)) {
                return trim($redir[1]);
            }

        }
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Uppit.com Download Plugin
 * Date: 01.09.2018
 */
