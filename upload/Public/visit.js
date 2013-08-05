$(document).ready(function() {
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
	$('#letter_to_him').click(function(){
				if(uid=='')
					{
						tip('登录后才能发送')	
					}else{
							$('#cover,#letter_div').show();
							$('#letter_input').val($('#his_username').html());
						 }
			})
	$('#visit_main').html()==''&&$('#visit_main').html('<div id="question_error">内容为空</div>');							
});