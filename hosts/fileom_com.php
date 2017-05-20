<?php

class dl_fileom_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://fileom.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until " .$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD>'). "<br/>Traffic available today: " .$this->lib->cut_str($data, 'Traffic available today:</TD><TD><b>','</b></TD></TR>'));
        elseif(stristr($data, 'Username:') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://fileom.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://fileom.com/");
        return "lang=english; {$this->lib->GetCookies($data)}";
    }
	  
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			else return trim($this->lib->cut_str($this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</tr></table>'), 'href="', '">'));
		}
		if(stristr($data,'Password:</b> <input type="password" name="password"')) $this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(preg_match('@You have reached the download-limit of (\d+) (T|G|M|K)b for last (\d+) days?@i', $data, $redir)) $this->error($redir[0], true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			return trim($this->lib->cut_str($this->lib->cut_str($data, 'background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</tr></table>'), 'href="', '">'));
		}
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* fileom Download Plugin by giaythuytinh176 [6.8.2013]
* Downloader Class By [FZ]
*/
?>