<?php

class dl_upfile_mobi extends Download {
	
	public function FreeLeech($url){
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, "lang=en", "");
		$this->save($this->lib->GetCookies($data));
		if($pass) {
			$url = str_replace("http://upfile.mobi/", "http://upfile.mobi/view.php?f=", $url);
			$post["key"] = $pass;
			$data = $this->lib->curl($url, "lang=en;".$this->lib->cookie, $post);
			if(stristr($data,'Enter password:'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/upfile\.mobi\/download\.php[^"\'><\r\n\t]+@i', "http://upfile.mobi/{$this->lib->cut_str($data, 'title="download" href="', '">')}", $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="key')) $this->error("reportpass", true, false);
		elseif(preg_match('@https?:\/\/upfile\.mobi\/download\.php[^"\'><\r\n\t]+@i', "http://upfile.mobi/{$this->lib->cut_str($data, 'title="download" href="', '">')}", $giay))
		return trim($giay[0]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* upfile.mobi Download Plugin by giaythuytinh176 [16.8.2013]
* Downloader Class By [FZ]
*/
?>