<?php

class dl_terafile_co extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://terafile.co/account.html", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium Expires:') && !stristr($data, 'No Data')) {
			$bw = $this->lib->curl("http://terafile.co/880470382875/WO-JAV.XV-885.part03.rar", "lang=english;{$cookie}", "");
			return array(true, "Until " .$this->lib->cut_str($data, 'Premium Expires:', '<a href=') . (strpos($bw, 'You have reached the download-limit:') ? '<br/>You have reached the download-limit: 25000 Mb for last 3 days' : ''));
        }
		else if(stristr($data, 'Current Password') && !stristr($data, 'Premium Expires:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://terafile.co/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
		return "lang=english;{$this->lib->GetCookies($data)}";
    }
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'>The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'You have reached the download-limit')) $this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data))  return trim($this->redirect);
		} 
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Terafile.co Download Plugin by giaythuytinh176 [22.1.2014]
* Downloader Class By [FZ]
*/
?>