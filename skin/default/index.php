		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">
		<head>
			<link rel="SHORTCUT ICON" href="images/vngicon.png" type="image/x-icon" />
			<title><?php printf($obj->lang['title'],$obj->lang['version']); ?></title>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<meta name="keywords" content="<?php printf($obj->lang['version']); ?>, download, get, vinaget, file, generator, premium, link, sharing, bitshare.com, crocko.com, depositfiles.com, extabit.com, filefactory.com, filepost.com, filesmonster.com, freakshare.com, gigasize.com, hotfile.com, jumbofiles.com, letitbit.net, mediafire.com, megashares.com, netload.in, oron.com, rapidgator.net, rapidshare.com, ryushare.com, sendspace.com, share-online.biz, shareflare.net, uploaded.to, uploading.com" />
			<link title="Rapidleech Style" href="<?php echo $skin;?>/rl_style_pm.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
			<script type="text/javascript" language="javascript" src="images/jquery-1.7.1.min.js"></script>
			<script type="text/javascript" src="images/ZeroClipboard.js"></script>
			<script type="text/javascript" src="images/sprintf.js"></script>
			<script type="text/javascript" language="javascript">
				var loadimg = "loading_black.gif";
				var loadcolor = "#FFFF99";
				var title = '<?php echo $obj->title; ?>';
				var colorname = '<?php echo $obj->colorfn; ?>';
				var colorfile = '<?php echo $obj->colorfs; ?>';
				var lang = new Array();
				<?php 
				foreach($obj->lang as $key=>$val){
					$val = str_replace("'", "\'", $val);
					echo "lang['{$key}'] = '{$val}'; ";
				}
				?>
			</script> 
			<!--
			<center><img src="images/logo.png" alt="RapidLeech PlugMod" border="0" /></center><br />
			-->
			<div id="showlistlink" class="showlistlink" align="center">
				<div style="border:1px #ffffff solid; width:960px; padding:5px; margin-top:50px;">
					<div id="listlinks"><textarea style='width:950px;height:400px' id="textarea"></textarea></div>
					<table style='width:950px;'><tr>
					<td width="50%" vAlign="left" align="left">	
					<input type='button' value="bbcode" onclick="return bbcode('list');" />
					<input type='button' id ='SelectAll' value="Select All"/>
					<input type='button' id="copytext" value="Copy To Clipboard"/>
					</td>
					<td id="report"  width="50%" align="center"></td>
					</tr></table>
				</div>
				<div style="width:120px; padding:5px; margin:2px;border:1px #ffffff solid;">
					<a onclick="return makelist(document.getElementById('showresults').innerHTML);" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>Click to close</font></a>
				</div>
			</div>
			<table align="center"><tbody>
				<tr>
				<!-- ########################## Begin Plugins ########################## -->
				<td valign="top">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:3px">
						<?php if ($obj->hide_plugins_col == false) { ?>
						<tr><td>
							<div class="cell-plugin"><?php printf($obj->lang['plugins']); ?></div>
							<div align="center" class="plugincolhd">
								<b><small><?php echo count($host);?></small></b> <?php printf($obj->lang['plugins']); ?>
							</div>
							<div dir="rtl" align="left" style="overflow-y:scroll; height:150px; margin-left: 2px; padding-left:10px;">
							<?php
								foreach ($host as $key => $val){
									echo "<span class='plugincollst'>" .$key."</span><br />";
								}
							?>
							</div>
						</td></tr>
						<tr><td><br></td></tr>
						<?php } ?>
						<?php if ($obj->hide_preacc_col == false) { ?>
						<tr><td>
							<div class="cell-plugin"><?php printf($obj->lang['premium']); ?></div>
							<table border="0">
								<tr><td style="padding:3px;">
									<div dir="rtl" align="left" style="padding-left:5px;">
										<?php showPlugin(); ?>
									</div>
								</td></tr>
							</table><br />
						</td></tr>
						<?php } ?>
					
					</table>
				</td>
				<!-- ########################## End Plugins ########################## -->
				<!-- ########################## Begin Main ########################### -->
				<td align="center" valign="top">
					<table border="0" cellpadding="0" cellspacing="1"><tbody>
						<tr>
							<td class="cell-nav"><a class="ServerFiles" href="./"><?php printf($obj->lang['main']); ?></a></td>
							<td class="cell-nav"><a class="ServerFiles" href="./?id=donate"><?php printf($obj->lang['donate']); ?></a></td>
							<td class="cell-nav"><a class="ServerFiles" href="./?id=listfile"><?php printf($obj->lang['listfile']); ?></a></td>
							<td class="cell-nav"><a class="ServerFiles" href="./?id=check"><?php printf($obj->lang['check']); ?></a></td>
							<?php if ($obj->Secure || $obj->isadmin()) 
							echo '<td class="cell-nav"><a class="ServerFiles" href="./?id=admin"> '.$obj->lang['admin'].'</a></td>'; ?>
							<?php if ($obj->Secure) 
							echo '<td class="cell-nav"><a class="ServerFiles" href="./login.php?go=logout"> '.$obj->lang['log'].'</a></td>'; ?>
						</tr>
					</tbody></table>
					<table id="tb_content"><tbody>
						<tr><td height="5px"></td></tr>
						<tr><td align="center">
<?php 
						#---------------------------- begin list file ----------------------------#
						if ((isset($_GET['id']) && $_GET['id']=='listfile') || isset($_POST['listfile']) || isset($_POST['option']) || isset($_POST['renn']) || isset($_POST['remove']))  {
							if($obj->listfile || $obj->isadmin())$obj->fulllist();
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end list file ----------------------------#

						#---------------------------- begin donate  ----------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='donate') { 
?>
							<div align="center">
								<BR><div id="wait"><font color="#FF3300"><?php printf($obj->lang['donations1']); ?><br/><?php printf($obj->lang['donations2']); ?></font></div>
								<BR><form action="javascript:donate(document.getElementById('donateform'));" name="donateform" id="donateform">
									<table>
										<tr>
											<td>
												<?php printf($obj->lang['acctype']); ?> 
												<select name='type' id='type'>
												<?php
												foreach($host as $key => $val) {
													if(!$val['alias']){
														require_once ('hosts/' . $val['file']);
														if(method_exists($val['class'], "CheckAcc")) echo "<option value='{$key}'>{$key}</option>";
													}
												}
												?>
												</select>
											</td>
											<td>
												&nbsp; &nbsp; &nbsp; <input type="text" name="accounts" id="accounts" value="" size="50"><br />
											</td>
											<td>&nbsp; &nbsp; &nbsp; <input type=submit value="<?php printf($obj->lang['sbdonate']); ?>">
											</td>
										</tr>
									</table>
								</form>
								<div id="check"><font color=#FF6600>user:pass</font> or <font color=#FF6600>cookie</font></div><BR><BR>
							</div>
<?php					
						}
						#---------------------------- end donate  ----------------------------#

						#---------------------------- begin check  ---------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='check'){
							if($obj->checkacc || $obj->isadmin()) include("checkaccount.php");
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end check  ------------------------------#
						
						#---------------------------- begin admin  ---------------------------#
						else if (isset($_GET['id']) && $_GET['id']=='admin'){
							if($obj->isadmin()) include("admin.php");
							else echo "<BR><BR><font color=red size=2>".$obj->lang['notaccess']."</b></font>";
						}
						#---------------------------- end admin  ------------------------------#
						
						#---------------------------- begin get  ------------------------------#
						else {
?>
							<form action="javascript:get(document.getElementById('linkform'));" name="linkform" id="linkform">
								<font color="#FF3300"><b><font color="#FFFF66"><?php printf($obj->lang['version']);?></font> - <?php printf($obj->lang['welcome']);?></b></font>
								<?php if($obj->isadmin()){
									$obj->last_version = $obj->getversion();
									if($obj->last_version > $obj->ltt_version)
										echo '<br><font color="#dbac58"><b>'.sprintf($obj->lang['update1']).'</b> - <a href="https://github.com/ltt2801/new-vinaget-script">'.sprintf($obj->lang['update2'],$obj->last_version).'</a></font>';
								}
								?>
								<br><font face=Arial size=1><span style="font-familty: Arial; color: #FFFFFF; font-size: 10px">Example: http://www.megaupload.com/?d=ABCDEXYZ<font size="3">|</font>password</span></font><BR>
								<textarea id='links' style='width:550px;height:100px;' name='links'></textarea><BR>
								<b>Proxy (host:port) or (host:port|user:pass)</b><BR>
								<input id='proxy' name='proxy' placeholder='proxy' style='width:300px;'><BR><BR>
								<input type="submit"  id ='submit' value='<?php printf($obj->lang['sbdown']); ?>'/>&nbsp;&nbsp;&nbsp;
								<input type="button" onclick="reseturl();return false;" value="<?php printf($obj->lang['reset']); ?>">&nbsp;&nbsp;&nbsp;
								<input type="checkbox" name="autoreset" id="autoreset" checked>&nbsp;<?php printf($obj->lang['autoreset']);?>&nbsp;&nbsp;&nbsp;
								<?php if ($obj->show_func_cbox == true) { ?>
								<input type="checkbox" id="autopcbox" /><font color="#FFFFFF"><?php printf($obj->lang['postcbox']);?></font>&nbsp;&nbsp;&nbsp;
								<input type="checkbox" id="autosearchuser" /><font color="#FFFFFF"><?php printf($obj->lang['searchuser']);?></font><br><br>
								<font color="#FFFFFF">Nick:</font> <input type="text" autocomplete="off" id="nick" style="width:25em"  value="" /><br><br>
								<font color="#FFFFFF">Key:</font> <input type="password" autocomplete="off" id="pass" style="width:25em" value="" />
								<?php } ?>
							</form><BR><BR>
							<div id="dlhere" align="left" style="display: none;">
								<BR><hr /><small style="color:#55bbff"><?php printf($obj->lang['dlhere']); ?></small>
								<div align="right"><a onclick="return bbcode('bbcode');" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>BB code</font></a>&nbsp;&nbsp;&nbsp;
								<a onclick="return makelist(document.getElementById('showresults').innerHTML);" href="javascript:void(0)" style='TEXT-DECORATION: none'><font color=#FF6600>Make List</font></a></div>
							</div>
							<div id="bbcode" align="center" style="display: none;"></div>
							<div id="showresults" align="center"></div>
<?php						
						}
						#---------------------------- end get  ------------------------------#
?>
						</td></tr>
					</tbody></table>
				</td></tr>
				<!-- ########################## End Main ########################### -->
			</tbody></table>

			<table width="60%" align="center" cellpadding="0" cellspacing="0">
				<tr><td>
					<div align="center" style="color:#ccc">
						<hr />
						<!-- Start Server Info -->
						<?php showStat();?>
						<!-- End Server Info -->
						<hr />
						<script type="text/javascript" language="javascript" src="ajax.js?v=<?php echo mt_rand(); ?>"></script> 
					<!-- Copyright please don't remove-->
						<?php printf($obj->lang['footer']);?>
						<STRONG><SPAN class='powered'>Based on <a href='https://github.com/giaythuytinh176/vinaget-script'>Vinaget 2.7.0 Final Revision <?php printf($obj->current_version); ?></a> by [FZ]<br/>
						Rebuild code by LTT♥. Current version: <a target='_blank' href='https://bitbucket.org/ltt2801/new-vinaget-script'><?php printf($obj->ltt_version); ?></a></SPAN><br/>
						<SPAN class='copyright'>Copyright 2009-<?php echo date('Y');?> by <a href='http://vinaget.us/'>http://vinaget.us</a>. All rights reserved. </SPAN><br />
					<!-- Copyright please don't remove-->
					</div>
				</td></tr>
			</table>
		</body>
	</html>