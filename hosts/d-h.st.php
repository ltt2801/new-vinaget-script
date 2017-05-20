<?php

class dl_d_h_st extends Download {
 
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(preg_match('@https?:\/\/fs(\d+\.)?d\-h\.st\/download\/\d+\/[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* d-h.st Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>