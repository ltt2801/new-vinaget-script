<?php

class dl_tune_pk extends Download {
	
    public function FreeLeech($url) {
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, '<li>Video does not exist </li>')) $this->error("dead", true, false, 2);
		if(stristr($data, 'var hq_video_file =')) 
		return trim($this->lib->cut_str($data, "var hq_video_file = '","'"));
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Tune.pk Download Plugin 
* Downloader Class By [FZ]
*/
?>
