<?php

class dl_videomega_tv extends Download {
	
    public function FreeLeech($url) {	
		preg_match('@http:\/\/videomega\.tv(.*)ref=(.*)@', $url, $fileID);
		$url = "http://videomega.tv/iframe.php?ref={$fileID[2]}&width=595&height=340";
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		$data = $this->lib->cut_str($data, "document.write(unescape(\"", "\"));");
		$data = $this->lib->cut_str(urldecode($data), '",file: "', '",');
		return trim($data."&start=0");
		return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* videomega.tv Download Plugin by giaythuytinh176 [17.9.2013]
* Downloader Class By [FZ]
*/
?>