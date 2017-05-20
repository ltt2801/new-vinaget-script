<?php

class dl_uploadhero_co extends Download {
    
    public function CheckAcc($cookie){
		$data = $this->lib->curl("http://uploadhero.co/my-account", $cookie, "");
		if(stristr($data, 'Premium days.')) return array(true, $this->lib->cut_str($data, 'You still have <span class="bleu">', '</span><br />')." days");
		elseif(!stristr($data, '<a href="/logout"')) return array(false, "accinvalid");
		else return array(false, "accfree");
	}
    
    public function Login($user, $pass){
		$data = $this->lib->curl("http://uploadhero.co/lib/connexion.php","lang=en","pseudo_login={$user}&password_login={$pass}");
		if(!stristr($data, '<div id="cookietransitload"')) return false;
		$uh = $this->lib->cut_str($data, 'style="display:none;">','</div>');
		$cookie = "uh={$uh};directdl=on;lang=en;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
    
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, "No such file with this filename</font>")) $this->error("dead", true, false, 2);
		elseif(stristr($data, "http://uploadhero.co/forbbiden")) $this->error("blockIP", true, false);
		elseif(preg_match('@https?:\/\/(\w+\.)?uploadhero\.co\/\?d=[^"\'><\r\n\t]+@i', $data, $link))
		return trim($link[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uploadhero Download Plugin by riping
* Downloader Class By [FZ]
*/
?>