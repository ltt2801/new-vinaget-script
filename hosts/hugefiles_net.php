<?php

class dl_hugefiles_net extends Download {
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://hugefiles.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://hugefiles.net/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data))   return trim($this->redirect);
		}
		if(stristr($data,'<b>Password: </b><br /><input type="password" name="password"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
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
* Hugefiles.net Download Plugin by giaythuytinh176 [19.9.2013]
* Downloader Class By [FZ]
*/
?>