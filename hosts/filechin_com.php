<?php		 

class dl_filechin_com extends Download {
    
	public function PreLeech($url){
		$page = $this->lib->curl("http://www.filechin.com/?op=checkfiles", "", "op=checkfiles&process=Check URLs&list=".urlencode($url));
		$data = $this->lib->cut_str($page, '<Table class="tbl1" cellpadding=2>', '</Table>');
		if(stristr($data,'>Found</td>'))  $url = $this->lib->cut_str($data, '<tr><td>', '</td><td style');
		else $this->error("dead", true, false, 2);
	}
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.filechin.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD>'));
        else if(stristr($data, 'Old password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.filechin.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.filechin.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function FreeLeech($url) {	//Thanks to Th3-822@rapidleech.com
        list($url, $pass) = $this->linkpassword($url);
		$page = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($page));
		$post = $this->parseForm($this->lib->cut_str($page, 'Form method="POST" action=', '</form>'));
		$post['method_free'] = "Free Download";
		$post['method_premium'] = "";
		
		$page = $this->lib->curl($url, $this->lib->cookie, $post);
		if(preg_match('@You have reached the download-limit: \d+ Mb for last 1 days@i', $page, $limit)) $this->error($limit[0], true, false);
		if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $page, $count)) 	$this->error($count[0], true, false);
		if(preg_match('@You can download files up to \d+ [KMG]b only@i', $page, $sizelim)) 	$this->error($sizelim[0], true, false);
		
		$post = $this->parseForm($this->lib->cut_str($page, '<form name="F1" method="POST"', '</form>'));
		$post['method_free'] = "Free Download";
		
		if (!preg_match_all("@<span style='[^\'>]*padding-left\s*:\s*(\d+)[^\'>]*'[^>]*>((?:&#\w+;)|(?:\d))</span>@i", $this->lib->cut_str($page, '<form name="F1" method="POST"', '</form>'), $spans)) 
		$this->error("Error: Cannot decode captcha.", true, false);	
		else {
			$spans = array_combine($spans[1], $spans[2]);
			ksort($spans, SORT_NUMERIC);
			$captcha = '';
			foreach ($spans as $digit) $captcha .= $digit;
			$post['code'] = html_entity_decode($captcha);
			
			if(preg_match('@<span id="countdown_str"[^>]*>[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $this->lib->cut_str($page, '<form name="F1" method="POST"', '</form>'), $count) && $count[1] > 0) 
			sleep($count[1]);
			
			if($pass) {
				$post = $this->parseForm($this->lib->cut_str($page, '<form name="F1" method="POST"', '</form>'));
				$post['method_free'] = "Free Download";
				$post["password"] = $pass;
				$post['code'] = html_entity_decode($captcha);
				$page = $this->lib->curl($url, $this->lib->cookie, $post);
				if(stristr($page,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif(stristr($page,'>Skipped countdown'))  $this->error("Error: Skipped countdown?.", true, false);
				elseif(stristr($page,'>Wrong captcha<'))  $this->error("Error: Unknown error after sending decoded captcha.", true, false);
				elseif(stristr($page,'>Expired session<'))  $this->error("Error: Expired Download Session.", true, false);
				elseif(preg_match('@You can download files up to \d+ [KMG]b only.@i', $page, $err))  $this->error('Error: '.$err[0], true, false);	
				elseif(preg_match('@http:\/\/\w+\.filechin\.com:443\/dl\/[^"\'><\r\n\t]+@i', $page, $link)) 
				return trim($link[0]);
			}
			if(stristr($page,'<input type="password" name="password" class="myForm">')) 	$this->error("reportpass", true, false);
			
			$page = $this->lib->curl($url, $this->lib->cookie, $post);

			if(stristr($page,'>Skipped countdown'))  $this->error("Error: Skipped countdown?.", true, false);
			elseif(stristr($page,'>Wrong captcha<'))  $this->error("Error: Unknown error after sending decoded captcha.", true, false);
			elseif(stristr($page,'>Expired session<'))  $this->error("Error: Expired Download Session.", true, false);
			elseif(preg_match('@You can download files up to \d+ [KMG]b only.@i', $page, $err))  $this->error('Error: '.$err[0], true, false);	
			elseif(preg_match('@http:\/\/\w+\.filechin\.com:443\/dl\/[^"\'><\r\n\t]+@i', $page, $link)) 
			return trim($link[0]);
		}
		return false;	
	}	
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@http:\/\/\w+\.filechin\.com:443\/dl\/[^"\'><\r\n\t]+@i', $data, $link))
			return trim($link[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'You have reached the download-limit:')) $this->error("LimitAcc", true, false);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@http:\/\/\w+\.filechin\.com:443\/dl\/[^"\'><\r\n\t]+@i', $data, $link))
			return trim($link[0]);
		}
		else  
		return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* filechin.com Download Plugin by giaythuytinh176 [17.8.2013]
* Downloader Class By [FZ]
*/
?>