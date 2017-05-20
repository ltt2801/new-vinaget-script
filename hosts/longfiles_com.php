<?php

class dl_longfiles_com extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->lib->cookie = $this->lib->GetCookies($data);
		if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $data, $count)) 	
		$this->error($count[0], true, false);
		if(preg_match('@Wait <span id="\w+">(\d+)<\/span>@i', $data, $count) && $count[1] > 0) 
		sleep($count[1]);
		if(!stristr($data, "<h3>Download File</h3>"))
		$this->error("Cannot get Download File", true, false, 2);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data)) 	return trim($this->redirect);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* longfiles Download Plugin by giaythuytinh176 [12.8.2013]
* Downloader Class By [FZ]
*/
?>