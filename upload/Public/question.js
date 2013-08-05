$(document).ready(function(){
		function swing(id)
			{
				$('#'+id).animate({marginLeft:"+=30"},200).animate({marginLeft:"-=60"},150).animate({marginLeft:"+=60"},100).animate({marginLeft:"-=60"},100).animate({marginLeft:"+=30"},100);
			}
		var is_SB_IE=($.browser.msie&&$.browser.version!=9.0)?true:false;
		$('#select_category').toggle(function(){
			$('#category').css({display:'block',opacity:0});
			$('#category').animate({marginLeft:215,opacity:1});
			if(category_index)
				{
					$('#category a').show();	
				}
			},function(){
				$('#category').animate({marginLeft:195,opacity:0},function(){
					$('#category').hide();
					});
				})
		$('.category_list').click(function(){
			var t=$(this);
				category_index=t.attr('name');
				t.siblings('a').hide();
				$('#category_hidden').val(category_index)
			})	
		$('div.reply_wrapper,div.reply_wrapper_right').hover(function(){
				sign!='帖子'&&$(this).find('.reply_icon_adopting').removeClass('hidden');
			},function(){
				sign!='帖子'&&$(this).find('.reply_icon_adopting').addClass('hidden');
				})			
		$('#pub_button,#edit_button').click(function(){
				var title=$('#publish_question_title').val();
				$('#blank').html(UE.getEditor('editor').getContent());
				$('#blank img').each(function(){
					var v=$(this);
						if(v.attr('src').indexOf('dialogs')==-1&&v.parent('a').attr('class')!='pic')
						{
							v.wrap('<a href="'+$(this).attr('src')+'" class="pic" rel="prettyPhoto[gallery1]"></a>');
							v.addClass('view_img');
						}
					})
				$('#detail_hidden').val($('#blank').html());	
				//$('#detail_hidden').val(UE.getEditor('editor').getContent().replace(/(<img[^>]*>)/gi,'<a class="prettyPhoto">$1</a>'));
				//editor.sync('pub_form');
				if(title=='输入'+sign+'标题...')
					{
						$(this).qcsAlert({title:'error',message:'请输入'+sign+'的标题哟~'})	
					}else if(!editor.hasContents())
						{
							$(this).qcsAlert({title:'error',message:'请输入'+sign+'的详细描述哟~'})	
						}else if(category_index==null)
							{
								$(this).qcsAlert({title:'error',message:'请选择'+sign+'的详细分类哟~'})
							}else{	
									var t=$(this);
									$('#loading_small').css({left:t.offset().left+102,top:t.offset().top+8,display:'block'});
									t.html('初始化...');
									$.post(u('receive_ini'),{title:title,ha:$('#ha').val()},function(data){
											$('#keywords').val(data);
											t.html('初始化完成');
											$('#loading_small').hide();
											$('#pub_form').submit();
										})
								 }
							
			})
		var l=$('.reply_list').size();
		var reply_up=$('#reply_upload');	
		var upload_preview=$('#upload_preview');
		var clicked=false;
		$('#reply').click(function(){
				if(clicked==true)
					{
						return false;
					}
				clicked=true;	
				var img=$('#realname').val()!=undefined?'<a href="'+public+'/upload/'+$('#realname').val()+'" target="_blank"><img src="'+public+'/upload/'+$('#realname').val()+'" width="'+imgwidth+'" height="'+imgheight+'" class="reply_img" border="0" /></a>':'';
				var src=$('#realname').val()!=undefined?$('#realname').val():'';
				var reply_content=html_encode($('#reply_area').val())+img;
				var length=reply_content.length;
				if(extra==1)
					{
						extra_quote='<div class="reply_quote">回复<img src="'+public+'/css/quot_left.png" />'+extra_title+'<img src="'+public+'/css/quot_right.png" />：</div>';
					}else{
							extra_quote='';
						 }
				if(length>reply_min_wordcount)
					{
						var t=$(this);
						$('#loading_small').css({left:t.offset().left+82,top:t.offset().top+8,display:'block'});
						t.html('提交中');
						t.css({backgroundPosition:'0px -1902px'});
						var title=$('#title_left').html().replace(/^\s+|\s+$/g,'');
						$('#loading_small').load(u('add_answer'),{content:$('#reply_area').val(),qid:qid,quid:quid,name:name,title:title,ha:$('#ha').val(),img:src,extra:extra,extra_aid:extra_aid,extra_auid:extra_auid,extra_title:extra_title,imgwidth:imgwidth,imgheight:imgheight},function(){
							clicked=false;
							$("#loading_small").hide();
							t.html("回复");
							t.css({backgroundPosition:'0px -1802px'});
							if($('#reply_zero').size()!=0)
								{
									$('#reply_zero').remove();	
								}
							if(l%2==1)
								{
									$('#r_list').append('<div class="reply_wrapper_right reply_list none"><table class="reply_tab_right"border="0"cellspacing="0"cellpadding="0"><tr><td class="td1_right"></td><td class="td2"></td><td class="td3_right"></td></tr><tr><td class="td4_right"></td><td class="td5 td_mid">'+extra_quote+reply_content+'</td><td class="td6_right"></td></tr><tr><td class="td7_right"></td><td class="td8"></td><td class="td9_right"><div class="avatar_wrapper reply_avatar_right"><img src="'+my_avatar+'" class="avatar"/><img src="'+public+'/css/avatar_cover.png"class="avatar_cover"/><input type="hidden" value="'+uid+'" class="avatar_hidden" /></div></td></tr></table><div class="clear"></div><div class="reply_opt_right"><div class="reply_icon_time">时间:1秒前</div><div class="reply_icon_agree">顶:<a>0</a></div><div class="reply_icon_against">踩:<a>0</a></div><div class="reply_icon_useless">没有帮助:<a>0</a></div><input type="hidden"value="2"/></div></div><div class="clear"></div>');	
								}else{
										$('#r_list').append('<div class="reply_wrapper reply_list none"><table class="reply_tab" border="0" cellspacing="0"cellpadding="0"><tr><td class="td1"></td><td class="td2"></td><td class="td3"></td></tr><tr><td class="td4"></td><td class="td5">'+extra_quote+reply_content+'</td><td class="td6"></td></tr><tr><td class="td7"><div class="avatar_wrapper reply_avatar"><img src="'+my_avatar+'"class="avatar"/><img src="http://127.0.0.1/quoracms/Public/css/avatar_cover.png"class="avatar_cover"/><input type="hidden" value="'+uid+'" class="avatar_hidden" /></div></td><td class="td8"></td><td class="td9"></td></tr></table><div class="clear"></div><div class="reply_opt"><div class="reply_icon_time">时间:1秒前</div><div class="reply_icon_agree">顶:<a>0</a></div><div class="reply_icon_against">踩:<a>0</a></div><div class="reply_icon_useless">没有帮助:<a>0</a></div><input type="hidden"value="1"/></div></div><div class="clear"></div>');
									 }
							var last=$('#r_list .none:last');
							!is_SB_IE?last.css({scale:0,display:'block'}).animate({scale:1}):last.slideDown();
							reply_up.css({background:'url('+public+'/css/sprites.png) no-repeat 0px -67px',display:'block'});
							upload_preview.hide();
							$('#preview_con').html('');
								l++;
								$('#reply_area').val('');	
							})
					}else{
							tip('回复不少于'+reply_min_wordcount+'个字');
							clicked=false;
						 }
			})
			$('.reply_icon_agree').live('click',function(){
					var rt=$(this);
					var a=rt.children('a');
					var agree_count=Number(a.html());
					var aid=rt.siblings('.aid_hidden').val();
					var auid=rt.siblings('.auid_hidden').val();
					var title=rt.parent('div').siblings('table').children('tbody').children('tr').children('.td5').children('p').html().replace(/^\s+|\s+$/g,'');
					a.load(u('agree'),{aid:aid,qid:qid,c:agree_count,auid:auid,name:name,title:title,ha:$('#ha').val()})
				})
			$('.reply_icon_against').live('click',function(){
					var rt=$(this);
					var a=rt.children('a');
					var against_count=Number(a.html());
					var aid=rt.siblings('.aid_hidden').val();
					var auid=rt.siblings('.auid_hidden').val();
					var title=rt.parent('div').siblings('table').children('tbody').children('tr').children('.td5').children('p').html().replace(/^\s+|\s+$/g,'');
					a.load(u('against'),{aid:aid,qid:qid,c:against_count,auid:auid,name:name,title:title,ha:$('#ha').val()})
				})
			$('.reply_icon_useless').live('click',function(){
					var rt=$(this);
					var a=rt.children('a');
					var useless_count=Number(a.html());
					var aid=rt.siblings('.aid_hidden').val();
					var auid=rt.siblings('.auid_hidden').val();
					var title=rt.parent('div').siblings('table').children('tbody').children('tr').children('.td5').children('p').html().replace(/^\s+|\s+$/g,'');
					rt.qcsConfirm({message:'确定认为该回复无帮助吗？超过'+helpless_min_count+'名网友认为该回复无帮助则自动隐藏该回复哟~'},function(data){
					data&&a.load(u('useless'),{aid:aid,qid:qid,c:useless_count,auid:auid,name:name,title:title,ha:$('#ha').val()})	
						})
				})
			$('#time_inc').mousedown(function(){
					$('.tab').css({backgroundPosition:'0px -1329px'});
				})							
			$('#agree_dec').mousedown(function(){
					$('.tab').css({backgroundPosition:'0px -1381px'});
				})
			$('#focus').click(function(){
				if(focus_ini%2==1)
				{
					$(this).css({backgroundPosition:'0px -2195px'});
					$(this).attr('title','取消关注');
					$(this).load(u('focus'),{quid:quid,qid:qid,title:$('#title_left').html().replace(/^\s+|\s+$/g,''),ha:$('#ha').val()});
				}else{
					$(this).css({backgroundPosition:'0px -2075px'});
					$(this).attr('title','关注此'+sign);
					$(this).load(u('focus'),{quid:quid,qid:qid,ha:$('#ha').val()});
					}
					focus_ini++;
				})
			$('#recommend_btn').click(function(){
					if(uid=='')
						{
							tip('请先登录');
						}else{
								$.post(u('recommend'),{qid:qid,ha:$('#ha').val()},function(data){
									if(data==1)
										{
											$('#up_num').show().animate({marginTop:-40,opacity:0},1000,function(){$('#up_num').remove();})	
										}else if(data==2)
											{
												tip("您已推荐过该"+sign);	
											}
								});
							 }
				})
			$('#user').click(function(){
				$('#ctgy').slideToggle(500)
				})
			$('#search_topic').autocomplete(u('search_topic'),{
				width:230,
				scroll:false
				})
			$('#invite_input').autocomplete(u('user_search','User'),{
				width:145,
				scroll:false
				}).result(function(event,list){
					$('#loading_small').css({left:$('#invite_input').offset().left+143,top:$('#invite_input').offset().top,display:'block'});
					if(issolve==1)
						{
							tip('该'+sign+'已有答案');	
							$('#loading_small').css({display:'none'});
						}else{
							 $.post(u('invite'),{qid:qid,ha:$('#ha').val(),myname:name,hisname:list.toString(),title:$('#title_left').html()},function(data){
							tip(data);
							$('#loading_small').css({display:'none'});
						})
							 }
					})
			$('#add_topic_btn').click(function(){
				if($('#search_topic').val().replace(/(^\s*)|(\s*$)/g,'')!='')
					{
						var con='<div class="topic_add_name"><div class="q_option_left"></div><div class="q_option_mid"><a>'+$('#search_topic').val()+'</a><input type="hidden" name="topic_name[]" value="'+$('#search_topic').val()+'" /><img class="topic_close" src="'+public+'/css/topic_close.gif" /></div><div class="q_option_right"></div></div>';
						$(con).insertBefore('#add_topic_div');
						$('#search_topic').val('');
					}
				})
			$('#q_add_btn').click(function(){
				if($('#search_topic').val().replace(/(^\s*)|(\s*$)/g,'')!='')
					{
						if($('.topic_add_name').size()<4)
							{
								var con='<div class="topic_add_name"><div class="q_option_left"></div><div class="q_option_mid"><a>'+$('#search_topic').val()+'</a><input type="hidden" name="topic_name" class="tpc_name" value="'+$('#search_topic').val()+'" /><img class="topic_close" src="'+public+'/css/topic_close.gif" /></div><div class="q_option_right"></div></div>';
								$('#q_topic_list').append(con);
								$('#search_topic').val('');	
							}else{
									tip('已超过3个话题');
								 }
					}
				})		
			$('#q_cancel_btn').click(function(){
					$('#q_topic_list').html($('#topic_data').html());
					$('#topic_div,.topic_add_name .topic_close').addClass('none');
					$('#q_topic_edit').show();
					clearInterval(tpc);
					$('.topic_add_name').animate({rotate:0},300);
				})	
			$('#q_save_btn').click(function(){
					if(uid!='')
						{
							var topic_arr=[];
							$('#q_topic_list .tpc_name').each(function(){
									topic_arr.push($(this).val());
								})
							$.post(u('add_topic'),{topic:topic_arr,qid:qid,ha:$('#ha').val()},function(data){
									$('#topic_data').html($('#q_topic_list').html());
									$('#topic_div,.topic_add_name .topic_close').addClass('none');
									$('#q_topic_edit').show();
									clearInterval(tpc);
									$('.topic_add_name').animate({rotate:0},300);
								});	
						}else{
								tip('登录后才能编辑');
							 }
				})	
			$('#q_topic_edit').live('click',function(){
					tpc=setInterval("d()",200);
					$(this).hide();
					$('#topic_div,.topic_add_name .topic_close').removeClass('none');
				})	
			$('.topic_close').live('click',function(){
				var th=$(this).parent('.q_option_mid').parent('.topic_add_name')
					th.animate({scale:0},function(){th.remove()});
				})
			$("a[rel^='prettyPhoto']").prettyPhoto({
					social_tools:'',
					opacity:0.5
				});	
			$('.reply_icon_adopting').click(function(){
				var thi=$(this);
				thi.qcsConfirm({message:'确定选择该回复作为最佳答案吗？'},function(data){
					data&&thi.load(u('set_best_answer'),{qid:qid,aid:thi.siblings('.aid_hidden').val(),auid:thi.siblings('.auid_hidden').val(),ha:$('#ha').val()},function(){$('.reply_icon_adopting').remove();})})
				})		
			var imgwidth='';	
			var imgheight='';
			 $.jUploader({
			 button: 'reply_upload',
			 action:u('reply_upload'),
			  onUpload: function (fileName) {
					reply_up.css({background:'url('+public+'/css/loading_min.gif)'})
				},
			 onComplete: function (fileName, response){Url:'' 
				tip(response.msg);
				if (response.status=='success'){
					var src=public+'/upload/'+response.realname;
					$('#preview_con').attr('href',src);
					var img = new Image();
					img.src=src;
					img.onload=function(){
					reply_up.hide();
					upload_preview.css({scale:0,display:'block'}).animate({scale:1});
					$('#preview_con').html('附件：'+response.filename+'<input type="hidden" id="realname" name="realname" value="'+response.realname+'" />');
					imgwidth=img.width>460?'460':img.width;
					imgheight=img.width>460?(Math.floor(460*img.height/img.width)):img.height;
					}
				} else {
					reply_up.css({background:'url('+public+'/css/sprites.png no-repeat 0px -67px'})	
				}
			}
			})
			extra=0;
			extra_aid='';
			extra_auid='';
			extra_title='';
			$('.reply_icon_reply').live('click',function(){
					if(uid=='')
						{
							tip('请先登录后回复');
							return false;	
						}
					extra=1;
					var rt=$(this);
					extra_aid=rt.siblings('.aid_hidden').val();
					extra_auid=rt.siblings('.auid_hidden').val();
					var title=rt.parent('div').siblings('table').children('tbody').children('tr').children('.td5').children('p').html().replace(/^\s+|\s+$/g,'');
					if(title.indexOf('<img')!=-1)
						{
							title=title.replace(/<[^>].*?>/g,"")+'[图片附件]';
						}
					var end=title.length>30?'...':'';
					extra_title=title.substr(0,30)+end;
					$('#reply_extra').show();
					$('#reply_extra_con').html('回复：'+extra_title);
					$('html,body').animate({scrollTop:$('#reply_area').offset().top},function(){
						$('#reply_area').focus();
						});
					
				})
			$('#extra_close').click(function(){
					$('#reply_extra').slideUp();
					extra=0;
				})		
			$('#del_upload').click(function(){
					upload_preview.animate({scale:0},function(){upload_preview.hide()});
					reply_up.css({background:'url('+public+'/css/sprites.png) no-repeat 0px -67px',display:'block'});
					$.post(u('reply_upload_delete'),{name:$('#realname').val(),ha:$('#ha').val()});
					$('#realname').remove();
				})
			$('.reply_icon_useless').each(function(index, element) {
                var t=$(this);
				var tp=t.parent('div');
				if(Number(t.children('a').html())>=helpless_min_count&&t.next('input').val()==0)
					{
						tp.parent('.reply_list').css({opacity:0.2});
						tp.html('该回复因“无帮助”的点击数超过了'+helpless_min_count+'次而被自动隐藏');	
					}
            });
			var i=document.URL.split('#qcs_');	
			index=i[1];
			var id=index&&'qcs_'+index;
			p==pa&&(index%2==0?swing_left(id):swing_right(id));
	})
	var q=0;
	var angle;
	function d()
		{
			q++;
			angle=q%2==0?8:-8;
			$('.topic_add_name').animate({rotate:angle},100);
		}
	function swing_left(id)
			{
				$('#'+id).animate({marginLeft:"+=30"},200).animate({marginLeft:"-=60"},150).animate({marginLeft:"+=30"},100).animate({marginLeft:"-=60"},100).animate({marginLeft:"+=30"},100);
				//$('#'+id).animate({marginLeft:"+=30"},75).animate({marginleft:"-=60"},150).animate({marginleft:"+=30"},75)
			}
	function swing_right(id)
			{
				$('#'+id).animate({marginRight:"+=30"},200).animate({marginRight:"-=60"},150).animate({marginRight:"+=60"},100).animate({marginRight:"-=60"},100).animate({marginRight:"+=30"},100);
				//$('#'+id).animate({marginRight:"+=30"},75).animate({marginRight:"-=60"},150).animate({marginRight:"+=30"},75)
			}			