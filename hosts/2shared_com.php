<?php

class dl_2shared_com extends Download {
	
	public function FreeLeech($url){
		list($url, $pass) = $this->linkpassword($url);
		if(stristr($url, "/fadmin/"))  $this->error("dead", true, false, 2);
		$post = !empty($pass) ? "userPass2=".$pass : "";
		$data = $this->lib->curl($url, $this->lib->cookie, $post ? "userPass2={$pass}" : "");
		$this->save($this->lib->GetCookies($data));
		if (stristr($data,"The file link that you requested is not valid.") && stristr($data,"Please contact link publisher or try to make a search."))  $this->error("dead", true, false, 2);
		elseif (stristr($data,"Your free download limit is over."))  $this->error("LimitAcc", true, false);
		elseif (stristr($data,"Please enter password to access this file"))  $this->error("reportpass", true, false);
		elseif (preg_match ('/dc(\d+)\.2shared\.com\/download\/([^\'|\"|\<|\>|\r|\n]+)/i', $data, $link))  {
			$link = "http://dc" . $link[1] . ".2shared.com/download/" . $link[2];
			return trim($link);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 2Shared.com Download Plugin by giaythuytinh176 [24.7.2013]
* Downloader Class By [FZ]
*/
?>