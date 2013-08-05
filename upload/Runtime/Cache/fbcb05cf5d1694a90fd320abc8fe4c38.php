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
<script>
var cid='<?php echo $_GET['cid']; ?>';
</script>
<?php  ?>
<script type="text/javascript" src="__PUBLIC__/index.js"></script>
    <div id="main">
    <img src="__PUBLIC__/css/loading_max.gif" id="loading_max" />
    	<div id="topic_info"><?php echo ($category_title); ?></div>
    	<ul id="topic_ul_list" class="topic_view_header">
                <li class="button_press ql" title="all_question">全部</li>
                <li class="button_round" title="all_question" id="time_order">时间 ▼</li>
                <li class="button_round ql" title="hot_question">热门</li>
                <li class="button_round ql" title="recommend_question">推荐</li>
         </ul>
        <div class="clear l"></div>
        <div id="main_left">
        	<div id="question_list">
                <?php if($question_list==NULL)
                    	{
                        	echo '<div id="question_error">暂无'.$sign.'列表</div>';
                        }else{
                    foreach($question_list as $k=>$v)
                        {
                        $solve=$v['issolve']==1?'解决':'未解决';
                            echo '
                                <div class="q_wrapper">
                                    <div class="avatar_wrapper" >
                                        <img src="__PUBLIC__'; echo get_avatar($v['uid'],'min');echo '" class="avatar" />
                                        <img src="__PUBLIC__/css/avatar_cover.png" class="avatar_cover" /> 
                                        <input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" />
                                    </div>
                                    <div class="q_list">
                                    <a href="'.U('Question/view?qid='.$v['id']).'" target="'.$setting['link_open'].'">'.$v['title'].'</a>
                                        <div class="opt">
                                            <div class="q_option_left"></div>
                                            <div class="q_option_mid">
                                                时间：'.time_mode($v['pubtime']).'<img src="__PUBLIC__/css/devider.png" />
                                                浏览:'.$v['viewcount'].'<img src="__PUBLIC__/css/devider.png" />
                                                回复:'.$v['answercount'].'<img src="__PUBLIC__/css/devider.png" />
                                                关注:'.$v['focuscount'].'<img src="__PUBLIC__/css/devider.png" />
                                                推荐:'.$v['recommendcount'].'<img src="__PUBLIC__/css/devider.png" />
                                                状态：'.$solve.'
                                            </div>
                                            <div class="q_option_right"></div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                      } ?>
                <div id="page" align="center">
                <?php echo ($page); ?>
                </div>
            </div>
         </div>   
         <div id="main_right">
                <div class="side_wrapper">
                    <div class="side_title">热门推荐</div>
                    <?php echo S('side_recommend_question'); ?>
                </div>
                <div class="side_wrapper">
                    <div class="side_title"><?php echo $setting['is_quora']==0?'待回复的帖子':'待解决的问题' ?></div>
                    <?php echo S('side_no_reply'); ?>
                </div>  
    		</div>   
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