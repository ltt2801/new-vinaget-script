<?php

class dl_metacafe_com extends Download {
	
    public function FreeLeech($url) {
		$urx = explode("/", $url);
		$data = $this->lib->curl("http://www.metacafe.com/fplayer/{$urx[4]}/download.swf", "", "");
		if(stristr($data, 'mediaURL')){
			$link = urldecode(urldecode($data));
			$link = $this->lib->cut_str($link, '"mediaURL":"','",');
			$link = str_replace("\/", "/", $link);
			return trim($link);
		}
		else $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Metacafe Download Plugin 
* Downloader Class By [FZ]
*/
?>
