<?php

class dl_demo_ovh_eu extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
 		if (preg_match('@^HTTP/1\.[0|1] 404 Not Found@i', $data))  $this->error("dead", true, false, 2);
		elseif(preg_match('@href="/(download/\w+/[^\"]+)"@i', $data, $dl))  {
			$dl = "http://demo.ovh.eu/".$dl[1];
			return trim($dl);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* demo.ovh.eu  Download Plugin by giaythuytinh176 [21.8.2013]
* Downloader Class By [FZ]
*/
?>