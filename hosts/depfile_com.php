<?php	

class dl_depfile_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://depfile.com/", "sdlanguageid=2;{$cookie}", "");
        if(stristr($data, "premium'>")) {
			$data2 = $this->lib->curl("https://depfile.com/Fg8cR98jOrK", "sdlanguageid=2;{$cookie}", "");
			if(stristr($data2, "class='notice'>")) return array(true, "Until ".$this->lib->cut_str($data, "premium'>", '<img')."<br>".$this->lib->cut_str($data2, "class='notice'>", "</p>"));
			return array(true, "Until ".$this->lib->cut_str($data, "premium'>", '<img'));
		}
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("https://depfile.com/", "sdlanguageid=2", "login=login&loginemail={$user}&loginpassword={$pass}&submit=login&rememberme=1");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
     
    public function Leech($url) {
		if(!stristr($url, "https")) {
			$url = str_replace('http', 'https', $url);
		}
		$data = $this->lib->curl($url, $this->lib->cookie, "");
        if(stristr($data, "class='notice'>")) $this->error("LimitAcc", true, false, 2);
        if(stristr($data, 'Page Not Found!') || stristr($data,'File was not found in the') || stristr($data,'Provided link contains errors')) $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/[a-z]+\.depfile\.com\/premdw\/\d+\/[a-z0-9]+\/[^"\'<>\r\n\t]+@i', $data, $giay)) 
		return trim($giay[0]);
		return false;
    }
	
}


/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.3 LTSB
* Depfile.com Download Plugin  
* Date: 04.09.2017
*/
?>