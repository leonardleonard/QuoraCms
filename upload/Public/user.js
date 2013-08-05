$(document).ready(function(){
	$('#job option').each(function(){
    if($(this).val()==career){$(this).attr('selected',true)};
});
		$('#letter_reply').click(function(){
			var th=$(this);
				if($('#letterview_textarea').val()!=null&&$('#letterview_textarea').val()!='回复TA:')
					{
						th.html('提交中');
						th.css({backgroundPosition:'0px -100px'});
						$('#loading_small').css({left:th.offset().left+82,top:th.offset().top+8,display:'block'});
						th.load(u('letter_reply'),{content:$('#letterview_textarea').val(),from:$('#from').val(),letterid:$('#letterid').val(),ha:$('#ha').val()},function(){
							$("#loading_small").hide();
							th.css({backgroundPosition:'0px 0px'});
							$('#main_letter').append('<div class="letter_wrapper"><div class="letter_view">'+$('#letterview_textarea').val()+'</div><div class="avatar_wrapper"><img src="'+$('#avatar_input').val()+'" class="avatar" /><img src="'+public+'/css/avatar_cover.png" class="avatar_cover" /><input type="hidden" value="'+$('#to').val()+'" class="avatar_hidden" /></div><div class="clear"></div><div class="letter_opt"><div class="letter_opt_time">发送时间:1秒前</div><div class="clear"></div></div></div>');
							})
					}
			})
		$('.q_wrapper:eq(0)').css({borderTop:0});	
		$('#user_nav a').click(function(){
			if($(this).attr('class')=='nav_wrapper')
				{
					$(this).toggleClass('nn');
					$(this).css({borderBottom:'3px solid #F2F1EC'})
					var c=$(this).children('div')
					var p=c.css('backgroundPosition');
					po=p.split(' ');
					c.css({backgroundPosition:'-32px '+po[1]});
				}
			})	
		$('.user_input').focus(function(){
				$(this).css({backgroundPosition:'-1px -692px'})
			})	
		$('.user_input').blur(function(){
				$(this).css({backgroundPosition:'-1px -657px'})
			})	
		$('.profile_wrapper a').click(function(){
			var t=$(this);
				t.removeClass('radio').addClass('radio_checked');
				t.siblings('a').removeClass('radio_checked').addClass('radio');
				t.siblings('.profile_val').val($(this).attr('name'));
			})
		$('#save_user').click(function(){
				$.post(u('profile_save'),{gender:$('#gender').val(),province:$('#province').val(),city:$('#city').val(),county:$('#hometown').val(),career:$('#job').val(),tag:$('#tag_input').val(),university:$('#school-name').val(),college:$('#college').val(),lovestate:$('#lovestate').val(),qqnumber:$('#qq').val(),phone:$('#phone').val(),ha:$('#ha').val()},function(data){
						tip(data);
					})
			})			
	})