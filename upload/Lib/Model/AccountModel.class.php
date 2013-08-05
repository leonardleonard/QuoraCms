<?php
class AccountModel extends RelationModel{
		public function send_email($from,$to,$subject,$message)
			{
				if($this->setting['mail_mode']==1)
					{
						$m=mail($to,$subject,$message)?true:false;	
						return $m;
					}else if( $this->setting['mail_mode']==2)
						{
							require('class.phpmailer.php'); //下载的文件必须放在该文件所在目录
							$mail = new PHPMailer(); //建立邮件发送类
							$mail->IsSMTP(); // 使用SMTP方式发送
							$mail->Host = "smtp.126.com"; // 您的企业邮局域名
							$mail->SMTPAuth = true; // 启用SMTP验证功能
							$mail->Username = $from; // 邮局用户名(请填写完整的email地址)
							$mail->Password = "cutegirl21"; // 邮局密码
							$mail->Port=25;
							$mail->From = $from; //邮件发送者email地址
							$mail->FromName = $this->setting['site_name'];
							$mail->AddAddress("$to", "a");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名
							$mail->Subject = $subject; //邮件标题
							$mail->Body = $message; //邮件内容
							$mail->AltBody = ""; //附加信息，可以省略
							$m=!$mail->Send()?false:true;
							file_put_contents('1.txt',$mail->ErrorInfo);
							return $m;
						}	
			}
		public function info()
			{
				return 'sdfsdfsdf';	
			}	
}
?>