<?php

class dl_easybytez_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.easybytez.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD'));
        else if(stristr($data, 'Used space:') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$cutrand = $this->lib->curl("http://www.easybytez.com/", "lang=english", "");
		$rand = $this->lib->cut_str($cutrand, 'name="rand" value="', '">');
        $data = $this->lib->curl("http://www.easybytez.com/", "lang=english", "login={$user}&password={$pass}&op=login2&rand={$rand}&redirect=http://www.easybytez.com/");
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
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false);
			elseif(preg_match('/href="(http.+)">http/i', $this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px', '</span>'), $link))
			return trim($link[1]);
		}
		if(stristr($data,'>Password:</b> <input type="password" name="password')) 	$this->error("reportpass", true, false);
 		elseif(stristr($data,'>The uploader deleted the file.<') || stristr($data,'>File Not Found<')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, 'Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$cut = $this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px', '</span>');
			if(preg_match('/href="(http.+)">http/i', $this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px', '</span>'), $link))
			return trim($link[1]);
		}
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Easybytez Download Plugin by giaythuytinh176 [10.8.2013][22.11.2013]
* Downloader Class By [FZ]
*/
?>