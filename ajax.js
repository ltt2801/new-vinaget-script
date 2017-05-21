/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Description: 
	- Vinaget is script generator premium link that allows you to download files instantly and at the best of your Internet speed.
	- Vinaget is your personal proxy host protecting your real IP to download files hosted on hosters like RapidShare, megaupload, hotfile...
	- You can now download files with full resume support from filehosts using download managers like IDM etc
	- Vinaget is a Free Open Source, supported by a growing community.
* Code LeechViet by VinhNhaTrang
* Developed by ..:: [H] ::..

*/

var tong = 0;
var errorlogin = false;
var auto_refresh = setInterval(function(){$("#server_stats").load('index.php?infosv='+ Math.random());}, 60000);
function showOrHide(){
	if ($('#showacc').css('display') == "none"){
		$('#showacc').slideDown(800);
		$("#moreacc").html(lang["lessacc"]);
	}
	else {
		$('#showacc').slideUp(800);
		$("#moreacc").html(lang["moreacc"]);
	}
}
function in_array(array, listarray){
	var a=false;
	for(var i=0;i<listarray.length;i++){
		if(array == listarray[i]){
			a=true;
			break;
		}
    }
    return a;
}
function get(obj) {
	$('#submit').attr('disabled', 'disabled');
	setTimeout(function(){$('#submit').removeAttr('disabled');},2000);
	$("#bbcode").html("");
	var urllist = $("#links").val();
	var links = urllist.split("\n");
	var showlink = "";
	var linkdown = new Array();
	var tonglink = 0;
	for (i=0;i< links.length;i++){
		if(links[i].length > 20 && (/http/i.test(links[i]) ===true || /www/.test(links[i]) ===true)) {
			links[i] = links[i]+" ";
			var patt=/((http|www.).+?) /gi;
			var urlget = links[i].match(patt);
			for (j=0;j< urlget.length ;j++){
				urlget[j] = urlget[j].replace(" ", "");
				if(urlget[j].substr(0,4) === "www.") urlget[j] = "http\:\/\/"+urlget[j];
				if(in_array(urlget[j], linkdown) == false) {
					linkdown[tonglink] = urlget[j];
					tonglink++;
				}
			}
		}
	};
	
	if(linkdown.length == 0)return;
	for (i=0;i< linkdown.length;i++){
		if (i === 10) break;
		showlink = showlink+"<div id='link"+i+"'><font color="+loadcolor+"><b>"+linkdown[i]+"</b></font> <img src='images/"+loadimg+"' /></div>";
		ajaxget(i,linkdown[i],'get');
	}
	if(document.getElementById("autoreset").checked === true) {
		$('#dlhere').show(800);
		$("#showresults").show(800);
		$('#links').val("");
		$('#bbcode').hide();
		$("#showresults").html(showlink+"<BR/>");
	}
	else {
		$('#dlhere').show(800);
		$("#showresults").show(800);
		$('#links').val("");
		$('#bbcode').hide();
		$("#showresults").html(showlink+"<BR/>"+$('#showresults').html());
	}

	if(linkdown.length >10) {
		var lefturl = "";
		for (i=10;i< linkdown.length ;i++){
			lefturl = lefturl+linkdown[i]+"\n";
		}
		$('#links').val(lefturl);
	}
}

function ajaxget(id,url,type){
	var autopcbox = 0;
	var autosearchuser = 0;
	
	if (document.getElementById("autopcbox") && document.getElementById("autopcbox").checked === true)
		autopcbox = 1;
	if (document.getElementById("autosearchuser") && document.getElementById("autosearchuser").checked === true)
		autosearchuser = 1;
	
	var nick = $("#nick").val();
	var pass = $("#pass").val();
	var param = $("form[name=formlink]").serialize();
	var proxy = $("#proxy").val();
	captcha = 'none';
	if (type =='reget'){
		$("#link"+id).html("<font color=#FFFF99><b>"+url+"</b></font> <img src='images/"+loadimg+"' />"); 
	}
	else if (type !=='get'){
		$("#link"+id).html("reload captcha... <img src='images/"+loadimg+"' />"); 
		captcha = 'reload';
	}
	if(i > 4 && type == 'get') time = (Math.round(i/5)*3000);
	else time = 0;
	setTimeout(function(){
		$.ajax({
			type: "POST",
			url: "index.php?rand="+ Math.random(),
			data: "urllist="+encodeURIComponent(url)+"&autopcbox="+autopcbox+"&autosearchuser="+autosearchuser+"&nick="+encodeURIComponent(nick)+"&pass="+encodeURIComponent(pass)+'&captcha='+captcha+'&proxy='+proxy+"&"+param,
			success: function(html) {
				/* limit ip for mod  */
				if (errorlogin === true) return;
				if(/errorlogin/.test(html) === true) {
					errorlogin = true;
					alert(unescape("%50%68%E1%74%20%68%69%u1EC7%6E%20%63%F3%20%73%u1EF1%20%74%68%61%79%20%u0111%u1ED5%69%20%49%50.%20%56%75%69%20%6C%F2%6E%67%20%u0111%u0103%6E%67%20%6E%68%u1EAD%70%20%6C%u1EA1%69%20%21"));
					location.href='./login.php?go=logout';
					 return;
				}
				/* limit ip for mod  */
				if(/type=\"password\" name=\"secure\"/i.test(html) == true) {
					alert('Expired Cookies ! Please login again.');
					location.href='./login.php?go=logout';
					return;
				}
				else if(/Link Dead/.test(html) ===true || /errorlimit/.test(html) ===true) $("#link"+id).html(html);
				else if(/password protected/.test(html) === true) {
					$("#link"+id).html('<form action="javascript:ajaxget(\''+id+'\',\''+url+'\',\'reget\');" name="formlink" id="formlink"> '+html+' <input type="text" name="password" value="" size="10" maxlength="150"> <input type=submit value="go"></form>');
				}
				else if(/captcha code/.test(html) === true) {
					var text = /captcha code \'(.*?)\'/g;
					var captcha=text.exec(html);
					 if(/Authentication/i.test($('#showresults').html()) == false) {
						 $("#link"+id).html('<center><form action="javascript:ajaxget(\''+id+'\',\''+url+'\',\'reget\');" name="formlink" id="formlink"><table><tr><td><img src="http://www.google.com/recaptcha/api/image?c='+captcha[1]+'"/></td><td>Authentication Required<br><input type="text" name="recaptcha_response_field" value="" size="20" maxlength="50" /><input type="hidden" name="recaptcha_challenge_field" value="'+captcha[1]+'" /><input type="submit" value="Go"/> <a onclick="ajaxget(\''+id+'\',\''+url+'\',\'refresh\');" href="javascript:void(0)" style="TEXT-DECORATION: none">Refresh</a></td></tr></table></b></form></center>');
					 }
					 else {
						$('#links').val(url+"\n"+$('#links').val());
						$("#link"+id).html('');
					 }
				}
				else if(/is this link sex/.test(html) === true) {
					$("#link"+id).html('<b><font color="red">is this link sex ??? ==&#9658; </font><a target="'+id+'" style=\'TEXT-DECORATION: none\' href="http://www.google.com/search?q='+url+'"><font color="#0066FF">'+url+'</font></a> <a onclick="return ajaxget(\''+id+'\',\''+url+'|not3x\',\'reget\');" href="javascript:void(0)" style=\'TEXT-DECORATION: none\'><font color=#ffcc33>&nbsp; &nbsp; if not click here to try again</font></a></b>');
				}
				else if(/please try again/.test(html) === true) {
					$("#link"+id).html('<b><a href='+url+' style="TEXT-DECORATION: none"><font color=red face=Arial size=2>'+url+'</font></a> <a onclick="return ajaxget(\''+id+'\',\''+url+'\',\'reget\');" href="javascript:void(0)" style=\'TEXT-DECORATION: none\'><font color=#ffcc33 face=Arial size=2> ==&#9658;  Can\'t get. click here to try again</font></a></b>');
				}
				else $("#link"+id).html(html);
			},
			error:function (){
				$('#submit').removeAttr('disabled');
				$("#link"+id).html('<b><a href='+url+' style="TEXT-DECORATION: none"><font color=red face=Arial size=2>'+url+'</font></a> <a onclick="return ajaxget(\''+id+'\',\''+url+'\',\'reget\');" href="javascript:void(0)" style=\'TEXT-DECORATION: none\'><font color=#ffcc33 face=Arial size=2> ==&#9658;  Can\'t get. click here to try again</font></a></b>');
			}
		});
	},time);
}

function reseturl() {
	$("#showresults").html("");
	$("#bbcode").html("");
	$('#urllist').val("");
	$("#dlhere").hide(800);

}

function checkacc(type){
	$("table[id='table-"+type+"'] td[id^='unknown']").html('Loading..');
	$.ajax({
		type: "POST",
		url: "index.php?id=check&rand="+ Math.random(),
		data: "check="+type,
		success: function(resp) {
			$("table[id='table-"+type+"']").html($("table[id='table-"+type+"']", resp).html());
		},
		error:function (){
			checkacc(type);
		}
	});
}

function donate(obj){
	var errinvalid = '<img src="images/error.png"> <font color="red"><b>'+lang["invalid"]+'</b></font>';
	var suc1 = '<b><font color="#FF3300">'+lang["success"]+'</font></b>';
	var suc2 = '<b><font color="#86a5d5">'+lang["dsuccess"]+'</font></b>';
	var loading = '<img src="images/loading.gif"><br/>'+lang["getloading"];
	var account = encodeURIComponent($("#accounts").val());
	if($("#accounts").val().length > 10) {
		$("#wait").html(loading);
		$.ajax({
			type: "POST",
			url: "add.php?rand="+ Math.random(),
			data: "type="+$("#type").val()+"&accounts="+account,
			success: function(html) {
				if(html == 'true') {
                    $("#wait").html('');
					$('#accounts').val("");
					$("#wait").fadeOut(1100, 'swing');
					$("#wait").fadeOut(1100, function(){
						$("#wait").html(suc2);
						$("#wait").html(suc1);                        	
					});
					$("#wait").html(suc2);
					$("#wait").fadeIn(800);
				}
				else $("#wait").html(errinvalid);	
			}
		});
	}
	else $("#wait").html(errinvalid);
}

function makelist(data){
	if ($('#showlistlink').css('display') == "none"){
		var showlinkgen = "";

		if(navigator.appName === "Microsoft Internet Explorer"){
			var linkgens= data.split("<DIV id=link");
			$.each(linkgens,
				function(i) {
				if(/click here to download/.test(linkgens[i]) === true){
					var text = /href=\"(.*?)\" target/g;
					var linkgen=text.exec(linkgens[i]);
					showlinkgen = showlinkgen+linkgen[1]+"\n";
				}
			});
		}
		else {
			var linkgens= data.split('<div id="link');
			$.each(linkgens,
				function(i) {
				if(/click here to download/.test(linkgens[i]) === true){
					var text = /href=\"(.*?)\" style/g;
					var linkgen=text.exec(linkgens[i]);
					showlinkgen = showlinkgen+linkgen[1]+"\n";
				}
			});
		}
		if(showlinkgen.length < 10) return;
		$('#showlistlink').show(800);
		$("#listlinks").html("<textarea style='width:950px;height:400px' id=\"textarea\">"+showlinkgen+"</textarea>");

	}
	else {
		$('#showlistlink').hide(800);
	}
}

function bbcode(type) {
	if ($('#bbcode').css('display') !== "none" && type !== "list"){
		$('#bbcode').slideUp();
		return;
	}
	if(type === "list") {
		$("#report").text("make BB Code").show().fadeOut(3000); 
		$("input[name ='bbcode']").attr('value', 'Make list');
	}
	if(type === "list" && /\[b]/i.test($("#listlinks").html()) === true) {
		data = $('#showresults').html();
		if(/id=link/i.test(data))  var linkgens= data.split('id=link');
		else var linkgens= data.split('id="link');
		var showlinkgen = "";
		$.each(linkgens,
			function(i) {
			if(/errorlink/i.test(linkgens[i]) ===false  && /please try again/i.test(linkgens[i]) === false  && /href=/i.test(linkgens[i]) === true){
				var text = /href=\"(.*?)\"/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+linkgen[1]+"\n";
			}
		});
		$("#listlinks").html("<textarea style='width:950px;height:400px' id=\"textarea\">"+showlinkgen+"</textarea>");
		$("input[name ='bbcode']").attr('value', 'bbcode');
		$("#report").text("Make list").show().fadeOut(3000); 
		return;
	}
	var showlinkgen = "";
	if(navigator.appName === "Microsoft Internet Explorer"){
		var linkgens= $('#showresults').html().split("<DIV id=link");
		$.each(linkgens,function(i) {
			if(/File too big/.test(linkgens[i]) === true) {
				var text = /<B><FONT color=#00cc00>(.*?)<\/FONT> <FONT color=red>(.*?)<\/FONT><FONT color=#3399ff>(.*?)<\/FONT> <FONT color=#ffcc00>(.*?)<\/FONT><\/B>/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+ "[B][color=blue]"+linkgen[1]+"[/color][color=red] "+linkgen[2]+" [/color][color=green] "+linkgen[3]+" [/color][color=#d71f83] "+linkgen[4]+" [/color][/b][br]";
			}
			else if(/is this link sex/.test(linkgens[i]) === true) {
				var text = /\/search\?q\=(.*?)\" target/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+"[B][url=http://www.google.com/search?q="+linkgen[1]+"][color=red]Link sex ??? ==&#9658; [/color][color=blue]"+linkgen[1]+"[/color][/b][/url][br]";
			}
			else if(/Link Dead/.test(linkgens[i]) === true) {
				var text = /href=\"(.*?)\"/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+"[B][url="+linkgen[1]+"][color=blue][s]"+linkgen[1]+"[/s][/color][/url] errorlink [color=red] ==&#9658; Link Dead !!![/color][/b][br]";
			}
			else if(/click here to download/.test(linkgens[i]) === true){
				var text = /href=\"(.*?)\" (.*?)> <FONT color=(.*?)>(.*?)<\/FONT> <FONT color=(.*?)>(.*?)<\/FONT>/g;
				var linkgen=text.exec(linkgens[i]);
				if(linkgen[4].length >35) linkgen[4] = linkgen[4].substring(0, 35)+'...';
				showlinkgen = showlinkgen+"[b][URL="+linkgen[1]+"] "+title+"  | [color="+colorname+"] "+linkgen[4]+" [/color][color="+colorfile+"]"+linkgen[6]+"[/color][/url][/b][br]";
			}	
		});	
	}
	else {
		var linkgens= $('#showresults').html().split('<div id="link');
		$.each(linkgens,function(i) {
			if(/File too big/.test(linkgens[i]) === true) {
				var text = /<b><font color=\"#00CC00\">(.*?)<\/font> <font color=\"red\">(.*?)<\/font><font color=\"#3399FF\">(.*?)<\/font> <font color=\"#FFCC00\">(.*?)<\/font><\/b>/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+ "[B][color=blue]"+linkgen[1]+"[/color][color=red] "+linkgen[2]+" [/color][color=green] "+linkgen[3]+" [/color][color=#d71f83] "+linkgen[4]+" [/color][/b][br]";
			}
			else if(/is this link sex/.test(linkgens[i]) === true) {
				var text = /\/search\?q\=(.*?)\"><font/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+"[B][url=http://www.google.com/search?q="+linkgen[1]+"][color=red]Link sex ??? ==&#9658; [/color][color=blue]"+linkgen[1]+"[/color][/b][/url][br]";
			}
			else if(/Link Dead/.test(linkgens[i]) === true) {
				var text = /href=\"(.*?)\"/g;
				var linkgen=text.exec(linkgens[i]);
				showlinkgen = showlinkgen+"[B][url="+linkgen[1]+"][color=blue][s]"+linkgen[1]+"[/s][/color][/url] errorlink [color=red] ==&#9658; Link Dead !!![/color][/b][br]";
			}
			else if(/click here to download/.test(linkgens[i]) === true){
				var text = /href=\"(.*?)\" (.*?)> <font color=\"(.*?)\">(.*?)<\/font> <font color=\"(.*?)\">(.*?)<\/font>/g;
				var linkgen=text.exec(linkgens[i]);
				if(linkgen[4].length >35) linkgen[4] = linkgen[4].substring(0, 35)+'...';
				showlinkgen = showlinkgen+"[b][URL="+linkgen[1]+"] "+title+"  | [color="+colorname+"] "+linkgen[4]+" [/color][color="+colorfile+"]"+linkgen[6]+"[/color][/url][/b][br]";
			}	
		});	
	}

	if(showlinkgen.length < 10) return;
	if(type === "list") {
		$("#listlinks").html("<textarea style='width:950px;height:400px' id=\"textarea\">[center]"+showlinkgen+"[/center]</textarea>");
	}
	else {
		if ($('#bbcode').css('display') == "none") $('#bbcode').slideDown();
		$("#bbcode").html("<BR/><textarea onclick='javascript:this.focus();this.select()' style='width:100%;height:100px' id=\"textarea\">[center]"+showlinkgen+"[/center]</textarea><BR/><BR/>");

	}
}

function selectAllText(textbox){
	textbox.focus();
	textbox.select();
}
$('#SelectAll').click(function(){
	$('#textarea').show();
	selectAllText($('#textarea'));
	$("#report").text("All text was selected").show().fadeOut(3000); 
});

$('#copytext').click(function(){
	var clip = new ZeroClipboard.Client();
	var lastTd = $(this);
	clip.glue(lastTd[0]);
	clip.addEventListener('mouseOver', function (client) {
	/* update the text on mouse over */
		clip.setText( $('#textarea').val());
	});
	clip.addEventListener('complete', function(client, text) {
		$("#report").text("Copied text to clipboard").show().fadeOut(3000); 
	});
});