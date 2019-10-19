<?php

class dl_isra_cloud extends Download
{
    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://isra.cloud/?op=my_account", "lang=english;{$cookie}", "");
        if (stristr($data, "expire</label>")) {
            return array(true, "Until " . strip_tags($this->lib->cut_str($data, "expire</label>", "<input class=\"extendaccount"))
                . "<br>Traffic available today: " . strtoupper(strip_tags($this->lib->cut_str($data, "Traffic available today</div>", "</div>"))));
        } elseif (stristr($data, "My affiliate link</label>")) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }
    }

    public function Login($user, $pass)
    {
        $data = $this->lib->curl("https://isra.cloud/login.html", "", "");
        $cook = $this->lib->GetCookies($data);
        $post = $this->parseForm($this->lib->cut_str($data, '<form method="POST"', '</form>'));
        $post['login'] = $user;
        $post['password'] = $pass;
        $post['redirect'] = '';
        $data = $this->lib->curl("https://isra.cloud/login.html", $cook, $post);
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";

        return array(true, $cookie);
    }

    public function Leech($url)
    {
        list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        $file_code_cookie = $this->lib->GetCookies($data);
        if (!empty($file_code_cookie)) {
            $data = $this->passRedirect($data, $this->lib->cookie . $file_code_cookie);
        }

        if ($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form', '</form>'));
            $post["password"] = $pass;
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (stristr($data, 'Wrong password')) {
                $this->error("wrongpass", true, false, 2);
            } elseif (preg_match('@https?:\/\/fs\d+\.isra.cloud.*@i', $data, $link)) {
                return trim($link[0]);
            }
        }

        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, 'The file was deleted by its owner') || stristr($data, 'Page not found')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (preg_match('@https?:\/\/fs\d+\.isra.cloud.*@i', $data, $link)) {
                return trim($link[0]);
            }
        } else {
            return trim($this->redirect);
        }

        return false;
    }
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Isra.cloud Download Plugin
 * Date: 27.09.2019
 */
