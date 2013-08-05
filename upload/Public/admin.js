$(document).ready(function(){
		function u(action)
			{ 
				return 'index.php?m=Admin&a='+action;	
			}		
		$('#iframe').css({width:$(window).width()-230});
		$('.input_text').focus(function(){
				if($(this).attr('title')==$(this).val())
					{
						$(this).val('');	
					}
				$(this).css({backgroundPosition:'-8px -168px'})	
			})
		$('.input_text').blur(function(){
				if($(this).val()=='')
					{
						$(this).val($(this).attr('title'));	
					}
				$(this).css({backgroundPosition:'-8px -13px'})	
			})
		var left,top	
		$('#login_submit').click(function(){
				if($('#admin_name').val()=='用户名')
					{
						top=$('#admin_name').offset().top;
						left=$('#admin_name').offset().left;
						$('#error').html('请输入管理员用户名');
						$('#error').css({left:left,top:top-40,display:'block'})
					}else if($('#admin_pwd').val()=='管理员密码')
							{
								top=$('#admin_pwd').offset().top;
								left=$('#admin_pwd').offset().left;
								$('#error').html('请输入管理员用户名');
								$('#error').css({left:left,top:top-40,display:'block'})	
							}else{
									$('#login_panel').submit();
								 }
			})
		$('.input').focus(function(){
				$(this).css({backgroundPosition:'-1px -692px'})
			})	
		$('.input').blur(function(){
				$(this).css({backgroundPosition:'-1px -657px'})
			})	
		$('.list_green').click(function(){
				var id=$(this).attr('name');
				$('#'+id+' a').removeClass('child_a_hover');
				$('#'+id).slideDown(400);
				$('#'+id).siblings('.child').slideUp(400);
			})	
		$('.child a').click(function(){
				$(this).addClass('child_a_hover');
				$(this).siblings('a').removeClass('child_a_hover');
				$('#iframe').attr('src',u($(this).attr('name')))
			})		
		$('#cfg_sub').click(function(){
				$('#cfg_form').submit();
			})		
		$('a.radio').click(function(){
				$(this).children('input').attr('checked',true);
				$(this).siblings('a').children('input').attr('checked',false);
			})
			
		
		var n=1;	
		$('.del_cate').live('click',function(){
			var tp=$(this).parents('.cate_wrapper')
				tp.fadeOut(function(){
					tp.remove()
					});
				$('#cate_arr').val($('#cate_list').sortable('toArray').toString().substring(5));
			})
		$('.add_cate').live('click',function(){
				$('#cate_list').append('<div class="cate_wrapper" id="cate_'+max_id+'"><a class="del_cate">删除</a><a class="tip">1.该分类名称:</a><input type="text" name="cate[]" class="input" value="新增分类_'+n+'" /><br /><a class="tip">该分类背景色(鼠标hover时箭头背景颜色):</a><input type="text" name="color[]" class="color" value="'+rand_color()+'" /><br /><a class="tip">上传该分类logo(110*80)：</a><input type="file" name="thumb[]" class="upload_thumb" /><div class="tip">该分类详细介绍文字：</div><textarea name="describe[]">这是该分类的详细介绍文字内容</textarea></div>');
				$('#cate_arr').val($('#cate_list').sortable('toArray').toString().substring(5));
				n++;
				max_id++;
			})
		$('#admin_table span').click(function(){
			var t=$(this);
				if(confirm('确定要删除此问题吗？'))
					{
						$.post(u('del_question'),{qid:$(this).attr('title')},function(data){
								data==1?(t.parents('td').parents('tr').slideUp()):(alert('删除失败,请稍后再试！'));
							})	
					}
			})		
		$('#clear_all_btn').click(function(){
				$('#clear_info').html('<img src="'+public+'css/loading_min.gif" />');
				var th=$(this);
				$(this).html('正在清除');
				$.post(u('del_dir'),{dir:'./Runtime/'},function(data){
						$('#clear_info').html(data);
						th.html('开始清除');
					})
			})
		$('#clear_category_btn').click(function(){
				$('#clear_info').html('<img src="'+public+'css/loading_min.gif" />');
				var th=$(this);
				$(this).html('正在清除');
				$.post(u('del_file'),{file:'./Runtime/Data/category.php'},function(data){
						$('#clear_info').html(data);
						th.html('开始清除');
					})
			})	
		$('#clear_setting_btn').click(function(){
				$('#clear_info').html('<img src="'+public+'css/loading_min.gif" />');
				var th=$(this);
				$(this).html('正在清除');
				$.post(u('del_file'),{file:'./Runtime/Data/setting.php'},function(data){
						$('#clear_info').html(data);
						th.html('开始清除');
					})
			})
		$('#mail_a').click(function(){
				$('#smtp').slideUp();
			})
		$('#smtp_a').click(function(){
				$('#smtp').slideDown();
			})			
		$('#cate_list').sortable({
						 revert:true,
						 cursor:'move',
						stop:function()
								{
									$('#cate_arr').val($('#cate_list').sortable('toArray').toString().substring(5));
								}
						 });
		$('#cate_arr').val($('#cate_list').sortable('toArray').toString().substring(5));		 
	})
	function rand_color()
		{
			var r=Math.floor(Math.random()*255).toString(16);
			var g=Math.floor(Math.random()*255).toString(16);
			var b=Math.floor(Math.random()*255).toString(16);
			r=r.length==1?"0"+r:r;	
			g=g.length==1?"0"+g:g;	
			b=b.length==1?"0"+b:b;	
			return '#'+r+g+b;
		}