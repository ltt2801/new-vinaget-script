<?php  

class dl_novafile_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://novafile.com/?op=my_account", "lang=english;{$cookie}", "");
		if(stristr($data, 'Premium account expires:')) {
			$checkbw = $this->lib->curl("http://novafile.com/?op=my_account", "lang=english;{$cookie}", "");
			return array(true, "Until ".$this->lib->cut_str($data, 'Premium Account expires:','<a href="') ."<br/> Traffic Available: " .$this->lib->cut_str($this->lib->cut_str($checkbw, '<td>Traffic Available:</td>','</tr>'), '<td>','</td>'));
		}
		elseif(stristr($data, 'FREE - member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}

    public function Login($user, $pass){
        $data = $this->lib->curl("http://novafile.com/login", "lang=english", "login={$user}&password={$pass}&op=login&rand=&redirect=");
		return "lang=english;{$this->lib->GetCookies($data)}";
    }
	
    public function Leech($url){
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, '>File Not Found<')) $this->error("dead", true, false, 2);
		elseif(stristr($data,"different IP")) $this->error("blockIP", true, false);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<form action="', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			//if(stristr($data,'You have reached the download limit'))  $this->error("LimitAcc", true, false);
			if(preg_match('@You have reached the download limit: (\d+) (T|G|M|K)B@i', $data, $limit)) $this->error($limit[0], true, false);
			else return trim($this->lib->cut_str($data, '<p><a href="', '" class="btn btn-green'));
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Novafile Download Plugin 
* Downloader Class By [FZ]
* Fixed download by giaythuytinh176 [20.7.2013]
*/
?>