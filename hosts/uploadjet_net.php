<?php

class dl_uploadjet_net extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://uploadjet.net/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'My affiliate link:') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://uploadjet.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://uploadjet.net/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		if(!stristr($data, 'method_free" value="Free Download'))
		$this->error("Cannot get Free Download", true, false);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" action=\'\'>', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->save($this->lib->GetCookies($data));
			if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $data, $count)) 	$this->error($count[0], true, false);
			if(preg_match('@<span id="countdown_str"[^>]*>[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $data, $count) && $count[1] > 0) 
			sleep($count[1]);
			if($pass) {
				if(preg_match('@<span id="countdown_str"[^>]*>[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $data, $count) && $count[1] > 0) 
				sleep($count[1]);
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$post1['password'] = $pass;
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif($this->isredirect($data1)) return trim($this->redirect);
			}
			if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
			elseif(!stristr($data, 'btn_download" value="Create Download Link'))   
			$this->error("Cannot get Create Download Link", true, false);
			else {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if($this->isredirect($data1)) 	return trim($this->redirect);
			}
		}
		return false;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		if($pass) {
			$post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post0["password"] = $pass;
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(stristr($data0,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data0))	return trim($this->redirect);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
        elseif(!$this->isredirect($data)) {
		    $post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if($this->isredirect($data0))	return trim($this->redirect);
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
* uploadjet Download Plugin by giaythuytinh176 [28.8.2013]
* Downloader Class By [FZ]
*/
?>