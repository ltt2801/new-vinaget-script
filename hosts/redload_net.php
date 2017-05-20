<?php

class dl_redload_net extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://redload.net/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, '>Premium account expire:<')) return array(true, "Until ".$this->lib->cut_str($data, 'style="width:80px;"><b>', '</b></div>'). "<br/> Traffic available today: " .$this->lib->cut_str($this->lib->cut_str($data, 'Traffic available today', 'My Account Settings'), 'style="width:250px;"><b>', '</b></div>'));
        else if(stristr($data, 'Retype New password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://redload.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
		return "lang=english;{$this->lib->GetCookies($data)}";
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) return trim($this->redirect);
		}
		if(stristr($data,'text-align <br><b>Password:</b></div>')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'You have reached the download-limit')) $this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data)) return trim($this->redirect);
		} 
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Redload.net Download Plugin by giaythuytinh176 [15.9.2013]
* Downloader Class By [FZ]
*/
?>