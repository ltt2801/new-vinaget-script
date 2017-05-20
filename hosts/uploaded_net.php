<?php

class dl_uploaded_net extends Download {

	public function PreLeech($url){ 
		if(stristr($url, "/f/")) {
			$data = $this->lib->curl($url, "", "");
			$data = $this->lib->cut_str($data, '<table id="fileList">', "</table>");
			$FID = explode('<h2><a href="file', $data);
			$maxfile = count($FID);
			for ($i = 1; $i < $maxfile; $i++) {
				preg_match('%\/(.+)\/from\/(.*)%U', $FID[$i], $code);
				$list = "<a href=http://uploaded.net/file/{$code[1]}>http://uploaded.net/file/{$code[1]}/</a><br/>";
				echo $list;
			}
			exit;
		}
	}  
  
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://uploaded.net/language/en", $cookie, "");
		$data = $this->lib->curl("http://uploaded.net/", $cookie, "");
		$dt = $this->lib->curl("http://uploaded.net/file/wojimfnt", $cookie, ""); 
		if(stristr($dt, 'You used too many different IPs')) return array(true, "blockAcc");
		elseif(stristr($dt, 'Hybrid-Traffic is completely exhausted')) return array(true, "LimitAcc");
		elseif(stristr($data, '<a href="register"><em>Premium</em></a>')) return array(true, $this->lib->cut_str($this->lib->cut_str($data, "Duration:</td>", "/th>"), "<th>", "<"));
		elseif(stristr($data, '<li><a href="logout">Logout</a></li>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
         
	public function Login($user, $pass){
		$data = $this->lib->curl("http://uploaded.net/io/login", "", "id={$user}&pw={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
         
	public function Leech($url) {
		//$url = str_replace("http://", "https://", $url);
		$data = $this->lib->curl($url, $this->lib->cookie, ""); 
		if (stristr($data,">Extend traffic<")) $this->error("LimitAcc");
		elseif (stristr($data,"Hybrid-Traffic is completely exhausted")) $this->error("LimitAcc");
		elseif (stristr($data,"Our service is currently unavailable in your country")) $this->error("blockCountry", true, false);
		elseif (stristr($data,"You used too many different IPs")) $this->error("blockAcc", true, false);
		elseif (stristr($data,"Download Blocked (ip)")) $this->error("blockIP", true, false);
		elseif(!$this->isredirect($data)) {
			if (preg_match('/action="(https?:\/\/.+)" style/i', $data, $link))	return trim($link[1]);
		}
		else{
			if (stristr($this->redirect,'uploaded.net/404')) $this->error("dead", true, false, 2);
			else return trim($this->redirect);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uploaded Download Plugin 
* Downloader Class By [FZ]
* Fixed By djkristoph
* Fixed download link By giaythuytinh176 [5.8.2013]
* Fixed plugin by Steam [09, Feb 2014]
*/
?>