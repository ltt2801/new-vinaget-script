<?php

class dl_netload_in extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://netload.in/index.php?id=2", $cookie, "");
		if(stristr($data, 'login')) return array(false, "Account Invalid");
		else if(stristr($data, '<span style="color: green">')) return array(true, $this->lib->cut_str($data, '<span style="color: green">','</span>'));
		else return array(false, "Account is FREE");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://netload.in/", "", "txtuser={$user}&txtpass={$pass}&txtcheck=login&txtlogin=");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
	/*
		$data = $this->lib->curl('http://urlchecker.net/', "", 'links='.urlencode($url).'&submit=Check Links');
		$data = $this->lib->cut_str($data, '<td class="working">', 'class="live');
		if(stristr($data,'Working</td>'))	$url = $this->lib->cut_str($data, 'href="', '" target');
		else $this->error("dead", true, false, 2);
		if(preg_match('@^https?:\/\/(www\.)?netload\.in\/(\w+)(.+)?@i', $url, $urlgiay))
		$url = 'http://netload.in/'.$urlgiay[2].'.htm';
		
		
		$data = $this->lib->curl('http://10s.mobi/', "", 'urllist='.urlencode($url));
		if(stristr($data,'<img src=chk_good.png '))	$url = $this->lib->cut_str($data, '<br><B><a href=', '>');
		else $this->error("dead", true, false, 2);
		
	*/
	
    public function Leech($url) {	
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl('http://api.netload.in/index.php?id=2', "", 'links='.urlencode($url).'&send=Absenden');
		$data = $this->lib->cut_str($this->lib->cut_str($data, '<h3', '</body>'), 'name="links">', '</textarea>');
		if(stristr($data,'online')) {
			$gach = explode(';', $data);
			$url = 'http://netload.in/datei'.$gach[0].'.htm';
			$this->lib->reserved['filename'] = $gach[1];
			//$this->lib->reserved['filesize'] = $gach[2];
		}
		else $this->error("dead", true, false, 2);
		//preg_match('@^https?:\/\/(www\.)?netload\.in\/(\w+)(.+)?@i', $url, $urlgiay);
		//$url = 'http://netload.in/'.$urlgiay[2].'.htm';
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($pass) {
			if(!preg_match('%action="([^"]+)"%U', $data, $urlp))  
			$this->error("Error: Cannot get Pass Link", true, false, 2);
			else {
				$urlpass = 'http://netload.in/'.$urlp[1];
				$post["file_id"] = $this->lib->cut_str($data, 'type="hidden" value="', '"');
				$post["password"] = $pass; 
				$post["submit"] = "Show";
				$data = $this->lib->curl($urlpass, $this->lib->cookie, $post);
				if(stristr($data,'You have entered an incorrect password'))  $this->error("wrongpass", true, false, 2);
				elseif(!$this->isredirect($data)) {
					if(preg_match('@https?:\/\/[\d.]+\/[^"\'><\r\n\t]+@i', $data, $dl))	return trim($dl[0]);
				}
				else  return trim($this->redirect);
			}
		}
		//if(stristr($data,"The file was deleted"))  $this->error("dead", true, false, 2);
		if(stristr($data,'This file is password-protected'))   $this->error("reportpass", true, false);
		elseif(!$this->isredirect($data)) {
			if(preg_match('@https?:\/\/[\d.]+\/[^"\'><\r\n\t]+@i', $data, $dl))	return trim($dl[0]);
		}
		else  return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Netload Download Plugin 
* Downloader Class By [FZ]
* Fixed download link, add support file password by giaythuytinh176 [19.8.2013]
*/
?>