<?php

class dl_filesmonster_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://filesmonster.com/", "yab_ulang=en;".$cookie, "");
		if(stristr($data, 'Your membership type: <span class="em lightblack">Registered')) return array(false, "accfree");
		elseif(stristr($data, 'Your membership type: <span class="em lightblack">Premium') && !stristr($data, '>Expired:')) return array(true, "Until ".$this->lib->cut_str($data, '<span>Valid until: <span class=\'green\'>','</span><br /><input type='));
		elseif(stristr($data, "class='red em'>Expired:")) return array(false, "Account Expired!");
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("http://filesmonster.com/login.php", "yab_ulang=en", "act=login&user={$user}&pass={$pass}&captcha_shown=0&login=Login");
		$cookie = "yab_ulang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
  
	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'File not found') || stristr($data,'<h1 class="block_header">The link could not be decoded</h1>'))   
		$this->error("dead", true, false, 2); 
		elseif(stristr($data,'Today you have already downloaded')) $this->error("LimitAcc", true, false);
		elseif(preg_match('/href="(https?:\/\/filesmonster\.com\/get\/[^"\'><\r\n\t]+)">/', $data, $data1))  {
			$data2 = $this->lib->curl($data1[1], $this->lib->cookie, "");
			if(preg_match('/get_link\("([^"\'><\r\n\t]+)"\)/', $data2, $data3)) {
				$data4 = $this->lib->curl("http://filesmonster.com".$data3[1], $this->lib->cookie, "");
				if(preg_match('%url":"(https?:.+fmdepo.net.+)"%U', $data4, $giay))  {
					$giay = str_replace('\\', '', $giay[1]);
					$giay = str_replace("https", "http", $giay);
					return trim($giay);
				}
			}
		}
		return false;
	}
	
}
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Downloader Class By [FZ]
* FilesMonster.com Download Plugin by giaythuytinh176
* Date: 23.7.2013
*/
?>