<?php

class dl_freakshare_com extends Download {

	public function PreLeech($url){
		if(stristr($url, "/folder/"))  $this->error("Not Support Folder", true, false, 2);
	}
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://freakshare.com/index.php?language=EN", $cookie, "");
		$data = $this->lib->curl("http://freakshare.com", $cookie, "");
		$dt =  $this->lib->curl("http://freakshare.com/member/history.html", $cookie, "");
		preg_match('/<td style="text-align:right;">(\d+(\.\d+)? (G|M|K)Byte)<\/td>/i', $dt, $trafused); 
		if (stristr($data, 'Member (premium)')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'valid until:</td>','</tr>'), '<td><b>','</b></td>'). " <br/>Traffic left: ". $this->lib->cut_str($this->lib->cut_str($data, 'Traffic left:</td>','</tr>'), '<td>','</td>'). " <br/>Date: ". $this->lib->cut_str($dt, '<td style="text-align:right;">','</td>'). " => Traffic used: ". $trafused[1]);
		else if(stristr($data, 'http://freakshare.com/member/logout.html')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}   
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://freakshare.com/?language=US", "", "");
		$data = $this->lib->curl("http://freakshare.com/login.html", "", "user={$user}&pass={$pass}&submit=Login");
		return $this->lib->GetCookies($data);
	}
         
	public function Leech($url) {
		$data = $this->lib->curl("http://freakshare.com/?language=US", "", "");
		$data = $this->lib->curl($url, $this->lib->cookie, "");
        if(stristr($data, 'our Traffic is used up for today'))    $this->error("LimitAcc", true, false);
		elseif(stristr($data, '503 Service Temporarily Unavailable'))    $this->error("unavailable", true, false);
		elseif(stristr($data, 'This file does not exist!'))    $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			preg_match('/type="hidden" value="(\d+)" name="did"/i', $this->lib->cut_str($data, "waitingtime","clear:both;margin"), $did);
			$data = $this->lib->curl($url, $this->lib->cookie, "did={$did[1]}&section=waitingtime&submit=Download");
			if(stristr($data, '503 Service Temporarily Unavailable'))    $this->error("unavailable", true, false);
			elseif($this->isredirect($data))  return trim($this->redirect);
		}
		else return trim($this->redirect);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Freakshare Download Plugin 
* Fixed by giaythuytinh176 [13.9.2013]
* Downloader Class By [FZ]
*/
?>