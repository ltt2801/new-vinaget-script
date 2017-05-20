<?php

class dl_depositfiles_com extends Download { 

	public function CheckAcc($cookie){
		$domain = $this->lib->cut_str($this->getredirect("http://depositfiles.com/"), "//", "/");
		$data = $this->lib->curl("http://{$domain}/gold/payment_history.php", "lang_current=en;{$cookie}", "");
		if(stristr($data, 'You have Gold access until:'))  {
			$checksubscribe = $this->lib->curl("http://{$domain}/gold/payment_subscribe_manage.php", "lang_current=en;{$cookie}", "");
			return array(true, "Until ".$this->lib->cut_str($data, '<div class="access">You have Gold access until: <b>','</b></div>') ."<br/> ". (strpos($checksubscribe, '>You are subscribed to automatically') ? "You are subscribed" : "You are not subscribed"));
		}
		elseif(stristr($data, 'Your current status: FREE - member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
		
	public function Login($user, $pass){
		$this->error("notsupportacc", true, false);
		return false;
	}
	/*
    public function Leech($url) {
		$url = preg_replace("@(depositfiles\.org|depositfiles\.net|dfiles\.eu|dfiles\.ru)@", "depositfiles.com", $url);
		list($url, $pass) = $this->linkpassword($url);
		list($name, $domain) = explode(".", $this->lib->cut_str(str_replace("www.", "", $url), "http://", "/"));
		$url = $this->getredirect($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($pass) {
			$post["file_password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data, 'The file\'s password is incorrect'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/fileshare\d+\.'.$name.'\.'.$domain.'\/auth\-[^"\'<>\r\n\t]+@i', $data, $link))
			return trim($link[0]);
		}
		if(stristr($data, "You have exceeded the")) $this->error("LimitAcc");
		elseif(stristr($data,'Please, enter the password for this file')) $this->error("reportpass", true, false);
		elseif(stristr($data, "it has been removed due to infringement of copyright") || stristr($data, "Such file does not exist")) $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/fileshare\d+\.'.$name.'\.'.$domain.'\/auth\-[^"\'<>\r\n\t]+@i', $data, $link))
		return trim($link[0]);
		return false;
		//if(preg_match('%<a href="(https?:\/\/fileshare.+depositfiles\.com\/auth.+)" onClick%U', $data, $redir2))
    }	*/

    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$domain = $this->lib->cut_str($this->getredirect("http://depositfiles.com/"), "//", "/");
		$tachid = explode("/", $url);  
		if(preg_match("/\/files\/(.*)\/(.+)/i", $url, $id)) $DFid = $id[1];
		elseif(count($tachid) == 5)  $DFid = $tachid[4];
		else $DFid = $tachid[5];
		$data = $this->lib->curl("http://depositfiles.com/api/download/file?file_id={$DFid}&file_password={$pass}", "lang_current=en;".$this->lib->cookie, "", 0);
		$page = json_decode($data, true);
		if($page['status'] !== "OK" && $page['error'] == "FileIsPasswordProtected")  $this->error("reportpass", true, false);
		elseif($page['status'] !== "OK" && $page['error'] == "FileDoesNotExist")   $this->error("dead", true, false, 2);
		elseif($page['status'] !== "OK" && $page['error'] == "FilePasswordIsIncorrect")   $this->error("wrongpass", true, false, 2);
		elseif($page['status'] == "OK" && isset($page['data']['download_url'])) return trim(str_replace("depositfiles.com", $domain, $page['data']['download_url']));
		else $this->error($page['error'], true, false);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Depositfiles.com Download Plugin  
* Date: 16.7.2013
* Fix download by giaythuytinh176 [21.7.2013]
* Fixed check account by giaythuytinh176 [24.7.2013]
* Add support file password by giaythuytinh176 [29.7.2013]
* Fix domain conflict, miss CheckAcc by Fz [24.11.2013]
* Fixed link by giay [29.11.2013]
*/
?>