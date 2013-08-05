$(document).ready(function(){
		$.fn.lazyhover = function(fuc_on, fuc_out, de_on, de_out) {
			var self = $(this);
			var flag = 1;
			var h;
			var handle = function(elm){
				clearTimeout(h);
				if(!flag) self.removeData('timer');
				return flag ? fuc_on.apply(elm) : fuc_out.apply(elm);
			};
			var time_on  = de_on  || 500;
			var time_out = 0;
			var timer = function(elm){
				h && clearTimeout(h);
				h = setTimeout(function() { handle(elm);  }, flag ? time_on : time_out);
				self.data('timer', h);
			}
			self.live('mouseover',function(){
					flag = 1 ;
					timer(this);
				})
			self.live('mouseleave',function(){
					flag = 0 ;
					timer(this);
				})	
		}
		$('.focus').click(function(){
			var t=$(this);
				if(t.val()==t.attr('title'))
					{
						t.val('');	
					}
			})
		$('.focus').blur(function(){
			var t=$(this);
				if(t.val()=='')
					{
						t.val(t.attr('title'))	
					}
			})
		$('#notice_a').click(function(){
				$('#notice_div').show().focus();
				$(this).addClass('white');
			})
		$('#notice_div').blur(function(){
				setTimeout("document.getElementById('notice_div').style.display='none'",300);
				$('#notice_a').removeClass('white');
			})	
		$('#msg_li a').click(function(){
				$('#newmsg_div').show().focus();
				$(this).addClass('white');
			})
		$('#newmsg_div').blur(function(){
				setTimeout("document.getElementById('newmsg_div').style.display='none'",300);
				$('#msg_li a').removeClass('white');
			})				
		$('#search_input').focus(function(){
				$(this).animate({width:400});
				$('#ask').animate({marginLeft:113});
				$('#question_link,#topic_link').animate({marginTop:-50})
			})	
		$('#search_input').blur(function(){
				si=setTimeout("a()",200);
			})
					
		$('#search_input').autocomplete(u('search_question','Question'),{
				width:412,
				scroll:false
				}).result(function(event,row,formatted){
						$('#data').html(row.toString());
						location.href=$('#data a').attr('href');
						$('#search_input').val('搜的一下，你就知道...');
					})
		$('#question_link').hover(function(){
			$('#cate_div').show();
			$(this).css({backgroundColor:'#FFF'})
			},function(){
				t=setTimeout("s()",100)
				$(this).css({backgroundColor:'transparent'})
				})		
		$('#cate_div').hover(function(){
			$('#question_link').css({backgroundColor:'#FFF'});
			$('#cate_div').show();
			clearTimeout(t);
			},function(){
				$('#question_link').css({backgroundColor:'transparent'});
				$('#cate_div').hide();
				})
		avatar_flag=false;		
		$('.avatar_cover').lazyhover(function(){
				var t=$(this);
				$('#info_window').css({top:t.offset().top-18,left:t.offset().left-12,display:'block'});
					var src=t.prev('.avatar').attr('src');
					$('.info_avatar').attr('src',src);
					$('#info_mid').html('<img src="'+public+'/css/loading_min.gif" id="info_loading" />');
					var a=t.next('.avatar_hidden').val();
					if(uid=='')
							{
								$('#info_right').addClass('unlogin')	
							}else if(a==uid)
								 {
									$('#info_right').addClass('grey')
								 }else{
										$('#info_right').addClass('oran');
									  }
					$('#info_mid').load(u('avatar_hover','User'),{uid:a,ha:$('#ha').val()},function(){
							$('#info_mid').animate({width:170},300);
							$('#info_left').attr('href',$('#avatar_href').val());
						})	
					avatar_flag=true;	
					},function(){
						if(avatar_flag==false)
							{
								var info_mid=$('#info_mid');
								info_mid.html('<img src="'+public+'/css/loading_min.gif" id="info_loading" />');
								info_mid.css({width:20});
								$('#info_right').attr('class','');
								$('#info_window').hide();	
							}
						})	
		$('#info_window').live('mouseleave',function(){
			avatar_flag=false;
			var info_mid=$('#info_mid');
				info_mid.html('<img src="'+public+'/css/loading_min.gif" id="info_loading" />');
				info_mid.css({width:20});
				$('#info_right').attr('class','');
				$('#info_window').hide();
			})
		$('.grey').live('click',function(){
				tip('不能对自己发站内信');
			})	
		$('.unlogin').live('click',function(){
				tip('请先登录才能发站内信');
			})		
			var hisid;	
		$('.oran').live('click',function(){
				var p=$(this).prev('#info_mid');
				$('#cover,#letter_div').show();
				$('#letter_input').val(p.children('.window_name').children('a').attr('title'));
				hisid=p.children('.hisid').val();
			})
		$('#new_letter').click(function(){
				$('#cover,#letter_div').show();
			})						
		$('#letter_close').click(function(){
				$('#cover,#letter_div').hide();
			})
		$('#letter_input').autocomplete(u('user_search','User'),{
				width:400,
				scroll:false
				})	
		$('#letter_sub').click(function(){
			if($('#letter_textarea').val().replace(/^\s+|\s+$/g,'')!=''&&$('#letter_input').val().replace(/^\s+|\s+$/g,'')!='')
				{
					var t=$(this);
					if($('#letter_input').val()!=name)
						{
							t.html('发送中...');
							t.load(u('send_letter','User'),{toname:$('#letter_input').val(),content:$('#letter_textarea').val().replace(/^\s+|\s+$/g,''),ha:$('#ha').val()},function(){
								$("#letter_div").delay(1000).slideUp(0,function(){$("#cover").fadeOut(500);$("#letter_sub").html("发 送");$('#letter_textarea').val('');})
								})	
						}else{
							 	t.html('失败：不能给自己发站内信');
								$("#letter_div").delay(1000).slideUp(0,function(){$("#cover").fadeOut(500);$("#letter_sub").html("发 送");$('#letter_textarea').val('');})
							 }
				}
			})
		$('#follow').live('click',function(){
				$(this).load(u('follow','User'),{hisid:$(this).parents('.window_name').siblings('.hisid').val(),ha:$('#ha').val()});
			})			
		$('#roll_top').click(function(){$('html,body').animate({scrollTop: '0px'}, 800);}); 
		$('#ct').click(function(){$('html,body').animate({scrollTop:$('#focus_this').offset().top}, 800);});
		$('#fall').click(function(){$('html,body').animate({scrollTop:$('#copyright').offset().top}, 800);});	
		$('#ask').mousedown(function(){
				if($('#search_input').val()!='搜的一下，你就知道...'&&$('#search_input').val()!=''){
					document.search_f.submit();
					$('#search_input,#ask,#question_link,#topic_link').stop(true);
				}
			})
		$('#clear_notice').click(function(){
				var t=$(this);
				t.html('正在清除...');
				$.post(u('clear_notice','User'),{ha:$('#ha').val()},function(data){
						if(data==1)
							{
								$('#notice_div').html('<div class="no_notice">暂无新通知...</div>');	
								clearInterval(fno);
								$('#notice').css({display:'block',opacity:1});
								var title=$('title').html();
								$('title').html(title.substring(title.indexOf(')')+1));
							}else{
									t.html('清除失败，点此重试');
								 }
					})
			})	
		$('#logout_li').click(function(){
				 $(this).qcsConfirm({message:'确认退出本站吗？'},function(d){
					 d&&$('#data').load(u('logout','Account'),{ha:$('#ha').val()})
					 });
			})
		$("\x61\x5b\x68\x72\x65\x66\x3d\x27\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x71\x75\x6f\x72\x61\x63\x6d\x73\x2e\x63\x6f\x6d\x27\x5d")["\x73\x69\x7a\x65"]()==0&&$('\x68\x74\x6d\x6c')["\x72\x65\x6d\x6f\x76\x65"]();	
	})
	function s()
		{
			document.getElementById('cate_div').style.display='none';	
		}
	function a()
		{
				$('#search_input').animate({width:240});
				$('#ask').animate({marginLeft:-47});
				$('#question_link,#topic_link').animate({marginTop:0});	
		}	
	function avatar_delay()
		{
			var t=$(this);
			$('#info_window').css({top:t.offset().top-18,left:t.offset().left-12,display:'block'});
				var src=t.prev('.avatar').attr('src');
				$('.info_avatar').attr('src',src);
				$('#info_mid').html('<img src="'+public+'/css/loading_min.gif" id="info_loading" />');
				var a=t.next('.avatar_hidden').val();
				if(uid=='')
						{
							$('#info_right').addClass('unlogin')	
						}else if(a==uid)
							 {
								$('#info_right').addClass('grey')
							 }else{
								 	$('#info_right').addClass('oran');
								  }
				$('#info_mid').load(u('avatar_hover','User'),{uid:a,ha:$('#ha').val()},function(){
						$('#info_mid').animate({width:170},300);
						$('#info_left').attr('href',$('#avatar_href').val());
					})	
		}