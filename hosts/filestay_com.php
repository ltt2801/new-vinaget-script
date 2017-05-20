<?php

class dl_filestay_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://filestay.com/?op=my_account", "lang=english;".$cookie, "");
        if(stristr($data, '<dt>Premium until</dt>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<dt>Premium until</dt>','<dd class="ddeditbtn">'), '<dd>', '</dd>'));
        else if(stristr($data, '<dd>Normal</dd>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filestay.com/login.html", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://filestay.com/");				
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
			elseif(preg_match('@https?:\/\/ftp(\d+\.)?filestay\.com:182\/d\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
        if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/ftp(\d+\.)?filestay\.com:182\/d\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $data, $giay))
			return trim($giay[0]);
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
* filestay Download Plugin by giaythuytinh176 [21.7.2013]
* Downloader Class By [FZ]
*/
?>