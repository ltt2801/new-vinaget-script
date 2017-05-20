<?php		 

class dl_queenshare_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.queenshare.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'Payment info') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.queenshare.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.queenshare.com/");
		$cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }

    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, 'method_free" value="Free Download')){
			$post = $this->parseForm($this->lib->cut_str($data, '<form method="POST" action=\'\'>', '<table align="center" cellspacing="10">'));
			$post['method_free'] = 'Free Download';
			$post['method_premium'] = '';
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->save($this->lib->GetCookies($data));
			if($pass) {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', 'value="Create Download Link">'));
				$post1['password'] = $pass;
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				if(preg_match('@http:\/\/ww\d+\.queenshare\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
			if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $data, $count)) 	$this->error($count[0], true, false);
			elseif(stristr($data,'type="password" name="password'))  $this->error("reportpass", true, false);
			elseif(!stristr($data, 'value="Create Download Link')) 
			$this->error("Cannot get Create Download Link", true, false);
			else {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', 'value="Create Download Link">'));
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(preg_match('@http:\/\/ww\d+\.queenshare\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
		}
		return false;
	}		
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post0["password"] = $pass;
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(stristr($data0,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@http:\/\/ww\d+\.queenshare\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data0, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
		    $post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '</Form>'));
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(preg_match('@http:\/\/ww\d+\.queenshare\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data0, $giay))
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
* queenshare Download Plugin by giaythuytinh176 [19.8.2013]
* Downloader Class By [FZ]
* Special thanks to test500@rapidleech.com for your Queenshare account.
*/
?>