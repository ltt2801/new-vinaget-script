<?php

class dl_solidfiles_com extends Download {
	
	public function FreeLeech($url){
		
		$data = $this->lib->curl($url, "", "");
		$ck = $this->lib->GetCookies($data);

		if (stristr($data, 'btn btn-primary btn-sm')) {
            preg_match('/csrfmiddlewaretoken\' value=\'(.*)\'/', $data, $match);
            preg_match('/<form action="(.*?)"/', $data, $match2);
			
           $data2 = $this->lib->curl('http://www.solidfiles.com'.$match2[1], $ck, 'csrfmiddlewaretoken='.$match[1]);
			
            preg_match('/seconds\, <a href="(.*)">click/', $data2, $match3);
			return $match3[1];
			}
		elseif (stristr($data, '301 Moved Permanently') || stristr($data, 'This file/folder could not be found')) {
            $this->error("dead", true, false, 2);
        }
		else {
            return trim($this->redirect);
        }
        return false;

}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
 * Created By Enigma [Jetleech.net]
 * Date: 06.05.2020
*/
?>
