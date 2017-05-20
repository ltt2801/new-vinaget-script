<?php

class dl_expressleech_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://expressleech.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'http://expressleech.com/?op=payments">Upgrade to premium')) return array(false, "accfree");
        elseif(stristr($data, 'Premium Account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium Account expire:</TD><TD><b>','</b>'));
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://expressleech.com/login.html", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
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
			$link = $this->lib->cut_str($this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			return trim($link);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data, "The file was deleted by its owner ")) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$link = $this->lib->cut_str($this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			return trim($link);
		}
		else  	return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Expressleech Download Plugin by riping
* Downloader Class By [FZ]
* Fixed by giaythuytinh176 [12.9.2013]
*/
?>