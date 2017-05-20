<?php

class dl_crocko_com extends Download {

	public function PreLeech($url){
		if(stristr($url, "crocko.com/f/")) $this->error("Not Support Folder", true, false, 2);
	}

	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.crocko.com/accounts", "language=en;".$cookie, "");
		if(stristr($data, 'Premium membership: Active<br />') || stristr($data, 'Premium membership: Expiring<br />')) return array(true, "Until ".$this->lib->cut_str($data, 'Ends:  ',', in'));
		else if(stristr($data, '<h4>Premium account <strong>active</strong></h4>')) return array(true, "Until ".$this->lib->cut_str($data, 'End time:  ',', in'));
		else if(stristr($data, 'Premium membership: No premium')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
       
    public function Login($user, $pass){
		$post["login"]= $user;
		$post["password"]= $pass;
		$data = $this->lib->curl("https://www.crocko.com/accounts/login", "language=en", $post);                        
		$cookie = "language=en; {$this->lib->GetCookies($data)}";
		return $cookie;
	}
	
    public function Leech($url) {
		$url = str_replace('http://', 'https://', $url);
		if(stristr($url, "easy-share.com")) $url = str_replace("easy-share.com", "crocko.com", $url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if(stristr($data,">the page you're looking for<br") || stristr($data,"Please go to home page or one of this links") || stristr($data,"Information is not available at this time")) $this->error("dead", true, false, 2);
		elseif($this->isredirect($data)) return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Crocko.com Download Plugin By giaythuytinh176
* Fix by [FZ]
* Downloader Class By [FZ]
* Date: 18.7.2013
* Fixed login account by giaythuytinh176 [22.7.2013]
*/
?>