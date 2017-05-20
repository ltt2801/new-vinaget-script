<?php

class dl_hipfile_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://hipfile.com/?op=payments", "lang=english;{$cookie}", "");
        if(stristr($data, 'Extend Premium account')) return array(true, "Until ".$this->lib->cut_str($data, '<b>Premium account expire:</b><br>','<br><br>'));
        elseif(stristr($data, '<h2>Become a PREMIUM-Member</h2>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://hipfile.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://hipfile.com/login.html");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
        return $cookie;
    }
	
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!stristr($data, 'value="Free Download"'))
		$this->error("Cannot get Free Download", true, false, 2);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST"', '</Form>'));
			$post['method_free'] = 'Free Download';
			$post['method_premium'] = '';
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->save($this->lib->GetCookies($data));
			if(preg_match('@<span id="countdown_str"[^>]*>[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $data, $count) && $count[1] > 0) 
			sleep($count[1]);
			if($pass) {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$post1['password'] = $pass;
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif(preg_match('@https?:\/\/hf(\d+)?\.hipfile\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
			if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
			elseif(!stristr($data, 'value="File Download"'))   
			$this->error("Cannot get File Download", true, false);
			else {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(preg_match('@https?:\/\/hf(\d+)?\.hipfile\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
		}
		return false;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/hf(\d+)?\.hipfile\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
        elseif(stristr($data,'You have reached the download-limit:')) $this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/hf(\d+)?\.hipfile\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
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
* Hipfile Download Plugin by giaythuytinh176 [6.8.2013]
* Downloader Class By [FZ]
*/
?>