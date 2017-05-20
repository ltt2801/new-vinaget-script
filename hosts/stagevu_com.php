<?php

class dl_stagevu_com extends Download {

    public function CheckAcc($cookie){	// Free Account only
        $data = $this->lib->curl("http://stagevu.com", "{$cookie}", "");
        if(stristr($data, 'Welcome, <a href="http://stagevu.com')) return array(true, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){ 
        $page = $this->lib->curl("http://stagevu.com/ajax/login.php", "", "un={$user}&pw={$pass}&shared=0");
        $cookie = $this->lib->GetCookies($page);
		return $cookie;
    }
/*	
    public function FreeLeech($url) {
		if(preg_match('@http:\/\/stagevu\.com\/embed(.+)uid=(.*)@', $url, $fileID))
		$url = "http://stagevu.com/video/{$fileID[2]}";
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, ">Error: No video with the provided information exists<") || stristr($data, "The video you are attempting to view has been removed<")) $this->error("dead", true, false, 2);
		$data = $this->lib->cut_str($data, '<div id="vidbox">', '</script>');
		if(preg_match("/= '(https?:\/\/.+stagevu\.com\/.+)';/i", $data, $link))	return trim($link[1]);
		return false;
	}	*/

    public function Leech($url) {
		if(preg_match('@http:\/\/stagevu\.com\/embed(.+)uid=(.*)@', $url, $fileID))
		$url = "http://stagevu.com/video/{$fileID[2]}";
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, ">Error: No video with the provided information exists<") || stristr($data, "The video you are attempting to view has been removed<")) $this->error("dead", true, false, 2);
		$data = $this->lib->cut_str($data, '<div id="vidbox">', '</script>');
		if(preg_match("/= '(https?:\/\/.+stagevu\.com\/.+)';/i", $data, $link))	return trim($link[1]);
		return false;
	}	
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Stagevu.com Download Plugin by giaythuytinh176 [16.9.2013]
* Downloader Class By [FZ]
*/
?>