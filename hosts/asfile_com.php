<?php

class dl_asfile_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://asfile.com/en/login", "", "login={$user}&password={$pass}&remember_me=1&referer={$url}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->curl("http://asfile.com/en/premium-download/file/{$this->exploder('/', $url, 4)}",($pass ? "password={$pass};" : "")."{$this->lib->cookie}","");
		if(preg_match('%"(http:\/\/.+asfile\.com/file/premium/.+)"%U', $data, $redir2)) return trim($redir2[1]);
		elseif(stristr($data,'http://asfile.com/file_is_unavailable')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'http://asfile.com/en/index/pay')) $this->error("blockAcc");
		elseif(stristr($data,'http://asfile.com/en/password/')) $this->error("notsupportpass", true, false);	
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Asfile Download Plugin 
* Downloader Class By [FZ]
*/
?>