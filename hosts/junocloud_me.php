<?php

class dl_junocloud_me extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://junocloud.me/home.html", "lang=english;{$cookie}", "");
        if(stristr($data, 'STATUS: <span class="val">PREMIUM</span>')) return array(true, "Until ".$this->lib->cut_str($data, '(till ', ')<'));
        else if(stristr($data, '>USER: <') && !stristr($data, 'STATUS: <span class="val">PREMIUM</span>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://junocloud.me/", "lang=english", "login={$user}&password={$pass}&op=login&submit_btn=Submit&redirect=http://junocloud.me/home.html");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		if(strpos($url, 'dl3') == false) $url = str_replace('junocloud.me', 'dl3.junocloud.me', $url);
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) return trim($this->redirect);
		}
		if(stristr($data,'Password:</b> <input type="password" name="password"')) $this->error("reportpass", true, false);
		elseif(stristr($data,'name="id" value="">')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data)) return trim($this->redirect);
		} 
		else return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Junocloud.me Download Plugin by giaythuytinh176 [27.2.2014]
* Downloader Class By [FZ]
*/
?>