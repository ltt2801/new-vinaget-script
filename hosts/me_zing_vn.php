<?php

class dl_me_zing_vn extends Download {

	public function FreeLeech($url){
		$thuytinh = file_get_contents($this->lib->cut_str(file_get_contents($url), 'width="100%" src="', '"'));
		if(stristr($thuytinh,'File không tồn tại')) $this->error("dead", true, false, 2);
		elseif(preg_match('/a href="(https?:\/\/.+zing.vn.+)" target/i', $thuytinh, $giay)) 
		return trim($giay[1]);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Me.zing.vn Download Plugin by giaythuytinh176 [5.8.2013]
* Downloader Class By [FZ]
*/
?>