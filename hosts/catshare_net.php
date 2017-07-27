<?php

class dl_catshare_net extends Download {
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://catshare.net/account", $cookie, "");
		if(stristr($data, 'Premium')) return array(true, "Time left: ".str_replace("dni", "days", $this->lib->cut_str($data, 'remium</span>','</a>')));
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://catshare.net/login", "", "user_email={$user}&user_password={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
	public function Leech($url) {        
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stristr($data,"Nie ma takiej strony")) $this->error("dead", true, false, 2);
		elseif ($this->isredirect($data)) return trim($this->redirect);
		elseif (preg_match('/<form action="(.+)" method="GET">/', $data, $match)) return trim($match[1]);
		return false;
	}
}
 
/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.2 Dev
* Catshare.net Download Plugin  
* Date: 24.07.2017
*/
?>