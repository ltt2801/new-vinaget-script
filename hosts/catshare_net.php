<?php

class dl_catshare_net extends Download {
 
		public function CheckAcc($cookie){
			$data = $this->lib->curl("http://catshare.net", $cookie, "");
			if(stristr($data, 'Premium')) return array(true, "Until ".$this->lib->cut_str($data, '<strong>','</strong>'));
			else return array(false, "accinvalid");
		}
		
		public function Login($user, $pass){
			$data = $this->lib->curl("http://catshare.net/login", "", "user_email={$user}&user_password={$pass}");
			$cookie = $this->lib->GetCookies($data);
			return $cookie;
		}
		
		public function Leech($url) {        
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stristr($data,"Podany plik zosta")) $this->error("dead", true, false, 2);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif(preg_match('/<form action="(.+)" /', $data, $a)) {if(preg_match('/^http:/', $a[1])) return trim($a[1]);}
		return false;
		}
 
}
 
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Catshare Download Plugin by djkristoph
* Downloader Class By [FZ]
*/
?>