<?php

class dl_datafile_com extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("http://www.datafile.com/profile.html", "lang=en;{$cookie}", "");

        if (stristr($data, '>Premium Expires:<')) {
            return array(true, "Until " . $this->lib->cut_str($data, '<td class="el" >', '&nbsp; (') . "<br/>Traffic left: " . $this->lib->cut_str($this->lib->cut_str($data, 'Traffic left:</td>', '</tr>'), '<td>', '</td>'));
        } elseif (stristr($data, '">Upgrade</a></span>)')) {
            return array(false, "accfree");
        }

        return array(false, "accinvalid");
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("http://www.datafile.com/login.html", "lang=en", "", 0);

        if (stristr($data, 'eval(atob(')) {
            if ($hash = $this->descrypt_hash($data)) {
                $ulogin = "http://www.datafile.com" . $hash;
                $this->lib->curl($ulogin, "", "", 0);
            } else {
                return false;
            }
        }

        $data = $this->lib->curl("https://www.datafile.com/login.html", "lang=en", "login={$user}&password={$pass}&remember_me=1");
        $cookie = "lang=en;" . $this->lib->GetCookies($data);

        return $cookie;
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");

        if ($this->isredirect($data)) {
            $link = trim("http://www.datafile.com" . $this->redirect);
            $data = $this->lib->curl($link, $this->lib->cookie, "");

            if (stristr($data, "ErrorCode 6: Download limit in")) {
                $this->error("LimitAcc", true, false);
            }
            if ($this->isredirect($data)) {
                $redir = trim($this->redirect);
            }

            $name = $this->lib->getname($redir, $this->lib->cookie);
            $tach = explode(';', $name);
            $this->lib->reserved['filename'] = $tach[0];

            return $redir;
        } elseif (stristr($data, 'ErrorCode 0: Invalid Link')) {
            $this->error("dead", true, false, 2);
        }

        return false;
    }

    private function descrypt_hash($html)
    {
        $html = str_replace("window.location.href=", "document.write(", $html);
        $html = str_replace("+'';", "+'');", $html);
        file_put_contents($this->lib->fileinfo_dir . "/datafile_descrypt.html", $html) or $this->error("Cannot locate " . $this->lib->fileinfo_dir . "/datafile_descrypt.html", true, false);
        $code = file_get_contents($this->lib->fileinfo_dir . "/datafile_descrypt.html");

        return $code;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTSB
 * Datafile.com Download Plugin
 * Date: 01.09.2018
 */
