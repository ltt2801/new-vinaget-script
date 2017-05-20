<?php

class dl_egofiles_com extends Download {

    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://egofiles.com", "lang=en;".$cookie, "");
        if(stristr($data, 'Free User')) return array(false, "accfree");
        elseif(stristr($data, 'Premium')) return array(true, "accpremium");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://egofiles.com/ajax/register.php", "lang=en", "log=1&loginV={$user}&passV={$pass}");
        $cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
    }
    
	public function Leech($url) {
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        if (stristr($data,"404 File not found")) $this->error("dead", true, false, 2);
        elseif($this->isredirect($data)) return trim($this->redirect);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Egofiles Download Plugin by djkristoph
* Downloader Class By [FZ]
* Fixed check account by giaythuytinh176 [7.8.2013]
*/
?>