<?php

class dl_file1_info extends Download {
	
	public function FreeLeech($url){
		if(!stristr($url, "noscript")) {
			$ex =  explode("/", $url); 
			$url = "https://www.file1.info/".$ex[3]."/noscript";
		}
		$data = $this->lib->curl($url, "", "");
		if (stristr($data, "Not Found") || stristr($data, "This file does not exist or has been removed")) $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/s(\d+)?\.file1\.info\/[a-z0-9]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* file1.info Download Plugin by giaythuytinh176 [12.8.2013]
* Downloader Class By [FZ]
*/
?>