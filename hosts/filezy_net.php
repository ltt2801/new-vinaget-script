<?php

class dl_filezy_net extends Download {

	private function JSun_packer($p,$a,$c,$k,$er) { // Thanks to Th3-822@rapidleech.com 
		$k = explode($er, $k);
		while ($c--) if($k[$c]) $p = preg_replace('@\b'.base_convert($c, 10, $a).'\b@', $k[$c], $p);
		return $p;
	}

    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://filezy.net/my_account.html", "lang=english;{$cookie}", "");
        if(stristr($data, '<p>Direct Download</p>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<h3 style="color:#4d9c06; font-size:22px;">Premium</h3>','Extend</a></h5>'), '<h5>(',') <br>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Direct Download')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://filezy.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=&checkedTOS=1&submit_btn=Login Now");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="A008', '</form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@eval\s*\(\s*function\s*\(p,a,c,k,e,d\)\s*\{[^\}]+\}\s*\(\s*\'([^\r|\n]*)\'\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*\'([^\']+)\'\.split\([\'|\"](.)[\'|\"]\)\)\)@', $data, $js)) 
			$this->error("PACKED code not found", true, false);
			else {
				$packed = $this->JSun_packer($js[1], $js[2], $js[3], $js[4], $js[5]);
				if(preg_match("@href=\\\'(.+)\\\'@i", $packed, $filezyLink)) 	
				return trim($filezyLink[1]);
			}
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="A008', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@eval\s*\(\s*function\s*\(p,a,c,k,e,d\)\s*\{[^\}]+\}\s*\(\s*\'([^\r|\n]*)\'\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*\'([^\']+)\'\.split\([\'|\"](.)[\'|\"]\)\)\)@', $data, $js)) 
			$this->error("PACKED code not found", true, false);
			else {
				$packed = $this->JSun_packer($js[1], $js[2], $js[3], $js[4], $js[5]);
				if(preg_match("@href=\\\'(.+)\\\'@i", $packed, $filezyLink)) 	
				return trim($filezyLink[1]);
				//return trim($this->lib->cut_str($packed, "window.location.href=\'", "\'"));
			}
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
* filezy.net Download Plugin by giaythuytinh176 [11.8.2013]
* Downloader Class By [FZ]
* Using JSun_packer function by Th3-822 to decrypt the packed link. [RapidLeech plugin]
*/
?>