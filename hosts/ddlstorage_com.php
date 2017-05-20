<?php

class dl_ddlstorage_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.ddlstorage.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'my_premium_expiration" class="td_descr">','</td>'));
        else if(stristr($data, 'class="blu_title">Registered</p>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.ddlstorage.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" name="F1', '</form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))    $this->error("wrongpass", true, false, 2);
			elseif(preg_match('/onclick="parent.location=\'(http:\/\/\w+\.ddlstorage\.com:182\/d\/.+)\'">/', $data, $link))
			return trim($link[1]);
		}
		if(stristr($data,'type="password" name="password" style="width:210px')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner'))    $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" name="F1', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('/onclick="parent.location=\'(https?:\/\/.+)\'"></', $data, $link))
			return trim($link[1]);
		} 
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* DDLstorage.com Download Plugin by giaythuytinh176 [25.8.2013]
* Downloader Class By [FZ]
*/
?>