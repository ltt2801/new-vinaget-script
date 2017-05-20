<?php

class dl_rghost_net extends Download {

	public function FreeLeech($url){
		if(!stristr($url, "http://rghost.net")) {
			$ext =  explode("/", $url); 
			$url = "http://rghost.net/".$ext[3];
		}
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if($pass) {	
			if (!preg_match('%UTF-8" action="([^"]+)"%', $data, $urlp))  
			$this->error("Error: Cannot get Pass Link", true, false, 2);
			else
			$urlgiay = 'http://rghost.net'.$urlp;
			$post["utf8"] = "&#x2713;";
			$post["authenticity_token"] = $this->lib->cut_str($data, 'authenticity_token" type="hidden" value="', '" />');
			$post["password"] = $pass;
			$post["commit"] = "Get link";
			$data = $this->lib->curl($urlgiay, $this->lib->cookie, $post);
			if(stristr($data,'Incorrect password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/rghost\.net\/download\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'File is deleted.') || stristr($data,'<p>this page is not found</p>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'password" name="password')) 	$this->error("reportpass", true, false);
		elseif(preg_match('@https?:\/\/rghost\.net\/download\/[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rghost.net Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>