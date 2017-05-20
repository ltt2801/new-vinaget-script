<?php

class dl_filestoragepro_net extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://filestoragepro.net/en/members.php", "mfh_mylang=en;".$cookie, "");
		if(stristr($data, 'http://filestoragepro.net/index.php?logout=1')) return array(true, "accpremium");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filestoragepro.net/en/login.php", "mfh_mylang=en", "user={$user}&pass={$pass}&act=login&autologin=1&login=Log me in&refer_url=");
		$cookie = "mfh_mylang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, "That file has been deleted.")) $this->error("dead", true, false, 2);
		if($pass) {
			$post["downloadverify"] = "1";
			$post["d"] = "1";
			$post["downloadpw"] = $pass;
			$post["Update"] = "Submit";
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Password Error')) $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/filestoragepro\.net\/getfile\.php\?id=\d+\&a=[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'name="downloadpw')) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			$post["downloadverify"] = "1";
			$post["d"] = "1";
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/filestoragepro\.net\/getfile\.php\?id=\d+\&a=[^"\'><\r\n\t]+@i', $data, $giay))
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
* Filestoragepro Download Plugin by giaythuytinh176 [30.7.2013]
* Downloader Class By [FZ]
*/
?>