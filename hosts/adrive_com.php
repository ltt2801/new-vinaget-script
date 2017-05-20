<?php

class dl_adrive_com extends Download {
	
	public function FreeLeech($url){
		$url = preg_replace("@https?:\/\/(www\.)?adrive\.com@", "http://www.adrive.com", $url);
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file is password protected')) 	$this->error("notsupportpass", true, false);
		elseif(stristr($data,'Not Found') || stristr($data,'The file you are trying to access is no longer available publicly') || stristr($data,'Public File Busy'))  $this->error("dead", true, false, 2);
		elseif(preg_match('%click <a href="(http:.+adrive.com.+)">here%U', $data, $giay))  
		return trim($giay[1]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Adrive.com Download Plugin by giaythuytinh176 [24.7.2013]
* Downloader Class By [FZ]
*/
?>