<?php		// Use account Free only.

class dl_upafile_com extends Download {
    
    public function CheckAcc($cookie){		
        $data = $this->lib->curl("http://upafile.com/?op=my_account", "lang=english;{$cookie}", "");
        //if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b>'));
        if(stristr($data, 'My published files link') && !stristr($data, 'Premium account expire:')) return array(true, "accfree");
		else return array(false, "accinvalid");
    }
	
    public function Login($user, $pass){
        $data = $this->lib->curl("http://upafile.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://upafile.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		if($pass) {
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post1["method_free"] = "Free Download";
			$post1['password'] = $pass;
			$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
			if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(!stristr($data, 'value="Create Download Link'))   $this->error("Cannot get Create Download Link", true, false);
		else {
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post1["method_free"] = "Free Download";
			$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
			if(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
			return trim($giay[0]);
		}
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* upafile Download Plugin by giaythuytinh176 [30.8.2013]
* Downloader Class By [FZ]
*/
?>