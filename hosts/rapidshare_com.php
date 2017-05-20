<?php

class dl_rapidshare_com extends Download {
    
	public function PreLeech($url) {
		if(stristr($url, "my.rapidshare.com"))  $this->error("Not Support Folder", true, false, 2);
	}
	
    public function CheckAcc($cookie){
		if(stristr($cookie, "=")) {
			$ckc =  explode("=", $cookie); 
			$cookie = $ckc[1];
		}
        $data = $this->lib->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi", "lang=en", "sub=getaccountdetails&withcookie=1&withpublicid=1&withsession=1&cookie={$cookie}&cbf=RSAPIDispatcher&cbid=1");	
		if(preg_match('/nbilleduntil=([0-9]+)/', $data, $giay))	{
			if($giay[1] == 0) return array(false, "accfree");
 			elseif(time() > $giay[1]) return array(false, "Account Expired!");
			else return array(true, "Until " .date('H:i:s Y-m-d', $giay[1]));
		}
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){
        $data = $this->lib->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi", "lang=en", "sub=getaccountdetails&withcookie=1&withpublicid=1&login={$user}&cbf=RSAPIDispatcher&cbid=2&password={$pass}");
		preg_match('/ncookie=([A-Z0-9]+)/', $data, $thuytinh);
		return "enc={$thuytinh[1]}; lang=en";
    }

    public function Leech($url) {
		if(stristr($url, 'rapidshare') && stristr($url, 'download|')) {
			$url = str_replace("%21","!",$url);
			$url = str_replace("%7C","|",$url);
			$arrRSL = explode('|', $url);
			if(isset($arrRSL)){
				$idfiles = $arrRSL[2];
				$idnames = $arrRSL[3];
				$url = "http://rapidshare.com/files/{$arrRSL[2]}/{$arrRSL[3]}";
			}
		}
		elseif(stristr($url, 'rapidshare.com/download/')) {		// Fixed link by giaythuytinh176 [15.10.2013]
			$arrRSL = explode('/', $url);
			$idfiles = $arrRSL[5];
			$idnames = base64_decode($arrRSL[6]);
			$url = "http://rapidshare.com/files/{$arrRSL[2]}/{$arrRSL[3]}";
		}
		elseif(stristr($url, 'rapidshare.com/desktop/download')) {		// Fixed link by giaythuytinh176 [30.9.2013]
			$arrRSL = explode('/', $url);
			$idfiles = $arrRSL[6];
			$idnames = base64_decode($arrRSL[7]);
			$url = "http://rapidshare.com/files/{$arrRSL[2]}/{$arrRSL[3]}";
		}
		else {
			$arrRSL = explode('/', $url);
			if(isset($arrRSL)){
				$idfiles = $arrRSL[4];
				$idnames = $arrRSL[5];
				$url = "http://rapidshare.com/files/{$arrRSL[4]}/{$arrRSL[5]}";
			}
		}
		$checkfile = file_get_contents("http://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=checkfiles&files={$idfiles}&filenames={$idnames}");
		if(stristr($checkfile,'0,0,0,0,0')) $this->error("dead", true, false, 2);
		$cookie = preg_replace("/(enc=|ENC=|Enc=|\s+|;|lang=en)/", "", $this->lib->cookie);
		$data = $this->lib->curl("https://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=download&cookie={$cookie}&fileid={$idfiles}&filename={$idnames}", "", "", 0);
		if(stristr($data, "File owner's public traffic exhausted")) $this->error("File owner's public traffic exhausted", true, false);
		elseif(stristr($data, "Download permission denied by uploader")) $this->error("Download permission denied by uploader", true, false);
		elseif(preg_match('/DL\:(.+rapidshare.com),/i', $data, $rssv)) {
			$link = "http://{$rssv[1]}/cgi-bin/rsapi.cgi?sub=download&cookie={$cookie}&fileid={$idfiles}&filename={$idnames}";
			return trim($link);
		}
		//$data = $this->lib->curl($url, "lang=en;".$this->lib->cookie, ""); 
		//if($this->isredirect($data))   return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rapidshare Download Plugin by giaythuytinh176 [2.8.2013][23.9.2013][Fixed link]
* Downloader Class By [FZ]
*/
?>