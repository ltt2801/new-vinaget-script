<?php

class dl_subyshare_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://subyshare.com/?op=my_account", $cookie, "");
        if(stristr($data, 'Premium account expire:') && stristr($data, '<strong>PREMIUM</strong>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '>Premium account expire:','</span>'), ' <b>','</b>'));
		else if(stristr($data, '<strong>REGISTERED</strong> ') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://subyshare.com/", "lang=english;", "op=login&login={$user}&password={$pass}");
        $cookie = "lang=english; {$this->lib->GetCookies($data)}";
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
			elseif($this->isredirect($data))	return trim($this->redirect);
		}
		if(stristr($data,'<h2>File Not Found</h2>') || stristr($data,'<h3>The file was removed by administrator</h3>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'<small>Password:</small> <input class="bttn')) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data))	return trim($this->redirect);
		}
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Tusfiles Download Plugin by thanhduongdj [15.10.2014]
* Downloader Class By [FZ]
*/
?>