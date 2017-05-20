<?php

class dl_share_vnn_vn extends Download {
   
    public function CheckAcc($cookie){
         $data = $this->lib->curl("http://share.vnn.vn/thong-tin-ca-nhan", $cookie, "");
         if(stristr($data, '<label class="caption">Thời hạn VIP còn lại:</label>')) return array(true, "".$this->lib->cut_str($this->lib->cut_str($data, '<label class="caption">Thời hạn VIP còn lại:</label>','<div class="field">'), '<div class="fieldtext">','ngày') ." days remaining");
         else if(stristr($data, 'MegaShare Free')) return array(false, "accfree");
		 else return array(false, "accinvalid");
    }
/* 
    public function Login($user, $pass){
		$data = $this->lib->curl('https://id.vnn.vn/login?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F', '', '');
		if(preg_match('%jsessionid=(.+)\?%U', $data, $match)) $jsid = $match[1]; 
		$lt = $this->lib->cut_str($data, '"lt" value="', '" />');	
		$cookies = 'jsessionid='.$jsid;
		$data = $this->lib->curl("https://id.vnn.vn/login;jsessionid={$jsid}?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F", $cookies, "username={$user}&password={$pass}&lt={$lt}&_eventId=submit&submit=Đăng nhập");
		$cookies1 = $this->lib->GetCookies($data);
		if(preg_match("#Location: (.*)#", $data, $match)) {
			$data = $this->lib->curl($match[1], $cookies1, '');
			$cookies2 = $cookies1. '; ' .$this->lib->GetCookies($data);
			if(preg_match('#PHPSESSID=ST(.+)#', $cookies2, $cookies3)) 
			$cookie = "{$cookies};{$cookies3[0]}";	
		}
		return $cookie;
    }
*/ 
  public function Login($user, $pass){
		$data = $this->lib->curl("https://id.vnn.vn/login?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F", "", "");
		$cookies = $jsid = $this->lib->GetCookies($data);
		$lt = $this->lib->cut_str($data,'"lt" value="', '" />');	
		$data = $this->lib->curl("https://id.vnn.vn/login;jsessionid={$jsid}?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F", $cookies, "username={$user}&password={$pass}&lt={$lt}&_eventId=submit&submit=Đăng%20nhập");
		$cookie = $this->lib->GetCookies($data);
		if(preg_match("/ocation: (.*)/", $data, $match)) {
			$data = $this->lib->curl($match[1], $cookie, "");
			$cookie = "{$cookie};{$this->lib->GetCookies($data)}";
			preg_match('/PHPSESSID=ST(.*)/i', $cookie, $cookie);
			$cookie = "{$cookies}{$cookie[0]}";	
			return $cookie;
		}
    }
 
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'Không tìm thấy file bạn yêu cầu') || stristr($data,'File bạn yêu cầu đã bị khóa') || stristr($data,'We cannot find the page you are looking for')) $this->error("dead", true, false, 2);
		elseif(preg_match('/window.location.href=\'(https?:\/\/.+)\'">/i', $data, $giay)) 	return trim($giay[1]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Share.VNN.VN Download Plugin  by giaythuytinh176 [28.7.2013]
* Downloader Class By [FZ]
*/
?>