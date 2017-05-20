<?php

class dl_safeshared_com extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'<center><h1>404 Not Found</h1></center>') && stristr($data,'<center>nginx</center>')) $this->error("blockCountry", true, false);
		elseif(stristr($data,'<div class="message-big">404</div>') && stristr($data,'<div class="message-small">HTTP Error - File or directory not found</div>')) $this->error("dead", true, false, 2);
		$ip = $this->lib->cut_str($data, 'var domainDownload = \'', '/\';');
		$id = $this->lib->cut_str($data, 'var uriDownload = \'', '\';');
		$link = ''.$ip.'/'.$id;  return trim($link);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Safeshared.com Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>