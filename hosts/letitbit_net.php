<?php

class dl_letitbit_net extends Download {

    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://letitbit.net/ajax/get_attached_passwords.php",  "lang=en;".$cookie, "");
        if(stristr($data, '<th>Premium account</th>') && stristr($data, '<th>Date of expiry</th>')) {
			preg_match('@\d+\-\d+\-\d+@', $data, $giay);
			preg_match('/td>(\d+\.\d+)<\/td/', $data, $thuytinh); 	
			return array(true, "Until {$giay[0]} and Points {$thuytinh[1]}");
		}
		else if(stristr($data, 'There are no attached premium accounts found')) return array(false, "accfree");
        else return array(false, "accinvalid");
    }

    public function Login($user, $pass){
        $data = $this->lib->curl("http://letitbit.net/", "lang=en", "act=login&login={$user}&password={$pass}");
        $cookie = "lang=en;{$this->lib->GetCookies($data)}";
        return $cookie;
    }
	/*
    public function Leech($url) {
		if(!stristr($url, "http://letitbit.net")) $url = preg_replace("/(u\d+(\.s\d+)?\.)/", "", $url);	
		$this->lib->cookie = preg_replace("/; PHPSESSID=[a-z0-9]+;/", "", $this->lib->cookie);	
		$data = $this->lib->curl($url, "lang=en;".$this->lib->cookie, "");
		if(stristr($data,'Please wait, there is a file search') || stristr($data,'File not found') || stristr($data,'The file is temporarily unavailable for download'))  $this->error("dead", true, false, 2);
		$this->save("lang=en;".$this->lib->GetCookies($data));
		if(!preg_match('@https?:\/\/(.*)?letitbit\.net\/download\/[^"\'><\r\n\t]+@', $data, $redir)) 
		$this->error("Cannot get Check2", true, false); 
		else {
			$check2 = trim($redir[0]);
			$data = $this->lib->curl($check2, "lang=en;".$this->lib->cookie, "");
			if(!stristr($data,'letitbit.net/sms/check2.php'))  $this->error("dead", true, false, 2);
			if(!preg_match('@https?:\/\/(.*)?letitbit.net\/sms\/check2\.php@', $data, $redir1)) 
			$this->error("Cannot get Check3", true, false);	
			else {
				$check3 = trim($redir1[0]);
				$data = $this->lib->curl($check3, "lang=en;".$this->lib->cookie, "");
				if(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[a-z0-9]+\/[^"\'><\r\n\t]+@i', $this->lib->cut_str($data, 'direct_link_1', 'direct_link_2'), $link))
				return trim($link[0]);
			}
		} 
		return false;
    }*/
	
    public function Leech($url) {
		$this->lib->cookie = preg_replace("/(; PHPSESSID=(.+);)|(; download_link=(.+);)|(; appid=(.+);)|(; jspcid=(.+);)/", "", $this->lib->cookie);	
		if(strpos($url, "//letitbit.net")) { 
			$data = $this->lib->curl($url, $this->lib->cookie, "");
			if(strpos($data, "TTP/1.0 200 OK") || strpos($data, "TTP/1.1 200 OK") || strpos($data, "404 Not Found"))  $this->error("dead", true, false, 2);
			$this->save($this->lib->GetCookies($data));
			$redir = preg_replace("/\s+/", "", $this->lib->cut_str($data, "ocation: ", "\n"));
			$data = $this->lib->curl($redir, $this->lib->cookie, ""); 
			$redir2 = preg_replace("/\s+/", "", $this->lib->cut_str($data, "ocation: ", "\n"));
			$data = $this->lib->curl($redir2, $this->lib->cookie, "");
			return trim($this->lib->cut_str($data, 'file download" href="', '" style="font-size'));
		}
		else {
			$data = $this->lib->curl($url, $this->lib->cookie, "");
			$this->save($this->lib->GetCookies($data));
			if(!strpos($data, "letitbit.net/sms/check2.php"))  $this->error("dead", true, false, 2);
			$redir = preg_replace("/\s+/", "", $this->lib->cut_str($data, "ocation: ", "\n"));
			$data = $this->lib->curl($redir, $this->lib->cookie, "");
			return trim($this->lib->cut_str($data, 'file download" href="', '" style="font-size'));
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Letitbit Download Plugin by giaythuytinh176 [16.8.2013]
* Thanks to Rapid61@rapidleech.com for your account.
* Downloader Class By [FZ]
*/
?>