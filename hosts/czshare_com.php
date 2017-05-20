<?php

class dl_czshare_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://czshare.com/", $cookie, "");
        if(stristr($data, 'kredit: <strong>')) return array(true, "kredit: ".$this->lib->cut_str($this->lib->cut_str($data, '<div class="credit">','</div><!-- .credit -->'), '<strong>','</strong>'));
        elseif(stristr($data, '>CZShare manager<') && !stristr($data, 'kredit: <strong>')) return array(false, "accfree");
        else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("https://czshare.com/index.php", "","login-name={$user}&login-password={$pass}&trvale=1&Prihlasit=Přihlásit SSL&submit=Obnov heslo");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
 
	
    public function Leech($url) {
 		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,">404 - Stránka nenalezena<span>") || stristr($data,">Soubor nenalezen<span>"))   $this->error("dead", true, false, 2);
		$id = $this->lib->cut_str($data, "name=\"id\" value=\"", "\"");
		$code = $this->lib->cut_str($data, "name=\"code\" value=\"", "\"");
		$data = $this->lib->curl("http://czshare.com/profi_down.php", $this->lib->cookie, "id={$id}&code={$code}");
		if($this->isredirect($data))   return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* czshare Download Plugin  by giaythuytinh176 [1.8.2013]
* Downloader Class By [FZ]
*/
?>