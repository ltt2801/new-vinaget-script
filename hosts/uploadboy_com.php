<?php		 

class dl_uploadboy_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://uploadboy.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'Payment info') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://uploadboy.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://uploadboy.com/");
		$cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }

    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		if(!stristr($data, 'method_free" value="  Free Download  "'))
		$this->error("Cannot get Free Download", true, false);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" action=', '<table border="0" width="600" cellspaci'));
			$post['method_free'] = '  Free Download  ';
			$post['method_premium'] = '';
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->save($this->lib->GetCookies($data));
			if($pass) {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$post1['password'] = $pass;
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif(preg_match('@https?:\/\/(\w+\.)?uploadboy\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
			if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
			elseif(!stristr($data, 'value="Create Download Link'))   $this->error("Cannot get Create Download Link", true, false);
			else {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(preg_match('@https?:\/\/(\w+\.)?uploadboy\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
		}
		return false;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		if($pass) {
			$post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post0["password"] = $pass;
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(stristr($data0,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/(\w+\.)?uploadboy\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data0, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
        elseif(!$this->isredirect($data)) {
		    $post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(preg_match('@https?:\/\/(\w+\.)?uploadboy\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data0, $giay))
			return trim($giay[0]);
		}
		else  
		return trim($this->redirect);
		return false;
    } 
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* uploadboy Download Plugin by giaythuytinh176 [14.8.2013]
* Downloader Class By [FZ]
*/
?>