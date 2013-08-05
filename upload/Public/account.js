$(document).ready(function(){
		var ini_auto=0;
		$('div.auto_login').click(function(){
				ini_auto++;
				if(ini_auto%2==1)
					{
						$(this).css({backgroundPosition:'0px -633px'});
						$('#auto_val').val(0);	
					}else{
							$(this).css({backgroundPosition:'0px -603px'});
							$('#auto_val').val(1);	
						 }
			});
		$('.account_input,.account_code').focus(function(){
				if($(this).attr('alt')==$(this).val())
					{
						$(this).val('');	
					}
			});
		$('.account_input,.account_code').blur(function(){
				if($(this).val()=='')
					{
						$(this).val($(this).attr('alt'));	
					}
			});
		$('.account_pwd').focus(function(){
				if($(this).val()=='')
					{
						$('#pwd_a').hide();	
					}
			})		
		$('.account_pwd').blur(function(){
				if($(this).val()=='')
					{
						$('#pwd_a').show();	
					}
			})		
		$('#pwd_a').click(function(){
				$(this).hide();
				$('.account_pwd').focus();
			})	
		$('#login_submit').click(function(){
				var username=$('#username').val();
				var pwd=$('#pwd').val();
				var token=$("input[name='__hash__']").val();
				if(username==$('#username').attr('alt'))
					{
						$('#tip_mid').html('请填写用户名啦！');
						$('#tip').css({left:$('#username').offset().left+100,top:$('#username').offset().top-46});
						swing('login_div');	
					}else if(pwd==''){
						$('#tip_mid').html('请填写用户密码啦！');
						$('#tip').css({left:$('#pwd').offset().left+90,top:$('#pwd').offset().top-46});
						swing('login_div');	
									}else{
											$('#login_submit').html('正在校验密码...');
											$.post(u('ajax_login'),{name:username,pwd:pwd,is_auto:$('#auto_val').val(),url:refer_url,ha:$('#ha').val()},function(data){
				$('#login_submit').html(data);
												})
										 }
			})
	/*/////////////////////////////////////Login javascript End////////////////////////////////////////////////////////*/
	/*QuoraCms use sina api for ip location,including the province,city and county,if it doesn't work, you can delete the code below */
	$.getScript('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js',function(_result){   
					if (remote_ip_info.ret == '1'){
					$('#province').val(remote_ip_info.province);
					$('#city').val(remote_ip_info.city); 
					$('#county').val(remote_ip_info.district);  
					} 
				})
	/*QuoraCms use sina api for ip location,including the province,city and county,if it doesn't work, you can delete the code above*/				
		$('#register_submit').click(function(){
				var reg_name=$('#reg_name').val();
				var reg_email=$('#reg_email').val();
				var reg_pwd=$('#reg_pwd').val();
				var reg_code=register_code==1?$('#reg_code').val():null;
				var invite_val=is_invite_register==1?$('#invite_code_input').val():null;
				if(reg_name==$('#reg_name').attr('alt'))
					{
						$('#tip_mid').html('请填写用户名啦！');
						$('#tip').css({left:$('#reg_name').offset().left+100,top:$('#reg_name').offset().top-46});
						swing('register_div');	
					}else if(reg_email.match( /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/)==null){
							$('#tip_mid').html('请填写正确的邮箱啦！');
							$('#tip').css({left:$('#reg_email').offset().left+70,top:$('#reg_email').offset().top-46});
							swing('register_div');
						}else if(reg_pwd==''){
						$('#tip_mid').html('请填写用户密码啦！');
						$('#tip').css({left:$('#reg_pwd').offset().left+90,top:$('#reg_pwd').offset().top-46});
						swing('register_div');	
									}else{
											$('#register_submit').html('检验表单数据中...');
											$('#register_submit').load(u('ajax_check_name'),{name:reg_name,email:reg_email,code:reg_code,invite_val:invite_val,ha:$('#ha').val()})
										 }
			})
					
	/*/////////////////////////////////////Register javascript End////////////////////////////////////////////////////////*/	
	$('#activate_a').click(function(){
		var h=$(this).html();
			if(h!='点此激活账户'&&h!='失败：用户名被占用'&&h!='失败：邮箱被占用')
				{
					location.href=site;	
				}else{
						$(this).load(u('activate_receive'),{ha:$('#ha').val(),province:$('#province').val(),city:$('#city').val(),county:$('#county').val()});
					 }
		})	
	})
	function refresh_code()
		{
			$('#code').attr('src',u('verify')+'&time='+new Date().getTime())
		}