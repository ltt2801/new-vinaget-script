<?php

class dl_megashares_com extends Download {
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://d01.megashares.com/myms.php", $cookie, "");
        if(stristr($data, 'Premium User</span>') && stristr($data, 'Period Ends')) return array(true, "Until ".$this->lib->cut_str($data, '<p class="premium_info_box">Period Ends: ','</p>'));
        else if(stristr($data, 'Premium Upgrade</span>') && !stristr($data, 'Premium User</span>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://d01.megashares.com/myms_login.php", "", "httpref=&mymslogin_name={$user}&mymspassword={$pass}&myms_login=Login");				
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		if(stristr($url, "megashares.com/dl/")) {
			$ex =  explode("/", $url); 
			$url = "http://d01.megashares.com/index.php?d01=".$ex[4];
		}
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			if(preg_match('%action="([^"]+)"%U', $data, $urlp))  
			$urlpass = 'http://d01.megashares.com'.$urlp[1];
			$post["passText"] = $pass;
			$data = $this->lib->curl($urlpass, $this->lib->cookie, $post);
			if(stristr($data,'Password incorrect! Please provide'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/(\w+)?\.megashares\.com\/index\.php\?d[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'This link requires a password to continue')) 	$this->error("reportpass", true, false);
		elseif (stristr($data,"Invalid Link") || stristr($data,"Link has been deleted") || stristr($data,"Link is invalid")) $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/(\w+)?\.megashares\.com\/index\.php\?d[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Megashares Download Plugin 
* Downloader Class By [FZ]
* Add check account, Fixed download link by giaythuytinh176 [9.8.2013]
*/
?>