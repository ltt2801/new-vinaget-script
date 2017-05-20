<?php

class dl_uploading_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://uploading.com/general/login_form/?ajax","","email={$user}&password={$pass}&remember=on&back_url=http://uploading.com/");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stristr($data,'file not found')) $this->error("dead", true, false, 2);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif(strpos($page,"Your account premium traffic has been limited")) $this->error("LimitAcc");
		else {
			$code = trim($this->cut_str($page, 'code: "', '",' ));
			$pages = $this->curl("http://uploading.com/files/get/?ajax", $this->lib->cookie, "action=get_link&code={$code}&pass=false", 0, 0, $url, 1);
			$json = json_decode($pages);
			return $json->answer->link;
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uploading Download Plugin 
* Downloader Class By [FZ]
*/
?>