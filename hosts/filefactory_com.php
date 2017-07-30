<?php

class dl_filefactory_com extends Download {

	public function PreLeech($url){ 
		if(stristr($url, "/f/") || stristr($url, "/folder/")) {
			$data = $this->lib->curl($url, "", "");
			if(stristr($data, "<input type=\"checkbox\" value=\"")) {
				$ffid = explode('<td><a href="http://www.filefactory.com/file', $data);
				$maxfile = count($ffid); 
				for ($i = 1; $i < $maxfile; $i++) {
					preg_match('%\/(.+)\/%U', $ffid[$i], $code);
					preg_match('%\/\w+\/(.+)"%U', $ffid[$i], $fn);
					//$list = "http://www.filefactory.com/file/".$code[1]."/".$fn[1]."<br/>"; 
					$list = "<a href=http://www.filefactory.com/file/".$code[1]."/".urlencode($fn[1]).">http://www.filefactory.com/file/".$code[1]."/".urlencode($fn[1])."<br/></a>";
					echo $list;
				}
				exit;
			}
			else $this->error("dead", true, false, 2);
		}
	}

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.filefactory.com/account/", "locale=en_US.utf8;".$cookie, "");
		if(stristr($data, 'Premium valid until:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium valid until: <strong>', '</strong>">'));
		elseif(stristr($data, '<strong>Free Member</strong>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.filefactory.com", "locale=en_US.utf8", "");
		$cookies = $this->lib->Getcookies($data);
		$post["loginEmail"] = $user;
		$post["loginPassword"] = $pass;
		$post['Submit'] = "Sign%20In";
		$data = $this->lib->curl("http://www.filefactory.com/member/signin.php", "locale=en_US.utf8; {$cookies}", $post);
		return "locale=en_US.utf8; {$cookies};".$this->lib->GetCookies($data);
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$url = preg_replace("@https?:\/\/(www\.)?filefactory\.com@", "http://www.filefactory.com", $url);
		$url = $this->getredirect($url);
		if(stristr($url,'code=273'))   $this->error("You have exceeded the file or folder password attempt limit. Please try the download again later.", true, false);
		elseif(stristr($url,'code=251'))  $this->error("dead", true, false, 2);
		elseif(stristr($url,'code=253'))  $this->error("Server Maintenance", true, false);
		$data = $this->lib->curl($url, $this->lib->cookie, "");

		if($pass) {
			$post["password"] = $pass;
			$post["Submit"] = "Continue";
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data, 'The Password entered was incorrect.') || stristr($data, 'The Password is required'))  $this->error("wrongpass", true, false, 2);
			elseif(!$this->isredirect($data)) {
				if(preg_match('/href="(https?:\/\/.+filefactory\.com\/get\/.+)" class/i', $data, $link)) return trim($link[1]);
			}
			else return trim($this->redirect);
		}
		if(stristr($data,'name="password" id="password" type="password"')) 	$this->error("reportpass", true, false);
        elseif(!$this->isredirect($data)) {
			if(preg_match('/href="(https?:\/\/.+filefactory\.com\/get\/.+)">/i', $data, $link)) return trim($link[1]);
		}
		else return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.3
* Filefactory.com Download Plugin
* Date: 30.07.2017
*/
?>