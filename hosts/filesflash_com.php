<?php

class dl_filesflash_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://filesflash.com/myaccount.php", "googtrans=/en/en;".$cookie, "");
		if(stristr($data, '<tr><td>Premium Status:</td><td>') && !stristr($data, 'Not Premium')) return array(true, "Until ".$this->lib->cut_str($data, '<tr><td>Premium Status:</td><td>',' (<a href="premium.php">Buy Premium</a>'));
		elseif(stristr($data, 'Not Premium')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filesflash.com/login.php", "googtrans=/en/en", "email={$user}&password={$pass}&submit=Submit");
		$cookie = "googtrans=/en/en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data, "That file has been deleted.")) $this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Filesflash Download Plugin by giaythuytinh176 [29.7.2013]
* Downloader Class By [FZ]
*/
?>