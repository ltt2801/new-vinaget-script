<?php

class dl_bayfiles_net extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://bayfiles.net/account", $cookie, "");
		if(stristr($data, '<p>Premium</p>') && stristr($data, 'Expiration date:')) return array(true, "Until ".$this->lib->cut_str($data, '<p class="help-text">Expiration date:', '<a href="http://www.vouchers.io"><b>(Extend)</b></a></p>'));
		else if(stristr($data, '<p class="account-label">Account status</p>') && stristr($data, '<p>Normal</p>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	
	public function Login($user, $pass){
		$post["username"]= $user;
		$post["password"]= $pass;
		$post["next"] = "%2F";
		$post["action"] = "login";
		$data = $this->lib->curl("http://bayfiles.net/ajax_login", "", $post);				
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}

	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
  		if (stristr($data,"<p>Invalid security token. Please check your link.</p>"))   $this->error("dead", true, false, 2);
		elseif (stristr($data,"<p>The requested file could not be found.</p>"))   $this->error("dead", true, false, 2);
		else {
			$link = $this->lib->cut_str($this->lib->cut_str($data, '<div style="text-align: center;">', '</div>'), '<a class="highlighted-btn" href="', '">Premium Download</a>');
			return trim($link);	
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Bayfiles.net Download Plugin 
* Downloader Class By [FZ]
* Plugin By giaythuytinh176
* Date: 22.7.2013
* Fix check file not avaiable by giaythuytinh176 [23.7.2013] 
*/
?>