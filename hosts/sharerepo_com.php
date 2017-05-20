<?php

class dl_sharerepo_com extends Download {
    
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "lang=english", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!stristr($data, 'value="Free Download">'))
		$this->error("Cannot get Free Download", true, false, 2); 	
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" action=', '</Form>'));
			$data = $this->lib->curl($url, "lang=english;".$this->lib->cookie, $post);
			if($pass) {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '</Form>'));
				$post1["password"] = $pass;
				$data1 = $this->lib->curl($url, "lang=english;".$this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
				return trim($giay[0]);
			}
			if(stristr($data,'type="password" name="password')) $this->error("reportpass", true, false);
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '</Form>'));
			$data1 = $this->lib->curl($url, "lang=english;".$this->lib->cookie, $post1);
			if(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data1, $giay))
			return trim($giay[0]);
		}
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* sharerepo Download Plugin by giaythuytinh176 [12.8.2013]
* Downloader Class By [FZ]
*/
?>