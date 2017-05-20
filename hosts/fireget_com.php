<?php

class dl_fireget_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://fireget.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</b></TD><TD style="padding-left:10px;">','</TD><TD>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://fireget.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
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
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(!$this->isredirect($data)) {
				$giay = $this->lib->cut_str($this->lib->cut_str($data, '<div style="padding-left:20px; padding-right:20px;">', '</a></div>'), 'href="', '" style');
				return trim($giay);
			}
			else  
			return trim($this->redirect);
		}
        if(stristr($data,'You have reached the download-limit:')) $this->error("LimitAcc", true, false);
		elseif(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			if(!stristr($data, "Create Download Link"))
			$this->error("Cannot get Create Download Link", true, false);
			else {
				$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
				$data = $this->lib->curl($url, $this->lib->cookie, $post);
				$giay = $this->lib->cut_str($this->lib->cut_str($data, '<div style="padding-left:20px; padding-right:20px;">', '</a></div>'), 'href="', '" style');
				return trim($giay);
			}
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
* Fireget Download Plugin by giaythuytinh176 [1.8.2013]
* Downloader Class By [FZ]
*/
?>