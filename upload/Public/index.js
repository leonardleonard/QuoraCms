$(document).ready(function(){
		$('.ql').click(function(){
			$(this).removeClass('button_round').addClass('button_press');
			$(this).siblings('li').removeClass('button_press').addClass('button_round');
			$('#loading_max').show();
			$('#question_list').html('');
			var title=$(this).attr('title');
				$.get(u(title),{cid:cid,ha:$('#ha').val()},function(response){
						$('#question_list').html(response);
						$('#loading_max').fadeOut(400);
					})
			})
		var i=0;	
		$('#time_order').click(function(){
			$(this).removeClass('button_round').addClass('button_press');
			$(this).siblings('li').removeClass('button_press').addClass('button_round');
			$('#loading_max').show();
			$('#question_list').html('');
			i++;
			var y=i%2;
			if(y==0)
			{
				$(this).html('时间 ▼');	
			}else{
					$(this).html('时间 ▲');
				 }
						$.get(u('time_question'),{cid:cid,y:y,ha:$('#ha').val()},function(dat){
						$('#question_list').html(dat);
						$('#loading_max').fadeOut(400)
					})
			})	
		$('#page a').live('click',function(e){
				e.preventDefault();
				$.get($(this).attr('href'),{ha:$('#ha').val()},function(data){
						$('#question_list').html(data);
						$('body,html').animate({scrollTop:0},500);
					})
			})
		$('div.c_wrapper').hover(function(){
				tc=$(this).children('.c_wrapper_inner');
				tc.children('.c_detail').stop(true,true).animate({marginLeft:110},300)
				tc.children('.c_arrow').stop(true,true).css({width:159}).animate({marginLeft:198},400);
			},function(){
				tc=$(this).children('.c_wrapper_inner');
				tc.children('.c_arrow').stop(true,true).css({width:159}).animate({marginLeft:0},300).css({width:110});
				tc.children('.c_detail').stop(true,true).animate({marginLeft:0},400);
				$(this).children('.c_specific').slideUp(300);	
				});		
		$('div.c_detail').click(function(){
				//var h=$(this).parent('.c_wrapper_inner').next('.c_specific').height();
				$(this).parent('.c_wrapper_inner').next('.c_specific').slideToggle();
				//$(this).parent('.c_wrapper_inner').parent('.c_wrapper').next('.c_wrapper_right').next('.c_wrapper').animate({marginLeft:357})
			})		
	})