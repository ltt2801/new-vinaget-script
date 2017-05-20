<?php

class dl_firedrive_com extends Download {

    public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.firedrive.com/my_settings", $cookie, "");
        if(stristr($data, '>You currently have a Pro')) {
			$checkbw = $this->lib->cut_str($data, '>Ad Free Bandwidth<', '>Upgrade<');
			preg_match("/overview_bottomright_text\" id='storage_total'>(\d+(\.\d+)? (T|G|M|K)B) remaining/i", $checkbw, $bw);
			return array(true, "Until " .(strpos($data, 'Pro features end on: ') ? $this->lib->cut_str($data, 'Pro features end on: ', '</span>') : $this->lib->cut_str($data, 'Next billing date: ', '</span>')). "<br/> Free Bandwidth: " .$bw[1]. " remaining");
        }
		else if(stristr($data, '>You currently have a Free<')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	
    public function Login($user, $pass){
		$post = "user={$user}&pass={$pass}&remember=1&login_submit=Log%20In";
        $data = $this->lib->curl("https://auth.firedrive.com/", "", $post);
		return $this->lib->GetCookies($data);
    }
	
    public function FreeLeech($url) {
		$url = str_replace('https', 'http', $url);
		$url = str_replace('putlocker', 'firedrive', $url);
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, '', '');  
		if(stristr($data, '>File is private | Firedrive<')) $this->error('File is private', true, false); 
		elseif(stristr($data,'404: This file might have been moved, replaced or deleted.')) $this->error("dead", true, false, 2);
		$this->save($this->lib->GetCookies($data));
		if($pass) {
			$post = array(
				'item_pass' => $pass,
			);
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->lib->reserved['filename'] = $this->lib->cut_str($data, '<title>', ' | Firedrive<');
			if(stristr($data,'>Wrong Password<')) $this->error("wrongpass", true, false, 2);
			elseif(preg_match('/a href="(https?:\/\/dl\.firedrive\.com\/\?key=.*)" t/i', $data, $redir)) 
			return trim($redir[1]);
		}
		if(stristr($data,'This file is password protected.')) $this->error("reportpass", true, false);
		elseif(stristr($data,'You have exceeded the daily download limit for your country')) $this->error("You have exceeded the daily download limit for your country", true, false);
		$data = $this->lib->curl($url, $this->lib->cookie, array('confirm' => $this->lib->cut_str($data, 'confirm" value="', '"/>')));  
		$this->lib->reserved['filename'] = $this->lib->cut_str($data, '<title>', ' | Firedrive<');
		if(preg_match('/a href="(https?:\/\/dl\.firedrive\.com\/\?key=.*)" t/i', $data, $redir))
		return trim($redir[1]);
		return false;
    }
	
    public function Leech($url) {
		$url = str_replace('https', 'http', $url);
		$url = str_replace('putlocker', 'firedrive', $url);
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");  
		if(stristr($data, '>File is private | Firedrive<')) $this->error('File is private', true, false); 
		if($pass) { 
			$data = $this->lib->curl($url, $this->lib->cookie, array('item_pass' => $pass,));
			$this->lib->reserved['filename'] = $this->lib->cut_str($data, '<title>', ' | Firedrive<');
			if(stristr($data, '>Wrong Password<'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('/a href="(https?:\/\/dl\.firedrive\.com\/\?key=.*)" t/i', $data, $redir)) 
			return trim($redir[1]);
		}
		if(stristr($data, 'This file is password protected.')) $this->error("reportpass", true, false);
		elseif(stristr($data, '404: This file might have been moved, replaced or deleted.')) $this->error("dead", true, false, 2);
		elseif(preg_match('/a href="(https?:\/\/dl\.firedrive\.com\/\?key=.*)" t/i', $data, $redir)) {
			$this->lib->reserved['filename'] = $this->lib->cut_str($data, '<title>', ' | Firedrive<');
			return trim($redir[1]);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* FireDrive Download Plugin by giaythuytinh176 [14.2.2014]
* Downloader Class By [FZ]
*/
?>