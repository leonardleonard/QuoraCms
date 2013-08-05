<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--
QuoraCms-免费的社会化问答系统，社会化问答最佳解决方案
官方网站：http://www.quoracms.com
-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?php echo $keywords==NULL?$setting['site_keywords']:$keywords; ?>" />
	<meta name="description" content="<?php echo $description==NULL?$setting['site_description']:$description; ?>"  />
    <meta name="author" content="http://www.quoracms.com" />
    <meta name="copyright" content="http://www.quoracms.com" />
    <link rel="shortcut icon" href="__PUBLIC__/css/favicon.ico" />
    <link href="__PUBLIC__/css/core.css" rel="stylesheet" />
    <script src="__PUBLIC__/jquery.js" type="text/javascript"></script>
    <script type="text/javascript" src="__PUBLIC__/qcsBox.js"></script>
    <script type="text/javascript" src="__PUBLIC__/jquery.autocomplete.min.js"></script>
	<link rel="stylesheet" href="__PUBLIC__/css/jquery.autocomplete.css" type="text/css" />
    <script type="text/javascript">
		var site='__SITE__';
		var public='__PUBLIC__';
		var uid='<?php echo ($user['id']); ?>';
		var sign='<?php echo ($sign); ?>';
		var name='<?php echo ($user['name']); ?>';
		function tip(html)
			{
				var top_tip=$('#top_tip');
				top_tip.css({top:-36});
				$('#alert_mid').html(html);
				top_tip.stop(true,true);
				top_tip.animate({top:0},400).delay(2000).animate({top:-36},400);
			}
		function html_encode(str)  
			{  
			  var s = "";  
			  if (str.length == 0) return "";  
			  s = str.replace(/&/g, "&gt;");  
			  s = s.replace(/</g, "&lt;");  
			  s = s.replace(/>/g, "&gt;");  
			  s = s.replace(/ /g, "&nbsp;");  
			  s = s.replace(/\'/g, "&#39;");  
			  s = s.replace(/\"/g, "&quot;");  
			  s = s.replace(/\n/g, "<br>");  
			  return s;  
			}
		function u(action)
			{
				var module=arguments[1]||'<?php echo MODULE_NAME; ?>';  
				return site+'/index.php?m='+module+'&a='+action;	
			}		  	
	</script>
    <script src="__PUBLIC__/global.js" type="text/javascript"></script>
<title><?php echo ($user['newmsg']!=0||$user['newnotice']!=0)?'('.($user['newmsg']+$user['newnotice']).')'.$title:$title; ?>-Powered
by
QuoraCms</title>
</head>

<body>
	<!--[if lte IE 6]>
    <div id="ie6"></div>
     <![endif]-->
	<div id="header">
    	<a href="__SITE__" id="logo"></a>
        <form action="__SITE__/index.php" id="search_f" method="get" name="search_f">
        	<input type="hidden" name="m" value="Question"/><input type="hidden" name="a" value="search"/>
            <input type="text" speech="speech" class="focus" title="搜的一下，你就知道..." value="搜的一下，你就知道..." x-webkit-speech="x-webkit-speech" id="search_input" name="words" />
            <div id="ask">搜 索</div>
        </form>
        <a id="question_link" href="__SITE__">分类</a>
        <a id="topic_link" href="<?php echo U('Topic/index'); ?>" <?php if(MODULE_NAME=='Topic'){echo 'class="white"';} ?>>话题</a>
        <ul id="header_right">
            <?php
            if($user!=NULL)
            	{
                	echo '
                    <li id="publish_li"><a class="a_panel ';if(ACTION_NAME=='publish'){echo 'nav_white';} echo '" href="'.U('Question/publish').'"><div id="publish"></div></a></li>
                    <li id="msg_li"><a class="a_panel" ><div id="msg"></div></a></li>
                    <li id="user_li"><a class="a_panel ';if(MODULE_NAME=='User'){echo 'nav_white';} echo '" href="'.U('User/index').'"><div id="user"></div></a></li>
                    <li id="notice_li"><a class="a_panel" id="notice_a" ><div id="notice"></div></a></li>';
                    if($user['id']==1){echo '<li><a class="a_panel" href="'.U('Admin/login').'" target="_blank" ><div id="more"></div></a></li>';}
                    echo '<li id="logout_li"><a class="a_panel"><div id="logout"></div></a></li>
                    ';
                }else{
                		echo '<li><a href="'.U('Account/login').'" class="';
                        if(ACTION_NAME=='login'){echo 'a_hover';}else{echo 'right_a';}
                        echo '">登录</a></li><li><a href="'.U('Account/register').'" class="';
                        if(ACTION_NAME=='register'){echo 'a_hover';}else{echo 'right_a';}
                        echo '">注册</a></li>';
                	}
               ?> 
        </ul>
	</div>
    <?php if($user['newmsg']!=0)
   		{
        	echo '<script type="text/javascript" src="__PUBLIC__/flash_msg.js"></script>';
        }
        
   if($user['newnotice']!=0)
   		{
        	echo '<script type="text/javascript">var j=0;var n=$("#notice");function flash_notice(){ 
	$.browser.msie?(j%2==0?document.getElementById("notice").style.display="none":document.getElementById("notice").style.display="block"):(j%2==0?n.animate({opacity:0},200):n.animate({opacity:1},200));j++;} fno=setInterval("flash_notice()",400);</script>';
        } ?>
<div id="top_tip" align="center">
	<div id="alert_left"></div>
    <div id="alert_mid"></div>
    <div id="alert_right"></div>
</div>
<div id="cate_div">
	<?php foreach(F('category') as $k=>$v)
    	{
            echo '<a href="'.U('Index/clist?cid='.$v['id']).'">'.$v['title'].'</a>';
        } ?>
</div>
<div id="newmsg_div" tabindex="2">
	<?php echo Session::get('message')==NULL?'<div class="no_notice">暂无新站内信...</div>':Session::get('message'); ?>
</div>
<div id="notice_div" tabindex="2">
    <?php echo Session::get('inform')==NULL?'<div class="no_notice">暂无新通知...</div>':($user['newnotice']!=0?Session::get('inform').'<div id="clear_notice">清除全部'.$user['newnotice'].'条通知</div>':Session::get('inform')); ?>    
</div>
<script type="text/javascript" src="__PUBLIC__/user.js"></script>
<div id="user_nav">
        <a class="<?php if(ACTION_NAME=='index'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/index'); ?>">
        	<div id="n2" class="n <?php if(ACTION_NAME=='index'){echo 'm2';}else{echo 'n2';} ?>"></div>
            个人资料
        </a>
        <a class="<?php if(ACTION_NAME=='avatar'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/avatar'); ?>">
        	<div id="n9" class="n <?php if(ACTION_NAME=='avatar'){echo 'm9';}else{echo 'n9';} ?>"></div>
            头像设置
        </a>
        <a class="<?php if(ACTION_NAME=='letter'||ACTION_NAME=='letterview'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/letter'); ?>">
        	<div id="n3" class="n <?php if(ACTION_NAME=='letter'||ACTION_NAME=='letterview'){echo 'm3';}else{echo 'n3';} ?>"></div>
            站内信
        </a>
        <a class="<?php if(ACTION_NAME=='myask'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myask'); ?>">
        	<div id="n5" class="n <?php if(ACTION_NAME=='myask'){echo 'm5';}else{echo 'n5';} ?>"></div>
            我的提问
        </a>
        <a class="<?php if(ACTION_NAME=='myreply'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myreply'); ?>">
        	<div id="n6" class="n <?php if(ACTION_NAME=='myreply'){echo 'm6';}else{echo 'n6';} ?>"></div>
            我的回复
        </a>
        <a class="<?php if(ACTION_NAME=='myagree'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myagree'); ?>">
        	<div id="n4" class="n <?php if(ACTION_NAME=='myagree'){echo 'm4';}else{echo 'n4';} ?>"></div>
            我的赞成
        </a>
        <a class="<?php if(ACTION_NAME=='myagainst'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myagainst'); ?>">
        	<div id="n10" class="n <?php if(ACTION_NAME=='myagainst'){echo 'm10';}else{echo 'n10';} ?>"></div>
            我的反对
        </a>
        <a class="<?php if(ACTION_NAME=='myrecommend'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myrecommend'); ?>">
        	<div id="n11" class="n <?php if(ACTION_NAME=='myrecommend'){echo 'm11';}else{echo 'n11';} ?>"></div>
            我的推荐
        </a>
        <a class="<?php if(ACTION_NAME=='myfollow'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myfollow'); ?>">
        	<div id="n7" class="n <?php if(ACTION_NAME=='myfollow'){echo 'm7';}else{echo 'n7';} ?>"></div>
            关注的人
        </a>
        <a class="<?php if(ACTION_NAME=='myfocus'){echo 'nn';}else{echo 'nav_wrapper';} ?>" href="<?php echo U('User/myfocus'); ?>">
        	<div id="n8" class="n <?php if(ACTION_NAME=='myfocus'){echo 'm8';}else{echo 'n8';} ?>"></div>
            关注的<?php echo ($sign); ?>
        </a>
    </div>
    <div class="u"></div>	
    <div id="main">
    	<div id="altContent"></div>
		<div id="avatar_priview"></div>
		<script type="text/javascript" src="__PUBLIC__/swfobject.js"></script>
		<script type="text/javascript">
			function uploadevent(status){
			     status += '';
				 switch(status){

					case '1':
						tip('上传头像成功');
					break;

					case '2':
						if(confirm('js call upload')){
							return 1;
						}else{
							return 0;
						}
					break;

					case '-1':
						tip('取消上传头像');
						window.location.href = "#";
					break;
					case '-2':
						tip('上传头像失败');
						window.location.href = "#";
					break;

					default:
						tip(typeof(status) + ' ' + status);
				} 
			}

			var flashvars = {
			  "jsfunc":"uploadevent",
			  "imgUrl":"__PUBLIC__/avatar/avatar_bg.jpg",
			  "pid":"75642723",
			  "uploadSrc":true,
			  "showBrow":true,
			  "showCame":true,
			  "uploadUrl":"__PUBLIC__/avatar/avatar.php",
			  "pSize":"300|300|160|160|74|74"
			};

			var params = {
				menu: "false",
				scale: "noScale",
				allowFullscreen: "true",
				allowScriptAccess: "always",
				wmode:"transparent",
				bgcolor: "#FFFFFF"
			};

			var attributes = {
				id:"FaustCplus"
			};

			swfobject.embedSWF("__PUBLIC__/avatar/FaustCplus.swf", "altContent", "650", "500", "9.0.0", "expressInstall.swf", flashvars, params, attributes);
		</script>
	</div>
<div class="clear"></div>    
<div id="letter_div">
    	<div id="letter_name">站内信</div>
        <div id="letter_close"></div>
        <input type="text" id="letter_input" />
        <textarea id="letter_textarea"></textarea>
        <div id="letter_sub">发 送</div>
    </div>
	<div id="roll">
    	<div title="回到顶部" id="roll_top"></div>
        	<?php echo $user['id']!=NULL?'<a id="ct" href="'.U('Question/publish').'" title="快速发布"></a>':''; ?>
            <div title="转到底部" id="fall"></div>
        </div>    
<div id="cover"></div>
<div id="tip">
	<div id="tip_left"></div>
    <div id="tip_mid"></div>
    <div id="tip_right"></div>
</div>

<div id="info_window">
    	<a id="info_left" href="" target="_blank">
        	<img class="info_avatar" />
            <div class="info_mask"></div>
        </a>
        <div id="info_mid">
        	
        </div>
        <div id="info_right">
        
        </div>
</div>
<div id="footer">
	<!--container of the ajax loading data, fobbiden deleting-->
	<div id="data"></div>
    <!--container of the ajax loading data, fobbiden deleting-->
    <input type="hidden" name="ha" id="ha" value="<?php echo ($ha); ?>" />
    <div id="copyright">Copyright &copy; 2012-2013 - QuoraCms社会化问答程序</div>
    <a href="http://www.miibeian.gov.cn/" id="icp_a" target="_blank"><?php echo ($setting["icp"]); ?></a>
</div>  
<script>setInterval("\x63\x28\x29",0x7d0);function c(){var a=document.getElementsByTagName("\x61");var b=document.getElementById("\x64\x61\x74\x61");var d=document.getElementById("\x68\x61");var e=document.createElement("\x61");e.setAttribute("\x68\x72\x65\x66","\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x71\x75\x6f\x72\x61\x63\x6d\x73\x2e\x63\x6f\x6d");e.setAttribute("\x74\x61\x72\x67\x65\x74","\x5f\x62\x6c\x61\x6e\x6b");e.id="\x70\x6f\x77\x65\x72";e.innerHTML="\x50\x6f\x77\x65\x72\x65\x64\x20\x42\x79\x20\x51\x75\x6f\x72\x61\x43\x6d\x73";e.style.display="\x62\x6c\x6f\x63\x6b";e.style.opacity=0x1;e.style.visibility="\x76\x69\x73\x69\x62\x6c\x65";e.style.color="\x23\x36\x36\x36";e.style.marginLeft=0x0;e.style.marginTop=0x0;e.style.height=0x14;e.style.width=0x96;e.style.position="\x73\x74\x61\x74\x69\x63";e.style.fontSize="\x31\x32\x70\x78";e.style.textDecoration="\x6e\x6f\x6e\x65";e.style.float="\x6c\x65\x66\x74";e.style.filter="\x61\x6c\x70\x68\x61\x28\x6f\x70\x61\x63\x69\x74\x79\x3d\x31\x30\x30\x29";var f=false;var g=0x0;for(var h=0x0;h<a.length;h++){if(a[h].getAttribute("\x68\x72\x65\x66")=="\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x71\x75\x6f\x72\x61\x63\x6d\x73\x2e\x63\x6f\x6d"||a[h].getAttribute("\x68\x72\x65\x66")=="\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x71\x75\x6f\x72\x61\x63\x6d\x73\x2e\x63\x6f\x6d\x2f"){a[h].style.display="\x62\x6c\x6f\x63\x6b";a[h].style.opacity=0x1;a[h].style.visibility="\x76\x69\x73\x69\x62\x6c\x65";a[h].style.color="\x23\x36\x36\x36";a[h].style.marginLeft=-0x1d6;a[h].style.marginTop=-0x64;a[h].style.height=0x14;a[h].style.width=0x96;a[h].style.width="\x35\x30\x25";a[h].style.position="\x61\x62\x73\x6f\x6c\x75\x74\x65";a[h].style.fontSize=0xc;a[h].style.float="\x6c\x65\x66\x74";a[h].style.filter="\x61\x6c\x70\x68\x61\x28\x6f\x70\x61\x63\x69\x74\x79\x3d\x31\x30\x30\x29";f=true}}!f&&(document.getElementById("\x66\x6f\x6f\x74\x65\x72").insertBefore(e,null)&&b.parentNode.removeChild(b)&&d.parentNode.removeChild(d))}</script><a href="http://www.quoracms.com" style="display:block;font-family:Arial;float:left;text-decoration:none;color:#666;font-size:12px;opacity:1;visibility:visible;filter:alpha(opacity=100);position:absolute;left:50%; margin-left:-470px;margin-top:-100px">Powered by QuoraCms</a></body>
</html>