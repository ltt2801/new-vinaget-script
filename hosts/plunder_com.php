<?php 

class dl_plunder_com extends Download {

    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.plunder.com/", $cookie, "");
        if(stristr($data, 'href="/logout/">Logout</a>')) return array(true, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass) {		// use cookie loginx= if not work.
		$data = $this->lib->curl("http://www.plunder.com/login/","","");
        $post["Username"]= $user;
        $post["Password"]= $pass;
		$post["__VIEWSTATE"] = $this->lib->cut_str($data, "__VIEWSTATE\" value=\"", "\"");
		$post["__EVENTVALIDATION"] = $this->lib->cut_str($data, "__EVENTVALIDATION\" value=\"", "\"");
        $post["return"] = "";
		$post["handshake"] = "";
		$post["submit"] = "Submit";
        $data = $this->lib->curl("http://www.plunder.com/login/","",$post);                        
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(preg_match('@https?:\/\/[a-z]+\.plunder\.com\/[a-z]+\/[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* plunder.com Download Plugin by giaythuytinh176 [11.8.2013]
* Downloader Class By [FZ]
*/
?>