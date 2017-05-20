<?php

class dl_tusfiles_net extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.tusfiles.net/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<h4 id="title-tags">Premium account expire</h4>',' <dt><input type="button"'), '<dd>','</dd>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.tusfiles.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.tusfiles.net/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
  
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) 	return trim($this->redirect);
		}
		if(stristr($data,'<h2>File Not Found</h2>') || stristr($data,'<h3>The file was removed by administrator</h3>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'<small>Password:</small> <input class="bttn')) 	$this->error("reportpass", true, false);
		elseif(!stristr($data, "Download Now!"))
		$this->error("Cannot get Download Now", true, false);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data)) 	return trim($this->redirect);
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
		if(stristr($data,'<h2>File Not Found</h2>') || stristr($data,'<h3>The file was removed by administrator</h3>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'<small>Password:</small> <input class="bttn')) 	$this->error("reportpass", true, false);
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
* Tusfiles Download Plugin by giaythuytinh176 [31.7.2013]
* Downloader Class By [FZ]
*/
?>