<?php

class dl_ultramegabit_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://ultramegabit.com/user/subscription", $cookie, "");
        if(stristr($data, '"Premium Member"')) return array(true, "Until ".(strpos($data, ">Next rebill at") ? $this->lib->cut_str($data, '>Next rebill at ', '</') : $this->lib->cut_str($data, '>Account expires at', '<')));
        else if(stristr($data, '<a href="/logout">') && !stristr($data, '"Premium Member"')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$csrf = $this->lib->curl("https://ultramegabit.com/login", "", "");
		$csrftoken = $this->lib->cut_str($csrf, 'csrf_token" value="', '"');
        $data = $this->lib->curl("https://ultramegabit.com/login", "csrf_cookie={$csrftoken}", "username={$user}&password={$pass}&csrf_token={$csrftoken}&return_url=&submit=Login");
        $cookie = "csrf_cookie={$csrftoken}; {$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		$url = str_replace('http:', 'https:', $url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
 		if(stristr($data,'>File not available.<') || stristr($data,'>File has been deleted.</') || stristr($data,'File has been deleted in compliance with the'))      $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = array(
				'csrf_token' => $this->lib->cut_str($data, 'csrf_token" value="', '"'),
				'encode' => $this->lib->cut_str($data, 'encode" value="', '"'),
			);
			$data = $this->lib->curl("https://ultramegabit.com/file/download", $this->lib->cookie, $post);
			if($this->isredirect($data)) {
				$this->save($this->lib->GetCookies($data));
				return trim($this->redirect);
			}
		}
		else {
			$this->save($this->lib->GetCookies($data));
			return trim($this->redirect);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* ultramegabit Download Plugin by giaythuytinh176 [21.8.2013]
* Downloader Class By [FZ]
* Thanks to Rapid61@rapidleech.com for your account.
*/
?>