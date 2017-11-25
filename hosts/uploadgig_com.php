<?php

class dl_uploadgig_com extends Download { 

	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://uploadgig.com/user/my_account", $cookie, "");
		if (strstr($data, '<dd class="text-success">Active</dd>')) return array(true, "Until ".strip_tags($this->lib->cut_str($data, '<dt>Package expire date:</dt>','<span')) ."<br/> Bandwidth Left: ".strip_tags($this->lib->cut_str($data, '<dt>Daily traffic usage:</dt>','<span')));
		elseif (strstr($data, '<dt>Premium download:</dt>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
		
	public function Login($user, $pass){
		$data = $this->lib->curl("https://uploadgig.com/login/form","","");
		$cookie = $this->lib->GetCookies($data);
		if (preg_match('/<input type="hidden" name="csrf_tester" value="(.*?)"/', $data, $match)) $csrf_tester = $match[1];
		$data = $this->lib->curl("https://uploadgig.com/login/do_login", $cookie, "csrf_tester={$csrf_tester}&email={$user}&pass={$pass}&rememberme=1");

		return $this->lib->GetCookies($data);
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stristr($data, '<h2>File not found</h2>')) $this->error("dead", true, false, 2);
		elseif (stristr($data, 'bandwidth')) $this->error("LimitAcc");
		elseif ($this->isredirect($data)) return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.3 LTSB
* Uploadgig.com Download Plugin  
* Date: 25.11.2017
*/
?>