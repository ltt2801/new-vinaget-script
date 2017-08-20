<?php
/*
* Vinaget by LTT♥
* Script Name: Vinaget 
* Version: 3.3 LTSB
*/

/* Set default timezone */
date_default_timezone_set('UTC');

// #################################### Begin class getinfo #####################################
class getinfo extends Tools_get
{
	function config()
	{
		$this->self = 'http://' . $_SERVER['HTTP_HOST'] . preg_replace('/\?.*$/', '', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
		$this->Deny = true;
		$this->admin = false;
		$this->fileinfo_dir = "data";
		$this->filecookie = "/cookie.dat";
		$this->fileconfig = "/config.dat";
		$this->fileaccount = "/account.dat";
		$this->fileinfo_ext = "vng";
		$this->banned = explode(' ', '.htaccess .htpasswd .php .php3 .php4 .php5 .phtml .asp .aspx .cgi .pl');
		$this->unit = 512;
		$this->UserAgent = 'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.04';
		$this->config = $this->load_json($this->fileconfig);
		include ("config.php");
		if(count($this->config) == 0) {	
			$this->config = $config;
			$_GET['id'] = 'admin';
			$this->Deny = false;
			$this->admin = true;
		}
		else{
			foreach($config as $key=>$val){
				if (!isset($this->config[$key])) $this->config[$key] = $val;
			}
			if ($this->config['secure'] == false) $this->Deny = false;
			$password = explode(", ", $this->config['password']);
			$password[] = $this->config['admin'];
			foreach($password as $login_vng) if (isset($_COOKIE["secureid"]) && $_COOKIE["secureid"] == md5($login_vng)) {
				$this->Deny = false;
				break;
			}
		}
		$this->set_config();
		if (!file_exists($this->fileinfo_dir)) {
			mkdir($this->fileinfo_dir) or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$this->fileinfo_dir</B>\" to 777</font></CENTER>");
			@chmod($this->fileinfo_dir, 0777);
		}
		if (!file_exists($this->fileinfo_dir . "/files")) {
			mkdir($this->fileinfo_dir . "/files") or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$this->fileinfo_dir/files</B>\" to 777</font></CENTER>");
			@chmod($this->fileinfo_dir . "/files", 0777);
		}
		if (!file_exists($this->fileinfo_dir . "/index.php")) {
			$clog = fopen($this->fileinfo_dir . "/index.php", "a") or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$this->fileinfo_dir</B>\" to 777</font></CENTER>");
			fwrite($clog, '<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://' . $homepage . '">');
			fclose($clog);
			@chmod($this->fileinfo_dir . "/index.php", 0666);
		}
		if (!file_exists($this->fileinfo_dir . "/files/index.php")) {
			$clog = fopen($this->fileinfo_dir . "/files/index.php", "a") or die("<CENTER><font color=red size=4>Could not create folder! Try to chmod the folder \"<B>$this->fileinfo_dir/files</B>\" to 777</font></CENTER>");
			fwrite($clog, '<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://' . $homepage . '">');
			fclose($clog);
			@chmod($this->fileinfo_dir . "/files/index.php", 0666);
		}
	}
	
	function set_config(){
		include("lang/{$this->config['language']}.php");
		$this->lang = $lang;
		$this->Secure = $this->config['secure'];
		$this->skin = $this->config['skin'];
		$this->download_prefix = $this->config['download_prefix'];
		$this->download_suffix = $this->config['download_suffix'];
		$this->limitMBIP = $this->config['limitMBIP'];
		$this->ttl = $this->config['ttl'];
		$this->limitPERIP = $this->config['limitPERIP'];
		$this->ttl_ip = $this->config['ttl_ip'];
		$this->max_jobs_per_ip = $this->config['max_jobs_per_ip'];
		$this->max_jobs = $this->config['max_jobs'];
		$this->max_load = $this->config['max_load'];
		$this->max_size_default = $this->config['max_size_default'];
		$this->file_size_limit = $this->config['file_size_limit'];
		$this->adslink = $this->config['adslink'];
		$this->api_ads = $this->config['api_ads'];
		$this->tinyurl = $this->config['tinyurl'];
		$this->badword = explode(", ", $this->config['badword']);	
		$this->act = array('rename' => $this->config['rename'], 'delete' => $this->config['delete']);
		$this->listfile = $this->config['listfile'];
		$this->showlinkdown = $this->config['showlinkdown'];
		$this->checkacc = $this->config['checkacc'];
		$this->privatef = $this->config['privatefile'];
		$this->privateip = $this->config['privateip'];
		$this->redirdl = $this->config['redirectdl'];
		$this->check3x = $this->config['checklinksex'];
		$this->colorfn = $this->config['colorfilename'];
		$this->colorfs = $this->config['colorfilesize'];
		$this->title = $this->config['title'];
		$this->directdl = $this->config['showdirect'];
		$this->longurl = $this->config['longurl'];
		$this->display_error = $this->config['display_error'];
		$this->proxy = false;
		$this->bbcode = $this->config['bbcode'];
		$this->cbox_url = $this->config['cbox_url'];
		$this->hide_plugins_col = $this->config['hide_plugins_col'];
		$this->hide_preacc_col = $this->config['hide_preacc_col'];
		$this->hide_number_acc = $this->config['hide_number_acc'];
		$this->show_func_cbox = $this->config['show_func_cbox'];
		$this->del_checked_acc = $this->config['del_checked_acc'];
		$this->prox = isset($_POST['proxy']) ? $_POST['proxy'] : "";
		$this->autopost = isset($_POST['autopcbox']) ? $_POST['autopcbox'] : "";
		$this->autosearch = isset($_POST['autosearchuser']) ? $_POST['autosearchuser'] : "";
		$this->user = isset($_POST['nick']) ? $_POST['nick'] : "";
		$this->pass = isset($_POST['pass']) ? $_POST['pass'] : "";
	}
	
	function isadmin(){
		return (isset($_COOKIE['secureid']) && $_COOKIE['secureid'] == md5($this->config['admin']) ? true : $this->admin);
	}
	
	function getversion(){
		$version = $this->curl("https://raw.githubusercontent.com/ltt2801/new-vinaget-script/master/version.txt","","",0);
		return intval($version);
	}
	
	function notice($id="notice")
	{
		if($id=="notice") return sprintf($this->lang['notice'], Tools_get::convert_time($this->ttl * 60) , $this->limitPERIP, Tools_get::convert_time($this->ttl_ip * 60));
		else {
			$this->CheckMBIP();
			$MB1IP = Tools_get::convertmb($this->countMBIP * 1024 * 1024);
			$thislimitMBIP = Tools_get::convertmb($this->limitMBIP * 1024 * 1024);
			$maxsize = Tools_get::convertmb($this->max_size_other_host * 1024 * 1024);
			if($id=="yourip") return $this->lang['yourip'];
			if($id=="yourjob") return $this->lang['yourjob'];
			if($id=="userjobs") return' '.$this->lookup_ip($_SERVER['REMOTE_ADDR']).' (max '.$this->max_jobs_per_ip.') ';
			if($id=="youused") return sprintf($this->lang['youused']);
			if($id=="used") return' '.$MB1IP.' (max '.$thislimitMBIP.') ';
			if($id=="sizelimit") return $this->lang['sizelimit'];
			if($id=="maxsize") return $maxsize;
			if($id=="totjob") return $this->lang['totjob'];
			if($id=="totjobs") return' '.count($this->jobs).' (max '.$this->max_jobs.') ';
			if($id=="serverload") return $this->lang['serverload'];
			if($id=="maxload") return' '.$this->get_load().' (max '.$this->max_load.') ';
			if($id=="uonline") return $this->lang['uonline'];
			if($id=="useronline") return Tools_get::useronline();
		}
	}
	
	function load_jobs()
	{
		if (isset($this->jobs)) return;
		$dir = opendir($this->fileinfo_dir . "/files/");
		$this->lists = array();
		while ($file = readdir($dir)) {
			if (substr($file, -strlen($this->fileinfo_ext) - 1) == "." . $this->fileinfo_ext) {
				$this->lists[] = $this->fileinfo_dir . "/files/" . $file;
			}
		}
		closedir($dir);
		$this->jobs = array();
		if (count($this->lists)) {
			sort($this->lists);
			foreach($this->lists as $file) {
				$contentsfile = @file_get_contents($file);
				$jobs_data = @json_decode($contentsfile, true);
				if (is_array($jobs_data)) {
					$this->jobs = array_merge($this->jobs, $jobs_data);
				}
			}
		}
	}
	
	function save_jobs()
	{
		if (!isset($this->jobs) || is_array($this->jobs) == false) return;
		// ## clean jobs ###
		$oldest = time() - $this->ttl * 60;
		$delete = array();
		foreach($this->jobs as $key => $job) {
			if ($job['mtime'] < $oldest) {
				$delete[] = $key;
			}
		}
		foreach($delete as $key) {
			unset($this->jobs[$key]);
		}
		// ## clean jobs ###
		$namedata = $timeload = explode(" ", microtime());
		$namedata = $namedata[1] * 1000 + round($namedata[0] * 1000);
		$this->fileinfo = $this->fileinfo_dir . "/files/" . $namedata . "." . $this->fileinfo_ext;
		$tmp = @json_encode($this->jobs);
		$fh = fopen($this->fileinfo, 'w') or die('<CENTER><font color=red size=3>Could not open file ! Try to chmod the folder "<B>' . $this->fileinfo_dir . "/files/" . '</B>" to 777</font></CENTER>');
		fwrite($fh, $tmp);
		fclose($fh);
		@chmod($this->fileinfo, 0666);
		if (count($this->lists)) foreach($this->lists as $file) if (file_exists($file)) @unlink($file);
		return true;
	}
	
	function load_json($file)
	{
		$hash = substr($file, 1);
		$this->json[$hash] = @file_get_contents($this->fileinfo_dir . $file);
		$data = @json_decode($this->json[$hash], true);
		if (!is_array($data)) {
			$data = array();
			$this->json[$hash] = 'default';
		}
		return $data;
	}
	
	function save_json($file, $data)
	{
		$tmp = json_encode($data);
		$hash = substr($file, 1);
		if ($tmp !== $this->json[$hash]) {
			$this->json[$hash] = $tmp;
			$fh = fopen($this->fileinfo_dir . $file, 'w') or die('<CENTER><font color=red size=3>Could not open file ! Try to chmod the folder "<B>' . $this->fileinfo_dir . '</B>" to 777</font></CENTER>');
			fwrite($fh, $this->json[$hash]) or die('<CENTER><font color=red size=3>Could not write file ! Try to chmod the folder "<B>' . $this->fileinfo_dir . '</B>" to 777</font></CENTER>');
			fclose($fh);
			@chmod($this->fileinfo_dir . $file, 0666);
			return true;
		}
	}
	
	function load_cookies()
	{
		if (isset($this->cookies)) return;
		$this->cookies = $this->load_json($this->filecookie);
	}
	
	function get_cookie($site)
	{
		$cookie = "";
		if (isset($this->cookies) && count($this->cookies) > 0) {
			foreach($this->cookies as $ckey => $cookies) {
				if ($ckey === $site) {
					$cookie = $cookies['cookie'];
					break;
				}
			}
		}
		return $cookie;
	}
	
	function save_cookies($site, $cookie)
	{
		if (!isset($this->cookies)) return;
		if ($site) {
			$cookies = array(
				'cookie' => $cookie,
				'time' => time() ,
			);
			$this->cookies[$site] = $cookies;
		}
		$this->save_json($this->filecookie, $this->cookies);
	}
	
	function load_account(){
		if (isset($this->acc)) return;
		$this->acc = $this->load_json($this->fileaccount);
		foreach($this->list_host as $site => $host) {
			if(!$host['alias']){
				if(empty($this->acc[$site]['proxy'])) $this->acc[$site]['proxy'] = "";
				if(empty($this->acc[$site]['direct'])) $this->acc[$site]['direct'] = false;
				if(empty($this->acc[$site]['max_size'])) $this->acc[$site]['max_size'] = $this->max_size_default;
				if(empty($this->acc[$site]['accounts'])) $this->acc[$site]['accounts'] = array();
			}
		}		
	}
	
	function save_account($service, $acc){
		foreach ($this->acc[$service]['accounts'] as $value) if ($acc == $value) return false; 
		if(empty($this->acc[$service])) $this->acc[$service]['max_size'] = $this->max_size_default;
		$this->acc[$_POST['type']]['accounts'][] = $_POST['account'];
		$this->save_json($this->fileaccount, $this->acc);
	}
	
	function get_account($service)
	{
		$acc = '';
		if (isset($this->acc[$service])) {
			$service = $this->acc[$service];
			$this->max_size = $service['max_size'];
			if (count($service['accounts']) > 0) $acc = $service['accounts'][rand(0, count($service['accounts']) - 1) ];
		}
		return $acc;
	}
	
	function lookup_job($hash)
	{
		$this->load_jobs();
		foreach($this->jobs as $key => $job) {
			if ($job['hash'] === $hash) return $job;
		}
		return false;
	}
	
	function check_jobs()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$heute = 0;
		$lasttime = time();
		$altr = $lasttime - $this->ttl_ip * 60;
		foreach($this->jobs as $job) {
			if ($job['ip'] === $ip && $job['mtime'] > $altr) {
				$heute++;
				if ($job['mtime'] < $lasttime) $lasttime = $job['mtime'];
			}
		}
		$lefttime = $this->ttl_ip * 60 - time() + $lasttime;
		$lefttime = $this->convert_time($lefttime);
		return array(
			$heute,
			$lefttime
		);
	}
	
	function get_load($i = 0)
	{
		$load = array(
			'0',
			'0',
			'0'
		);
		if (@file_exists('/proc/loadavg')) {
			if ($fh = @fopen('/proc/loadavg', 'r')) {
				$data = @fread($fh, 15);
				@fclose($fh);
				$load = explode(' ', $data);
			}
		}
		else {
			if ($serverstats = @exec('uptime')) {
				if (preg_match('/(?:averages)?\: ([0-9\.]+),?[\s]+([0-9\.]+),?[\s]+([0-9\.]+)/', $serverstats, $matches)) {
					$load = array(
						$matches[1],
						$matches[2],
						$matches[3]
					);
				}
			}
		}
		return $i == - 1 ? $load : $load[$i];
	}
	
	function lookup_ip($ip)
	{
		$this->load_jobs();
		$cnt = 0;
		foreach($this->jobs as $job) {
			if ($job['ip'] === $ip) $cnt++;
		}
		return $cnt;
	}
}
// #################################### End class getinfo #######################################
// #################################### Begin class stream_get ##################################

class stream_get extends getinfo
{ 
	function stream_get()
	{
		$this->config();
		$this->max_size_other_host = $this->file_size_limit;
		$this->load_jobs();
		$this->load_cookies();
		$this->cookie = '';
		if (preg_match('%^(http.+.index.php)/(.*?)/(.*?)/%U', $this->self, $redir)) {
			if (stristr($redir[3], 'mega_')) $this->downloadmega($redir[3]);
			else $this->download($redir[3]);
		}
		elseif (isset($_REQUEST['file'])) {
			if (stristr($_REQUEST['file'], 'mega_')) $this->downloadmega($_REQUEST['file']);
			else $this->download($_REQUEST['file']);
		}
		else{
			include ("hosts/hosts.php");
			ksort($host);
			$this->list_host = $host;
			$this->load_account();
		}
		if (isset($_COOKIE['owner'])) {
			$this->owner = $_COOKIE['owner'];
		}
		else {
			$this->owner = intval(rand() * 10000);
			setcookie('owner', $this->owner, 0);
		}
	}
	
	function download($hash)
	{
		error_reporting(0);
		$job = $this->lookup_job($hash);
		if (!$job) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			die($this->lang['errorget']);
		}
		if (($_SERVER['REMOTE_ADDR'] !== $job['ip']) && $this->privateip == true) {
			sleep(15);
			die($this->lang['errordl']);
		}
		if ($this->get_load() > $this->max_load) sleep(15);
		$link = '';
		$filesize = $job['size'];
		$referer = urldecode($job['url']);
		$filename = $this->download_prefix . Tools_get::convert_name($job['filename']) . $this->download_suffix;
		$directlink = urldecode($job['directlink']['url']);
		$this->cookie = $job['directlink']['cookies'];
		$link = $directlink;
		$link = str_replace(" ", "%20", $link);

		if (!$link) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			$this->error1('erroracc');
		}
		if ($job['proxy'] != 0 && $this->redirdl == true) {
			list($ip, ) = explode(":", $job['proxy']);
			if($_SERVER['REMOTE_ADDR'] != $ip) { 
				$this->wrong_proxy($job['proxy']);
			}
			else {
				header('Location: '.$link);
				die;
			}
		}
		$range = '';
		if (isset($_SERVER['HTTP_RANGE'])) {
			$range = substr($_SERVER['HTTP_RANGE'], 6);
			list($start, $end) = explode('-', $range);
			$new_length = $filesize - $start;
		}
		$port = 80;
		$schema = parse_url(trim($link));
		$host = $schema['host'];
		$scheme = "http://";
		$gach = explode("/", $link);
		list($path1, $path) = explode($gach[2], $link);
		if (isset($schema['port'])) $port = $schema['port'];
		elseif ($schema['scheme'] == 'https') {
			$scheme = "ssl://";
			$port = 443;
		}
		if ($scheme != "ssl://") {
			$scheme = "";
		}
		$hosts = $scheme . $host . ':' . $port;
		if($job['proxy'] != 0){
			if(strpos($job['proxy'], "|")){
				list($ip, $user) = explode("|", $job['proxy']);
				$auth = base64_encode($user);
			}
			else $ip = $job['proxy'];
			$data = "GET {$link} HTTP/1.1\r\n";
			if(isset($auth)) $data.= "Proxy-Authorization: Basic $auth\r\n";
			$fp = @stream_socket_client("tcp://{$ip}", $errno, $errstr, 20, STREAM_CLIENT_CONNECT);
		}
		else {
			$data = "GET {$path} HTTP/1.1\r\n";
			$fp = @stream_socket_client($hosts, $errno, $errstr, 20, STREAM_CLIENT_CONNECT);
		}
		if (!$fp) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			die("HTTP/1.1 404 Not Found");
		}
		$data.= "User-Agent: " . $this->UserAgent . "\r\n";
		$data.= "Host: {$host}\r\n";
		$data.= "Accept: */*\r\n";
		$data.= "Referer: {$referer}\r\n";
		$data.= $this->cookie ? "Cookie: " . $this->cookie . "\r\n" : '';
		if (!empty($range)) $data.= "Range: bytes={$range}\r\n";
		$data.= "Connection: Close\r\n\r\n";
		@stream_set_timeout($fp, 2);
		fputs($fp, $data);
		fflush($fp);
		$header = '';
		$header .= stream_get_line($fp, $this->unit);
		
		do {
			if (!$header) {
				$header .= stream_get_line($fp, $this->unit);
				print_r($header);
				if (!stristr($header, "HTTP/1")) break;
			}
			else $header .= stream_get_line($fp, $this->unit);
		}
		while (stristr($header, "\r\n") == false);

		/* debug */
		if ($this->isadmin() && isset($_GET['debug'])) {
			// Uncomment next line for enable to admins this debug code.
			// echo "<pre>connected to : $hosts ".($job['proxy'] == 0 ? '' : "via {$job['proxy']}")."\r\n$data\r\n\r\nServer replied: \r\n$header</pre>";
			die();
		}
		/* debug */
		// Must be fresh start
		if (headers_sent()) die('Headers Sent');
		// Required for some browsers
		if (ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');
		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false); // required for certain browsers
		header("Content-Transfer-Encoding: binary");
		header("Accept-Ranges: bytes");

		if (stristr($header, "TTP/1.0 200 OK") || stristr($header, "TTP/1.1 200 OK")) {
			if (!is_numeric($filesize)) $filesize = trim($this->cut_str($header, "Content-Length:", "\n"));
			if (stristr($header, "filename") && !$filename) {
				$filename = trim($this->cut_str($header, "filename", "\n"));
				$filename = preg_replace("/(\"\;\?\=|\"|=|\*|UTF-8|\')/", "", $filename);
				$filename = $this->download_prefix . $filename . $this->download_suffix;
			}
			if (is_numeric($filesize)) {
				header("HTTP/1.1 200 OK");
				header("Content-Type: application/force-download");
				header("Content-Disposition: attachment; filename=\"".str_replace(" ","_",$filename)."\"");
				header("Content-Length: {$filesize}");
			}
			else {
				sleep(5);
				header("HTTP/1.1 404 Not Found");
				die("HTTP/1.1 404 Not Found");
			}
		}
		elseif (stristr($header, "TTP/1.1 206") || stristr($header, "TTP/1.0 206")) {
			sleep(2);
			header("HTTP/1.1 206 Partial Content");
			header("Content-Type: application/force-download");
			header("Content-Length: $new_length");
			header("Content-Range: bytes $range/{$filesize}");
		}
		else {
			sleep(10);
			header("HTTP/1.1 404 Not Found");
			die("HTTP/1.1 404 Not Found");
		}
		$tmp = explode("\r\n\r\n", $header);
		$max = count($tmp);
		for ($i = 1; $i < $max; $i++) {
			print $tmp[$i];
			if ($i != $max - 1) echo "\r\n\r\n";
		}
		while (!feof($fp) && (connection_status() == 0)) {
			$recv = @stream_get_line($fp, $this->unit);
			@print $recv;
			@flush();
			@ob_flush();
		}
		fclose($fp);
		exit;
	}

	function downloadmega($hash)
	{
		error_reporting(0);
		$job = $this->lookup_job($hash);
		if (!$job) {
			sleep(15);
			header("HTTP/1.1 404 Not Found");
			die($this->lang['errorget']);
		}
		if (($_SERVER['REMOTE_ADDR'] !== $job['ip']) && $this->privateip == true) {
			sleep(15);
			die($this->lang['errordl']);
		}
		if ($this->get_load() > $this->max_load) sleep(15);
		
		$megafile = new MEGA(urldecode($job['url'])); 
		$megafile->stream_download(); 
	}
	
	function CheckMBIP()
	{
		$this->countMBIP = 0;
		$this->totalMB = 0;
		$this->timebw = 0;
		$timedata = time();
		foreach($this->jobs as $job) {
			if ($job['ip'] == $_SERVER['REMOTE_ADDR']) {
				$this->countMBIP = $this->countMBIP + $job['size'] / 1024 / 1024;
				if ($job['mtime'] < $timedata) $timedata = $job['mtime'];
				$this->timebw = $this->ttl * 60 + $timedata - time();
			}

			if ($this->privatef == false) {
				$this->totalMB = $this->totalMB + $job['size'] / 1024 / 1024;
				$this->totalMB = round($this->totalMB);
			}
			else {
				if ($job['owner'] == $this->owner) {
					$this->totalMB = $this->totalMB + $job['size'] / 1024 / 1024;
					$this->totalMB = round($this->totalMB);
				}
			}
		}

		$this->countMBIP = round($this->countMBIP);
		if ($this->countMBIP >= $this->limitMBIP) return false;
		return true;
	}

	function curl($url, $cookies, $post, $header = 1, $json = 0, $ref = 0, $xml = 0)
	{
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($json == 1) {
			$head[] = "Content-type: application/json";
			$head[] = "X-Requested-With: XMLHttpRequest";
		}
		if ($xml == 1) {
			$head[] = "X-Requested-With: XMLHttpRequest";
		}
		$head[] = "Connection: keep-alive";
		$head[] = "Keep-Alive: 300";
		$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		$head[] = "Accept-Language: en-us,en;q=0.5";
		if ($cookies) curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->UserAgent);
		curl_setopt($ch, CURLOPT_REFERER, $ref === 0 ? $url : $ref);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
		if($header == -1){
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
		}
		else curl_setopt($ch, CURLOPT_HEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		if ($this->proxy != false) {
			if(strpos($this->proxy, "|")) {
				list($ip, $auth) = explode("|", $this->proxy);
				curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $auth);
			}
			else $ip = $this->proxy;
			curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
			curl_setopt($ch, CURLOPT_PROXY, $ip);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
		$page = curl_exec($ch);
		curl_close($ch);
		return $page;
	}

	function cut_str($str, $left, $right)
	{
		$str = substr(stristr($str, $left) , strlen($left));
		$leftLen = strlen(stristr($str, $right));
		$leftLen = $leftLen ? -($leftLen) : strlen($str);
		$str = substr($str, 0, $leftLen);
		return $str;
	}

	function GetCookies($content)
	{
		preg_match_all('/Set-Cookie: (.*);/U',$content,$temp);
		$cookie = $temp[1];
		$cookies = "";
		$a = array();
		foreach($cookie as $c){
			$pos = strpos($c, "=");
			$key = substr($c, 0, $pos);
			$val = substr($c, $pos+1);
			$a[$key] = $val;
		}
		foreach($a as $b => $c){
			$cookies .= "{$b}={$c}; ";
		}
		return $cookies;
	}

	function GetAllCookies($page)
	{
		$lines = explode("\n", $page);
		$retCookie = "";
		foreach($lines as $val) {
			preg_match('/Set-Cookie: (.*)/', $val, $temp);
			if (isset($temp[1])) {
				if ($cook = substr($temp[1], 0, stripos($temp[1], ';'))) $retCookie.= $cook . ";";
			}
		}

		return $retCookie;
	}

	function mf_str_conv($str_or)
	{
		$str_or = stripslashes($str_or);
		if (!preg_match("/unescape\(\W([0-9a-f]+)\W\);\w+=([0-9]+);[^\^]+\)([0-9\^]+)?\)\);eval/", $str_or, $match)) return $str_or;
		$match[3] = $match[3] ? $match[3] : "";
		$str_re = "";
		for ($i = 0; $i < $match[2]; $i++) {
			$c = HexDec(substr($match[1], $i * 2, 2));
			eval("\$c = \$c" . $match[3] . ";");
			$str_re.= chr($c);
		}

		$str_re = str_replace($match[0], stripslashes($str_re) , $str_or);
		if (preg_match("/unescape\(\W([0-9a-f]+)\W\);\w+=([0-9]+);[^\^]+\)([0-9\^]+)?\)\);eval/", $str_re, $dummy)) $str_re = $this->mf_str_conv($str_re);
		return $str_re;
	}

	function main()
	{
		if ($this->get_load() > $this->max_load) {
			echo '<center><b><i><font color=red>' . $this->lang['svload'] . '</font></i></b></center>';
			return;
		}

		if (isset($_POST['urllist'])) {
			$url = $_POST['urllist'];
			$url = str_replace("\r", "", $url);
			$url = str_replace("\n", "", $url);
			$url = str_replace("<", "", $url);
			$url = str_replace(">", "", $url);
			$url = str_replace(" ", "", $url);
		}

		if (isset($url) && strlen($url) > 10) {
			if (substr($url, 0, 4) == 'www.') $url = "http://" . $url;
			if (!$this->check3x) {
				if (stristr($url, 'mega.co.nz')) $dlhtml = $this->mega($url);
				else $dlhtml = $this->get($url);
			}
			else {

				// ################## CHECK 3X #########################

				$check3x = false;
				if (strpos($url, "|not3x")) $url = str_replace("|not3x", "", $url);
				else {
					$data = strtolower($this->google($url));
					if(strlen($data) > 1){
						foreach($this->badword as $bad){
							if(stristr($data, " {$bad}") || stristr($data, "_{$bad}") || stristr($data, ".{$bad}") || stristr($data, "-{$bad}")){
								$check3x = $bad;
								break;
							}
						}
					}
				}

				if ($check3x == false) {
					if (stristr($url, 'mega.co.nz')) $dlhtml = $this->mega($url);
					else $dlhtml = $this->get($url);
				}
				else {
					$dlhtml = printf($this->lang['issex'], $url);
					unset($check3x);
				}
				// ################## CHECK 3X #########################

			}
		}
		else $dlhtml = "<b><a href=" . $url . " style='TEXT-DECORATION: none'><font color=red face=Arial size=2><s>" . $url . "</s></font></a> <img src=images/chk_error.png width='15' alt='errorlink'> <font color=#ffcc33><B>" . $this->lang['errorlink'] . "</B></font><br />";
		echo $dlhtml;
	}
	
	function google($q){
		$q = urldecode($q);
		$q = str_replace(' ', '+', $q);
		$oldagent = $this->UserAgent;
		$this->UserAgent = "Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 800)";
		$data = $this->curl("http://www.google.com/search?q={$q}&hl=en", '', '', 0);
		$this->UserAgent = $oldagent;
		$parsing = $this->cut_str($data, '<ol>', '</ol>');
		$new = "<ol>{$parsing}</ol>";
		$new = str_replace('<ol><li class="g">', "", $new);
		$new = str_replace('</li><li class="g">', "\n\n\n", $new);
		$new = str_replace('</li></ol>', "", $new);
		$new = preg_replace ('%<a(.*?)href[^<>]+>|</a>%s', "", $new);
		$new = preg_replace ('%<b>|</b>%s', "", $new);
		$new = preg_replace ('%<h3 class="r">|</h3>%s', "", $new);
		$new = preg_replace ('%<div class="s"><div class="kv" style="margin-bottom:2px"><cite>[^<]+</cite></div><span class="st">%s', " ", $new);
		$new = str_replace(' ...', "", $new);
		$new = strip_tags($new);
		$new = str_replace('â€Ž', '', $new);
		$new = str_replace('', '', $new);
		$new = htmlspecialchars_decode($new);
		return $new;
	}
	
	function getsize($link, $cookie=""){
		$size_name = Tools_get::size_name($link, $cookie=="" ? $this->cookie : $cookie);
		return $size_name[0];
	}
	
	function getname($link, $cookie=""){
		$size_name = Tools_get::size_name($link, $cookie=="" ? $this->cookie : $cookie);
		return $size_name[1];
	}
	
	function get($url)
	{	
		$this->reserved = array();
		$this->CheckMBIP();
		$dlhtml = '';
		if (count($this->jobs) >= $this->max_jobs) {
			$this->error1('manyjob');
		}
		if ($this->countMBIP >= $this->limitMBIP) {
			$this->error1('countMBIP', Tools_get::convertmb($this->limitMBIP * 1024 * 1024) , Tools_get::convert_time($this->ttl * 60) , Tools_get::convert_time($this->timebw));
		}
		/* check 1 */
		$checkjobs = $this->check_jobs();
		$heute = $checkjobs[0];
		$lefttime = $checkjobs[1];
		if ($heute >= $this->limitPERIP) {
			$this->error1('limitPERIP', $this->limitPERIP, Tools_get::convert_time($this->ttl_ip * 60) , $lefttime);
		}
		/* /check 1 */
		if ($this->lookup_ip($_SERVER['REMOTE_ADDR']) >= $this->max_jobs_per_ip) {
			$this->error1('limitip');
		}

		$url = trim($url);
		
		if (empty($url)) return;
		$Original = $url;
		$link = '';
		$cookie = '';
		$report = false;
		
		if (!$link) {
			$site = $this->using;
			$this->proxy = isset($this->acc[$site]['proxy']) ? $this->acc[$site]['proxy'] : false;
			$this->proxy = isset($this->prox) ? $this->prox : false;
			if($this->get_account($site) != ""){
				require_once ('hosts/' . $this->list_host[$site]['file']);
				$download = new $this->list_host[$site]['class']($this, $this->list_host[$site]['site']);
				$link = $download->General($url);
			}
		}
		
		if (!$link) {
			$domain = str_replace("www.", "", $this->cut_str($Original, "://", "/"));
			if(strpos($domain, "1fichier.com")) $domain = "1fichier.com";
			if(strpos($domain, "letitbit.net"))   $domain = "letitbit.net";
			if(strpos($domain, "shareflare.net")) $domain = "shareflare.net";
			if(strpos($domain, "fileflash.net")) $domain = "fileflash.com";
			if(strpos($domain, "yfdisk.com") || strpos($domain, "filemarkets.com") || strpos($domain, "dfpan.com")) $domain = "yunfile.com";
			
			if(isset($this->list_host[$domain])){
				require_once ('hosts/' . $this->list_host[$domain]['file']);
				$download = new $this->list_host[$domain]['class']($this, $this->list_host[$domain]['site']);
				$site = $this->list_host[$domain]['site'];
				$this->proxy = isset($this->acc[$site]['proxy']) ? $this->acc[$site]['proxy'] : false;
				$this->proxy = isset($this->prox) ? $this->prox : false;
				$link = $download->General($url);
			}
		}
		
		if (!$link) {
			$this->proxy = isset($this->acc[$site]['proxy']) ? $this->acc[$site]['proxy'] : false;
			$this->proxy = isset($this->prox) ? $this->prox : false;
			$size_name = Tools_get::size_name($Original, "");
			$filesize = $size_name[0];
			$filename = $size_name[1];
			$this->max_size = $this->max_size_other_host;
			if ($size_name[0] > 1024 * 100) $link = $url;
			else $this->error2('notsupport', $Original);
		}
		else{
			$size_name = Tools_get::size_name($link, $this->cookie);
			$filesize = $size_name[0];
			$filename = isset($this->reserved['filename']) ? $this->reserved['filename'] : $size_name[1];
		}
		
		$hosting = Tools_get::site_hash($Original);
		if (!isset($filesize)) {
			$this->error2('notsupport', $Original);
		}
		$this->max_size = $this->acc[$site]['max_size'];
		if (!isset($this->max_size)) $this->max_size = $this->max_size_other_host;
		$msize = Tools_get::convertmb($filesize);
		$hash = md5($_SERVER['REMOTE_ADDR'] . $Original);
		if ($hash === false) {
			$this->error1('cantjob');
		}

		if ($filesize > $this->max_size * 1024 * 1024) {
			$this->error2('filebig', $Original, $msize, Tools_get::convertmb($this->max_size * 1024 * 1024));
		}

		if (($this->countMBIP + $filesize / (1024 * 1024)) >= $this->limitMBIP) {
			$this->error1('countMBIP', Tools_get::convertmb($this->limitMBIP * 1024 * 1024) , Tools_get::convert_time($this->ttl * 60) , Tools_get::convert_time($this->timebw));
		}

		/* check 2 */
		$checkjobs = $this->check_jobs();
		$heute = $checkjobs[0];
		$lefttime = $checkjobs[1];
		if ($heute >= $this->limitPERIP) {
			$this->error1('limitPERIP', $this->limitPERIP, Tools_get::convert_time($this->ttl_ip * 60) , $lefttime);
		}
		/* /check 2 */
		$job = array(
			'hash' => substr(md5($hash) , 0, 10) ,
			'path' => substr(md5(rand()) , 0, 5) ,
			'filename' => urlencode($filename) ,
			'size' => $filesize,
			'msize' => $msize,
			'mtime' => time() ,
			'speed' => 0,
			'url' => urlencode($Original) ,
			'owner' => $this->owner,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'type' => 'direct',
			'proxy' => $this->proxy == false ? 0 : $this->proxy,
			'directlink' => array(
				'url' => urlencode($link) ,
				'cookies' => $this->cookie,
			) ,
		);
		$this->jobs[$hash] = $job;
		$this->save_jobs();
		$tiam = time() . rand(0, 999);
		$gach = explode('/', $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		$sv_name = "";
		for ($i = 0; $i < count($gach) - 1; $i++) $sv_name.= $gach[$i] . "/";
		if($this->acc[$site]['direct']) $linkdown = $link;
		elseif($this->longurl){
			if(function_exists("apache_get_modules") && in_array('mod_rewrite',@apache_get_modules())) $linkdown = 'http://'.$sv_name.$hosting.'/'.$job['hash'].'/'.urlencode($filename);
			else $linkdown = 'http://'.$sv_name.'index.php/'.$hosting.'/'.$job['hash'].'/'.urlencode($filename);
		}
		else $linkdown = 'http://'.$sv_name.'?file='.$job['hash'];
		
		// #########Begin short link ############
		/* create ads link */
		if (empty($linkdown) == false && $this->adslink == true && empty($this->api_ads) == false) {
			$linkdown = $this->api_zip($linkdown, $this->api_ads);
		}
		/* create shorten link */
		if (empty($linkdown) == false && $this->tinyurl == true) {
			$datalink = $this->tinyurl($linkdown);
			if (preg_match('%(http:\/\/.++)%U', $datalink, $shortlink)) $linkdown = trim($shortlink[1]);
		}
		// ########### End short link  ##########
		
		$lik = $linkdown;
		
		if($this->bbcode) {
			if (isset($this->autosearch) && $this->autosearch == 1) {
				$domain = parse_url($Original, PHP_URL_HOST);
				$content = file_get_contents($this->cbox_url . "&sec=main");
				$matches = explode('<tr id=', $content);
				
				for($i = 2; $i < count($matches); $i++) {
					$mess = $matches[$i];
					preg_match('/<\/b>:(.*)/', $mess, $temp);
					$chat = $temp[1]; //Get Chat
			
					if (preg_match_all('/<a class="autoLink" href="(.*?' . $host_check . '.*?)" target="_blank">/i', $chat, $temp, PREG_PATTERN_ORDER)) {
						foreach ($temp[1] as $linkcheck) { 
							if (stristr($linkcheck, $Original)) {
								preg_match('/<b class="(.*?)">(.*?)<\/b>/', $mess, $mem);	
								$name = $mem[2]; //Get User Name
							}
						}
					}
				}
				
				if($this->proxy != false && $this->redirdl == true) {
					if(strpos($this->proxy, "|")){
						list($prox, $userpass) = explode("|", $this->proxy);
						list($ip, $port) = explode(":", $prox);
						list($user, $pass) = explode(":", $userpass);
					}
					else list($ip, $port) = explode(":", $this->proxy);
					
					$entry = "[b]@{$name} :[/b][center][b][URL=".urlencode($lik)."]{$this->title} | [color={$this->colorfn}]".urlencode($filename)."[/color][color={$this->colorfs}] ({$msize})[/color]  [/b][/url][b] [br] ([color=green]You must add this proxy[/color] ".(strpos($this->proxy, "|") ? 'IP: '.$ip.' Port: '.$port.' User: '.$user.' & Pass: '.$pass.'' : 'IP: '.$ip.' Port: '.$port.'').")[/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
				else {
					$entry = "[b]@{$name} :[/b][center][b][URL=".urlencode($lik)."]{$this->title} | [color={$this->colorfn}]".urlencode($filename)."[/color][color={$this->colorfs}] ({$msize}) [/color][/url][/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
			}
			else {
				if($this->proxy != false && $this->redirdl == true) {
					if(strpos($this->proxy, "|")){
						list($prox, $userpass) = explode("|", $this->proxy);
						list($ip, $port) = explode(":", $prox);
						list($user, $pass) = explode(":", $userpass);
					}
					else list($ip, $port) = explode(":", $this->proxy);
					
					$entry = "[center][b][URL={$lik}]{$this->title} | [color={$this->colorfn}]{$filename}[/color][color={$this->colorfs}] ({$msize})[/color]  [/b][/url][b] [br] ([color=green]You must add this proxy[/color] ".(strpos($this->proxy, "|") ? 'IP: '.$ip.' Port: '.$port.' User: '.$user.' & Pass: '.$pass.'' : 'IP: '.$ip.' Port: '.$port.'').")[/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
				else {
					$entry = "[center][b][URL={$lik}]{$this->title} | [color={$this->colorfn}]{$filename}[/color][color={$this->colorfs}] ({$msize}) [/color][/url][/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
			}
			
			if (isset($this->autopost) && $this->autopost == 1 && isset($this->user) && isset($this->pass)) {
				$url = $this->cbox_url . '&sec=submit';
				$data = 'nme=' . $this->user . '&key=' . $this->pass . '&eml=&lvl=4&pst=' . $entry;
				$content = $this->curl($url, '', $data);
			}
		}
		
		$dlhtml = "<b><a title='click here to download' href='$lik' style='TEXT-DECORATION: none' target='$tiam'> <font color='#00CC00'>" . $filename . "</font> <font color='#FF66FF'>($msize)</font> ".($this->directdl && !$this->acc[$site]['direct'] ? "<a href='{$link}'>Direct<a> " : ""). "</a>" .($this->proxy != false ? "<font id='proxy'>({$this->proxy})</font>" : ""). "</b>".(($this->proxy != false && $this->redirdl == true) ? "<br/><b><font color=\"green\">You must add proxy or you can not download this link</font></b>" : "");
		return $dlhtml;
	}

	function mega($url)
	{	
		$this->reserved = array();
		$this->CheckMBIP();
		$dlhtml = '';
		if (count($this->jobs) >= $this->max_jobs) {
			$this->error1('manyjob');
		}
		if ($this->countMBIP >= $this->limitMBIP) {
			$this->error1('countMBIP', Tools_get::convertmb($this->limitMBIP * 1024 * 1024) , Tools_get::convert_time($this->ttl * 60) , Tools_get::convert_time($this->timebw));
		}
		/* check 1 */
		$checkjobs = $this->check_jobs();
		$heute = $checkjobs[0];
		$lefttime = $checkjobs[1];
		if ($heute >= $this->limitPERIP) {
			$this->error1('limitPERIP', $this->limitPERIP, Tools_get::convert_time($this->ttl_ip * 60) , $lefttime);
		}
		/* /check 1 */
		if ($this->lookup_ip($_SERVER['REMOTE_ADDR']) >= $this->max_jobs_per_ip) {
			$this->error1('limitip');
		}

		$url = trim($url);
		
		if (empty($url)) return;
		$Original = $url;
		$link = ''; 
		$cookie = '';
		$report = false; 
		
		$megafile = new MEGA(urldecode($url));
		
		$info = $megafile->file_info();
		
		$link = 'https://mega.co.nz/';
		 
		$filesize = $info['size'];
		$filename = isset($this->reserved['filename']) ? $this->reserved['filename'] : Tools_get::convert_name($info['attr']['n']);
		
		$hosting = Tools_get::site_hash($Original);
		if (!isset($filesize)) {
			$this->error2('notsupport', $Original);
		}
		$this->max_size = $this->acc[$site]['max_size'];
		if (!isset($this->max_size)) $this->max_size = $this->max_size_other_host;
		$msize = Tools_get::convertmb($filesize);
		$hash = md5($_SERVER['REMOTE_ADDR'] . $Original);
		if ($hash === false) {
			$this->error1('cantjob');
		}
		
		if ($filesize > $this->max_size * 1024 * 1024) {
			$this->error2('filebig', $Original, $msize, Tools_get::convertmb($this->max_size * 1024 * 1024));
		}
		
		if (($this->countMBIP + $filesize / (1024 * 1024)) >= $this->limitMBIP) {
			$this->error1('countMBIP', Tools_get::convertmb($this->limitMBIP * 1024 * 1024) , Tools_get::convert_time($this->ttl * 60) , Tools_get::convert_time($this->timebw));
		}
		
		/* check 2 */
		$checkjobs = $this->check_jobs();
		$heute = $checkjobs[0];
		$lefttime = $checkjobs[1];
		if ($heute >= $this->limitPERIP) {
			$this->error1('limitPERIP', $this->limitPERIP, Tools_get::convert_time($this->ttl_ip * 60) , $lefttime);
		}
		/* /check 2 */
		$job = array(
			'hash' => "mega_".substr(md5($hash) , 0, 10) ,
			'path' => substr(md5(rand()) , 0, 5) ,
			'filename' => urlencode($filename) ,
			'size' => $filesize,
			'msize' => $msize,
			'mtime' => time() ,
			'speed' => 0,
			'url' => urlencode($Original) ,
			'owner' => $this->owner,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'type' => 'direct',
			'proxy' => 0,
			'directlink' => array(
				'url' => urlencode($link) ,
				'cookies' => $this->cookie,
			) ,
		);
		$this->jobs[$hash] = $job;
		$this->save_jobs();
		$tiam = time() . rand(0, 999);
		$gach = explode('/', $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		$sv_name = "";
		for ($i = 0; $i < count($gach) - 1; $i++) $sv_name.= $gach[$i] . "/";
		if($this->acc[$site]['direct']) $linkdown = $link;
		elseif($this->longurl){
			if(function_exists("apache_get_modules") && in_array('mod_rewrite',@apache_get_modules())) $linkdown = 'http://'.$sv_name.$hosting.'/'.$job['hash'].'/'.urlencode($filename);
			else $linkdown = 'http://'.$sv_name.'index.php/'.$hosting.'/'.$job['hash'].'/'.urlencode($filename);
		}
		else $linkdown = 'http://'.$sv_name.'?file='.$job['hash'];
		// #########Begin short link ############
		if (empty($this->adslink) == true && empty($link) == false && empty($this->tinyurl) == true) {
			$datalink = $this->tinyurl($linkdown);
			if (preg_match('%(http:\/\/.++)%U', $datalink, $shortlink)) $lik = trim($shortlink[1]);
			else $lik = $linkdown;
		}
		elseif (empty($this->adslink) == false && empty($link) == false) {
			$lik = $linkdown;
			if (empty($this->api_ads1) == false) {
				$lik = $this->api_zip($lik, $this->api_ads1);
			}
			if (empty($this->tinyurl_each_ziplink) == false) {
				$datalink = $this->tinyurl($lik);
				if (preg_match('%(http:\/\/.++)%U', $datalink, $shortlink)) $lik = trim($shortlink[1]);
			}
			if (empty($this->api_ads2) == false) {
				$lik = $this->api_zip($lik, $this->api_ads2);
			}
			if (empty($this->tinyurl_each_ziplink) == false) {
				$datalink = $this->tinyurl($lik);
				if (preg_match('%(http:\/\/.++)%U', $datalink, $shortlink)) $lik = trim($shortlink[1]);
			}
			if (empty($this->api_ads3) == false) {
				$lik = $this->api_zip($lik, $this->api_ads3);
			}
			if (empty($this->tinyurl) == false && empty($this->tinyurl_each_ziplink) == true) {
				$datalink = $this->tinyurl($lik);
				if (preg_match('%(http:\/\/.++)%U', $datalink, $shortlink)) $lik = trim($shortlink[1]);
			}
		}
		// ########### End short link  ##########
		else $lik = $linkdown;
		
		if($this->bbcode) {
			if (isset($this->autosearch) && $this->autosearch == 1) {
				$domain = parse_url($Original, PHP_URL_HOST);
				$content = file_get_contents($this->cbox_url . "&sec=main");
				$matches = explode('<tr id=', $content);
				
				for($i = 2; $i < count($matches); $i++) {
					$mess = $matches[$i];
					preg_match('/<\/b>:(.*)/', $mess, $temp);
					$chat = $temp[1]; //Get Chat
			
					if (preg_match_all('/<a class="autoLink" href="(.*?' . $host_check . '.*?)" target="_blank">/i', $chat, $temp, PREG_PATTERN_ORDER)) {
						foreach ($temp[1] as $linkcheck) { 
							if (stristr($linkcheck, $Original)) {
								preg_match('/<b class="(.*?)">(.*?)<\/b>/', $mess, $mem);	
								$name = $mem[2]; //Get User Name
							}
						}
					}
				}
				
				if($this->proxy != false && $this->redirdl == true) {
					if(strpos($this->proxy, "|")){
						list($prox, $userpass) = explode("|", $this->proxy);
						list($ip, $port) = explode(":", $prox);
						list($user, $pass) = explode(":", $userpass);
					}
					else list($ip, $port) = explode(":", $this->proxy);
					
					$entry = "[b]@{$name} :[/b][center][b][URL=".urlencode($lik)."]{$this->title} | [color={$this->colorfn}]".urlencode($filename)."[/color][color={$this->colorfs}] ({$msize})[/color]  [/b][/url][b] [br] ([color=green]You must add this proxy[/color] ".(strpos($this->proxy, "|") ? 'IP: '.$ip.' Port: '.$port.' User: '.$user.' & Pass: '.$pass.'' : 'IP: '.$ip.' Port: '.$port.'').")[/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
				else {
					$entry = "[b]@{$name} :[/b][center][b][URL=".urlencode($lik)."]{$this->title} | [color={$this->colorfn}]".urlencode($filename)."[/color][color={$this->colorfs}] ({$msize}) [/color][/url][/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
			}
			else {
				if($this->proxy != false && $this->redirdl == true) {
					if(strpos($this->proxy, "|")){
						list($prox, $userpass) = explode("|", $this->proxy);
						list($ip, $port) = explode(":", $prox);
						list($user, $pass) = explode(":", $userpass);
					}
					else list($ip, $port) = explode(":", $this->proxy);
					
					$entry = "[center][b][URL={$lik}]{$this->title} | [color={$this->colorfn}]{$filename}[/color][color={$this->colorfs}] ({$msize})[/color]  [/b][/url][b] [br] ([color=green]You must add this proxy[/color] ".(strpos($this->proxy, "|") ? 'IP: '.$ip.' Port: '.$port.' User: '.$user.' & Pass: '.$pass.'' : 'IP: '.$ip.' Port: '.$port.'').")[/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
				else {
					$entry = "[center][b][URL={$lik}]{$this->title} | [color={$this->colorfn}]{$filename}[/color][color={$this->colorfs}] ({$msize}) [/color][/url][/b][/center]";
					echo "<input name='176' type='text' size='100' value='{$entry}' onClick='this.select()'>";
					echo "<br>"; 
				}
			}
			
			if (isset($this->autopost) && $this->autopost == 1 && isset($this->user) && isset($this->pass)) {
				$url = $this->cbox_url . '&sec=submit';
				$data = 'nme=' . $this->user . '&key=' . $this->pass . '&eml=&lvl=4&pst=' . $entry;
				$content = $this->curl($url, '', $data);
			}
		}
		
		$dlhtml = "<b><a title='click here to download' href='$lik' style='TEXT-DECORATION: none' target='$tiam'> <font color='#00CC00'>" . $filename . "</font> <font color='#FF66FF'>($msize)</font> ";
		return $dlhtml;
	}
	
	function datecmp($a, $b)
	{
		return ($a[1] < $b[1]) ? 1 : 0;
	}

	function fulllist()
	{
		$act = "";
		if ($this->act['delete'] == true) {
			$act.= '<option value="del">' . $this->lang['del'] . '</option>';
		}

		if ($this->act['rename'] == true) {
			$act.= '<option value="ren">' . $this->lang['rname'] . '</option>';
		}

		if ($act != "") {
			if ((isset($_POST['checkbox'][0]) && $_POST['checkbox'][0] != null) || isset($_POST['renn']) || isset($_POST['remove'])) {
				echo '<table style="width: 500px; border-collapse: collapse" border="1" align="center"><tr><td><center>';
				switch ($_POST['option']) {
				case 'del':
					$this->deljob();
					break;

				case 'ren':
					$this->renamejob();
					break;
				}

				if (isset($_POST['renn'])) $this->renamejob();
				if (isset($_POST['remove'])) $this->deljob();
				echo "</center></td></tr></table><br/>";
			}
		}
		else echo '</select>';
		$files = array();
		foreach($this->jobs as $job) {
			if ($job['owner'] != $this->owner && $this->privatef == true) continue;
			$files[] = array(
				urldecode($job['url']) ,
				$job['mtime'],
				$job['hash'],
				urldecode($job['filename']) ,
				$job['size'],
				$job['ip'],
				$job['msize'],
				urldecode($job['directlink']['url']) ,
				$job['proxy']
			);
		}

		if (count($files) == 0) {
			echo "<Center>" . $this->lang['notfile'] . "<br/><a href='$this->self'> [" . $this->lang['main'] . "] </a></center>";
			return;
		}

		echo "<script type=\"text/javascript\">function setCheckboxes(act){elts = document.getElementsByName(\"checkbox[]\");var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;if (elts_cnt){ for (var i = 0; i < elts_cnt; i++){elts[i].checked = (act == 1 || act == 0) ? act : (elts[i].checked ? 0 : 1);} }}</script>";
		echo "<center><a href=javascript:setCheckboxes(1)> {$this->lang['checkall']} </a> | <a href=javascript:setCheckboxes(0)> {$this->lang['uncheckall']} </a> | <a href=javascript:setCheckboxes(2)> {$this->lang['invert']} </a></center><br/>";
		echo "<center><form action='$this->self' method='post' name='flist'><select onchange='javascript:void(document.flist.submit());'name='option'>";
		if ($act == "") echo "<option value=\"dis\"> " . $this->lang['acdis'] . " </option>";
		else echo '<option selected="selected">' . $this->lang['ac'] . '</option>' . $act;
		echo '</select>';
		echo '<div style="overflow: auto; height: auto; max-height: 500px; width: 713px;"><table id="table_filelist" class="filelist" align="left" cellpadding="3" cellspacing="1" width="100%"><thead><tr class="flisttblhdr" valign="bottom"><td id="file_list_checkbox_title" class="sorttable_checkbox">&nbsp;</td><td class="sorttable_alpha"><b>' . $this->lang['name'] . '</b></td>'.($this->directdl ? '<td><b>'.$this->lang['direct'].'</b></td>' : '').'<td><b>' . $this->lang['original'] . '</b></td><td><b>' . $this->lang['size'] . '</b></td><td><b>' . $this->lang['date'] . '</b></td><td><b>IP</b></td></tr></thead><tbody>';
		usort($files, array(
			$this,
			'datecmp'
		));
		$data = "";
		foreach($files as $file) {
			$timeago = Tools_get::convert_time(time() - $file[1]) . " " . $this->lang['ago'];
			if (strlen($file[3]) > 80) $file[3] = substr($file[3], 0, 70);
			$hosting = substr(Tools_get::site_hash($file[0]) , 0, 15);
			if($this->longurl){
				if(function_exists("apache_get_modules") && in_array('mod_rewrite',@apache_get_modules())) $linkdown = Tools_get::site_hash($file[0])."/$file[2]/$file[3]";
				else $linkdown = 'index.php/'.Tools_get::site_hash($file[0])."/$file[2]/$file[3]"; 
			}
			else $linkdown = '?file='.$file[2]; 
			$data.= "
      <tr class='flistmouseoff' align='center'>
        <td><input name='checkbox[]' value='$file[2]+++$file[3]' type='checkbox'></td>
        ".($this->showlinkdown ? "<td><a href='$linkdown' style='font-weight: bold; color: rgb(0, 0, 0);'>$file[3]" . ($file[8] != 0 ? "<br/>({$file[8]})" : "") . "</a></td>" : "<td>$file[3]</td>" )."
        ".($this->directdl ? "<td><a href='$file[7]' style='color: rgb(0, 0, 0);'>" . $hosting . "</a></td>" : "")."
        <td><a href='$file[0]' style='color: rgb(0, 0, 0);'>" . $hosting . "</a></td>
        <td>" . $file[6] . "</td>
        <td><a target='_blank' href='https://www.google.com/search?q=$file[0]' title='" . $this->lang['clickcheck'] . "' target='$file[1]'><font color=#000000>$timeago</font></a></center></td><td><a target='_blank' href='http://whatismyipaddress.com/ip/$file[5]'><font color=#000000>".$file[5]."</font></a></td>
      </tr>";
		}

		$this->CheckMBIP();
		echo $data;
		$totalall = Tools_get::convertmb($this->totalMB * 1024 * 1024);
		$MB1IP = Tools_get::convertmb($this->countMBIP * 1024 * 1024);
		$thislimitMBIP = Tools_get::convertmb($this->limitMBIP * 1024 * 1024);
		$timereset = Tools_get::convert_time($this->ttl * 60);
		if($this->config['showdirect'] == true)  
		echo "</tbody><tbody><tr class='flisttblftr'><td>&nbsp;</td><td>" . $this->lang['total'] . ":</td><td></td><td></td><td>$totalall</td><td></td><td>&nbsp;</td></tr></tbody></table>
				</div></form><center><b>" . $this->lang['used'] . " $MB1IP/$thislimitMBIP - " . $this->lang['reset'] . " $timereset</b>.</center><br/>"; 
		
		else echo "</tbody><tbody><tr class='flisttblftr'><td>&nbsp;</td><td>" . $this->lang['total'] . ":</td><td></td><td>$totalall</td><td></td><td>&nbsp;</td></tr></tbody></table>
				</div></form><center><b>" . $this->lang['used'] . " $MB1IP/$thislimitMBIP - " . $this->lang['reset'] . " $timereset</b>.</center><br/>"; 
	}

	function deljob()
	{
		if ($this->act['delete'] == false) return;
		if (isset($_POST['checkbox'])) {
			echo "<form action='$this->self' method='post'>";
			for ($i = 0; $i < count($_POST['checkbox']); $i++) {
				$temp = explode("+++", $_POST['checkbox'][$i]);
				$ftd = $temp[0];
				$name = $temp[1];
				echo "<br/><b> $name </b>";
				echo '<input type="hidden" name="ftd[]" value="' . $ftd . '" />';
				echo '<input type="hidden" name="name[]" value="' . $name . '" />';
			}

			echo "<br/><br/><input type='submit' value='" . $this->lang['del'] . "' name='remove'/> &nbsp; <input type='submit' value='" . $this->lang['canl'] . "' name='Cancel'/><br /><br />";
		}

		if (isset($_POST['remove'])) {
			echo "<br />";
			for ($i = 0; $i < count($_POST['ftd']); $i++) {
				$ftd = $_POST['ftd'][$i];
				$name = $_POST['name'][$i];
				$key = "";
				foreach($this->jobs as $url => $job) {
					if ($job['hash'] == $ftd) {
						$key = $url;
						break;
					}
				}

				if ($key) {
					unset($this->jobs[$key]);
					echo "<center>File: <b>$name</b> " . $this->lang['deld'];
				}
				else echo "<center>File: <b>$name</b> " . $this->lang['notfound'];
				echo "</center>";
			}

			echo "<br />";
			$this->save_jobs();
		}

		if (isset($_POST['Cancel'])) {
			$this->fulllist();
		}
	}

	function renamejob()
	{
		if ($this->act['rename'] == false) return;
		if (isset($_POST['checkbox'])) {
			echo "<form action='$this->self' method='post'>";
			for ($i = 0; $i < count($_POST['checkbox']); $i++) {
				$temp = explode("+++", $_POST['checkbox'][$i]);
				$name = $temp[1];
				echo "<br/><b> $name </b>";
				echo '<input type="hidden" name="hash[]" value="' . $temp[0] . '" />';
				echo '<input type="hidden" name="name[]" value="' . $name . '" />';
				echo '<br/>' . $this->lang['nname'] . ': <input type="text" name="nname[]" value="' . $name . '"/ size="70"><br />';
			}

			echo "<br/><input type='submit' value='" . $this->lang['rname'] . "' name='renn'/> &nbsp; <input type='submit' value='" . $this->lang['canl'] . "' name='Cancel'/><br /><br />";
		}

		if (isset($_POST['renn'])) {
			for ($i = 0; $i < count($_POST['name']); $i++) {
				$orname = $_POST['name'][$i];
				$hash = $_POST['hash'][$i];
				$nname = $_POST['nname'][$i];
				$nname = Tools_get::convert_name($nname);
				$nname = str_replace($this->banned, '', $nname);
				if ($nname == "") {
					echo "<br />" . $this->lang['bname'] . "<br /><br />";
					return;
				}
				else {
					echo "<br/>";
					$key = "";
					foreach($this->jobs as $url => $job) {
						if ($job['hash'] == $hash) {
							$key = $url;

							// $hash = $this->create_hash($key,$nname);

							$jobn = array(
								'hash' => $job['hash'],
								'path' => $job['path'],
								'filename' => urlencode($nname) ,
								'size' => $job['size'],
								'msize' => $job['msize'],
								'mtime' => $job['mtime'],
								'speed' => 0,
								'url' => $job['url'],
								'owner' => $job['owner'],
								'ip' => $job['ip'],
								'type' => 'direct',
								'directlink' => array(
									'url' => $job['directlink']['url'],
									'cookies' => $job['directlink']['cookies'],
								) ,
							);
						}
					}

					if ($key) {
						$this->jobs[$key] = $jobn;
						$this->save_jobs();
						echo "File <b>$orname</b> " . $this->lang['rnameto'] . " <b>$nname</b>";
					}
					else echo "File <b>$orname</b> " . $this->lang['notfound'];
					echo "<br/><br />";
				}
			}
		}

		if (isset($_POST['Cancel'])) {
			$this->fulllist();
		}
	}
	function error1($msg, $a = "", $b = "", $c = "", $d = ""){
		if(isset($this->lang[$msg])) $msg = sprintf($this->lang[$msg], $a, $b, $c, $d);
		$msg = sprintf($this->lang["error1"], $msg);
		die($msg);
	}
	function error2($msg, $a = "", $b = "", $c = "", $d = ""){
		if(isset($this->lang[$msg])) $msg = sprintf($this->lang[$msg], $b, $c, $d);
		$msg = sprintf($this->lang["error2"], $msg, $a);
		die($msg);
	}
	
	function api_zip($url, $type) 
	{
		if (stristr($type, "sh.st") || stristr($type, "shorte.st")) {
			$data = $this->curl($type.$url,'','');
			if (preg_match('/ocation: (.*)/i', $data, $matches)) {
				$link = explode("?utm_source", trim($matches[1]));
				return $link[0];
			}
			else {
				$data = $this->curl("http://api.shorte.st/s/5192a39e8481477ead95b83ef84070fe/".str_replace("http://","",$url),"","",0);
				$array = json_decode($data, true);
				if ($array['status'] == "ok") {
					$link = explode("?utm_source", trim($array['shortenedUrl']));
					return $link[0];
				}
			}
		}
		elseif (stristr($type, "ouo.io") || stristr($type, "bc.vc") || stristr($type, "adf.ly")) {
			$data = $this->curl($type.$url,'','', 0);
			return $data;
		}
	}
	
	function tinyurl($url) 
	{
		$data = $this->curl("http://tinyurl.com/create.php", "", "url=$url", 0);
		preg_match('/<div class="indent"><b>(.*?)<\/b><div id="success">/', $data, $match);
		return trim($match[1]);
	}
	
	function wrong_proxy($proxy) 		
	{	
		if(strpos($proxy, "|")){
			list($prox, $userpass) = explode("|", $proxy);
			list($ip, $port) = explode(":", $prox);
			list($user, $pass) = explode(":", $userpass);
		}
		else list($ip, $port) = explode(":", $proxy);
		die('<title>You must add this proxy to IDM '.(strpos($proxy, "|") ? 'IP: '.$ip.' Port: '.$port.' User: '.$user.' & Pass: '.$pass.'' : 'IP: '.$ip.' Port: '.$port.'').'</title><center><b><span style="color:#076c4e">You must add this proxy to IDM </span> <span style="color:#30067d">('.(strpos($proxy, "|") ? 'IP: '.$ip.' Port: '.$port.' User: '.$user.' and Pass: '.$pass.'' : 'IP: '.$ip.' Port: '.$port.'').')</span> <br><span style="color:red">PLEASE REMEMBER: IF YOU DO NOT ADD THE PROXY, YOU CAN NOT DOWNLOAD THIS LINK!</span><br><br>  Open IDM > Downloads > Options.<br><img src="http://i.imgur.com/v7FR3HE.png"><br><br>  Proxy/Socks > Choose "Use Proxy" > Add proxy server: <font color=\'red\'>'.$ip.'</font>, port: <font color=\'red\'>'.$port.'</font> '.(strpos($proxy, "|") ? ', username: <font color=\'red\'>'.$user.'</font> and password: <font color=\'red\'>'.$pass.'</font>' : '').' > Choose http > OK.<br>'.(strpos($proxy, "|") ? '<img src="http://i.imgur.com/LUTpGyN.png">' : '<img src="http://i.imgur.com/zExhNVR.png">').'<br><br>  Copy your link > Paste in IDM > OK.<br><img src="http://i.imgur.com/S355c5J.png"><br><br>  It will work > Start Download > Enjoy!<br><img src="http://i.imgur.com/vlh2vZf.png"></b></center>');
	}
}

// #################################### End class stream_get ###################################
// #################################### Begin class Tools_get ###################################

class Tools_get
{
	function useronline()
	{
		$data = @file_get_contents($this->fileinfo_dir . "/online.dat");
		$online = @json_decode($data, true);
		if (!is_array($online)) {
			$online = array();
			$data = 'vng';
		}

		$online[$_SERVER['REMOTE_ADDR']] = time();

		// ## clean jobs ###

		$oldest = time() - 45;
		foreach($online as $ip => $time) {
			if ($time < $oldest) unset($online[$ip]);
		}

		// ## clean jobs ###

		/*-------------- save --------------*/
		$tmp = json_encode($online);
		if ($tmp !== $data) {
			$data = $tmp;
			$fh = fopen($this->fileinfo_dir . "/online.dat", 'w') or die('<CENTER><font color=red size=3>Could not open file ! Try to chmod the folder "<B>' . $this->fileinfo_dir . '</B>" to 777</font></CENTER>');
			fwrite($fh, $data) or die('<CENTER><font color=red size=3>Could not write file ! Try to chmod the folder "<B>' . $this->fileinfo_dir . '</B>" to 777</font></CENTER>');
			fclose($fh);
			@chmod($this->fileinfo_dir . "/online.dat", 0666);
		}

		/*-------------- /save --------------*/
		return count($online);
	}

	function size_name($link, $cookie)
	{
		if (!$link || !stristr($link, 'http')) return;
		$link = str_replace(" ", "%20", $link);
		$port = 80;
		$schema = parse_url(trim($link));
		$host = $schema['host'];
		$scheme = "http://";
		if (empty($schema['path'])) return;
		$gach = explode("/", $link);
		list($path1, $path) = explode($gach[2], $link);
		if (isset($schema['port'])) $port = $schema['port'];
		elseif ($schema['scheme'] == 'https') {
			$scheme = "ssl://";
			$port = 443;
		}

		if ($scheme != "ssl://") {
			$scheme = "";
		}
		$errno = 0;
		$errstr = "";
		$hosts = $scheme . $host . ':' . $port;
		if($this->proxy != 0){
			if(strpos($this->proxy, "|")){
				list($ip, $user) = explode("|", $this->proxy);
				$auth = base64_encode($user);
			}
			else $ip = $this->proxy;
			$data = "GET {$link} HTTP/1.1\r\n";
			if(isset($auth)) $data.= "Proxy-Authorization: Basic $auth\r\n";
			$fp = @stream_socket_client("tcp://{$ip}", $errno, $errstr, 20, STREAM_CLIENT_CONNECT);
		}
		else {
			$data = "GET {$path} HTTP/1.1\r\n";
			$fp = @stream_socket_client($hosts, $errno, $errstr, 20, STREAM_CLIENT_CONNECT);
		}

		$data.= "User-Agent: " . $this->UserAgent . "\r\n";
		$data.= "Host: {$host}\r\n";
		$data.= "Referer: {$this->url}\r\n";
		$data.= $cookie ? "Cookie: $cookie\r\n" : '';
		$data.= "Connection: Close\r\n\r\n";

		if (!$fp) return -1;
		fputs($fp, $data);
		fflush($fp);
		
		$header = "";
		do {
			if (!$header) {
				$header.= fgets($fp, 8192);
				if (!stristr($header, "HTTP/1")) break;
			}
			else $header.= fgets($fp, 8192);
		}
		while (strpos($header, "\r\n\r\n") === false);

		if (stristr($header, "TTP/1.0 200 OK") || stristr($header, "TTP/1.1 200 OK") || stristr($header, "TTP/1.1 206")) $filesize = trim($this->cut_str($header, "Content-Length:", "\n"));
		else $filesize = - 1;
		if (!is_numeric($filesize)) $filesize = - 1;
		$filename = "";
		
		if (stristr($header, "filename")) {
			if (preg_match("/; filename=(.*?);/", $header, $match)) $filename = trim($match[1]);
			else $filename = trim($this->cut_str($header, "filename", "\n"));
		}
		else $filename = substr(strrchr($link, '/') , 1);
		
		$filename = self::convert_name($filename);
		return array(
			$filesize,
			$filename
		);
	}

	function site_hash($url)
	{
		if (strpos($url, "4shared.com")) $site = "4S";
		elseif (strpos($url, "asfile.com")) $site = "AS";
		elseif (strpos($url, "bitshare.com")) $site = "BS";
		elseif (strpos($url, "depositfiles.com") || strpos($url, "dfiles.eu")) $site = "DF";
		elseif (strpos($url, "extabit.com")) $site = "EB";
		elseif (strpos($url, "filefactory.com")) $site = "FF";
		elseif (strpos($url, "filepost.com")) $site = "FP";
		elseif (strpos($url, "hotfile.com")) $site = "HF";
		elseif (strpos($url, "lumfile.com")) $site = "LF";
		elseif (strpos($url, "mediafire.com")) $site = "MF";
		elseif (strpos($url, "megashares.com")) $site = "MS";
		elseif (strpos($url, "netload.in")) $site = "NL";
		elseif (strpos($url, "rapidgator.net")) $site = "RG";
		elseif (strpos($url, "ryushare.com")) $site = "RY";
		elseif (strpos($url, "turbobit.net")) $site = "TB";
		elseif (strpos($url, "uploaded.to") || strpos($url, "ul.to") || strpos($url, "uploaded.net")) $site = "UT";
		elseif (strpos($url, "uploading.com")) $site = "UP";
		elseif (strpos($url, "1fichier.com")) $site = "1F";
		elseif (strpos($url, "rapidshare.com")) 	$site = "RS";
		elseif (strpos($url, "fshare.vn"))	   $site = "FshareVN";
		elseif (strpos($url, "up.4share.vn")  || strpos($url, "4share.vn"))  $site = "4ShareVN";
		elseif (strpos($url, "share.vnn.vn"))   $site = "share.vnn.vn";
		elseif (strpos($url, "upfile.vn"))   $site = "UpfileVN";
		elseif (strpos($url, "mega.co.nz"))   $site = "MEGA";
		else {
			$schema = parse_url($url);
			$site = preg_replace("/(www\.|\.com|\.net|\.biz|\.info|\.org|\.us|\.vn|\.jp|\.fr|\.in|\.to)/", "", $schema['host']);
		}

		return $site;
	}

	function convert($filesize)
	{
		$filesize = str_replace(",", ".", $filesize);
		if (preg_match('/^([0-9]{1,4}+(\.[0-9]{1,2})?)/', $filesize, $value)) {
			if (stristr($filesize, "TB")) $value = $value[1] * 1024 * 1024 * 1024 * 1024;
			elseif (stristr($filesize, "GB")) $value = $value[1] * 1024 * 1024 * 1024;
			elseif (stristr($filesize, "MB")) $value = $value[1] * 1024 * 1024;
			elseif (stristr($filesize, "KB")) $value = $value[1] * 1024;
			else $value = $value[1];
		}
		else $value = 0;
		return $value;
	}

	function convertmb($filesize)
	{
		if (!is_numeric($filesize)) return $filesize;
		$soam = false;
		if ($filesize < 0) {
			$filesize = abs($filesize);
			$soam = true;
		}

		if ($filesize >= 1024 * 1024 * 1024 * 1024) $value = ($soam ? "-" : "") . round($filesize / (1024 * 1024 * 1024 * 1024) , 2) . " TB";
		elseif ($filesize >= 1024 * 1024 * 1024) $value = ($soam ? "-" : "") . round($filesize / (1024 * 1024 * 1024) , 2) . " GB";
		elseif ($filesize >= 1024 * 1024) $value = ($soam ? "-" : "") . round($filesize / (1024 * 1024) , 2) . " MB";
		elseif ($filesize >= 1024) $value = ($soam ? "-" : "") . round($filesize / (1024) , 2) . " KB";
		else $value = ($soam ? "-" : "") . $filesize . " Bytes";
		return $value;
	}

	function uft8html2utf8($s)
	{
		if (!function_exists('uft8html2utf8_callback')) {
			function uft8html2utf8_callback($t)
			{
				$dec = $t[1];
				if ($dec < 128) {
					$utf = chr($dec);
				}
				else
				if ($dec < 2048) {
					$utf = chr(192 + (($dec - ($dec % 64)) / 64));
					$utf.= chr(128 + ($dec % 64));
				}
				else {
					$utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
					$utf.= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
					$utf.= chr(128 + ($dec % 64));
				}

				return $utf;
			}
		}

		return preg_replace_callback('|&#([0-9]{1,});|', 'uft8html2utf8_callback', $s);
	}

	function convert_name($filename)
	{
		$filename = urldecode($filename);
		$filename = Tools_get::uft8html2utf8($filename);
		$filename = preg_replace("/(\]|\[|\@|\"\;\?\=|\"|=|\*|UTF-8|\')/", "", $filename);
		$filename = preg_replace("/(HTTP|http|WWW|www|\.html|\.htm)/i", "", $filename);
		$filename = str_replace($this->banned, '.xxx', $filename);
		if (empty($filename) == true) $filename = substr(md5(time() . $url) , 0, 10);
		return $filename;
	}

	function convert_time($time)
	{
		if ($time >= 86400) $time = round($time / (60 * 24 * 60) , 1) . " " . $this->lang['days'];
		elseif (86400 > $time && $time >= 3600) $time = round($time / (60 * 60) , 1) . " " . $this->lang['hours'];
		elseif (3600 > $time && $time >= 60) $time = round($time / 60, 1) . " " . $this->lang['mins'];
		else $time = $time . " " . $this->lang['sec'];
		return $time;
	}
}
// #################################### End class Tools_get #####################################
// #################################### Begin class Download ####################################
class Download {
	public $last = false;
	public function __construct ($lib, $site) {
		$this->lib = $lib;
		$this->site = $site;
	}
	
	public function error($msg, $force = false, $delcookie = true, $type = 1){
		if(isset($this->lib->lang[$msg])) $msg = sprintf($this->lib->lang[$msg], $this->site, $this->url);
		$msg = sprintf($this->lib->lang["error{$type}"], $msg, $this->url);
		if($delcookie) $this->save();
		if($force || $this->last) die($msg);
	}
	
	public function filter_cookie($cookie, $del = array('', '""', 'deleted')){
		$cookie = explode(";", $cookie);
		$cookies = "";
		$a = array();
		foreach($cookie as $c){
			$delete = false;
			$pos = strpos($c, "=");
			$key = str_replace(" ", "", substr($c, 0, $pos));
			$val = substr($c, $pos+1);
			foreach($del as $dul) {
				if($val == $dul) $delete = true;
			}
			if(!$delete) $a[$key] = $val;
		}
		foreach($a as $b => $c){
			$cookies .= "{$b}={$c}; ";
		}
		return $cookies;
	}
	
	public function save($cookies = "", $save = true){
		$cookie = $cookies != "" ? $this->filter_cookie(($this->lib->cookie ? $this->lib->cookie.";" : "").$cookies) : "";
		if($save) $this->lib->save_cookies($this->site, $cookie);
		$this->lib->cookie = $cookie;
	}
	
	public function exploder($del, $data, $i){
		$a = explode($del, $data);
		return $a[$i];
	}
	
	public function isredirect($data){
		if (preg_match('/ocation: (.*)/',$data,$match)) {
			$this->redirect = trim($match[1]);
			return true;
		}
		else return false;
	}
	
	public function getredirect($link, $cookie=""){
		$data = $this->lib->curl($link,$cookie,"",-1);
		if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
		$cookies = $this->lib->GetCookies($data);
		if($cookies != "") $this->save($cookies);
		return $link;
	}
	
	public function parseForm($data){
		$post = array();
		if(preg_match_all('/<input(.*)>/U', $data, $matches)){
			foreach($matches[0] as $input){
				if(!stristr($input, "name=")) continue;
				if(preg_match('/name=(".*"|\'.*\')/U', $input, $name)){
					$key = substr($name[1], 1, -1);
					if(preg_match('/value=(".*"|\'.*\')/U', $input, $value)) $post[$key] = substr($value[1], 1, -1);
					else $post[$key] = "";
				}
			}
		}
		return $post;
	}
	
	public function linkpassword($url){
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; 
			$password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		return array($url, $password);
	}
	
	public function forcelink($link, $a){
		$link = str_replace(" ", "%20", $link);
		for($i=0;$i<$a;$i++){
			if($size = $this->lib->getsize($link, $this->lib->cookie) <= 0) {
				$link = $this->getredirect($link, $this->lib->cookie);
			}
			else return $link;
		}
		$this->error("cantconnect", false, false); 
		return false;
	}
	
	public function General($url){
		$this->url = $url;
		$this->lib->url = $url;
		$this->cookie = "";
		if($this->lib->acc[$this->site]['proxy'] != "") $this->lib->proxy = $this->lib->acc[$this->site]['proxy'];
		if(method_exists($this, "PreLeech")) {
			$this->PreLeech($this->url);
		}
		if(method_exists($this, "FreeLeech")) {
			$link = $this->FreeLeech($this->url);
			if($link) {
				$link = $this->forcelink($link, 2);
				if($link) return $link;
			}
		}
		$maxacc = count($this->lib->acc[$this->site]['accounts']);
		if($maxacc == 0) $this->error('noaccount', true);
		for ($k=0; $k < $maxacc; $k++){
			$account = trim($this->lib->acc[$this->site]['accounts'][$k]);
			if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			else $cookie = $account;
			if(!empty($cookie) || (!empty($user) && !empty($pass))){
				for ($j=0; $j < 2; $j++){
					if(($maxacc-$k) == 1 && $j == 1) $this->last = true;
					if(empty($cookie)) $cookie = $this->lib->get_cookie($this->site);
					if(empty($cookie)) {
						$cookie = false;
						if(method_exists($this, "Login")) $cookie = $this->Login($user, $pass);
					}
					if(!$cookie) continue;
					$this->save($cookie);
					if(method_exists($this, "CheckAcc")) $status = $this->CheckAcc($this->lib->cookie);
					else $status = array(true, "Without Acc Checker");
					if($status[0]){
						$link = false;
						if(method_exists($this, "Leech")) $link = $this->Leech($this->url);
						if($link) {
							$link = $this->forcelink($link, 3);
							if($link) return $link;
						}
						else $this->error('pluginerror');
					}
					else{
						$this->error($status[1]);
					}
				}
			}
		}
		return false;
	}
}
// #################################### End class Download ####################################

/**
 * Mega.co.nz downloader
 * Require mcrypt, curl
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @author ZonD80
 * Fix by rayyan2005
 */
class MEGA {

    private $seqno, $f;

    /**
     * Class constructor
     * @param string $file_hash File hash, coming after # in mega URL
     */
    function __construct($file_hash) {
        $this->seqno = rand(0, 0xFFFFFFFF);
        $this->f = $this->mega_get_file_info($file_hash);
    }

    function a32_to_str($hex) {
        return call_user_func_array('pack', array_merge(array('N*'), $hex));
    }

    function aes_ctr_decrypt($data, $key, $iv) {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, 'ctr', $iv);
    }

    function base64_to_a32($s) {
        return $this->str_to_a32($this->base64urldecode($s));
    }

    function base64urldecode($data) {
        $data .= substr('==', (2 - strlen($data) * 3) % 4);
        $data = str_replace(array('-', '_', ','), array('+', '/', ''), $data);
        return base64_decode($data);
    }

    function str_to_a32($b) {
        // Add padding, we need a string with a length multiple of 4
        $b = str_pad($b, 4 * ceil(strlen($b) / 4), "\0");
        return array_values(unpack('N*', $b));
    }

    function post($url, $post){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
    }


    /**
     * Handles query to mega servers
     * @param array $req data to be sent to mega
     * @return type
     */

    function mega_api_req($req) {
	$resp = $this->post('https://g.api.mega.co.nz/cs?id='.($this->seqno++).'&', json_encode(array($req)),0);
        $resp = json_decode($resp, true);
        return $resp[0];
    }

    function aes_cbc_decrypt($data, $key) {
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
    }

    function mega_dec_attr($attr, $key) {
        $attr = trim($this->aes_cbc_decrypt($attr, $this->a32_to_str($key)));
        if (substr($attr, 0, 6) != 'MEGA{"') {
            return false;
        }
        return json_decode(substr($attr, 4), true);
    }

    /*
     * Downloads file from megaupload
     * @param string $as_attachment Download file as attachment, default true
     * @param string $local_path Save file to specified by $local_path folder
     * @return boolean True
     */
	 
    function download($as_attachment = true, $local_path = null) {
        $ch = curl_init($this->f['binary_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data_enc = curl_exec($ch);
        curl_close($ch);
        $data = $this->aes_ctr_decrypt($data_enc, $this->a32_to_str($this->f['k']), $this->a32_to_str($this->f['iv']));
        if ($as_attachment) {
            header("Content-Disposition: attachment;filename=\"{$this->f['attr']['n']}\"");
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $this->f['size']);
            header('Pragma: no-cache');
            header('Expires: 0');
            print $data;
            return true;
        } else {
            file_put_contents($local_path . DIRECTORY_SEPARATOR . $this->f['attr']['n'], $data);
            return true;
        }
    }

    function get_chunks($size) {
        $chunks = array();
        $p = $pp = 0;

        for ($i = 1; $i <= 8 && $p < $size - $i * 0x20000; $i++) {
            $chunks[$p] = $i * 0x20000;
            $pp = $p;
            $p += $chunks[$p];
        }

        while ($p < $size) {
            $chunks[$p] = 0x100000;
            $pp = $p;
            $p += $chunks[$p];
        }

        $chunks[$pp] = ($size - $pp);
        if (!$chunks[$pp]) {
            unset($chunks[$pp]);
        }

        return $chunks;
    }

    /*
     * Downloads file from megaupload as a stream (useful if you want to implement megaupload proxy)
     * @param string $as_attachment Download file as attachment, default true
     * @param string $local_path Save file to specified by $local_path folder
     * @return boolean True
     */
	 
    function stream_download($as_attachment = true, $local_path = null) {
        if ($as_attachment) {
            header("Content-Disposition: attachment;filename=\"{$this->f['attr']['n']}\"");
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . $this->f['size']);
            header('Pragma: no-cache');
            header('Expires: 0');
        } else {
            $destfile = fopen($local_path . DIRECTORY_SEPARATOR . $this->f['attr']['n'], 'wb');
        }
        $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'ctr', '');

        mcrypt_generic_init($cipher, $this->a32_to_str($this->f['k']), $this->a32_to_str($this->f['iv']));

        $chunks = $this->get_chunks($this->f['size']);

        $protocol = parse_url($this->f['binary_url'], PHP_URL_SCHEME);

        $opts = array(
            $protocol => array(
                'method' => 'GET'
            )
        );

        $context = stream_context_create($opts);
        $stream = fopen($this->f['binary_url'], 'rb', false, $context);

        $info = stream_get_meta_data($stream);
        $end = !$info['eof'];
        foreach ($chunks as $length) {

            $bytes = strlen($buffer);
            while ($bytes < $length && $end) {
                $data = fread($stream, min(1024, $length - $bytes));
                $buffer .= $data;

                $bytes = strlen($buffer);
                $info = stream_get_meta_data($stream);
                $end = !$info['eof'] && $data;
            }

            $chunk = substr($buffer, 0, $length);
            $buffer = $bytes > $length ? substr($buffer, $length) : '';

            $chunk = mdecrypt_generic($cipher, $chunk);
            if ($as_attachment) {
                print $chunk;
                ob_flush();
                }
            else
                fwrite($destfile, $chunk);
        }

        // Terminate decryption handle and close module
        mcrypt_generic_deinit($cipher);
        mcrypt_module_close($cipher);
        fclose($stream);
        if (!$as_attachment)
            fclose($destfile);

        return true;
    }

    private function mega_get_file_info($hash) {
	preg_match('/\!(.*?)\!(.*)/', $hash, $matches);
	$id = $matches[1];
	$key = $matches[2];
	$key = $this->base64_to_a32($key);
	$k = array($key[0] ^ $key[4], $key[1] ^ $key[5], $key[2] ^ $key[6], $key[3] ^ $key[7]);
	$iv = array_merge(array_slice($key, 4, 2), array(0, 0));
	$meta_mac = array_slice($key, 6, 2);
	$info = $this->mega_api_req(array('a' => 'g', 'g' => 1, 'p' => $id));
	if (!$info['g']) die('<b><font color=red>No such file on mega. Maybe it was deleted.</font></b>');
	return array('id' => $id, 'key' => $key, 'k' => $k, 'iv' => $iv, 'meta_mac' => $meta_mac, 'binary_url' => $info['g'], 'attr' => $this->mega_dec_attr($this->base64urldecode($info['at']), $k), 'size' => $info['s']);
    }

    /**
     * Returns file information
     * @return array File information
     */
	 
    function file_info() {
        return $this->f;
    }
}
?>
