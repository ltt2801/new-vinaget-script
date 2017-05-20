<?php

class dl_uploadstation_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://bitshare.com/myaccount.html", "{$cookie}", "");
		if(preg_match('/Expiry date: ([^\r]+)/i', $data, $matches)) return array(true, "Until ".$matches[1]);
		else if(stristr($data, '<span>FREE</span>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.uploadstation.com/login.php", "", "loginUserName={$user}&loginUserPassword={$pass}&autoLogin=on&recaptcha_response_field=&recaptcha_challenge_field=&recaptcha_shortencode_field=&loginFormSubmit=Login");
		return "{$this->lib->GetCookies($data)}";
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "download=premium");	
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data,'>File is not available<'))  $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Bitshare.com Download Plugin
* Downloader Class By [FZ]
* Uploadstation Download Plugin By giaythuytinh176 [6.9.2013]
*/
?>