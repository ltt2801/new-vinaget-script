<?php

class dl_mega_co_nz extends Download {

	public function PreLeech($url){
		if (!extension_loaded('mcrypt') || !in_array('rijndael-128', mcrypt_list_algorithms(), true)) $this->error("Mcrypt module isn't installed or it doesn't have support for the needed encryption.", true, false);
	}
	
	public function FreeLeech($url) {
 		return trim($url);
		return false;  
    }

}

/* 
* Mega.co.nz Download Plugin by giaythuytinh176 [29.04.2015] 
*/
?>