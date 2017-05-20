<?php

class dl_exclusivefaile_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://exclusivefaile.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'font-size:17px;">Premium account expire: ', '</td>'));
        else if(stristr($data, 'E-mail') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://exclusivefaile.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
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
			elseif(preg_match('@https?:\/\/(\w+\.)?exclusivefaile\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
			return trim($giay[0]);
		}
        if($this->isredirect($data)) return trim($this->redirect);
        elseif(stristr($data,'Downloads are disabled for your country:')) $this->error("blockCountry", true, false);
		elseif(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/(\w+\.)?exclusivefaile\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
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
* Exclusivefaile Download Plugin by giaythuytinh176 [31.7.2013]
* Downloader Class By [FZ]
*/
?>