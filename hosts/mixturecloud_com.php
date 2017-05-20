<?php

class dl_mixturecloud_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://www.mixturecloud.com/account", "mx_l=en;{$cookie}", "");
        if(stristr($data, '>Unlimited</h1><h4><span title')) return array(true, "Until ".$this->lib->cut_str($data, 'Unlimited</h1><h4><span title="','">('));
        else if(stristr($data, '>Basic</h1><h4>') && !stristr($data, '>Unlimited</h1>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
/* 
	public function Login($user, $pass){		
        $data = $this->lib->curl("https://www.mixturecloud.com/", "mx_l=en;{$user}={$pass}", "");	
		return "{$user}={$pass}; {$this->lib->GetCookies($data)}";
    }*/
   
    public function Login($user, $pass){
        $data = $this->lib->curl("https://www.mixturecloud.com/", "mx_l=en", "");
		$securecode = $this->lib->cut_str($this->lib->cut_str($data, 'method="post" action="login">','Sign in<'), 'name="securecode" value="', '" />');
		$data = $this->lib->curl("https://www.mixturecloud.com/login", "mx_l=en", "back=&securecode={$securecode}&email={$user}&password={$pass}&login=1");
        $cookie = "{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$url = str_replace("mixturecloud.com/video=", "mixturecloud.com/media/download/", $url);
		$url = str_replace("http://", "https://", $url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post["password"] = $pass;
			$post["submit"] = 'Confirm';
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data, 'The password is wrong'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('%href="(https?:\/\/.+)" class%U', $this->lib->cut_str($data, 'Display download', 'Download now'), $giay))	return trim($giay[1]);
		}
		if(stristr($data,'>Access protected by password<')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'Sorry, but the page you were trying to view does not exist')) $this->error("dead", true, false, 2);
		elseif(!preg_match('%href="(https?:\/\/.+)" class%U', $this->lib->cut_str($data, 'Display download', 'Download now'), $giay)) {
			$code = $this->lib->cut_str($data, '<a href="media/download/', '" class="btn btn-mc">');
			$url = "https://www.mixturecloud.com/media/download/{$code}";
			$data = $this->lib->curl($url, $this->lib->cookie, "");
			if(preg_match('%href="(https?:\/\/.+)" class%U', $this->lib->cut_str($data, 'Display download', 'Download now'), $giay)) return trim($giay[1]);
		}
		else return trim($giay[1]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Mixturecloud.com Download Plugin by giaythuytinh176 [15.9.2013]
* Downloader Class By [FZ]
*/
?>