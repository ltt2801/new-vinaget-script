<?php

class dl_mediafire_com extends Download {
	
	public function PreLeech($url) {
		if(stristr($url, "sharekey="))   $url = str_replace("http://www.mediafire.com/?sharekey=", "http://www.mediafire.com/folder/", $url);
		if(stristr($url, "/folder/")) {
			$url = explode('/', $url);
			$sharekey = $url[4];
			$data = $this->lib->curl("http://www.mediafire.com/api/folder/get_content.php?r=jyxt&content_type=files&order_by=name&order_direction=asc&version=2.13&folder_key={$sharekey}&response_format=json", "", "", 0);
			if(stristr($data, 'Unknown or invalid FolderKey') || stristr($data, 'Required parameters')) $this->error("dead", true, false, 2);
			else {
				$quickkey = explode('"quickkey":', $data);
				$maxqk = count($quickkey);
				for ($i = 1; $i < $maxqk; $i++) {
					preg_match('%"(.+)","filename%U', $quickkey[$i], $code);
					preg_match('%filename":"(.+)",%U', $quickkey[$i], $filename);
					preg_match('%size":"(.+)",%U', $quickkey[$i], $filesize);
					if($filesize[1] > 1024*1024*1024) $size = round($filesize[1]/(1024*1024*1024),2).' GB';
					else $size = round($filesize[1]/(1024*1024),2).' MB';  
					//$list = "http://www.mediafire.com/download/".$code[1]."/".urlencode($filename[1])."<br/>";
					$list = "<a href=http://www.mediafire.com/download/{$code[1]}/".urlencode($filename[1]).">http://www.mediafire.com/download/{$code[1]}/".urlencode($filename[1])."</a>  | <font color=blue face=Arial size=2>{$filename[1]}</font> | <font color=green face=Arial size=2>{$size}</font><br>";
					echo $list;
				}
			}
			exit;
		}
	}
	
    public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.mediafire.com/myaccount/billinghistory.php", $cookie, "");
		$dt = $this->lib->curl("https://www.mediafire.com/myaccount.php", $cookie, "");
		if(stristr($data, 'Billed on date of purchase') && !stristr($dt, '>Upgrade your Account<')) return array(true, "Until ".$this->lib->cut_str($data, '<div> <div class="lg-txt">','</div>'));
		elseif(stristr($data, 'You are not currently and never have been a MediaPro') || stristr($dt, '>Upgrade your Account<')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}	
	
	public function Login($user, $pass){
		$page1 = $this->lib->curl("http://www.mediafire.com/", "", "");
		$cookies = $this->lib->GetCookies($page1);
		$page2 = $this->lib->curl("http://www.mediafire.com/dynamic/login.php?popup=1", $cookies, "login_email={$user}&login_pass={$pass}&login_remember=1&submit_login=Log%20in%20to%20MediaFire");
		$cookie = "{$cookies};{$this->lib->GetCookies($page2)}";
		return $cookie;
	}
/*
	public function FreeLeech($url) {
		//echo '<center><form id="formlink" name="formlink" action="javascript:ajaxget(\'0\',\'http://www.mediafire.com/download/h82oa8cg3572f92/Tuyen_tap_de_thi_Toan_HNUE-_4_nam.rar\',\'getlink\',\'reget\');"><table><tbody><tr><td><img src="http://www.google.com/recaptcha/api/image?c=03AHJ_Vuue47Hb0pwJjVMdTh4tW4xV6F9z-E9JrZbtPbmKuW48kMPU7Lvd6exgByhODIDgnDxyVaWptz8PzaYm5ZgVj7RDfwvF6NrozxT8yLiVxuThAvO-hQtN5HvI1b0iN_fNr9bjTCLN0_X3_-UYIHwkkDV7LggvfiBOqumUsSRAedlfIlD5hwk"></img></td><td>
     // Authentication Required
  //  <br></br><input type="text" maxlength="50" size="20" value="" name="recaptcha_response_field"></input><input type="hidden" value="03AHJ_Vuue47Hb0pwJjVMdTh4tW4xV6F9z-E9JrZbtPbmKuW48kMPU7Lvd6exgByhODIDgnDxyVaWptz8PzaYm5ZgVj7RDfwvF6NrozxT8yLiVxuThAvO-hQtN5HvI1b0iN_fNr9bjTCLN0_X3_-UYIHwkkDV7LggvfiBOqumUsSRAedlfIlD5hwk" name="recaptcha_challenge_field"></input><input type="hidden" value="0" name="random"></input><input type="submit" value="Go"></input><a style="TEXT-DECORATION: none" href="javascript:void(0)" onclick="ajaxget(\'0\',\'http://www.mediafire.com/download/h82oa8cg3572f92/Tuyen_tap_de_thi_Toan_HNUE-_4_nam.rar\',\'getlink\',\'0\');">
  //    Refresh
 //   </a></td></tr></tbody></table></form></center>';
		
		$data = $this->lib->curl("http://vinaget.us/getmf/index.php?rand=".time(), "", "url=".urlencode($url)."&type=getlink&captcha=&", 0);
		echo '<textarea rows="5" cols="100">'.$data.'</textarea>';exit;
		return false;
    }*/
	
/*
	public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$fileID = $this->exploder('/', $url, 4);
		if($pass) $this->lib->curl("http://www.mediafire.com/?{$fileID}",$this->lib->cookie,"downloadp=".$pass);
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,"error.php")) $this->error("dead", true, false, 2);
		elseif(preg_match ( '/ocation: (.*)/', $data, $linkpre)) return trim ($linkpre[1]);
		elseif(stristr($data,'<div class="password_download_link"')) $this->error("notsupportpass", true, false);	
		elseif(stristr($data,'class="download_link"')) {
			$page = $this->lib->cut_str($data, 'class="download_link"', "output");
			if (preg_match('/(http.+)"/i', $page, $value)) return trim($value[1]);
		}
		elseif(stristr($data,"6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe")) {
			$page = $this->lib->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
			if(preg_match("%challenge : '(.*)'%U", $page, $matches)) $this->error("captcha code '".trim($matches[1])."' rand '{$rand}'", true, false);
			else $this->error("Mediafire Authentication Required. Contact admin!", true, false);
		}
		elseif(stristr($data,"This file is temporarily unavailable because")) $this->error("File too big, only allowed 200MB", false, false, 2);
		return false;
    }		 */
	
		/*	
		$data = $this->lib->curl('http://urlchecker.net/', "", 'links='.urlencode($url).'&submit=Check Links');
		$data = $this->lib->cut_str($data, '<td class="working">', 'class="live');
		if(stristr($data,'Working</td>'))	$url = $this->lib->cut_str($data, 'href="', '" target');
		else $this->error("dead", true, false, 2);
		
		$data = $this->lib->curl('http://10s.mobi/', "", 'urllist='.urlencode($url));
		if(stristr($data,'<img src=chk_good.png '))	$url = $this->lib->cut_str($data, '<br><B><a href=', '>');
		else $this->error("dead", true, false, 2);
		*/
		
    public function Leech($url) {
		if(stristr($url, "mediafire.com/?")) {
			$ex = explode("?", $url);
			$url = "http://www.mediafire.com/download/".$ex[1];
		}
		$url = preg_replace("@https?:\/\/(www\.)?mediafire\.com\/(view|edit|watch|listen|play)@", "http://www.mediafire.com/download", $url);	
		list($url, $pass) = $this->linkpassword($url);
		$fileID = $this->exploder('/', $url, 4);
		if($pass)   $data = $this->lib->curl("http://www.mediafire.com/?{$fileID}",$this->lib->cookie,"downloadp={$pass}");
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,'Please enter password to unlock this file')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,"error.php"))  $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			if(preg_match('/kNO = "(http:\/\/.+)";/i', $data, $giay))	return trim($giay[1]);
		}
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Mediafire Download Plugin 
* Downloader Class By [FZ]
* Add check account by giaythuytinh176 [19.8.2013]
* Special thanks to Rapid61@rapidleech.com for your MediaFire account.
*/
?>