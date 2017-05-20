<?php   // need check
 
class dl_filegagpro_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.filegagpro.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium-Account expire:</TD></TR>')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD><b>','</b></TD></TR>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium-Account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.filegagpro.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.filegagpro.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password')) $this->error("reportpass", true, false);
			elseif($this->isredirect($data)) return trim($this->redirect);
		}
		if (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif(stristr($data,'Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data)) return trim($this->redirect);
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
* filegagpro.com Download Plugin by giaythuytinh176 [29.7.2013]
* Downloader Class By [FZ]
*/
?>