<?php
// +----------------------------------------------------------------------+
// | QuoraCms          			                                          |
// +----------------------------------------------------------------------+
// | Copyright © 2012-2013 QuoraCms WorkGroup All Rights Reserved.        |
// +----------------------------------------------------------------------+
// | 注意：当前您使用的是未授权版本，仅可用于“个人非盈利的网站”并须保留网页底部的       |
// | 的“powered by quoracms”字样，其他网站需购买 QuoraCms使用授权, 例如：政府单   |
// | 位、教育机构、协会团体、企业、以赢利为目的的站点等，购买商业授权请联系官方QQ       |
// | Caution: Currently you are using an unlicensed version, it's free    |
// | for the "personal website" but must retain bottom of the page        |
// | "powered by QuoraCms" words, other sites need to purchase QuoraCms   |
// | license, for example: government agencies, educational institutions, |
// | associations organizations, enterprises and for-profit sites, etc.,  |
// |to buy commercial license, please contact the official QQ.			  |
// +----------------------------------------------------------------------+
// | Author:<QQ:452605524(Quoracms Official)>                       	  |
// | Email :<452605524@qq.com >                               			  |
// | Official Website :<http://www.quoracms.com >                         |
// +----------------------------------------------------------------------+
define("INC",true);
class AccountAction extends BaseAction {
    function register() {
        $this->toclose();
        $this->uid != NULL && $this->redirect('Index/index');
        $this->assign('title', '注册本站');
        $this->display();
    }
    function login() {
        $this->uid != NULL && $this->redirect('Index/index');
        $this->assign('title', '登录本站');
        $this->display();
    }
    private function toclose() {
        if ($this->setting['register_close'] == 1) {
            $this->redirect('Account/registerclose');
            exit;
        }
    }
    function registerclose() {
        ($this->uid != NULL || $this->setting['register_close'] != 1) && $this->redirect('Index/index');
        $this->assign('title', '站点注册已关闭');
        $this->display();
    }
    function ajax_check_name() //ajax check when registering,this is the first checking for security
    {
        $this->uid != NULL && exit('Acess Denied!');
        $this->toclose();
        $register = M('user');
        if ($register->where(array(
            'name' => $this->post['name']
        ))->count() != 0) {
            exit('重新注册<script>$("#tip_mid").html("该用户名已被使用！");
						$("#tip").css({left:$("#reg_name").offset().left+100,top:$("#reg_name").offset().top-46});
						swing("register_div");</script>	');
        } else if ($register->where(array(
            'email' => $this->post['email']
        ))->count() != 0) {
            exit('重新注册<script>$("#tip_mid").html("该邮箱已被使用！");
						$("#tip").css({left:$("#reg_email").offset().left+100,top:$("#reg_email").offset().top-46});
						swing("register_div");</script>');
        } else if ($this->setting['is_invite_register'] == 1 && ($register->where(array(
            'invitecode' => $this->post['invite_val']
        ))->count() == 0 || $register->where(array(
            'invitecode' => $this->post['invite_val']
        ))->getField('invitecount') < 1)) {
            exit('重新注册<script>$("#tip_mid").html("该邀请码已失效！");
						$("#tip").css({left:$("#invite_code_input").offset().left+100,top:$("#invite_code_input").offset().top-46});
						swing("register_div");</script>');
        } else if ($this->setting['register_code'] == 1 && md5($this->post['code']) != $_SESSION['verify']) {
            exit('重新注册<script>$("#tip_mid").html("验证码输入错误！");
						$("#tip").css({left:$("#reg_code").offset().left+100,top:$("#reg_code").offset().top-46});
						swing("register_div");</script>');
        } else {
            if ($this->setting['ucenter_on'] == 1) {
                if (uc_user_checkemail($this->post['email'] != 1)) {
                    exit('重新注册<script>$("#tip_mid").html("UC：该邮箱不能注册！");
						$("#tip").css({left:$("#reg_email").offset().left+100,top:$("#reg_email").offset().top-46});
						swing("register_div");</script>');
                } else if (uc_user_checkname($this->post['name'] != 1)) {
                    exit('重新注册<script>$("#tip_mid").html("UC:该用户名不能注册！");
						$("#tip").css({left:$("#reg_email").offset().left+100,top:$("#reg_email").offset().top-46});
						swing("register_div");</script>');
                }
            }
            echo '正在注册...<script>document.register_form.submit();</script>';
        }
    }
    private function check_register() //check when registering,this is the second checking for security
    {
        $this->uid != NULL && exit('Acess Denied!');
        $this->toclose();
        $register = M('user');
        $this->setting['ucenter_on'] == 1 && (uc_get_user($this->post['name']) != 0 && exit('Access Denied(UC)'));
        (trim($this->post['name']) == '' || $register->where(array(
            'name' => $this->post['name']
        ))->count() != 0 ||$this->post['email']==''||$this->post['pwd']==''|| $register->where(array(
            'email' => $this->post['email']
        ))->count() != 0 || ($this->setting['register_code'] == 1 && md5($this->post['code']) != $_SESSION['verify']) || ($this->setting['is_invite_register'] == 1 && ($register->where(array(
            'invitecode' => $this->post['invite_code']
        ))->count() == 0 || $register->where(array(
            'invitecode' => $this->post['invite_code']
        ))->getField('invitecount') < 1))) && exit('Access Denied(account checking error)');
    }
    private function send_email($to, $username, $subject, $message) {
        if ($this->setting['mail_mode'] == 1) {
            $m = mail($to, $subject, $message) ? true : false;
            return $m;
        } else if ($this->setting['mail_mode'] == 2) {
			import("ORG.Util.Phpmailer");
            $mail = new PHPMailer();
            $mail->IsSMTP(); 
            $mail->Host = $this->setting['mail_host']; 
            $mail->SMTPAuth = true; 
            $mail->Username = $this->setting['mail_addr']; 
            $mail->Password = $this->setting['mail_pwd'];
            $mail->Port = $this->setting['mail_port'];
            $mail->From = $this->setting['mail_addr'];
            $mail->FromName = $this->setting['site_name'];
            $mail->AddAddress("$to", "$username"); 
            $mail->Subject = $subject;
            $mail->Body = $message; 
            $mail->AltBody = ""; 
            $m = !$mail->Send() ? false : true;
            return $m;
        }
    }
    function mail_verify() {
        $this->uid != NULL && exit('Acess Denied!');
        $this->toclose();
        $this->check_register();
        $this->setting['ismailverify'] != 1 && exit;
        Session::set('profile', $this->post);
        !Session::get('verifycode') && Session::set('verifycode', uniqid() . md5(rand(0, 99999)));
        $c = C('tmpl_parse_string');
        $verify_url = $c['__SITE__'] . '/index.php?m=Account&a=verify_receive&verifycode=' . Session::get('verifycode');
        $u = explode('@', $this->post['email']);
        $mail_url = 'http://mail.' . $u[1];
        $this->assign('title', '注册本站');
        $msg = $this->send_email($this->post['email'], $this->post['name'], '您的验证网址', $this->post['name'] . '您好，您在“' . $this->setting['site_name'] . '”注册时的验证网址为：' . $verify_url . ',请将该网址复制到您当前的浏览器地址栏内并打开完成注册！') ? '<div class="account_header">验证网址发送成功！</div><div id="mail_verify">您注册本站的验证网址已成功发送到您的邮箱' . $this->post['email'] . '.您可以点击下方按钮进入邮箱验证</div><a id="verify_a" href="' . $mail_url . '" target="_blank">点此进入邮箱</a>' : '<div class="account_header">验证网址发送失败！</div><div id="mail_verify">您注册本站的验证网址没能成功发送到您的邮箱' . $this->post['email'] . '.您可以点击下方按钮重新进入注册页面</div><a id="verify_a" href="' . U('account/register') . '">点此重新注册</a>';
        $this->assign('msg', $msg);
        $this->display();
    }
    function verify_receive() {
        $this->uid != NULL && exit('Acess Denied!');
        $this->toclose();
        if ($this->setting['ismailverify'] == 1 && $this->get['verifycode'] == Session::get('verifycode') && Session::get('verifycode') != NULL) {
            $profile = Session::get('profile');
            $profile['pwd'] = pwd_encode($profile['pwd']);
            $profile['regtime'] = time();
            $profile['invitecode'] = uniqid() . rand(1000, 9999);
            $profile['invitecount'] = $this->setting['invite_count_available'];
            $register = M('user');
            $cookie_reg = $register->add($profile);
            $this->setting['is_invite_register'] == 1 && $register->where(array(
                'invitecode' => $profile['invite_val']
            ))->setDec('invitecount');
            $auth_id = strcode($cookie_reg . "\t" . md5($this->setting['auth_key']) , $this->setting['auth_key'], 'ENCODE');
            setcookie('qcs_auth', $auth_id, null, '/');
            Session::set('profile', NULL);
            Session::get('verifycode', NULL);
            if ($this->setting['ucenter_on'] == 1) {
                uc_user_register($profile['name'], $profile['password'], $profile['email']);
            }
            $this->redirect('Index/index');
        } else {
            Session::set('verifycode', NULL);
            echo '<meta http-equiv="refresh" content="5; url=' . U('Account/register') . '" />验证码错误，3秒后重新转向注册页面';
        }
    }
    function register_submit() {
        $this->uid != NULL && exit('Access Denied!');
        $this->toclose();
        $this->check_register();
        $this->setting['ismailverify'] == 1 && exit;
        $reg['name'] = $this->post['name'];
        $reg['email'] = $this->post['email'];
        $reg['pwd'] = pwd_encode($this->post['pwd']);
        $reg['province'] = $this->post['province'];
        $reg['city'] = $this->post['city'];
        $reg['county'] = $this->post['county'];
        $reg['regtime'] = time();
        $reg['invitecode'] = uniqid() . rand(1000, 9999);
        $reg['invitecount'] = $this->setting['invite_count_available'];
        $register = M('user');
        if ($cookie_reg = $register->add($reg)) {
            $auth_id = strcode($cookie_reg . "\t" . md5($this->setting['auth_key']) , $this->setting['auth_key'], 'ENCODE');
            setcookie('qcs_auth', $auth_id, null, '/');
            $this->setting['is_invite_register'] == 1 && $register->where(array(
                'invitecode' => $this->post['invite_code']
            ))->setDec('invitecount');
            if ($this->setting['ucenter_on'] == 1) {
                uc_user_register($this->post['name'], $this->post['password'], $this->post['email']);
            }
            $this->redirect('Index/index');
        }
    }
    function ajax_login() {
        $this->uid != NULL && exit('Access Denied!');
        $user = M('user');
		(trim($this->post['name'])==''||$this->post['pwd']=='')&&exit;
        $u = $user->where(array(
            'name' => $this->post['name'],
            'pwd' => pwd_encode($this->post['pwd'])
        ))->select();
        if ($this->setting['ucenter_on'] == 1) {
            $info = uc_user_login($this->post['name'], $this->post['pwd']);
            list($uid, $username, $password, $email) = $info;
            if ($username && $uid > 0) {
                if ($u[0]['name'] == $username && $u[0]['email'] == $email && $u[0]['pwd'] = pwd_encode($password)) {
                    $auth_id = strcode($u[0]['id'] . "\t" . md5($this->setting['auth_key']) , $this->setting['auth_key'], 'ENCODE');
                    if ($this->post['is_auto'] == 1) {
                        setcookie('qcs_auth', $auth_id, time() + 365 * 24 * 3600, '/');
                    } else {
                        setcookie('qcs_auth', $auth_id, NULL, '/');
                    }
                    $ucsynlogin = uc_user_synlogin($uid);
                    exit('正在跳转...<script type="text/javascript">location.href="' . $this->post['url'] . '";</script>' . $ucsynlogin);
                } else {
                    $info['auto'] = $this->post['is_auto'];
                    Session::set('activate_info', serialize($info));
                    exit('正转向激活页面...<script type="text/javascript">location.href="' . U('Account/activate') . '";</script>');
                }
            }
        }
        if ($u) {
            $auth_id = strcode($u[0]['id'] . "\t" . md5($this->setting['auth_key']) , $this->setting['auth_key'], 'ENCODE');
            if ($this->post['is_auto'] == 1) {
                setcookie('qcs_auth', $auth_id, time() + 365 * 24 * 3600, '/');
            } else if ($this->post['is_auto'] == 0) {
                setcookie('qcs_auth', $auth_id, NULL, '/');
            }
            echo '登录成功<script>location.href="' . $_POST['url'] . '";</script>';
        } else {
            echo '重新登录<script>$("#tip_mid").html("用户名和密码不匹配！");
								$("#tip").css({left:$("#pwd").offset().left+70,top:$("#pwd").offset().top-46});
			swing("login_div");</script>';
        }
    }
    function activate() {
		$this->uid != NULL && exit('Access Denied!');
        $this->toclose();
        (Session::get('activate_info') == NULL || $this->setting['ucenter_on'] != 1 || $this->uid != NULL) && exit('Access Denied!(activate)');
        $this->assign('title', '激活用户');
        $this->assign('info', unserialize(Session::get('activate_info')));
        $this->display();
    }
    function activate_receive() {
		$this->uid != NULL && exit('Access Denied!');
        $this->toclose();
        $info = unserialize(Session::get('activate_info'));
        ($this->setting['ucenter_on'] != 1 || $info == NULL || $info[0] < 0 || !$this->isAjax() || $this->uid != NULL) && exit('Access Denied!');
        $user = M('user');
        $user->where(array(
            'name' => $info[1]
        ))->count() != 0 && exit('失败：用户名被占用');
        $user->where(array(
            'email' => $info[3]
        ))->count() != 0 && exit('失败：邮箱被占用');
        if ($id = $user->add(array(
            'name' => $info[1],
            'pwd' => pwd_encode($info[2]) ,
            'email' => $info[3],
            'province' => $this->post['province'],
            'city' => $this->post['city'],
            'county' => $this->post['county']
        ))) {
            $auth_id = strcode($id . "\t" . md5($this->setting['auth_key']) , $this->setting['auth_key'], 'ENCODE');
            if ($info['auto'] == 1) {
                setcookie('qcs_auth', $auth_id, time() + 365 * 24 * 3600, '/');
            } else if ($this->post['is_auto'] == 0) {
                setcookie('qcs_auth', $auth_id, NULL, '/');
            }
            Session::set('activate_info', NULL);
            echo '激活成功,点此进入首页' . uc_user_synlogin($info[0]);
        }
    }
    function logout() {
        setcookie('qcs_auth', NULL, time() - 1, '/');
		Session::set('uid',NULL);
        if ($this->setting['ucenter_on'] == 1) {
            echo uc_user_synlogout();
        }
        echo '<script>$("#alert_mid").html("正在注销...");$("#top_tip").animate({top:0},600,function(){location.reload();})</script>';
    }
    function verify() {
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }
}
?>
