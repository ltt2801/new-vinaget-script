<?php

class dl_shareflare_net extends Download {

    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://shareflare.net/ajax/get_attached_passwords.php",  "lang=en;".$cookie, "");
        if(stristr($data, '<th>Premium account</th>') && stristr($data, '<th>Date of expiry</th>')) {
			if(preg_match('@\d+\-\d+\-\d+@', $data, $giay))
			if(preg_match('/td>(\d+\.\d+)<\/td/', $data, $thuytinh))  	
			return array(true, "Until ".$giay[0]." and Points ".$thuytinh[1]);
		}
		else if(stristr($data, 'There are no attached premium accounts found')) return array(false, "accfree");
        else return array(false, "accinvalid");
    }

    public function Login($user, $pass){
        $data = $this->lib->curl("http://shareflare.net/", "lang=en", "act=login&login={$user}&password={$pass}");
        $cookie = "lang=en;{$this->lib->GetCookies($data)}";
        return $cookie;
    }
	
    public function Leech($url) {
		if(!stristr($url, "http://shareflare.net")) $url = preg_replace("/(u\d+(\.s\d+)?\.)/", "", $url);	
		$this->lib->cookie = preg_replace("/; PHPSESSID=[a-z0-9]+;/", "", $this->lib->cookie);	
		$data1 = $this->lib->curl($url, "lang=en;".$this->lib->cookie, "");
		if(stristr($data1,'Please wait, there is a file search') || stristr($data1,'File not found') || stristr($data1,'The file is temporarily unavailable for download')) 
		$this->error("dead", true, false, 2);
		$this->save("lang=en;".$this->lib->GetCookies($data1));
		if(!preg_match('@https?:\/\/u\d+\.(s\d+\.)?shareflare\.net\/download\/[^"\'><\r\n\t]+@', $data1, $thuytinh)) 
		$this->error("Cannot get Check2", true, false, 2); 
		else {
			$check2 = trim($thuytinh[0]);
			$data2 = $this->lib->curl($check2, "lang=en;".$this->lib->cookie, "");
			if(!preg_match('@https?:\/\/u\d+\.(s\d+\.)?shareflare.net\/sms\/check2\.php@', $data2, $thuytinh)) 
			$this->error("Cannot get Check3", true, false, 2);	
			else {
				$check3 = trim($thuytinh[0]);
				$data3 = $this->lib->curl($check3, "lang=en;".$this->lib->cookie, "");
				if(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[a-z0-9]+\/[^"\'><\r\n\t]+@i', $this->lib->cut_str($data3, 'direct_link_1', 'direct_link_2'), $giay176))
				return trim($giay176[0]);
			}
		} 
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* shareflare.net Download Plugin by giaythuytinh176 [30.8.2013]
* Downloader Class By [FZ]
*/
?>