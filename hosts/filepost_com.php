<?php

class dl_filepost_com extends Download {
	
	public function PreLeech($url){
		$data = $this->lib->curl($url,"lang=1","");
		if(stristr($data,'This IP address has been blocked')) $this->error("blockIP", true, false);
	}
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://filepost.com/", "lang=1;{$cookie}", "");
        if(stristr($data, '<span>Premium</span>')) return array(true, "Until ".$this->lib->cut_str($data, '<li>Valid until: <span>','</span>'));
        else if(stristr($data, 'Account type: <span>Free')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filepost.com/general/login_form/", "lang=1", "email={$user}&password={$pass}&remember=1");
		$cookie = "lang=1;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		if(stristr($url, "fp.io")) {
			$ex = explode("/", $url);
			$url = 'http://filepost.com/files/' .$ex[3];
		}
		$gach = explode('/', $url);
		if (count($gach) > 5) $url = 'http://filepost.com/files/' . $gach[4];
		$data = $this->lib->curl($url,"lang=1;".$this->lib->cookie,"");
		if(stristr($data,'Password is required to download this file')) 	$this->error("linkpass", true, false);
		elseif(stristr($data,'It may have been deleted by the uploader')) $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/fs\d+\.filepost\.com(:\d+)?\/get_file\/[^"\'><\r\n\t]+@i', $data, $link))
		return trim($link[0]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Filepost Download Plugin 
* Downloader Class By [FZ]
* Add check acc by giaythuytinh176 [19.8.2013]
* Thanks to Rapid61@rapidleech.com for your Filepost account.
*/
?>