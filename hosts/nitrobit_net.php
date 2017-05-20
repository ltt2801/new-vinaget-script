<?php		

class dl_nitrobit_net extends Download {
			// Need fix!
    public function Leech($url) {
		$pwdkey = preg_replace("/\s+/", "", str_replace(";", "", $pwdkey = str_replace("=", "", $this->lib->cookie)));
		if(preg_match('@^https?:\/\/www.nitrobit\.net\/view\/(.*)@i', $url, $fileID));
		$data = $this->lib->curl("http://www.nitrobit.net/ajax/unlock.php", "", "file={$fileID[1]}&password=N{$pwdkey}&keep=true");
		$link = $this->lib->cut_str($data, 'id="download" href="', '">');
		return trim($link);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* nitrobit Download Plugin by giaythuytinh176 [17.9.2013]
* Downloader Class By [FZ]
*/
?>