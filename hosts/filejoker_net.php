<?php

class dl_filejoker_net extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://filejoker.net/profile", $cookie, "");
        $dt = $this->lib->cut_str($data, '<div class="alert alert-success alert-promo">', '</div>');
        if (stristr($data, 'Premium account expires') && stristr($dt, ">Extend Premium<")) {
            return array(true, "Until " . $this->lib->cut_str($dt, "Premium account expires: ", '				<a h') . '<br>Traffic Available: ' . $this->lib->cut_str($this->lib->cut_str($data, "td>Traffic Available:</td>", "</tr>"), 'td>', '</td'));
        } else {
            return array(false, "accinvalid");
        }

    }

    public function Login($user, $pass)
    {
        $data = $this->curl_old('https://filejoker.net', '', '');
        $data = $this->curl_old('https://filejoker.net/login', '', "recaptcha_response_field=&op=login&redirect=&rand=&email={$user}&password={$pass}");
        $cookie = $this->lib->GetCookies($data);

        return array(true, $cookie);
    }

    private function curl_old($url, $cookies, $post, $header = 1)
    {
        $ch = @curl_init();
        $head[] = "X-Requested-With: XMLHttpRequest";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        if ($cookies) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        }

        curl_setopt($ch, CURLOPT_USERAGENT, $this->UserAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }

    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, 'File Not Found')) {
            $this->error("dead", true, false, 2);
        } else {
            $post = $this->parseForm($data, '<form action="', '</form>');
            $data = $this->lib->curl($url, $this->lib->cookie, $post);
            if (strstr($data, "You have reached your download limit:")) {
                $this->error("LimitAcc", true, false, 2);
            }

            if (preg_match('%a href="(.*)" class="btn btn-green">Download File</a%U', $data, $linkpre)) {
                return trim($linkpre[1]);
            }

        }
        return false;
    }

}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Filejoker.net Download Plugin
 * Date: 01.09.2018
 */
