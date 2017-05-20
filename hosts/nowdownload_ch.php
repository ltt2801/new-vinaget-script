<?php

class dl_nowdownload_ch extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.nowdownload.ch/premium.php", $cookie, "");
        if(stristr($data, '>You are a premium user.')) return array(true, "Until ".$this->lib->cut_str($data, 'Your membership expires on', '. '));
        else if(stristr($data, 'nowdownload.ch/logout.php') && !stristr($data, '>You are a premium user.')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.nowdownload.ch/login.php", "", "user={$user}&pass={$pass}");
		return $this->lib->GetCookies($data);
    }
	
    public function Leech($url) {
 		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, 'This file does not exist!')) $this->error("dead", true, false);
		elseif(stristr($data, 'The file is being transfered. Please wait!')) $this->error("The file is being transfered. Please wait!", true, false);
		elseif(preg_match('/><a href="(https?:\/\/.*)" class="btn btn-danger">Click here/i', $data, $redir)) return trim($redir[1]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Nowdownload.ch Download Plugin by giaythuytinh176 [1.3.2014]
* Downloader Class By [FZ]
*/
?>