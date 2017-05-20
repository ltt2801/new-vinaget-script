<?php

class dl_uploadbaz_com extends Download {
	
    public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.uploadbaz.com/?op=my_account", "lang=english;{$cookie}", "");
		if(stristr($data, '<a href="http://www.uploadbaz.com/?op=payments">Upgrade to premium</a>')) return array(false, "accfree");
		elseif(stristr($data, 'Premium Account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium Account expire:</TD><TD><b>','</b>')."<br/> Traffic available today: ".$this->lib->cut_str($data, 'Traffic available today:</TD><TD><b>','</b></TD><TD></TD></TR>'));
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.uploadbaz.com/login.html", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
	}
/*
    public function FreeLeech($url) {	//Thanks to Th3-822@rapidleech.com
		list($url, $pass) = $this->linkpassword($url);
        $page = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($page));
		$post = $this->parseForm($this->lib->cut_str($page, '<Form method="POST" action=', '</form>'));
		$post['method_free'] = "Free Download";
		$post['method_premium'] = "";
		
		$page = $this->lib->curl($url, $this->lib->cookie, $post);
		if(preg_match('@You have reached the download-limit: \d+ Mb for last 1 days@i', $page, $limit)) $this->error($limit[0], true, false);
		elseif(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $page, $count)) 	$this->error($count[0], true, false);
		elseif(preg_match('@You can download files up to \d+ [KMG]b only@i', $page, $sizelim)) 	$this->error($sizelim[0], true, false);
		
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
				elseif(preg_match('@https?:\/\/(\w+\.)?uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $page, $dlink)) 
				return trim($dlink[0]);
			}
			if(stristr($page,'<input type="password" name="password" class="myForm">')) 	$this->error("reportpass", true, false);
			
			$page = $this->lib->curl($url, $this->lib->cookie, $post);

			if(stristr($page,'>Skipped countdown'))  $this->error("Error: Skipped countdown?.", true, false);
			elseif(stristr($page,'>Wrong captcha<'))  $this->error("Error: Unknown error after sending decoded captcha.", true, false);
			elseif(stristr($page,'>Expired session<'))  $this->error("Error: Expired Download Session.", true, false);
			elseif(preg_match('@You can download files up to \d+ [KMG]b only.@i', $page, $err))  $this->error('Error: '.$err[0], true, false);	
			elseif(preg_match('@https?:\/\/(\w+\.)?uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $page, $dlink)) 
			return trim($dlink[0]);
		}
		return false;	
	}				*/
	
	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/(\w+\.)?uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'<input type="password" name="password" class="myForm">')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'<b>File Not Found</b><br><br>')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/(\w+\.)?uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		else 	return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uploadbaz Download Plugin by riping [22/7/2013]
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [29.7.2013]
*/
?>