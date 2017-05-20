<?php

class dl_bitshare_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://bitshare.com/myaccount.html", "language_selection=EN;{$cookie}", "");
		$dt =  $this->lib->curl("http://bitshare.com/mylogs.html", $cookie, "");
		preg_match('/<td style="text-align:center;">(\d+(\.\d+)? (G|M|K)Byte)<\/td>/i', $dt, $trafused); 
		if(stristr($data, 'Premium  <a href="http://bitshare.com/myupgrade.html">Extend</a>')) return array(true, "Until ".$this->lib->cut_str($data, 'Valid until:','</div>'). "<br/>Date: ". $this->lib->cut_str($dt, 'type="text" id="date2" value="','" />'). " => Traffic used: ". $trafused[1]); 
		else if(stristr($data, '<i>Basic</i> <a href="http://bitshare.com/myupgrade.html">Upgrade</a>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://bitshare.com/login.html", "language_selection=EN", "user={$user}&password={$pass}&rememberlogin=&submit=Login");
		$cookie = "language_selection=EN;{$this->lib->GetCookies($data)}";
		return $cookie;
	}
	
    public function Leech($url) {
		if(preg_match('@^http:\/\/bitshare\.com\/files\/(.*)\/(.*)@i', $url, $fileID));
		elseif(preg_match('@^http:\/\/bitshare\.com\/\?f=(.*)@i', $url, $fileID));
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'>Error - File not available<') || stristr($data,'>We are sorry, but the requested file was not found'))  $this->error("dead", true, false, 2);
		elseif(stristr($data,'Your Traffic is used up for today')) 	$this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data)) {
			$ajaxid = $this->lib->cut_str($data, 'var ajaxdl = "', '";');
			$data = $this->lib->curl("http://bitshare.com/files-ajax/{$fileID[1]}/request.html", $this->lib->cookie, "request=generateID&ajaxid={$ajaxid}");	
			$data = $this->lib->curl("http://bitshare.com/files-ajax/{$fileID[1]}/request.html", $this->lib->cookie, "request=getDownloadURL&ajaxid={$ajaxid}");
			if(preg_match('/SUCCESS\#(https?:\/\/.+)/', $data, $link))  return trim($link[1]);
		}
		else return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Bitshare.com Download Plugin
* Downloader Class By [FZ]
* Fixed By giaythuytinh176 In : 16.7.2013
* Fixed check account By giaythuytinh176 [6.8.2013]
* Fix download link by giaythuytinh176 [13.9.2013][if not enable direct download]
*/
?>