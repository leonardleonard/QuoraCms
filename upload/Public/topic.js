$(document).ready(function(){
		$('#topic_list').masonry({
			  itemSelector: '.topic_wrapper'
			});		
		$('.topic_wrapper').hover(function(){
			$(this).children('.topic_mid').children('.topic_opt').children('.topic_edit').show();
			$(this).children('.topic_top').children('.admin_tip').show();
			},function(){
				$(this).children('.topic_mid').children('.topic_opt').children('.topic_edit').hide();
				$(this).children('.topic_top').children('.admin_tip').hide();
				})	
		$('.topic_edit').toggle(function(){
				$(this).html('保存编辑')
				var des=$(this).parents('.topic_opt').siblings('.topic_describe');
				var area=$(this).parents('.topic_opt').prev('.topic_edit_textarea');
				area.show();
				area.focus();
				des.hide();
				area.val(des.children('a').html());
				var p=$(this).parents('.topic_opt').parents('.topic_mid').parents('.topic_wrapper');
				p.css({zIndex:999});
				p.siblings({zIndex:1});
			},function(){
				$(this).html('编辑话题');
				var des=$(this).parents('.topic_opt').siblings('.topic_describe');
				var area=$(this).parents('.topic_opt').prev('.topic_edit_textarea');
				if(area.val().replace(/(^\s*)|(\s*$)/g,'')!=''&&area.val()!=des.children('a').html()){
					des.children('a').html(area.val());
					$('#data').load(u('topic_edit'),{topicid:$(this).next('input').val(),describe:area.val(),ha:$('#ha').val()})
				}
				area.hide();
				des.show();
				})
		$('.topic_focus').click(function(){
				var t=$(this);
				t.load(u('topic_focus'),{topicid:$(this).siblings('.topic_opt').children('input').val(),ha:$('#ha').val()},function(){
						if(me==true)
							{
								t.parent('.topic_mid').parent('.topic_wrapper').animate({scale:0},function(){
									t.parent('.topic_mid').parent('.topic_wrapper').remove();
								})	
							}
					})
			})
		$('#topic_tab1').mousedown(function(){
				$(this).parent('div').removeClass('tab_bottom');
			})	
		$('#topic_tab2').mousedown(function(){
				$(this).parent('div').addClass('tab_bottom');
			})
		$('.admin_tip').click(function(){
				var topic_id=$(this).parent('.topic_top').next('.topic_mid').children('.topic_opt').children('.topic_id').val();
				var topic_div=$(this).parent('.topic_top').parent('.topic_wrapper');
				if(confirm('此操作不可恢复，确定删除此话题吗？'))
					{
						$.post(u('topic_del'),{topic_id:topic_id,ha:$('#ha').val()},function(data){
								tip(data);
								data=='删除话题成功'&&topic_div.slideUp();
							});	
					}
			})				
	})