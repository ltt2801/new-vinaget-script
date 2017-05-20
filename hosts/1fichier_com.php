<?php

class dl_1fichier_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.1fichier.com/en/console/abo.pl", $cookie, "");
		if(stristr($data, "You are a premium user until")) return array(true, $this->lib->cut_str($data, "You are a premium user ","."));
		elseif(stristr($data, "You must be registered and logged in before subscribing")) return array(false, "accinvalid");
			else return array(false, "accfree");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("https://www.1fichier.com/en/login.pl", "", "mail={$user}&pass={$pass}&lt=on&Login=Login");
		$cookie = $this->lib->GetCookies($data);
			return $cookie;
	}
	
	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, "The requested file could not be found")) $this->error("dead", true, false, 2);
		elseif($this->isredirect($data)) return trim($this->redirect);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 1fichier Download Plugin 
* Downloader Class By [FZ]
*/
?>