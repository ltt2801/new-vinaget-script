<?php

class dl_cloudzer_net extends Download {
         
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://cloudzer.net/", $cookie, "");
		if(stristr($data, 'status status_premium">Premium')) return array(true, $this->lib->cut_str($data, "Premium</div></a><br/>","<br/>"));
		elseif(stristr($data, 'status status_free">Free')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
         
	public function Login($user, $pass){
		$data = $this->lib->curl("http://cloudzer.net/io/login", "", "id={$user}&pw={$pass}&rememberME=1");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
         
	public function Leech($url) {
		if(stristr($url, "clz.to")) {
			$ex = explode("/", $url);
			$url = 'http://cloudzer.net/file/' .$ex[3];
		}
		$url = $this->getredirect($url);
		if (stristr($url,'cloudzer.net/404')) $this->error("dead", true, false, 2);
		$data = $this->lib->curl($url, $this->lib->cookie, ""); 
		if (stristr($data,"<h1>Extend traffic</h1>")) $this->error("LimitAcc");
		elseif (stristr($data,"Hybrid-Traffic is completely exhausted")) $this->error("LimitAcc");
		elseif (stristr($data,"Our service is currently unavailable in your country")) $this->error("blockCountry", true, false);
		elseif (stristr($data,"You used too many different IPs")) $this->error("blockAcc", true, false);
		elseif (stristr($data,"Download Blocked (ip)")) $this->error("blockIP", true, false);
		elseif(!$this->isredirect($data)) {
			if (preg_match('@https?:\/\/[a-z0-9-]+stor\d+\.cloudzer\.net(:\d+)?\/dl\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		else  return trim($this->redirect);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* cloudzer.net Download Plugin  By giaythuytinh176 [20.8.2013]
* Downloader Class By [FZ]
* Special thanks to test500@rapidleech.com for your cloudzer.net account.
*/
?>