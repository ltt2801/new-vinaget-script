<?php

class dl_cyberlocker_ch extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://cyberlocker.ch/?op=my_account", "lang=english;".$cookie, "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://cyberlocker.ch/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }

    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(!stristr($data, 'btn.value = \'Free Download\';'))
		$this->error("Cannot get Free Download", true, false);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" action=\'\'>', 'class="1tbl_pre" border="0" style="width:750px; max-width:750px'));
			$post['method_free'] = 'Wait for 0 seconds';
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->save($this->lib->GetCookies($data));
			if($pass) {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '<input type="hidden" name="method_premium" value="">'));
				$post1['method_premium'] = "";
				$post1['down_direct'] = "1";
				$post1['password'] = $pass;
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif($this->isredirect($data1))	return trim($this->redirect);
			}
			if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $data, $count)) 	$this->error($count[0], true, false);
			elseif(stristr($data,'<input type="password" name="password" class="myForm"><br>')) 	$this->error("reportpass", true, false);
			elseif(!stristr($data, 'btn_download" value="Download" class="bt')) 
			$this->error("Cannot get DOWNLOAD", true, false);
			else {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '<input type="hidden" name="method_premium" value="">'));
				$post1['method_premium'] = "";
				$post1['down_direct'] = "1";
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if($this->isredirect($data1)) return trim($this->redirect);
			}
		}
		return false;
	}			
   
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', 'value="Download" class="btn_download buttons">'));
			$post["password"] = $pass; 
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data))  return trim($this->redirect);
		}
		elseif (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif (stristr($data,'404 Not Found'))  $this->error("dead", true, false, 2);
		elseif(stristr($data,'<input type="password" name="password" class="myForm"><br>')) 	$this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', 'value="Download" class="btn_download buttons">'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data))    return trim($this->redirect);
		}
		else  
		return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Cyberlocker.ch Download Plugin by giaythutyinh176 [30.7.2013]
* Downloader Class By [FZ]
*/
?>