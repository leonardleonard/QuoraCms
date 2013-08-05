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
<script type="text/javascript" src="__PUBLIC__/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>
<link rel="stylesheet" href="__PUBLIC__/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css" type="text/css" />
<link rel="stylesheet" href="__PUBLIC__/prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
<script type="text/javascript" src="__PUBLIC__/rotate.js"></script>
<script src="__PUBLIC__/prettyPhoto/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	var qid=<?php echo $_GET['qid']; ?>;
	var quid=<?php echo ($question['uid']); ?>;
	var reply_min_wordcount=<?php echo ($setting['reply_min_wordcount']); ?>;
	var focus_ini=<?php echo ($focus_ini); ?>;
	var my_avatar='__PUBLIC__<?php echo get_avatar($user['id'],'min'); ?>';
	var issolve=<?php echo $question['issolve']; ?>;
	var p='<?php echo $_GET['p']; ?>';
	var pa='<?php echo $_GET['noticepage']; ?>';
	var helpless_min_count='<?php echo ($setting['helpless_min_count']); ?>';
	SyntaxHighlighter.all();
</script>
<script type="text/javascript" src="__PUBLIC__/jUploader.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/question.js"></script>
<div id="main">
	<div id="main_left">
    	<div id="view_title">
            <h1 id="title_left">
            <?php echo ($question['title']); ?>
            </h1>
            <div class="avatar_wrapper" id="title_avatar" >
                 <img src="__PUBLIC__<?php echo get_avatar($question['uid'],'min'); ?>" class="avatar" />
                 <img src="__PUBLIC__/css/avatar_cover.png" class="avatar_cover" /> 
                 <input type="hidden" value="<?php echo ($question['uid']); ?>" class="avatar_hidden" /> 
            </div>
            <div class="clear"></div>
        </div>
        <div class="opt" id="view_opt">
                 <div class="q_option_left"></div>
                 <div class="q_option_mid">
                   时间： <?php echo time_mode($question['pubtime']); ?><img src="__PUBLIC__/css/devider.png" />
                   回复: <?php echo ($question['answercount']); ?><img src="__PUBLIC__/css/devider.png" />
                   浏览: <?php echo ($question['viewcount']); ?><img src="__PUBLIC__/css/devider.png" />
                   关注: <?php echo ($question['focuscount']); ?><img src="__PUBLIC__/css/devider.png" />
                   <a id="recommend_btn">推荐一下:</a> <?php echo ($question['recommendcount']); ?><img src="__PUBLIC__/css/devider.png" />
                   <?php if($question['issolve']==0){echo '未解决';}else{echo '已解决';}
                   echo $is_me?'<img src="__PUBLIC__/css/devider.png" /><a href="'.U('Question/edit?qid='.$_GET['qid']).'" class="edi_question">编辑</a>':''; ?>
                  </div>
                  <div class="q_option_right"></div>
        		  <a id="up_num">+1</a>
                  <div class="clear"></div>
        </div>
        <h2 id="view_detail">
        <?php echo stripslashes(htmlspecialchars_decode($question['detail'])); ?>
        <div class="clear"></div>
        </h2>
        <div id="reply_div">
            <div id="q_opt">
            	<div id="topic_data" class="none">
                	<?php if($question['topic'])
                    	{
                        	foreach($question['topic'] as $k=>$v)
                                {
                                   echo '<div class="topic_add_name"><div class="q_option_left"></div><div class="q_option_mid"><a href="'.U('Topic/view?tid='.$v['id']).'" target="_blank">'.$v['name'].'</a><input type="hidden" name="topic_name" class="tpc_name" value="'.$v['name'].'" /><input type="hidden" name="topic_id" value="'.$v['id'].'" /><img class="topic_close none" src="__PUBLIC__/css/topic_close.gif" /></div><div class="q_option_right"></div></div>';
                                }
                        } ?>
                </div>
            	<div id="q_topic_div">
                	<div id="q_topic_list">
                	<?php if($question['topic'])
                    	{
                        	foreach($question['topic'] as $k=>$v)
                                {
                                   echo '<div class="topic_add_name"><div class="q_option_left"></div><div class="q_option_mid"><a href="'.U('Topic/view?tid='.$v['id']).'" target="_blank">'.$v['name'].'</a><input type="hidden" name="topic_name" class="tpc_name" value="'.$v['name'].'" /><input type="hidden" name="topic_id" value="'.$v['id'].'" /><img class="topic_close none" src="__PUBLIC__/css/topic_close.gif" /></div><div class="q_option_right"></div></div>';
                                }
                        } ?>
                    </div>
                    <div id="topic_div" class="none">
                            <input maxlength="8" id="search_topic" name="topic" type="text" />
                            <div class="s_btn" id="q_add_btn">添加话题</div>
                            <div class="s_btn" id="q_save_btn">保存当前</div>
                            <div class="s_btn" id="q_cancel_btn">放弃更改</div>
                    </div>
                    <a id="q_topic_edit"><?php echo $topic_list_arr?'编辑话题':'点此编辑话题'; ?></a>
                </div>
                <div class="tab <?php $_GET['order']=='agreedec'&&print 'tab_bottom'; ?>">
                    <a id="time_inc" href="<?php echo U('Question/view?qid='.$question['id'].'&order=timeinc'); ?>">时间增序</a>
                    <a id="agree_dec" href="<?php echo U('Question/view?qid='.$question['id'].'&order=agreedec'); ?>">赞成数降序</a>
                </div>
                <div class="clear"></div>
            </div>
        <div id="r_list">    
        <?php if($answer==NULL)
        	{
            	echo '<div id="reply_zero">暂无回复</div>';
            }else{
       	foreach($answer as $k=>$v)
        	{
            	($v['bestanswer']==1&&$setting['is_quora']==1)?($solve='_solve'):($solve='');
                $v['img']!=NULL?($img='<a href="__PUBLIC__/upload/'.$v['img'].'" rel="prettyPhoto"><img src="__PUBLIC__/upload/'.$v['img'].'" width="'.$v['imgwidth'].'" height="'.$v['imgheight'].'" border="0" /></a>'):($img=NULL);
            	if($k%2==0)
                	{
                    	echo '
                        <div class="reply_wrapper reply_list" id="qcs_'.$k.'">
           	  <table class="reply_tab" border="0" cellspacing="0" cellpadding="0">
            	  <tr>
            	    <td class="td1'.$solve.'"></td>
            	    <td class="td2'.$solve.'"></td>
            	    <td class="td3'.$solve.'"></td>
          	      </tr>
            	  <tr>
            	    <td class="td4'.$solve.'"></td>
            	    <td class="td5'.$solve.'">';
                    if($v['extra']!='')
                    	{
                        	echo '<div class="reply_quote">回复
                                    <img src="__PUBLIC__/css/quot_left.png" />
                                    '.$v['extra'].'
                                    <img src="__PUBLIC__/css/quot_right.png" />：
                                </div>';
                        }
                    	echo '<p>'.$v['content'].$img.'</p>
                    </td>
            	    <td class="td6'.$solve.'"></td>
          	     </tr>
            	  <tr>
            	    <td class="td7'.$solve.'">
                    	<div class="avatar_wrapper reply_avatar">
                             <img src="__PUBLIC__'; echo get_avatar($v['uid'],'min');echo '" class="avatar" />
                             <img src="__PUBLIC__/css/avatar_cover.png" class="avatar_cover" /> 
                             <input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" /> 
                        </div>
                    </td>
            	    <td class="td8'.$solve.'"></td>
            	    <td class="td9'.$solve.'"></td>
          	    </tr>
          	  </table>
              <div class="clear"></div>
               <div class="reply_opt">
               		<div class="reply_icon_time">时间:'.time_mode($v['addtime']).'</div>
                    <div class="reply_icon_agree">顶:<a>'.$v['agreecount'].'</a></div>
                    <div class="reply_icon_against">踩:<a>'.$v['againstcount'].'</a></div>
                    <div class="reply_icon_useless">无帮助:<a>'.$v['uselesscount'].'</a></div>
                    <input type="hidden" value="'.$v['bestanswer'].'" class="best_answer_hidden" />
                    ';if($question['issolve']==0||$setting['is_quora']==0){echo'<div class="reply_icon_reply">回复</a></div>';};
                    if($v['bestanswer']==1&&$setting['is_quora']==1){echo '<div class="reply_icon_adopted">最佳答案</div>';}else if($is_me&&$question['issolve']==0&&$setting['is_quora']==1){echo '<div class="reply_icon_adopting hidden">设为答案</div>';} echo '
                    <input type="hidden" class="aid_hidden" value="'.$v['id'].'" />
                    <input type="hidden" class="auid_hidden" value="'.$v['uid'].'" />
               </div>
        	</div>
            <div class="clear"></div>';
                    }else{
                    		echo '
                            <div class="reply_wrapper_right reply_list" id="qcs_'.$k.'">
           	  <table class="reply_tab_right" border="0" cellspacing="0" cellpadding="0">
            	  <tr>
            	    <td class="td1_right"></td>
            	    <td class="td2"></td>
            	    <td class="td3_right"></td>
          	      </tr>
            	  <tr>
            	    <td class="td4_right"></td>
            	    <td class="td5 td_mid">';
                    if($v['extra']!='')
                    	{
                        	echo '<div class="reply_quote">回复
                                    <img src="__PUBLIC__/css/quot_left.png" />
                                    '.$v['extra'].'
                                    <img src="__PUBLIC__/css/quot_right.png" />：
                                </div>';
                        }
                    	echo '<p>'.$v['content'].$img.'</p>
                    </td>
            	    <td class="td6_right"></td>
          	    </tr>
            	  <tr>
            	    <td class="td7_right"></td>
            	    <td class="td8"></td>
            	    <td class="td9_right">
                    	<div class="avatar_wrapper reply_avatar_right">
                             <img src="__PUBLIC__'; echo get_avatar($v['uid'],'min');echo '" class="avatar" />
                             <img src="__PUBLIC__/css/avatar_cover.png" class="avatar_cover" /> 
                             <input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" /> 
                        </div>
                    </td>
          	    </tr>
          	  </table>
               <div class="clear"></div>
               <div class="reply_opt_right">
               		<div class="reply_icon_time">时间:'.time_mode($v['addtime']).'</div>
                    <div class="reply_icon_agree">顶:<a>'.$v['agreecount'].'</a></div>
                    <div class="reply_icon_against">踩:<a>'.$v['againstcount'].'</a></div>
                    <div class="reply_icon_useless">无帮助:<a>'.$v['uselesscount'].'</a></div>
                    <input type="hidden" value="'.$v['bestanswer'].'" class="best_answer_hidden" />
                     ';if($question['issolve']==0||$setting['is_quora']==0){echo'<div class="reply_icon_reply">回复</a></div>';};
                     if($is_me&&$question['issolve']==0&&$setting['is_quora']==1){echo '<div class="reply_icon_adopting hidden">设为答案</div>';}echo '
                    <input type="hidden" class="aid_hidden" value="'.$v['id'].'" />
                    <input type="hidden" class="auid_hidden" value="'.$v['uid'].'" />
               </div>
        	</div>
            <div class="clear"></div>';
                    	 }
            }
           } ?>
        </div>	
            
            <div class="clear"></div>
            <div id="page" align="center">
                <?php echo ($page); ?>
            </div>
        </div>
        <div id="reply_textarea_wrapper">
        <?php if($user['id']!='')
        	{
            if($question['issolve']==1&&$setting['is_quora']==1){
            echo '<div class="reply_textarea" align="center">
            	<div id="unreply">
                	该问题已有最佳答案
                </div>
            </div>';
            }else if($setting['reply_self']==0&&$question['uid']==$user['id'])
            	{
                	echo '<div class="reply_textarea" align="center">
                            <div id="unreply">
                                不能对自己的'.$sign.'回复
                            </div>
                        </div>';
                }
            else{
            	echo '
            <form>
                <textarea class="reply_textarea" id="reply_area"></textarea>
                <div id="reply_extra">
                <div id="reply_extra_con">回复：</div>
                <div id="extra_close"></div>
                </div>
                <div id="reply_upload"></div>
                <div class="reply_btn" id="reply">回复</div>
                <div id="upload_preview">
                	<div class="q_option_left"></div>
                    <div class="q_option_mid">
                        <a id="preview_con" rel="prettyPhoto" href=""></a>
                        <img id="del_upload" src="__PUBLIC__/css/topic_close.gif" />
                    </div><div class="q_option_right"></div>
                </div>    
                <div class="clear"></div>
            </form>
            ';}
            }else{
            echo '
            <div class="reply_textarea" align="center">
            	<div id="unreply">
                	回复请先<a href="'.U('Account/login').'">登录</a>或<a href="'.U('Account/register').'">注册</a>
                </div>
            </div>';
            }?>
        </div>
    </div>
    <div id="main_right">
    	<div id="focus" class="<?php echo ($focus_class); ?>"></div>
        <div class="search" id="invite_search">
            <input type="text" id="invite_input" class="focus" title="邀请网友解答" value="邀请网友解答" />
        </div>
        <div class="clear"></div>
        <div class="side_wrapper">
        	<div class="side_title">热门推荐</div>
        	<?php echo S('side_recommend_question'); ?>
        </div>
        <div class="side_wrapper">
        	<div class="side_title">关注该<?php echo $sign; ?>用户(<?php echo count($focus_uid); ?>)</div>
        	<?php if($focus_uid==0)
            	{
                	echo '<div id="no_focus_user">暂无用户关注该'.$sign.'</div>';
                }else{
                		foreach($focus_uid as $k=>$v)
                            {
                                echo '<div class="avatar_wrapper avatar_left">
                                     <img src="__PUBLIC__'.get_avatar($v['uid'],'min').'" class="avatar" />
                                     <img src="__PUBLIC__/css/avatar_cover.png" class="avatar_cover" />
                                     <input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" /> 
                                </div>';
                            }
                } ?>   
            <div class="clear"></div>
        </div>
        <div class="side_wrapper">
        	<div class="side_title"><?php echo ($related_title); ?></div>
        	<?php echo ($related_question); ?>
        </div>
        <div class="side_wrapper">
        	<div class="side_title">分享到</div>
        	<!-- Baidu Button BEGIN -->
            <div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">
            <a class="bds_tsina"></a>
            <a class="bds_qzone"></a>
            <a class="bds_tqq"></a>
            <a class="bds_renren"></a>
            <a class="bds_kaixin001"></a>
            </div>
            <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=66969" ></script>
            <script type="text/javascript" id="bdshell_js"></script>
            <script type="text/javascript">
            document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
            </script>
            <!-- Baidu Button END -->
        </div>
    </div>
</div>      
<div id="loading_small"></div>
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