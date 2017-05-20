<?php

class dl_novamov_com extends Download {
	
    public function FreeLeech($url) {	//http://embed.novamov.com/embed.php?width=640&height=390&v=ba9b530d3f65f&px=1
		if(preg_match('@http:\/\/embed\.novamov\.com\/embed\.php(.+)v=(.*)&(.*)@', $url, $fileID))
		$url = "http://www.novamov.com/video/{$fileID[2]}";
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, "This file no longer exists") || stristr($data, "The file is being transfered")) $this->error("dead", true, false, 2);
		preg_match('@\.file="(\w+)"@i', $data, $fid); 
		preg_match('@\.filekey="([^"]+)"@i', $data, $fkey);
		$data = $this->lib->curl("http://www.novamov.com/api/player.api.php?user=undefined&codes=1&file={$fid[1]}&pass=undefined&key={$fkey[1]}", $this->lib->cookie, "");
 		$data = $this->lib->cut_str($data, 'url=', '&title=');
		return trim(urldecode($data)."?client=FLASH");
		return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* novamov.com Download Plugin by giaythuytinh176 [16.9.2013]
* Downloader Class By [FZ]
*/
?>
