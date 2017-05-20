<?php

class dl_verzend_be extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://verzend.be/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:</TD><TD><b>')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'Payment info') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://verzend.be/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://verzend.be/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $data, $count)) 	$this->error($count[0], true, false);
		if($pass) {
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post1["method_free"] = "Free Download";
			$post1['password'] = $pass;
			$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
			if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data1))	return trim($this->redirect);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(!stristr($data, 'value="Create Download Link'))   $this->error("Cannot get Create Download Link", true, false);
		else {
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post1["method_free"] = "Free Download";
			$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
			if($this->isredirect($data1))	return trim($this->redirect);
		}
		return false;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data))	return trim($this->redirect);
		}
		if (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif (stristr($data,'<br><b>Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data))	return trim($this->redirect);
		} 
		else  
		return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Verzend.be Download Plugin by giaythuytinh176 [29.7.2013]
* Downloader Class By [FZ]
*/
?>