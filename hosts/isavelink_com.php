<?php

class dl_isavelink_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://isavelink.com/?op=payments", "lang=english;{$cookie}", "");
        if(stristr($data, '<b>Premium account expire:</b><br>')) return array(true, "Until ".$this->lib->cut_str($data, '<b>Premium account expire:</b><br>','<br><br>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://isavelink.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://isavelink.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
    
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif(!$this->isredirect($data)) {
				$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
				return trim($giay);
			}
			else
			return trim($this->redirect);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			return trim($giay);
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
* Isavelink Download Plugin by giaythuytinh176 [31.7.2013]
* Downloader Class By [FZ]
*/
?>