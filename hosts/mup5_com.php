<?php

class dl_mup5_com extends Download {

	public function FreeLeech($url) {
        $data = $this->lib->curl($url, "", "");
		if(stristr($data,'Error 404!Please come back <a href="http://mup5.com">home') || stristr($data,'Files have been deleted for breach of'))  $this->error("dead", true, false, 2);
		elseif(preg_match('@https?:\/\/mup5\.com\/dl\.php\?token=[^"\'><\r\n\t]+@i', "http://mup5.com/dl.php?token={$this->lib->cut_str($data, 'token" value="', '">')}", $giay))
		return trim($giay[0]);
	return false;
  } 
  
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* mup5.com Download Plugin by giaythuytinh176 [4.8.2013]
* Downloader Class By [FZ]
*/
?>