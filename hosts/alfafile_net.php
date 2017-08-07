<?php

class dl_alfafile_net extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://alfafile.net/user", "lang=en;{$cookie}", "");
        if (stristr($data, "Premium, till")) return array (true, "Premium Until: ".$this->lib->cut_str($data, "Premium, till","</span>")."<br/>Bandwidth available: ". $this->lib->cut_str($data, 'sp_bandwidth_used">', '</span'));
        elseif (stristr($data, 'Free')) return array (false, "accfree");
        else return array (false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://alfafile.net/user/login/?url=%2F", "lang=en", "email=".urlencode($user)."&password=".urlencode($pass)."&remember_me=1");
		if (stristr($data, "Invalid captcha")) die("Captcha found. Wait 30 mins to login again");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,$this->lib->cookie,"");
        if(stristr($data, "<strong>404</strong>")) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			if(preg_match('/href="(https?:\/\/.+alfafile\.net\/dl\/.+)" class/i', $data, $link)) return trim($link[1]);
		}
		else return trim($this->redirect);
		return false;
	}

}

/*
* Open Source Project
* New Vinaget by LTT?
* Version: 3.3
* Alfafile.net Download Plugin  
* Date: 07.08.2017
*/
?>