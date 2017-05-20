<?php
ob_start();
if( !ini_get('safe_mode') ){
	set_time_limit(60);
} 
error_reporting(0); 
ignore_user_abort(TRUE);
date_default_timezone_set('Asia/Jakarta');
$data = json_decode(file_get_contents("data/config.dat"), true);
if ($_GET['go']=='logout') {
	setcookie("secureid", "owner", time());
} 
else {
	$login = false;
	$password = explode(", ", $data['password']);
	$password[] = $data['admin'];
	foreach ($password as $login_vng)
	if($_POST['secure'] == $login_vng){
		#-----------------------------------------------
		$file = "data/log.txt";	//	Rename *.txt
		$date = date('H:i:s Y-m-d');
		$entry  = sprintf("Passlogin=%s\n", $_POST["secure"]);
		$entry .= sprintf("IP: ".$_SERVER['REMOTE_ADDR']." | Date: $date\n");
		$entry .= sprintf("------------------------------------------------------------------------\n");
		$handle = fopen($file, "a+")
		or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>'.$file.'</B>" to 666</font></CENTER>');
		fwrite($handle, $entry)
		or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>'.$file.'</B>" to 666</font></CENTER>');
		fclose($handle);
		#-----------------------------------------------
		setcookie("secureid",md5($login_vng),time()+3600*24*7);
		$login = true;
	}
	if($login == false) die("<script>alert(\"Wrong password !\"); history.go(-1)</script>");
}

header("location:index.php");
ob_end_flush();
?>
