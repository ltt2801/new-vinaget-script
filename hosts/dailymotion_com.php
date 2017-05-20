<?php

class dl_dailymotion_com extends Download {
	
    public function FreeLeech($url) {
		$id = $this->lib->cut_str($url, '/video/','_');
		$data = $this->lib->curl("http://www.dailymotion.com/embed/video/{$id}", "", "");
		if(stristr($data, "<title>")){
			$title = $this->lib->cut_str($data, '<title>', '</title>');
			$data = urldecode(urldecode($this->lib->cut_str($data, "var info =", "catch (e)")));
			$data = str_replace("\/", "/", $this->lib->cut_str($data, "http:\/\/www.dailymotion.com\/cdn\/", '",'));
			$link = "http://www.dailymotion.com/cdn/{$data}";
			$ext = $this->lib->cut_str($data, '.','?auth=');
			$this->lib->reserved['filename'] = urldecode(str_replace(str_split('\\/:*?"<>|'), '_', html_entity_decode(trim($title), ENT_QUOTES))) . ".$ext";
			return $this->getredirect(trim($link));
		}
		else $this->error("dead", true, false, 2);
		return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dailymotion Download Plugin 
* Downloader Class By [FZ]
*/
?>
