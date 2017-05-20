<?php

class dl_uppit_com extends Download {

    public function FreeLeech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		else {
			$post = array(
				'op' => 'download1',
				'usr_login' => 'admin',
				'id' => $this->lib->cut_str($data, 'id" value="', '">'),
				'fname' => $this->lib->cut_str($data, 'fname" value="', '">'),
				'referer' => 'http://uppit.com',
				'method_free' => ' Generate Link ',
			);
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('/a href="(http:\/\/srv\d+\.uppcdn\.com\/dl\/.+)" onClick/i', $data, $redir)) return trim($redir[1]);			
		} 
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uppit.com Download Plugin by giaythuytinh176 [24.2.2014]
* Downloader Class By [FZ]
*/
?>