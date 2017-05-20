<?php

class dl_filecloud_io extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("https://secure.filecloud.io/user-login_p.html", "lang=en", "username={$user}&password={$pass}");
		return "lang=en;{$this->lib->GetCookies($data)}";
	}
	
    public function Leech($url) {
		$data = $this->lib->curl('http://urlchecker.net/', "", 'links='.urlencode($url).'&submit=Check Links');
		$data = $this->lib->cut_str($data, '<td class="working">', 'class="live');
		if(stristr($data,'Working</td>'))	$url = $this->lib->cut_str($data, 'href="', '" target');
		else $this->error("dead", true, false, 2);
		$url = str_relace("ifile.it", "filecloud.io", $url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(empty($data) == true)  $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Bitshare.com Download Plugin
* Downloader Class By [FZ]
* filecloud.io Download Plugin By giaythuytinh176 [6.9.2013]
*/
?>