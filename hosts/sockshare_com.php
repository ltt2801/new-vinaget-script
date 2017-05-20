<?php

class dl_sockshare_com extends Download {  // code same with putlocker.com :D
	
	public function PreLeech($url) {
		$url = str_replace("http://sockshare.com", "http://www.sockshare.com", $url);
		$url = str_replace("/embed/", "/file/", $url);
	}
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.sockshare.com/profile.php?pro", "{$cookie}", "");
        if(stristr($data, 'profile.php?pro" class="logout_link">Pro Status</a>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<td>Expiring </td>','</tr>'), '<td>','</td>'));
        else if(stristr($data, '<td>Free Account - <strong><a')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){		// use cookie auth= 
		$this->error("notsupportacc");
		return false;
	}
	
	public function FreeLeech($url){
		$url = $this->getredirect($url);
		if (stristr($url,'404')) $this->error("dead", true, false, 2);
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, "", "");
		$this->lib->cookie = $this->lib->GetCookies($data);
		$hash = $this->lib->cut_str($data, '<input type="hidden" value="','" name="hash">');
		if(stristr($data,'name="captcha_code"'))  $this->error("Captcha required!", true, false);
		if(preg_match('@var countdownNum = (\d+);@i', $data, $count) && $count[1] > 0) 
		sleep($count[1]+1);
		$data = $this->lib->curl($url, $this->lib->cookie, "hash={$hash}&confirm=Continue as Free User");
		if($pass) {
			$post["file_password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->lib->cookie .= ";".$this->lib->GetCookies($data);
			$id = $this->lib->cut_str($data, '<a href="/get_file.php?id=','"');
			$data = $this->lib->curl("http://www.sockshare.com/get_file.php?id=".trim($id),$this->lib->cookie,"");
			if($this->isredirect($data)) return trim($this->redirect);
			return false;
		}
		if(stristr($data,'This file requires a password. Please enter it.')) 	$this->error("reportpass", true, false);
		if(stristr($data,'You have exceeded the daily download limit for your country')) 	$this->error("LimitAcc", true, false);
		$this->lib->cookie .= ";".$this->lib->GetCookies($data);
		$id = $this->lib->cut_str($data, '<a href="/get_file.php?id=','"');
		$data = $this->lib->curl("http://www.sockshare.com/get_file.php?id=".trim($id),$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		return false;
    }	
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$url = $this->getredirect($url);
		if (stristr($url,'404'))  $this->error("dead", true, false, 2);
		$data =  $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post["file_password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('/<a href="(\/get_file\.php\?id=.+)" class/', $data, $id)) 
			$data = $this->lib->curl("http://www.sockshare.com".trim($id[1]),$this->lib->cookie,"");
			if($this->isredirect($data)) return trim($this->redirect);
		}
		if(stristr($data, "This file requires a password")) $this->error("reportpass", true, false);
		elseif(preg_match('/<a href="(\/get_file\.php\?id=.+)" class/', $data, $id)) 
		$data = $this->lib->curl("http://www.sockshare.com".trim($id[1]),$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* sockshare Download Plugin by giaythuytinh176 [30.8.2013]
* Downloader Class By [FZ]
*/
?>