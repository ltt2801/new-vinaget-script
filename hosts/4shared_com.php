<?php

class dl_4shared_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.4shared.com/web/account/settings/overview", "4langcookie=en;{$cookie}", "");
		if(stristr($data, 'Account has already expired'))  return array(false, "Account Expried!");
		elseif(stristr($data, 'Account type:') && stristr($data, 'Premium') && !stristr($data, 'FREE (<a href="/premium.jsp')) {
			preg_match('/alignRight bold">(.*)Gb of (.*)Gb<\/div>/i', $this->lib->cut_str($data, '>Premium traffic:<','"spaceScale"'), $result);
			if($result[1] > $result[2]) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'Expires in:</div>','/div>'), 'alignRight bold">','<') ."<br/> Our 4shared.com account has reach bandwidth limit <br/> {$result[1]}Gb / {$result[2]}Gb");
			else return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'Expires in:</div>','/div>'), 'alignRight bold">','<') ."<br/> Traffic Avalidable: ". $result[1]."Gb / ". $result[2]."Gb");
		}
		elseif(stristr($data, 'Used space:</div>') && !stristr($data, '>Premium <'))  return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.4shared.com/web/login", "4langcookie=en", "login={$user}&password={$pass}&remember=1&_remember=on&returnTo=http://www.4shared.com/account/home.jsp&ausk=&inviteId=&inviterName=");
		$cookie = "4langcookie=en; savelogin=true; {$this->lib->GetCookies($data)}";
		return $cookie;
	}
	
    public function Leech($url) {	
		if(!stristr($url, "4shared.com/file/")) {
			$ex =  explode("/", $url); 
			$url = 'http://www.4shared.com/file/'.$ex[4];
		}
		list($url, $pass) = $this->linkpassword($url);
		$post = !empty($pass) ? "userPass2=".$pass : "";
		$data = $this->lib->curl($url, "4langcookie=en;".$this->lib->cookie, $post ? "userPass2={$pass}" : "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file link that you requested is not valid')) $this->error("dead", true, false, 2);
		elseif(stristr($data, 'Please enter a password to access this file')) $this->error("reportpass", true, false);
		elseif(preg_match('@https?:\/\/dc\d+\.4shared\.com\/download[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4shared Download Plugin 
* Downloader Class By [FZ]
* Fixed check account by giaythuytinh176 [14.8.2013]
*/
?>