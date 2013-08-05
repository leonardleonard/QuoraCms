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
define("INC", true);
class UserAction extends BaseAction {
    function index() {
        $this->tolog();
        $this->assign('title', '我的资料');
        $follow_me = M('follow')->where(array(
            'hisid' => $this->uid
        ))->field('myid')->select();
        $this->assign('follow', $follow_me);
        $this->display();
    }
    private function tolog() {
        $this->uid == NULL && $this->redirect('Account/login');
    }
    function avatar() {
        $this->tolog();
        $this->assign('title', '头像设置');
        $this->display();
    }
    function profile_save() {
        $this->tolog();
        array_pop($this->post);
        M('user')->where(array(
            'id' => $this->uid
        ))->save($this->post);
        echo '保存个人资料成功';
    }
    function avatar_hover()
		{
			if($this->post['uid'])
				{
					$u=M('user')->where(array('id'=>$this->post['uid']))->select();
					echo '
						<div class="window_name"><a href="'.U('Visit/index?uid='.$this->post['uid']).'" target="_blank" title="'.$u[0]['name'].'">'.getsubstr($u[0]['name'],0,8).'</a>';
							if($this->uid!=NULL&&$this->uid!=$this->post['uid'])
								{
										echo '<div class="window_focus" id="follow">';
										if(M('follow')->where(array('hisid'=>$this->post['uid'],'myid'=>$this->uid))->count()==0)
											{
												echo '关注TA';	
											}else{
													echo '取消关注';
												 }
										echo '</div></div>';
								}
						echo'
						<div class="window_address" title="'.$u[0]['province'].$u[0]['city'].$u[0]['county'].'">';if($u[0]['province']!=''){echo '来自'.getsubstr($u[0]['province'].$u[0]['city'].$u[0]['county'],0,9);}else{echo '暂无TA的位置信息';}echo '</div>
						<div class="window_score">当前积分:'.$u[0]['score'].'</div>
						<input type="hidden" value="'.$this->post['uid'].'" class="hisid" />
						<input type="hidden" value="'.U('Visit/index?uid='.$this->post['uid']).'" id="avatar_href" />
						';
						 
										
				}	
		}
    function send_letter() {
        $this->tolog();
        if (empty($this->post['toname']) || empty($this->post['content'])) {
            exit('参数错误');
        }
        $time = time();
        $user = M('user');
        $to = $user->where(array(
            'name' => $this->post['toname']
        ))->getField('id');
        if ($to == $this->uid) {
            exit('失败：不能给自己发送站内信');
        }
        if ($to == NULL) {
            exit('失败：暂无该用户');
        }
        $letterid = M('letter')->add(array(
            'to' => $to,
            'content' => $this->post['content'],
            'addtime' => $time,
            'from' => $this->uid
        ));
        if (M('letterview')->add(array(
            'to' => $to,
            'content' => $this->post['content'],
            'addtime' => $time,
            'from' => $this->uid,
            'letterid' => $letterid
        ))) {
            $user->where(array(
                'id' => $to
            ))->setInc('newmsg');
            M('newmsg')->add(array(
                'name' => $this->username,
                'letterid' => $letterid,
                'uid' => $to
            ));
            echo '发送成功';
        }
    }
    function letter() {
        $this->tolog();
        $Letter = M('letter');
        $letter = $Letter->page($_GET['p'] . ',' . $this->setting['reply_per_page'])->where(array(
            'from' => $this->uid,
            'to' => $this->uid,
            '_logic' => 'or'
        ))->select();
        $count = $Letter->where(array(
            'from' => $this->uid,
            'to' => $this->uid,
            '_logic' => 'or'
        ))->count();
        $Page = new Page($count, $this->setting['reply_per_page']);
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('letter', $letter);
        $this->assign('title', '我的站内信');
        $this->display();
    }
    function letterview() {
        $this->tolog();
        $l = M('letter')->where(array(
            'id' => $this->get['lid']
        ))->select();
        if ($l[0]['from'] == $this->uid || $l[0]['to'] == $this->uid) {
            $let = M('letterview')->where(array(
                'letterid' => $this->get['lid']
            ))->select();
            $this->assign('letterview', $let);
            $this->assign('title', $let[0]['content']);
            $this->display();
        } else {
            $this->redirect('Index/index');
        }
    }
    function letter_reply() {
        $this->tolog();
        ($this->post['content'] == '' || $this->post['from'] == '' || $this->post['letterid'] == '') && exit('access denied');
        if (M('letterview')->add(array(
            'content' => $this->post['content'],
            'from' => $this->uid,
            'to' => $this->post['from'],
            'letterid' => $this->post['letterid'],
            'addtime' => time()
        ))) {
            M('letter')->where(array(
                'id' => $this->post['letterid']
            ))->setInc('lettercount');
            M('user')->where(array(
                'id' => $this->post['from']
            ))->setInc('newmsg');
            M('newmsg')->add(array(
                'name' => $this->username,
                'letterid' => $this->post['letterid'],
                'uid' => $this->post['from']
            ));
            echo '回复';
        }
    }
    function myask() {
        $this->tolog();
        $q = M('question');
        $question_list = $q->where(array(
            'uid' => $this->uid
        ))->page($_GET['p'] . ',' . $this->setting['question_per_page'])->order('id desc')->select();
        $count = $q->where(array(
            'uid' => $this->uid
        ))->count();
        $Page = new Page($count, $this->setting['question_per_page']);
        $this->assign('page', $Page->show());
        $this->assign('question_list', $question_list);
        $this->assign('title', '我发表的' . $this->sign);
        $this->assign('no_list', '您暂未发表' . $this->sign);
        $this->display('User/list');
    }
    private function mylist($my, $no_list) {
        $arr = array();
        foreach ($my as $k => $v) {
            !in_array($v['qid'], $arr) && array_push($arr, $v['qid']);
        }
        $p = $this->get['p'] ? $this->get['p'] : 1;
        $page_arr = array_slice($arr, ($p - 1) * 10, 10);
        $count = count($arr);
        $Page = new Page($count, 10);
        $this->assign('page', $Page->show());
        $question = M('question')->where(array(
            'id' => array(
                'in',
                $page_arr
            )
        ))->select();
        $this->assign('question_list', $question);
        $this->assign('no_list', '没有' . $no_list . '的' . $this->sign);
        $this->assign('title', '我' . $no_list . '的' . $this->sign);
        $this->display('User/list');
    }
    function myreply() {
        $this->tolog();
        $my = M('answer')->where(array(
            'uid' => $this->uid
        ))->order('addtime DESC')->field('qid')->select();
        $this->mylist($my, '回复');
    }
    function myagainst() {
        $this->tolog();
        $my = M('replyagainst')->where(array(
            'actionid' => $this->uid
        ))->field('qid')->select();
        $this->mylist($my, '反对');
    }
    function myagree() {
        $this->tolog();
        $my = M('replyagree')->where(array(
            'actionid' => $this->uid
        ))->field('qid')->select();
        $this->mylist($my, '赞成');
    }
    function myrecommend() {
        $this->tolog();
        $my = M('recommend')->where(array(
            'uid' => $this->uid
        ))->field('qid')->select();
        $this->mylist($my, '推荐');
    }
    function myfollow() {
        $this->tolog();
        $follow = D('User')->relation(true)->where(array(
            'id' => $this->uid
        ))->select();
        $follow = $follow[0]['action'];
        $p = $this->get['p'] ? $this->get['p'] : 1;
        $count = count($follow);
        $Page = new Page($count, 10);
        $this->assign('page', $Page->show());
        $follow_list = array_slice($follow, ($p - 1)* 10, 10);
        $this->assign('title', '我关注的人动态');
        $this->assign('follow', $follow_list);
        $this->display();
    }
    function myfocus() {
        $this->tolog();
        $question_list = M('focus')->where(array(
            'uid' => $this->uid
        ))->limit($this->setting['question_per_page'])->select();
        $this->assign('question_list', $question_list);
        $this->assign('title', '我关注的' . $this->sign);
        $this->assign('no_list', '暂无关注' . $this->sign);
        $this->display('User/list');
    }
    function user_search() {
        if (!strtolower($this->get['q'])) return;
        $result = M('user')->where(array(
            'name' => array(
                'like',
                '%' . strtolower($this->get['q']) . '%'
            )
        ))->select();
        foreach ($result as $k => $v) {
            echo $v['name'];
            echo "\n";
        }
    }
    function follow() {
        $this->tolog();
        $data = array(
            'hisid' => $this->post['hisid'],
            'myid' => $this->uid
        );
        $follow = M('follow');
        if ($follow->where($data)->count() == 0) {
            $follow->add($data);
            echo '取消关注';
        } else {
            $follow->where($data)->delete();
            echo '关注TA';
        }
    }
    function clear_notice() {
        $this->tolog();
        M('notice')->where(array(
            'uid' => $this->uid
        ))->delete();
		Session::set('inform',NULL);
        if (M('user')->where(array(
            'id' => $this->uid
        ))->save(array(
            'newnotice' => 0
        ))) {
            echo 1;
        } else {
            echo 0;
        }
    }
    function ranklist() {
        $u = M('user');
        $avatar_list_score = $u->order('score DESC')->limit(10)->field('id,score')->select();
        $avatar_list_question = $u->order('totalpub DESC')->limit(10)->field('id,totalpub')->select();
        $avatar_list_reply = $u->order('totalreply DESC')->limit(10)->field('id,totalreply')->select();
        $this->assign('avatar_list_score', $avatar_list_score);
        $this->assign('avatar_list_question', $avatar_list_question);
        $this->assign('avatar_list_reply', $avatar_list_reply);
		$this->assign('title','用户排行榜');
        $this->display();
    }
    function total() {
        $u = M('user');
        $user = $u->field('id')->select();
        $q = M('question');
        $a = M('answer');
        foreach ($user as $k => $v) {
            $qn = $q->where(array(
                'uid' => $v['id']
            ))->count();
            $an = $a->where(array(
                'uid' => $v['id']
            ))->count();
            $u->where(array(
                'id' => $v['id']
            ))->save(array(
                'totalpub' => $qn,
                'totalreply' => $an
            ));
        }
    }
}
?>
