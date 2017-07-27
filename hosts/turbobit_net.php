<?php

class dl_turbobit_net extends Download {

    public function CheckAcc($cookie) {
        $data = $this->lib->curl("https://turbobit.net", $cookie, 0);
		if (stristr($data, "HTTP/1.1 301 Moved Permanently") && $this->isredirect($data)) $data = $this->lib->curl(trim($this->redirect), $cookie, 0);
		
        if (stristr($data, 'Turbo access till')) {
			if(stristr($data, '> limit of premium downloads'))   return array(true, "LimitAcc");
			else return array(true, "Until ".$this->lib->cut_str($data, '>Turbo access till ','</span><a'));
        }
		else if(stristr($data, '<u>Turbo Access</u> denied.')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
         
    public function Login($user, $pass){
        $data = $this->lib->curl("https://turbobit.net/user/login", "user_lang=en", "user[login]={$user}&user[pass]={$pass}&user[captcha_type]=&user[captcha_subtype]=&user[submit]=Sign+in&user[memory]=on");
		if (stristr($data, "HTTP/1.1 301 Moved Permanently") && $this->isredirect($data)) $this->lib->curl(trim($this->redirect), "user_lang=en", "user[login]={$user}&user[pass]={$pass}&user[captcha_type]=&user[captcha_subtype]=&user[submit]=Sign+in&user[memory]=on");
        $cookie = "user_lang=en;".$this->lib->GetCookies($data);
        return $cookie;
    }
         
    public function Leech($url) {
        if(strpos($url, "/download/free/") == true) {
			$gach = explode('/', $url);
			$url = "https://turbobit.net/{$gach[5]}.html";		
		}
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stristr($data, "HTTP/1.1 301 Moved Permanently") && $this->isredirect($data)) $data = $this->lib->curl(trim($this->redirect), $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($data));
        if(stristr($data,'site is temporarily unavailable') || stristr($data,'This document was not found in System')) $this->error("dead", true, false, 2);
        elseif(stristr($data,'Please wait, searching file')) $this->error("dead", true, false, 2);
        elseif(stristr($data, 'You have reached the <a href=\'/user/messages\'>daily</a> limit of premium downloads') || stristr($data, 'You have reached the <a href=\'/user/messages\'>monthly</a> limit of premium downloads')) $this->error("LimitAcc");
		elseif(stristr($data, '<u>Turbo Access</u> denied')) $this->error("blockAcc", true, false);
 		elseif(preg_match("%a href='(.*)'><b>Download%U", $data, $link)){
			$link = trim($link[1]);
			$data = $this->lib->curl($link, $this->lib->cookie, "");
			if($this->isredirect($data)) return trim($this->redirect);
		}
		return false;
    }
	
}

/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.3
* Turbobit Download Plugin
* Date: 09.06.2017
*/
?>