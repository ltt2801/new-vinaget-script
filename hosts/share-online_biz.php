<?php

class dl_share_online_biz extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://www.share-online.biz/user/profile", "page_language=english;".$cookie, "");
		$dt = $this->lib->cut_str($data, ">Account valid until:", ">Registration date");
        if(stristr($data, 'Premium        </p>') && stristr($dt, "<span class='red'>")) 	return array(true, "Until ".$this->lib->cut_str($dt, "<span class='red'>", '</span>'));
		elseif(stristr($data, 'Premium        </p>') && stristr($dt, "<span class='green'>"))	 return array(true, "Until ".$this->lib->cut_str($dt, "<span class='green'>", '</span>'));
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$data = $this->lib->curl("https://www.share-online.biz/user/login", "animations=1;newsscrl=1;page_language=english", "user={$user}&pass={$pass}&l_rememberme=1&submit=Log%20in");
        $cookie = "animations=1;newsscrl=1;page_language=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }

    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, 'The requested file is not available')) $this->error("dead", true, false, 2);
		elseif ($this->isredirect($data)) return trim($this->redirect);
		elseif(preg_match('/var dl="(.*)";var file/', $data, $en64)) {
			$link = base64_decode($en64[1]);
			return trim($link);
		}
		return false;
    }
	
}

/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.3 LTSB
* Share-online.biz Download Plugin  
* Date: 02.12.2017
*/
?>