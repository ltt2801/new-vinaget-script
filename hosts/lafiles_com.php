<?php

class dl_lafiles_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://lafiles.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
        else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://lafiles.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://lafiles.com/");
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
			if(stristr($data,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) 	return trim($this->redirect);
		}
        if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
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
* Lafiles Download Plugin by giaythuytinh176 [31.7.2013]
* Downloader Class By [FZ]
*/
?>