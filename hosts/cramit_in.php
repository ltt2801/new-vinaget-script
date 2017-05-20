<?php

class dl_cramit_in extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://cramit.in/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium Account expires on')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium Account expires on</TD><TD>', '</TD><TD class=right>'));
        else if(stristr($data, 'http://cramit.in/?op=logout') && !stristr($data, 'Premium Account expires on')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://cramit.in/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
		return "lang=english;{$this->lib->GetCookies($data)}";
    }
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, '>The file was deleted by its owner <')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$link = trim($this->lib->cut_str($data, '<BR><BR> <BR> <span class=green><b><a href="', '">CLICK'));
			$tach = explode('/', $link);
			$tach1 = explode('?st=', $tach[10]);
			$this->lib->reserved['filename'] = $tach1[0];
			return $link;
		} 
		else {
			$tach = explode('/', $this->redirect);
			$tach1 = explode('?st=', $tach[10]);
			$this->lib->reserved['filename'] = $tach1[0];
			return trim($this->redirect);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Cramit.in Download Plugin by giaythuytinh176 [27.1.2014]
* Downloader Class By [FZ]
*/
?>