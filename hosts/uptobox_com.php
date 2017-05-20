<?php
class dl_uptobox_com extends Download {
   
	public function CheckAcc($cookie){
        $data = $this->lib->curl("http://uptobox.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium-Account expire')) return array(true, "Until ".$this->lib->cut_str($data, '>Premium-Account expire:', '</'));
        else if(stristr($data, 'My affiliate link:') && !stristr($data, 'Premium-Account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    public function Login($user, $pass){
		$data = $this->lib->curl("https://login.uptobox.com/", "lang=english", "op=login&login={$user}&password={$pass}&redirect=");
		$cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form', '</form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/www\d+\.uptobox.com\/d\/[^\'\"\s\t<>\r\n]+@i', $data, $link)) return trim(str_replace('https', 'http', $link[0]));
		}
		if(stristr($data,'type="password" name="password'))  $this->error("reportpass", true, false);
		elseif (stristr($data,'The file was deleted by its owner') || stristr($data,'Page not found / La page')) $this->error("dead", true, false, 2);
		elseif (!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/www\d+\.uptobox.com\/d\/[^\'\"\s\t<>\r\n]+@i', $data, $link)) return trim(str_replace('https', 'http', $link[0]));
		}
		else return trim(str_replace('https', 'http', trim($this->redirect))); 
		return false;
    }
}
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uptobox Download Plugin
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [26.7.2013][18.9.2013][Fixed]
* Fixed Login: KulGuY [16.01.2015]
*/
?>