<?php

class dl_chiasenhac_com extends Download {

    public function CheckAcc($cookie) {		// use acc free
        $data = $this->lib->curl("http://chiasenhac.com/member.php", $cookie, "");
        if(stristr($data, 'Tài khoản: <b>')) return array(true, "accfree");
        else return array(false, "accinvalid");
    }
     
    public function Login($user, $pass) {	
        $post["username"]= $user;
        $post["password"]= $pass;
        $post["redirect"] = "";
		$post["autologin"] = "checked";
		$post["login"] = 'Đăng nhập';
        $data = $this->lib->curl("http://chiasenhac.com/login.php","",$post);                        
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(!preg_match('@https?:\/\/chiasenhac\.com\/s\/(\d+)\.swf@i', $data, $id))
		$this->error("Cannot get ID", true, false, 2);	
		else 
		$thuytinh = $this->lib->curl("http://download.chiasenhac.com/download.php?m=".$id[1], $this->lib->cookie, "");
		if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[FLAC Lossless\]\.flac)"/', $thuytinh, $giay)) {
			if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[M4A 500kbps\]\.m4a)"/', $thuytinh, $giay)) {
				if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP3 320kbps\]\.mp3)"/', $thuytinh, $giay)) {
					if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP3 256kbps\]\.mp3)"/', $thuytinh, $giay)) {	
						if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP3 192kbps\]\.mp3)"/', $thuytinh, $giay)) {	
							if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP3 128kbps\]\.mp3)"/', $thuytinh, $giay)) {
								if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP3 64kbps\]\.mp3)"/', $thuytinh, $giay)) {
									if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[M4A 32kbps\]\.m4a)"/', $thuytinh, $giay)) {
										if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 1902x1080\]\.mp4)"/', $thuytinh, $giay)) {
											if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 HD 1080p\]\.mp4)"/', $thuytinh, $giay)) {
												if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 1280x726\]\.mp4)"/', $thuytinh, $giay)) {
													if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 HD 720p\]\.mp4)"/', $thuytinh, $giay)) {
														if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 1280x726\]\.mp4)"/', $thuytinh, $giay)) {
															if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 HD 720p\]\.mp4)"/', $thuytinh, $giay)) {
																if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 HD 480p\]\.mp4)"/', $thuytinh, $giay)) {
																	if(!preg_match('/href="(https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/(.+)\[MP4 HD 360p\]\.mp4)"/', $thuytinh, $giay)) {
																		if(preg_match('@https?:\/\/(:?(:?(data(\d+)?))|:?download)\.chiasenhac\.com\/downloads\/[^"\'><\r\n\t]+@i', $thuytinh, $giay))
																		return trim($giay[0]);
																	}
																	else
																	return trim($giay[1]);
																}
																else
																return trim($giay[1]);
															}
															else
															return trim($giay[1]);
														}
														else
														return trim($giay[1]);
													}
													else
													return trim($giay[1]);
												}
												else
												return trim($giay[1]);
											}
											else
											return trim($giay[1]);
										}
										else
										return trim($giay[1]);
									}
									else
									return trim($giay[1]);
								}
								else
								return trim($giay[1]);
							}
							else
							return trim($giay[1]);
						}
						else
						return trim($giay[1]);
					}
					else
					return trim($giay[1]);
				}
				else
				return trim($giay[1]);
			}
			else
			return trim($giay[1]);
		}
		else  
		return trim($giay[1]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Chiasenhac.com Download Plugin 
* Downloader Class By [FZ]
* Plugin By giaythuytinh176
* Date: 20.8.2013
*/
?>