<?php

class dl_secretfile_net extends Download
{

    public function CheckAcc($cookie)
    {
        $data = $this->lib->curl("https://secretfile.net/?op=my_account", "{$cookie}", "");
        if (stristr($data, 'Premium expiration')) {
            return array(true, "Until " . $this->lib->cut_str($data, "Premium expiration</td>\n<td>", '</')."<br>Kota: ".$this->lib->cut_str($data, "Traffic available today</td>\n<td>", '<'));
        } else if (stristr($data, '>Upgrade to premium<')) {
            return array(false, "accfree");
        } else {
            return array(false, "accinvalid");
        }

    }
/*
    public function Login($user, $pass)
    {

		$data = $this->lib->curl("https://secretfile.net/", "", "login={$user}&password={$pass}&op=login");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
*/
    public function Leech($url)
    {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data, 'type="password" name="password')) {
            $this->error("reportpass", true, false);
        } elseif (stristr($data, 'The file was deleted by its owner')) {
            $this->error("dead", true, false, 2);
        } elseif (!$this->isRedirect($data)) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
            $data = $this->lib->curl($url, $this->lib->cookie, $post);

            if (preg_match('@https:\/\/(\w+\.)?secretfile.net\/cgi-bin\/dl.cgi\/[^"\'><\r\n\t]+@i', $data, $link)) {
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
 * Secretfile.net Download Plugin
 * Created By Jetleech [Jetleech.net]
 * Date: 10.20.2021
 */