<?php

class dl_dizzcloud_com extends Download {
   
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://dizzcloud.com/", $cookie, "");
		if(stristr($data, ">Premium till ")) return array(true, "Until" .$this->lib->cut_str($data, ">Premium till ", "&nbsp;&nbsp;<"));
		elseif(stristr($data, "http://dizzcloud.com/logout") && !stristr($data, "Premium till")) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
   
    public function Login($user, $pass){
        $data = $this->lib->curl("http://dizzcloud.com/login", "", "email={$user}&pass={$pass}");
		return "{$this->lib->GetCookies($data)}";
    }
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, "File not found or deleted")) $this->error("dead", true, false, 2);
		elseif(stristr($data, ">You have exceeded the daily limit on downloads<")) $this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data))  {
			$data = $this->lib->curl($url, $this->lib->cookie, "getlnk=1", 0, 1);
			$page = json_decode($data);
			return trim($page->msg);
		}
		else return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dizzcloud.com Download Plugin by giaythuytinh176 [15.11.2013]
* Thanks to Cik-iNaR™@rapidleech.com for your account.
* Downloader Class By [FZ]
*/
?>