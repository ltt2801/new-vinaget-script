<?php

class dl_userscloud_com extends Download {

	public function FreeLeech($url) {
		$data = $this->lib->curl($url, "", "");
		if (strpos($data, "<Title>Download") === false) $this->error("dead", true, false, 2);
		else {
			$post1 = explode("/", $url)[3];
			preg_match('/name="fname" value="(.*)">/', $data, $post2);
			$post = "op=download2&usr_login=&id=".$post1."&fname=".$post2[1]."&referer=&method_free=Free+Download";
			$data2 = $this->lib->curl($url, "", $post);
			if (preg_match('@https?:\/\/.+?\.usercdn\.com:443\/d\/[^\'\"\t<>\r\n]+@i', $data2, $link)) {
				return $link[0];
			}
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Userscloud.com Download Plugin
* Downloader Class By Jetleech
* Date 13.5.2020
*/
?>