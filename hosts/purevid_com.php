<?php

class dl_purevid_com extends Download {
   
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.purevid.com/?m=video_manager", $cookie, "");
		$dt = $this->lib->curl("http://www.purevid.com/?m=login&action=autologin&r=http://www.purevid.com/?m=main", $cookie, "");
        if(stristr($data, '><span>premium</span> until')) return array(true, $this->lib->cut_str($data, '><span>premium</span>', '<br />'));
        else if(stristr($data, '/><span>regular</span>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	 
    public function Login($user, $pass){
		$data = $this->lib->curl("http://www.purevid.com/", "", "");
		$cookies = $this->lib->GetCookies($data);
        $data = $this->lib->curl("http://www.purevid.com/?m=login", $cookies, "username={$user}&password={$pass}&remember=yes");
		$cookie = $cookies.";".$this->lib->GetCookies($data);
		return $cookie;
    }
	
    public function Leech($url) {
 		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$dt = $this->lib->curl("http://www.purevid.com/?m=login&action=autologin&r={$url}", $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($dt));
		if(stristr($data, "this video has been deleted by the user or") || stristr($data, "404 - Not Found")) $this->error("dead", true, false, 2);
		$link = $this->lib->cut_str($this->lib->cut_str($data, '<div class="video-bg">', '</div>'), 'frameBorder="0" src="', '"></iframe>');
		$data = $this->lib->curl($link, $this->lib->cookie, "");
		$link = str_replace("%26", "&", $this->lib->cut_str($data, '<param value="config=', '" name="flashvars"/>'));
		$data = $this->lib->curl($link, $this->lib->cookie, "", 0);
		$page = json_decode($data, true);
		return trim($page['clip']['downloadUrl']);
		return false;
    }	
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Purevid.com Download Plugin by giaythuytinh176 [15.9.2013]
* Downloader Class By [FZ]
*/
?>