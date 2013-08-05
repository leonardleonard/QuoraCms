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
!defined("INC")&&exit('Access Denied');
class BaseAction extends Action {
    public $username;
    public $setting;
    public $post;
    public $get;
    public $uid;
    public $sign;
    public function _initialize() {
        if (!F('setting')) {
            $setting = M('setting')->select();
            $set = array();
            foreach ($setting as $k => $v) {
                $set[$v['name']] = $v['value'];
            }
            F('setting', $set);
        }
        $this->assign('setting', F('setting'));
        $this->setting = F('setting');
        $setting = F('setting');
		$this->sign = $this->setting['is_quora'] == 0 ? '帖子' : '问题';
        $this->assign('sign', $this->sign);
        $this->setting['ucenter_on'] == 1 && (include CONFIG_PATH.'/uc_config.php') . (include './uc_client/client.php');
		$u=M('user');
        if (Session::get('uid') == NULL && $this->setting['ucenter_on'] == 1 && isset($_COOKIE['qcs_ucenter'])) {
            $name = explode("\t", uc_authcode($_COOKIE['qcs_ucenter'], 'DECODE'));
            $i = uc_get_user($name[1]);
            $uid = $u->where(array(
                'name' => remove_xss($i[1]) ,
                'email' => remove_xss($i[2])
            ))->getField('id');
			Session::set('uid',$uid);
        } else if (Session::get('uid') == NULL && isset($_COOKIE['qcs_auth'])) {
            $id = explode("\t", strcode($_COOKIE['qcs_auth'], $this->setting['auth_key'], 'DECODE'));
			$uid= is_numeric($id[0])?$id[0]:NULL;
			Session::set('uid',$uid);
        }
		$this->uid = Session::get('uid');
		$user_arr = $this->uid!=NULL ? $u->where(array(
                'id' => $this->uid
            ))->find() : NULL;
        /*Using function remove_xss and token to filter all of the dangerous xss POST or GET content or remote data from the browser-start*/
        if ($_POST) {
            $po = array();
            foreach ($_POST as $k => $v) {
                $po[$k] = remove_xss(htmlspecialchars($v));
            }
            $this->post = $po;
            $this->post['ha'] != Session::get('ha') && exit('Access denied! hash value'); //对所有post的值均要先验证客户端hash值，防止远程提交
        }
        if ($_GET) {
            $g = array();
            foreach ($_GET as $k => $v) {
                $g[$k] = remove_xss(htmlspecialchars($v));
            }
            $this->get = $g;
        }
        /*Using function remove_xss and token to filter all of the dangerous xss POST or GET content or remote data from the browser-end*/
        import("ORG.Util.Page");
        if ($this->get['noticeid']) {
            $notify = M('notice');
            $n = $notify->where(array(
                'id' => $this->get['noticeid']
            ))->find();
            if ($n != NULL && $n['uid'] == $this->uid) {
                $notify->where(array(
                    'id' => $this->get['noticeid']
                ))->delete();
                $u->where(array(
                    'id' => $this->uid
                ))->setDec('newnotice');
                if ($user_arr != NULL) {
                    $user_arr['newnotice'] = $user_arr['newnotice'] - 1;
                }
            }
        }
        if ($this->get['msgid'] != NULL) {
            $Newmsg = M('newmsg');
            $newmsg = $Newmsg->where(array(
                'id' => $this->get['msgid']
            ))->find();
            if ($newmsg != NULL && $newmsg['uid'] == $this->uid) {
                $Newmsg->where(array(
                    'id' => $this->get['msgid']
                ))->delete();
                $msgcount = $Newmsg->where(array(
                    'uid' => $this->uid
                ))->count();
                $u->where(array(
                    'id' => $this->uid
                ))->save(array(
                    'newmsg' => $msgcount
                ));
                if ($user_arr != NULL) {
                    $user_arr['newmsg'] = $user_arr['newmsg'] - 1;
                }
            }
        }
        $this->assign('user', $user_arr);
        $this->username = $user_arr['name'];
        if (!F('category')) {
            $category = M('category')->select();
            F('category', $category);
        }
        !Session::get('ha') && Session::set('ha', md5(rand(0, 99999) . uniqid())); //生成HASH值防止远程提交，所有post值均要传递此参数，否则首先被阻止
        $this->assign('ha', Session::get('ha'));
        if ($this->uid != NULL) {
            if ($user_arr['newnotice'] != 0) {
                if(Session::get('inform')==NULL)
					{
						$notice = M('notice')->where(array(
								'uid' => $this->uid
							))->limit(5)->select();
						$a=M('answer');
						$notice_content='';	
						foreach($notice as $k=>$v)
							{
								if ($v['aid'] == 0) {
									$notice_content.='<a href="'.U('Question/view?qid='.$v['qid'].'&noticeid='.$v['id']).'">'.$v['title'].'<div class="a_list">'.$v['content'].'</div></a>';
								} else {
									$bestanswer=$a->where(array('id'=>$v['aid']))->getField('bestanswer');
									$map['id'] = array(
										'lt',
										$v['aid']
									);
									$map['qid'] = $v['qid'];
									$ga = $a->where($map)->count();
									$pa=floor($ga/$setting['reply_per_page'])+1;
									if($bestanswer==1)
										{
											$notice_content.='<a href="'.U('Question/view?qid='.$v['qid'].'&noticeid='.$v['id'].'&noticepage=1&p=1').'#qcs_0">'.$v['title'].'<div class="a_list">'.$v['content'].'</div></a>';//when question has bestanswer	
										}else{
									$notice_content.='<a href="'.U('Question/view?qid='.$v['qid'].'&noticeid='.$v['id'].'&noticepage='.$pa.'&p='.$pa).'#qcs_'.($ga%$setting['reply_per_page']).'">'.$v['title'].'<div class="a_list">'.$v['content'].'</div></a>';		
											 }
								}			
							}
						Session::set('inform',$notice_content);			
					}
            }else if(Session::get('inform')!=NULL){
					Session::set('inform',NULL);
				 }
            if ($user_arr['newmsg'] != 0) {
                if(Session::get('message')==NULL)
					{
						$msg = M('newmsg')->where(array(
							'uid' => $this->uid
						))->limit(10)->select();
						$message='';
						foreach($msg as $k=>$v)
							{
								$message.='<a href="'.U('User/letterview?lid='.$v['letterid'].'&msgid='.$v['id']).'">'.$v['name'].'给您发了一封站内信</a>';
							}
						Session::set('message',$message);	
					}
            }else if(Session::get('message')!=NULL){
					Session::set('message',NULL);
				}
        }
        if (!S('side_no_reply')) {
            $list = M('question')->where('answercount=0')->limit($this->setting['side_list_count'])->order('id desc')->select();
            $result = NULL;
            if ($list == NULL) {
                $result = '<div class="no_focus_user">暂无未解决' . $this->sign . '</div>';
            } else {
                foreach ($list as $k => $v) {
                    $result.= '<a href="' . U('Question/view?qid=' . $v['id']) . '" class="side_list">' . $v['title'] . '</a>';
                }
            }
            S('side_no_reply', $result, $this->setting['side_list_cachetime']);
        }
        if (!S('side_hot_user')) {
            $avatar_list = M('user')->order('score DESC')->limit(9)->field('id')->select();
            S('side_hot_user', $avatar_list, $this->setting['side_list_cachetime']);
        }
        if (!S('side_recommend_question')) {
            $list = M('question')->limit($this->setting['side_list_count'])->order('recommendcount desc')->select();
            $result = NULL;
            if ($list == NULL) {
                $result = '<div class="no_focus_user">暂无热门' . $this->sign . '</div>';
            } else {
                foreach ($list as $k => $v) {
                    $result.= '<a href="' . U('question/view?qid=' . $v['id']) . '" class="side_list">' . $v['title'] . '</a>';
                }
            }
            S('side_recommend_question', $result, $this->setting['side_list_cachetime']);
        }
    }
}
?>
