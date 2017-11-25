<?php

class dl_keep2share_cc extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://keep2share.cc", $cookie, "");
		if(stristr($data, 'Premium expires:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium expires: <b>','</b>'));
		else if(stristr($data, '<a href="/premium.html" class="free">Free</a>')) return array(true, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://keep2share.cc/login.html", "", "LoginForm[username]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=1&yt0=login");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$url = str_replace("k2s.cc", "keep2share.cc", $url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");	 
		if(stristr($data,"File not found or deleted") && stristr($data,">Error 404<"))   $this->error("dead", true, false, 2);
		elseif(stristr($data,"Traffic limit exceed!<br>"))   $this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data)) {
			$id = $this->lib->cut_str($data, 'window.location.href = \'', '\';');		
			$giay = $this->lib->curl("http://keep2share.cc".trim($id), $this->lib->cookie, "");
			if($this->isredirect($giay)) return trim($this->redirect); 
		}
		else return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Downloader Class By [FZ]
* Keep2share Download plugin By giaythuytinh176 [3.12.2013][fixed link]
* Date: 30.7.2013 
*/
?>