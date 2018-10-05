<?php

class dl_mega_nz extends Download
{

    public function PreLeech($url)
    {
        if (!extension_loaded('mcrypt') || !in_array('rijndael-128', mcrypt_list_algorithms(), true)) {
            $this->error("Mcrypt module isn't installed or it doesn't have support for the needed encryption.", true, false);
        }

    }

    public function FreeLeech($url)
    {
        return trim($url);
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Mega.nz Download Plugin
 * Date: 06.09.2017
 */
