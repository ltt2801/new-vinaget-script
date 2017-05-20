<?php

class dl_rapidgator_net extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://rapidgator.net/profile/index", "lang=en;{$cookie}", "");
		if(stristr($data, '<a href="/article/premium">Free</a>')) return array(false, "accfree");
		elseif(stristr($data, '<a href="/article/premium">Premium</a>')) {
			$oob = $this->lib->curl("http://rapidgator.net/file/79674811", "lang=en;{$cookie}", "");
			if(stristr($oob, 'You have reached quota of downloaded information')) return array(true, "Until ".$this->lib->cut_str($data, ' ">Premium','</a></li>'). "<br> Account out of BW");
			else return array(true, "Until ".$this->lib->cut_str($data, 'Premium services will end on ','<br/>If')." <br/>Bandwith available:" .$this->lib->cut_str($this->lib->cut_str($data, 'Bandwith available</td>','<div style='), '<td>','</br>'));
		}
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("https://rapidgator.net/auth/login", "lang=en", "LoginForm[email]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=1");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,"lang=en;".$this->lib->cookie,"");
		if(stristr($data, "You have reached quota of downloaded information") || stristr($data, "You have reached daily quota")) $this->error("LimitAcc");
		elseif(stristr($data,'File not found</div>'))  $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/\w+\.rapidgator\.net\/\/\?r=download\/index[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rapidgator Download Plugin 
* Downloader Class By [FZ]
* Add check account by giaythuytinh176 19.7.2013
* Fix check account by rayyan2005 24.4.2014
*/
?>
