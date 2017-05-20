<?php

class dl_gamefront_com extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		$lik1 = $this->lib->cut_str($this->lib->cut_str($data, '<div class="action">', 'id="downloadLink">'), '<a href="', '" class="downloadNow');
		$lik2 = $this->lib->curl($lik1, $this->lib->cookie, "");
		$giay = $this->lib->cut_str($lik2, '<p>Your download will begin in a few seconds.<br />If it does not, <a href="', '">click here</a>.</p>');
		return trim($giay);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Gamefront.com Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>