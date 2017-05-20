<?php

class dl_gigasize_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.gigasize.com/signin", "", "email={$user}&password={$pass}");
		return "{$this->lib->GetCookies($data)}";
	}
	
    public function Leech($url) {	
		$gach = explode('/', $url);
		$fileId = $gach[4];
		$token = $this->lib->curl("http://www.gigasize.com/formtoken", $this->lib->cookie, "");
		$data = $this->lib->curl("http://www.gigasize.com/getoken", $this->lib->cookie, "fileId={$fileId}&token={$token}");
		if(preg_match('%getcgi(.*)"%U', $data, $redir)) {
			$link = "http://www.gigasize.com/getcgi/".substr($redir[1], 2, -13)."/".$fileId;
			$data =  $this->lib->curl($link, $this->lib->cookie, "");
			if($this->isredirect($data)) return trim($this->redirect);
		}
		elseif(stristr($data,"Download error")|| stristr($data,"has been removed because we have received a legitimate complaint")) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Downloader Class By [FZ]
* gigasize.com Download Plugin by giaythuytinh176 [6.9.2013]
*/
?>