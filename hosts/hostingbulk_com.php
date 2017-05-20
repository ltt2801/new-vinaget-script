<?php		 

class dl_hostingbulk_com extends Download {

    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post['password'] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'<input type="password" name="password" class="myForm"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!stristr($data, 'value="Create Download Link'))   
		$this->error("Cannot get Create Download Link", true, false);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1" method="POST', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/[\d.]+(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* hostingbulk Download Plugin by giaythuytinh176 [21.8.2013]
* Downloader Class By [FZ]
*/