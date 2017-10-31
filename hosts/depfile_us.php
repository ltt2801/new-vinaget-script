<?php	

class dl_depfile_us extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://depfile.us/", "sdlanguageid=2;{$cookie}", "");
        if(stristr($data, "premium'>")) {
			$data2 = $this->lib->curl("https://depfile.us/Fg8cR98jOrK", "sdlanguageid=2;{$cookie}", "");
			if(stristr($data2, "class='notice'>")) return array(true, "Until ".$this->lib->cut_str($data, "premium'>", '<img')."<br>".$this->lib->cut_str($data2, "class='notice'>", "</p>"));
			return array(true, "Until ".$this->lib->cut_str($data, "premium'>", '<img'));
		}
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$data = $this->lib->curl("https://depfile.us/", "sdlanguageid=2", "login=login&loginemail={$user}&loginpassword={$pass}&submit=login&rememberme=1");
		$cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
     
    public function Leech($url) {
		if(!stristr($url, "https")) {
			$url = str_replace('http', 'https', $url);
		}
		$data = $this->lib->curl($url, "sdlanguageid=2;{$this->lib->cookie}", "");
        if (stristr($data, "class='notice'>")) $this->error("LimitAcc", true, false, 2);
        if (stristr($data, 'Page Not Found!') || stristr($data,'File was not found in the') || stristr($data,'Provided link contains errors')) $this->error("dead", true, false, 2);
		else if (stristr($data,'<th>Download:</th>')) {
			$t = $this->lib->cut_str($data, "<th>Download:</th>","</td>");
			if (preg_match("/<a href='(.*?)'>/", $t, $match)) return trim($match[1]);
		}
		return false;
    }
	
}


/*
* Open Source Project
* New Vinaget by LTT❤
* Version: 3.3 LTSB
* Depfile.us Download Plugin  
* Date: 31.10.2017
*/
?>