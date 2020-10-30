<?php
class dl_hotlink_cc extends Download
{
	public function CheckAcc($cookie)
	{
		$data = $this->lib->curl("https://hotlink.cc/?op=my_account", $cookie, "", 0);
		if (stristr($data, "Premium account expire")) {
			if (preg_match("/[0-9]+ Mb/", $data, $result)) {
				if ($result[0] != "0 Mb") {
					return array(true, "Until " . $this->lib->cut_str($data, 'style="text-align:left;"><span class="acc_data">', '</span>') . "<br/> Traffic Available: " . $result[0]);
				}

				return array(false, "Our hotlink.cc account has reach bandwidth limit");
			}

			return array(false, "accfree");
		}

		return array(false, "accinvalid");
	}

	public function Login($user, $pass)
	{
		$data = $this->lib->curl("https://hotlink.cc/", "", "op=login&login={$user}&password={$pass}", 1, 0, "https://hotlink.cc/login.html");
		$cookie = $this->lib->GetCookies($data);
		return array(true, $cookie);
	}

	public function Leech($url)
	{
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$id = $this->lib->cut_str($data, '<input type="hidden" name="id" value="', '">');
		$page = $this->lib->curl($url, $this->lib->cookie, "op=download2&id={$id}&method_premium=1");
		if (stristr("You have reached the download-limit", $page)) {
			$this->error("LimitAcc", true, false);
		} elseif (stristr("ile was deleted by its own", $page)) {
			$this->error("Link dead.", true, false);
		}

		if (preg_match('/<a href="(.*?) class="files_list--active">/', $page, $match)) {
			return trim($match[1]);
		}

		return false;
	}
}

/*
 * Open Source Project
 * New Vinaget by LTT
 * Version: 3.3 LTS
 * Hotlink.cc Download Plugin
 * Date: 21.08.2020
 */
