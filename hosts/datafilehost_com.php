<?php

class dl_datafilehost_com extends Download {

	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
        if (preg_match('/a href=\'(http:\/\/www\.datafilehost\.com\/get\.php\?file\=[^\']+)/i', $data, $dl)) 
		return trim($dl[1]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Datafilehost.com Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>