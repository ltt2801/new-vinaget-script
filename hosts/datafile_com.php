<?php

class dl_datafile_com extends Download {
  
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.datafile.com/profile.html", "lang=en;{$cookie}", "");
		if(stristr($data, '>Premium Expires:<')) return array(true, "Until " .$this->lib->cut_str($data, '<td class="el" >',  '&nbsp; ('). "<br/>On the left today: " .$this->lib->cut_str($this->lib->cut_str($data, 'On the left today:</td>',  '</tr>'), '<td>', '</td>'));
		else if(stristr($data, '">Upgrade</a></span>)')) return array(false, "accfree"); 
		else return array(false, "accinvalid"); 
	}
  
	public function Login($user, $pass){
		$data = $this->lib->curl("https://www.datafile.com/login.html", "lang=en", "login={$user}&password={$pass}&remember_me=1");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
  
	public function Leech($url) {
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if($this->isredirect($data)) {
			$link = trim("http://www.datafile.com" .$this->redirect);
			$data = $this->lib->curl($link,$this->lib->cookie,"");
			if(stristr($data, "ErrorCode 6: Download limit in")) $this->error("LimitAcc", true, false);
			if($this->isredirect($data)) $redir = trim($this->redirect); 
			$name = $this->lib->getname($redir, $this->lib->cookie);
			$tach = explode(';', $name);
			$this->lib->reserved['filename'] = $tach[0];
			return $redir;
		}
		elseif(stristr($data,'ErrorCode 0: Invalid Link')) $this->error("dead", true, false, 2); 
		return false;
	}
	
}
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* DataFile.com Download Plugin by giaythuytinh176
* Downloader Class By [FZ]
* Date: 20.7.2013
* Fix check account by giaythuytinh176 [21.7.2013]
* Fix check account by giaythuytinh176 [6.8.2013]
*/
?>