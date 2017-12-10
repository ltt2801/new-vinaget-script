<?php	

class dl_yunfile_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.yunfile.com/user/edit.html", "language=en_us;{$cookie}", "");
        if(stristr($data, 'premium-pack') && stristr($data, 'bottom ">Yes')) return array(true, "Until ".$this->lib->cut_str($data, '(Expire:',')'));
        else if(stristr($data, 'premium-pack')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.yunfile.com/view", "language=en_us", "module=member&action=validateLogin&username={$user}&password={$pass}&remember=1");
        $cookie = "language=en_us;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		//$url = preg_replace("@https?:\/\/(yfdisk|filemarkets|yunfile)\.com@", "http://page2.yunfile.com", $url);
 		$data = $this->lib->curl($url, $this->lib->cookie, "");
		//$link = $this->lib->cut_str($this->lib->cut_str($data, '<td  class ="down_url_table_td">', 'onclick=\'setCookie'), '<a href="', '"');
		if(preg_match('@http:\/\/dl\d+\..+\.com\/downfile\/[^"\'><\r\n\t]+@i', $data, $link)) {
			if (preg_match('/setCookie\(\'(.*?)\', \'(.*?)\'/', $data, $cook)) {
				if (strpos($this->lib->cookie, "vid1=")) {
					$this->lib->cookie = preg_replace("/vid1=(.*?);/i", "vid1=$cook[2]; ", $this->lib->cookie);
				}
				else {
					$this->lib->cookie .= "$cook[1]=$cook[2]; ";
				}
			}
			
			//$this->lib->cookie .= 'validCodeUrl="page2.dfpan.com:8880"';
			//die($this->lib->cookie);
			//$data = $this->lib->curl(trim($link[0]), $this->lib->cookie, "", 1, 0, $url);
			return trim($link[0]);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* yunfile Download Plugin by giaythuytinh176 [13.8.2013]
* Downloader Class By [FZ]
*/
?>