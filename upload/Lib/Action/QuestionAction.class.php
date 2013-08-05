<?php
// +----------------------------------------------------------------------+
// | QuoraCms          			                                          |
// +----------------------------------------------------------------------+
// | Copyright © 2012-2013 QuoraCms WorkGroup All Rights Reserved.        |
// +----------------------------------------------------------------------+
// | 注意：当前您使用的是未授权版本，仅可用于“个人非盈利的网站”并须保留网页底部的       |
// | 的“Powered by QuoraCms”字样，其他网站需购买 QuoraCms使用授权, 例如：政府单   |
// | 位、教育机构、协会团体、企业、以赢利为目的的站点等，购买商业授权请联系官方QQ       |
// | Caution: Currently you are using an unlicensed version, it's free    |
// | for the "personal website" but must retain bottom of the page        |
// | "Powered by QuoraCms" words, other sites need to purchase QuoraCms   |
// | license, for example: government agencies, educational institutions, |
// | associations organizations, enterprises and for-profit sites, etc.,  |
// |to buy commercial license, please contact the official QQ.			  |
// +----------------------------------------------------------------------+
// | Author:<QQ:452605524(Quoracms Official)>                       	  |
// | Email :<452605524@qq.com >                               			  |
// | Official Website :<http://www.quoracms.com >                         |
// +----------------------------------------------------------------------+
define("INC", true);
class QuestionAction extends BaseAction {
    private function tologin() {
        $this->uid == NULL && exit($_POST['c'] . '<script>tip("请先登录");</script>');
    }
    function publish() {
        if ($this->uid == NULL) {
            $this->redirect('Account/login');
        } else {
            $this->assign('title', '发布' . $this->sign);
            $this->display();
        }
    }
    function edit() {
        if ($this->uid == NULL) {
            $this->redirect('Account/login');
        } else {
            if ($this->get['qid']) {
                $q = M('question')->where(array(
                    'id' => $this->get['qid']
                ))->select();
                $q == NULL && exit($this->sign . '已删除');
                if ($this->uid == 1 || $q[0]['uid'] == $this->uid) {
                    $pub_title = $q[0]['title'];
                    $pub_detail = $q[0]['detail'];
                    $pub_categoryid = $q[0]['categoryid'];
                    $this->assign('category', F('category'));
                    $this->assign('title', '编辑' . $this->sign);
                    $this->assign('pub_title', $pub_title);
                    $this->assign('pub_detail', $pub_detail);
                    $this->assign('pub_categoryid', $pub_categoryid);
                    $this->display('Question/edit');
                }
            } else {
                header('location:' . U('Index/index'));
            }
        }
    }
    function receive_ini() {
        $this->tologin();
        $kwd = get_keywords($this->post['title']);
        echo $kwd ? implode(",", $kwd) : '';
    }
    function receive() {
        $this->tologin();
        (trim($this->post['pub_title']) == '' || $this->post['category'] == '') && exit;
        $data['title'] = F('banwords') ? (strtr($this->post['pub_title'], F('banwords'))) : ($this->post['pub_title']);
        $pub_detail = (!get_magic_quotes_gpc()) ? addslashes($_POST['pub_detail']) : $_POST['pub_detail'];
        $data['detail'] = $pub_detail != $this->sign . '详细描述...' ? (F('banwords') ? (strtr(htmlspecialchars($pub_detail) , F('banwords'))) : (htmlspecialchars($pub_detail))) : '';
        $data['categoryid'] = $this->post['category'];
        $data['pubtime'] = time();
        $data['uid'] = $this->uid;
        $data['pinyin'] = getpinyin($this->post['pub_title']);
        $data['lastreply'] = $data['pubtime'];
        $data['keywords'] = htmlspecialchars($_POST['keywords']);
        $question = M('question');
        if ($qid = $question->add($data)) {
            $u = M('user');
            $u->where(array(
                'id' => $this->uid
            ))->setInc('score', '', $this->setting['publish_add_score']);
            $u->where(array(
                'id' => $this->uid
            ))->setInc('totalpub');
            M('action')->add(array(
                'username' => $this->username,
                'actionname' => '发布',
                'questiontitle' => $this->post['pub_title'],
                'qid' => $qid,
                'id' => $this->uid,
                'addtime' => $data['pubtime']
            ));
            if ($this->post['keywords'] != '') {
                $kwd = explode(',', $data['keywords']);
                $searchwords = M('searchwords');
                foreach ($kwd as $k => $v) {
                    $searchwords->add(array(
                        'qid' => $qid,
                        'keywords' => $v
                    ));
                }
            }
            if ($_POST['topic_name'] != NULL) {
                $topic = M('topic');
                $tqid = M('tqid');
                $topicfocus = M('topicfocus');
                foreach ($_POST['topic_name'] as $k => $v) {
                    $topic_name = remove_xss($v);
                    if ($topic->where(array(
                        'name' => $topic_name
                    ))->count() == 0) {
                        $tid = $topic->add(array(
                            'name' => $topic_name,
                            'addtime' => $data['pubtime'],
                            'focuscount' => 1,
                            'questioncount' => 1
                        ));
                    } else {
                        $tid = $topic->where(array(
                            'name' => $topic_name
                        ))->getField('id');
                        $topic->where(array(
                            'name' => $topic_name
                        ))->setInc('questioncount');
                        $topic->where(array(
                            'name' => $topic_name
                        ))->setInc('focuscount');
                        $topicfocus->where(array(
                            'topicid' => $tid
                        ))->setInc('newquestioncount');
                    }
                    $tqid->add(array(
                        'topicname' => $topic_name,
                        'topicid' => $tid,
                        'questionid' => $qid
                    ));
                }
            }
            header('location:' . U('question/view?qid=' . $qid));
        } else {
            echo '网络繁忙！';
        }
    }
    function editdone() {
        $this->tologin();
        $data['title'] = F('banwords') ? (strtr($this->post['pub_title'], F('banwords'))) : ($this->post['pub_title']);
        $data['detail'] = F('banwords') ? (strtr(htmlspecialchars($_POST['pub_detail']) , F('banwords'))) : (htmlspecialchars($_POST['pub_detail']));
        $data['categoryid'] = $this->post['category'];
        $data['keywords'] = htmlspecialchars($_POST['keywords']);
        $data['pinyin'] = getpinyin($this->post['pub_title']);
        $question = M('question');
        $is_me = ($this->uid == $question->where(array(
            'id' => $this->post['qid']
        ))->getField('uid') || $this->uid == 1) ? true : false;
        $is_me && $question->where(array(
            'id' => $this->post['qid']
        ))->save($data);
        if ($this->post['keywords'] != '') {
            $kwd = explode(',', $data['keywords']);
            $searchwords = M('searchwords');
            $searchwords->where(array(
                'qid' => $this->post['qid']
            ))->delete();
            foreach ($kwd as $k => $v) {
                $searchwords->add(array(
                    'keywords' => $v,
                    'qid' => $this->post['qid']
                ));
            }
        }
        header('location:' . U('Question/view?qid=' . $this->post['qid']));
    }
    function set_best_answer() {
        $question = M('question');
        $q = $question->where(array(
            'id' => $this->post['qid']
        ))->find();
        $is_me = ($q[0]['uid'] == $this->uid || $this->uid == 1) ? true : false;
        if ($is_me && $q['issolve'] == 0) {
            $data['bestanswer'] = 1;
            $dat['issolve'] = 1;
            $question->where(array(
                'id' => $this->post['qid']
            ))->save($dat);
            M('answer')->where(array(
                'id' => $this->post['aid']
            ))->save($data);
            M('user')->where(array(
                'id' => $this->post['auid']
            ))->save(array(
				'newnotice'=>array('exp',newnotice+1),
				'score'=>array('exp',score+$this->setting['adopted_add_score'])
			));
            $dat['title'] = $this->username. '采纳了您的答案:';
            $dat['content'] = getsubstr($q['title'], 0, 20);
            $dat['uid'] = $this->post['auid'];
            $dat['aid'] = $this->post['aid'];
            $dat['qid'] = $this->post['qid'];
            M('notice')->add($dat);
            echo '<script>tip("设置最佳答案成功");</script>';
        } else {
            echo '<script>tip("非法提交");</script>';
        }
    } 
    function view() {
        $q = D('Question');
        $question = $q->relation(true)->where(array(
            'id' => $this->get['qid']
        ))->find();
        $is_me = ($question['uid'] == $this->uid || $this->uid == 1) ? true : false;
        $f = M('focus');
        $focus_id = $f->where(array(
            'qid' => $this->get['qid']
        ))->field('qid,uid')->select();
        $focus_arr = array();
        foreach ($focus_id as $k => $v) {
            $focus_arr[$k] = $v['uid'];
        }
        if (in_array($this->uid, $focus_arr) && $focus_id[0]['uid'] != NULL) {
            $f->where(array(
                'qid' => $this->get['qid'],
                'uid' => $this->uid
            ))->setField('newanswer', 0);//clear newnum
            $this->assign('focus_class', 'focus_press');
            $this->assign('focus_ini', 0);
        } else {
            $this->assign('focus_class', 'focus_btn');
            $this->assign('focus_ini', 1);
        }
        if ($question != NULL) {
            $q->where(array(
                'id' => $this->get['qid']
            ))->setInc('viewcount');
            $f->where(array(
                'qid' => $this->get['qid']
            ))->setInc('viewcount');
            $focus_uid = $f->where(array(
                'qid' => $this->get['qid']
            ))->select();
            if ($focus_uid) {
                $this->assign('focus_uid', $focus_uid);
            }
            $this->assign('question', $question);
			$p = $this->get['p'] ? $this->get['p'] : 1;
            switch ($this->get['order']) {
                case 'timeinc':
					$answer=array_slice(multi_array_sort($question['answer'],'bestanswer',SORT_DESC,'id',SORT_ASC),($p-1)*$this->setting['reply_per_page'],$this->setting['reply_per_page']);
                    break;

                case 'agreedec':
					$answer=array_slice(multi_array_sort($question['answer'],'bestanswer',SORT_DESC,'agreecount',SORT_DESC),($p-1)*$this->setting['reply_per_page'],$this->setting['reply_per_page']);
                    break;

                default:
					$answer=array_slice(multi_array_sort($question['answer'],'bestanswer',SORT_DESC,'id',SORT_ASC),($p-1)*$this->setting['reply_per_page'],$this->setting['reply_per_page']);
            }
            $count = count($question['answer']);
            $Page = new Page($count, $this->setting['reply_per_page']);
            $show = $Page->show();
            $this->assign('is_me', $is_me);
            $this->assign('page', $show);
            $this->assign('answer', $answer);
            $this->assign('title', $question['title']);
            $this->assign('keywords', str_replace(' ', ',', $question['keywords']));
            $this->assign('description', strip_tags($question['detail']));
            /*related question code start*/
            $keywords = explode(",", $question['keywords']);
            $related_qid = array();
            $seachwords = M('searchwords');
            foreach ($keywords as $k => $v) {
                $qid = $seachwords->where(array(
                    'keywords' => $v
                ))->select();
                foreach ($qid as $k2 => $v2) {
                    array_push($related_qid, $v2['qid']);
                }
            }
            $related_qid = array_count_values($related_qid);
            arsort($related_qid);
            $related_qid = array_slice(array_keys($related_qid) , 1, 5);
            $related_question = '';
            if ($related_qid == NULL) {
                $related_question = S('side_no_reply');
                $related_title = '期待您来回答';
            } else {
                foreach ($related_qid as $k => $v) {
                    $t = $q->where(array(
                        'id' => $v
                    ))->getField('title');
                    $related_question.= '<a href="' . U('Question/view?qid=' . $v) . '" class="side_list">' . $t . '</a>';
                }
                $related_title = '相关的' . $this->sign;
            }
            $this->assign('related_question', $related_question);
            $this->assign('related_title', $related_title);
            /*related question code end*/
            $this->display();
        } else {
            $this->display('question_error');
        }
    }
    function add_answer() {
		trim($this->post['content']=='')&&exit;
        $data['content'] = F('banwords') ? (strtr($this->post['content'], F('banwords'))) : ($this->post['content']);
        $data['addtime'] = time();
        $data['uid'] = $this->uid;
        $data['qid'] = $this->post['qid'];
        $data['img'] = $this->post['img'];
        $data['extra'] = $this->post['extra'] == 1 ? $this->post['extra_title'] : '';
		$data['imgwidth']=$this->post['imgwidth'];
		$data['imgheight']=$this->post['imgheight'];
        $answer = D('Answer');
        if (!$ai = $answer->add($data)) {
            echo '<script>tip("提交数据失败，请联系管理员!")</script>';
        } else {
            $question = M('question');
            $user = M('user');
            $focus = M('focus');
            $user->where(array(
                'id' => $this->uid
            ))->setInc('score', '', $this->setting['reply_add_score']);
            $user->where(array(
                'id' => $this->uid
            ))->setInc('totalreply');
            $question->where(array(
                'id' => $this->post['qid']
            ))->setInc('answercount');
            $question->where(array(
                'id' => $this->post['qid']
            ))->save(array(
                'lastreply' => time()
            ));
            M('action')->add(array(
                'username' => $this->username,
                'actionname' => '回复',
                'questiontitle' => $this->post['title'],
                'qid' => $this->post['qid'],
                'id' => $this->uid,
                'addtime' => time()
            ));
            $focus->where(array(
                'qid' => $this->post['qid']
            ))->setInc('answercount');
            $focus->where(array(
                'qid' => $this->post['qid']
            ))->setInc('newanswer');
            $c = $focus->where(array(
                'qid' => $this->post['qid'],
                'uid' => $this->uid
            ));
            ($this->post['focus'] == 1 && $c == 0) && $focus->where(array(
                'qid' => $this->post['qid']
            ))->save(array(
                'lastreply' => $data['addtime']
            ));
            if ($this->post['quid'] != $this->uid) {
                $user->where(array(
                    'id' => $this->post['quid']
                ))->setInc('newnotice');
                $dat['title'] = $this->post['name'] . '回复了您的' . $this->sign . ':';
                $dat['content'] = getsubstr($this->post['title'], 0, 20);
                $dat['uid'] = $this->post['quid'];
                $dat['qid'] = $this->post['qid'];
                $dat['aid'] = $ai;
                M('notice')->add($dat);
            }
            if ($this->post['extra'] == 1 && $this->post['extra_auid'] != $this->post['quid']) {
                $user->where(array(
                    'id' => $this->post['extra_auid']
                ))->setInc('newnotice');
                $d['title'] = $this->post['name'] . '回复了您的答案：';
                $d['content'] = getsubstr($this->post['extra_title'], 0, 20);
                $d['uid'] = $this->post['extra_auid'];
                $d['aid'] = $ai;
                $d['qid'] = $this->post['qid'];
                $d['myid'] = $this->uid;
                M('notice')->add($d);
            }
        }
    }
    function add_topic() {
        $this->tologin();
        $tqid = M('tqid');
        $topic = M('topic');
        $topicfocus = M('topicfocus');
        $tqid_arr = $tqid->where(array(
            'questionid' => $this->post['qid']
        ))->select(); //save the tqid array before deleting
        $tqid->where(array(
            'questionid' => $this->post['qid']
        ))->delete();
        foreach ($_POST['topic'] as $k => $v) {
            $topic_name = remove_xss($v);
            if ($topic->where(array(
                'name' => $topic_name
            ))->count() == 0) {
                $tid = $topic->add(array(
                    'name' => $topic_name,
                    'addtime' => time() ,
                    'focuscount' => 0,
                    'questioncount' => 1
                ));
                $tqid->add(array(
                    'topicname' => $topic_name,
                    'topicid' => $tid,
                    'questionid' => $this->post['qid']
                ));
            } else {
                $tid = $topic->where(array(
                    'name' => $topic_name
                ))->getField('id');
                $tqid->add(array(
                    'topicname' => $topic_name,
                    'topicid' => $tid,
                    'questionid' => $this->post['qid']
                ));
                $topic->where(array(
                    'id' => $tid
                ))->setInc('questioncount');
                $topicfocus->where(array(
                    'topicid' => $tid
                ))->setInc('newquestioncount');
            }
        }
        foreach ($tqid_arr as $k2 => $v2) //update questioncount and focuscount after deleting the topics
        {
            $questioncount = $tqid->where(array(
                'topicid' => $v2['topicid']
            ))->count();
            $topic->where(array(
                'id' => $v2['topicid']
            ))->save(array(
                'questioncount' => $questioncount
            ));
        }
    }
    function agree() {
        $this->tologin();
        $data['aid'] = $this->post['aid'];
        $data['uid'] = $this->post['auid'];
        $data['qid'] = $this->post['qid'];
        $data['actionid'] = $this->uid;
        if ($this->setting['agree_self'] == 0 && $data['uid'] == $this->uid) {
            exit($_POST['c'] . '<script>tip("不能赞成自己的回复");</script>');
        }
        $replyagree = M('replyagree');
        if ($replyagree->where($data)->count() == 0) {
            $replyagree->add($data);
            M('answer')->where(array(
                'id' => $this->post['aid']
            ))->setInc('agreecount');
            /*添加notice START*/
            M('user')->where(array(
                'id' => $this->post['auid']
            ))->setInc('newnotice');
            $dat['title'] = $this->post['name'] . '赞成了您的回复:';
            $dat['content'] = getsubstr($this->post['title'], 0, 20);
            $dat['uid'] = $this->post['auid'];
            $dat['aid'] = $this->post['aid'];
            $dat['qid'] = $this->post['qid'];
            $dat['myid'] = $this->uid;
            M('notice')->add($dat);
            /*添加notice END*/
            echo $this->post['c'] + 1;
        } else {
            echo $_POST['c'] . '<script>tip("您已赞成过");</script>';
        }
    }
    function against() {
        $this->tologin();
        $data['aid'] = $this->post['aid'];
        $data['uid'] = $this->post['auid'];
        $data['qid'] = $this->post['qid'];
        $data['actionid'] = $this->uid;
        if ($this->setting['agree_self'] == 0 && $data['uid'] == $this->uid) {
            exit($_POST['c'] . '<script>tip("不能反对自己的回复");</script>');
        }
        $replyagainst = M('replyagainst');
        if ($replyagainst->where($data)->count() == 0) {
            $replyagainst->add($data);
            M('answer')->where(array(
                'id' => $this->post['aid']
            ))->setInc('againstcount');
            /*添加notice START*/
            M('user')->where(array(
                'id' => $this->post['auid']
            ))->setInc('newnotice');
            $dat['title'] = $this->post['name'] . '反对了您的回复:';
            $dat['content'] = getsubstr($this->post['title'], 0, 20);
            $dat['uid'] = $this->post['auid'];
            $dat['aid'] = $this->post['aid'];
            $dat['qid'] = $this->post['qid'];
            $dat['myid'] = $this->uid;
            M('notice')->add($dat);
            /*添加notice END*/
            echo $this->post['c'] + 1;
        } else {
            echo $_POST['c'] . '<script>tip("您已反对过");</script>';
        }
    }
    function useless() {
        $this->tologin();
        $data['aid'] = $this->post['aid'];
        $data['uid'] = $this->post['auid'];
        $data['actionid'] = $this->uid;
        if ($this->setting['agree_self'] == 0 && $data['uid'] == $this->uid) {
            exit($_POST['c'] . '<script>tip("不能对自己回复点击没有帮助");</script>');
        }
        $replyuseless = M('replyuseless');
        if ($replyuseless->where($data)->count() == 0) {
            $replyuseless->add($data);
            M('answer')->where(array(
                'id' => $this->post['aid']
            ))->setInc('uselesscount');
            /*添加notice START*/
            M('user')->where(array(
                'id' => $this->post['auid']
            ))->setInc('newnotice');
            $dat['title'] = $this->post['name'] . '认为您的回复没有帮助:';
            $dat['content'] = getsubstr($this->post['title'], 0, 20);
            $dat['uid'] = $this->post['auid'];
            $dat['aid'] = $this->post['aid'];
            $dat['qid'] = $this->post['qid'];
            $dat['myid'] = $this->uid;
            M('notice')->add($dat);
            /*添加notice END*/
            echo $this->post['c'] + 1;
        } else {
            echo $_POST['c'] . '<script>tip("你已认为该回复没有帮助");</script>';
        }
    }
    function focus() {
        $this->tologin();
        $focus = M('focus');
        $data['qid'] = $this->post['qid'];
        $data['uid'] = $this->uid;
        if ($focus->where(array(
            'qid' => $data['qid'],
            'uid' => $this->uid
        ))->count() == 0) {
            $question_detail = M('question')->where(array(
                'id' => $data['qid']
            ))->select();
            $data['title'] = $question_detail[0]['title'];
            $data['pubtime'] = $question_detail[0]['pubtime'];
            $data['answercount'] = $question_detail[0]['answercount'];
            $data['viewcount'] = $question_detail[0]['viewcount'];
            $data['lastreply'] = $question_detail[0]['lastreply'];
            $data['recommendcount'] = $question_detail[0]['recommendcount'];
            $data['quid'] = $this->post['quid'];
            $focus->add($data);
            M('question')->where(array(
                'id' => $this->post['qid']
            ))->setInc('focuscount');
            $focus->where(array(
                'qid' => $this->post['qid']
            ))->save(array(
                'focuscount' => $question_detail[0]['focuscount'] + 1
            ));
            echo '<script>tip("已关注该' . $this->sign . '");</script>';
        } else {
            $focus->where($data)->delete();
            M('question')->where(array(
                'id' => $this->post['qid']
            ))->setDec('focuscount');
            $n = M('question')->where(array(
                'id' => $this->post['qid']
            ))->getField('focuscount');
            $focus->where(array(
                'qid' => $this->post['qid']
            ))->save(array(
                'focuscount' => $n
            ));
            echo '<script>tip("已取消关注该' . $this->sign . '");</script>';
        }
    }
    function recommend() {
        $this->tologin();
        $data = array(
            'qid' => $this->post['qid'],
            'uid' => $this->uid,
			'addtime' => time()
        );
        $recommend = M('recommend');
        if ($recommend->where($data)->count() == 0) {
            $recommend->add($data);
            M('question')->where(array(
                'id' => $this->post['qid']
            ))->setInc('recommendcount');
            M('focus')->where(array(
                'qid' => $this->post['qid']
            ))->setInc('recommendcount');
            echo 1;
        } else {
            echo 2;
        }
    }
    function invite_search() {
        $map['name'] = array(
            'like',
            $this->get['q'] . '%'
        );
        $r = M('user')->where($map)->field('name')->select();
        foreach ($r as $k => $v) {
            $va = $v['name'];
            echo "$va\n";
        }
    }
    function invite() {
        $this->uid == NULL && exit('登录后才能邀请');
        $hisid = M('user')->where(array(
            'name' => $this->post['hisname']
        ))->getField('id');
        $this->uid == $hisid && exit('不能邀请自己回答');
        $dat['title'] = $this->post['myname'] . '邀请您回答问题:';
        $dat['content'] = getsubstr(trim($this->post['title']) , 0, 20);
        $dat['uid'] = $hisid;
        $dat['qid'] = $this->post['qid'];
        $dat['myid'] = $this->uid;
        $notice = M('notice');
        $aa = $notice->where(array(
            'qid' => $dat['qid'],
            'uid' => $hisid,
            'myid' => $this->uid
        ))->count() >= 1 && exit('您已向' . $this->post['hisname'] . '发送了邀请');
        M('user')->where(array(
            'id' => $hisid
        ))->setInc('newnotice');
        echo $notice->add($dat) ? '邀请回答成功' : '邀请回答失败';
    }
    function search_question() {
        if (!strtolower($this->get['q'])) return;
        $keywords = '%' . getpinyin($this->get['q']) . '%';
        $result = M('question')->where(array(
            'pinyin' => array(
                'like',
                $keywords
            )
        ))->select();
        foreach ($result as $k => $v) {
            echo '<a href="' . U('question/view?qid=' . $v['id']) . '">' . getsubstr($v['title'], 0, 25) . '</a>';
            echo "\n";
        }
    }
    function categorylist() {
        $question_list = M('question')->where(array(
            'categoryid' => $this->get['cid']
        ))->limit($this->setting['question_per_page'])->select();
        $this->assign('question_list', $question_list);
        $this->display();
    }
    function reply_upload() {
        $this->uid == NULL && exit('尚未登录');
        $max_filesize = 2097152;
        $allowed_filetypes = array(
            'jpg',
            'jpeg',
            'gif',
            'png',
            'bmp'
        );
        $filename = $_FILES['jUploaderFile']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_strip = str_replace(" ", "_", $filename);
        $upload_path = './Public/upload/' . date('Ymd') . '/';
        !is_dir($upload_path) && mkdir($upload_path);
        !file_exists($upload_path . 'index.html') && touch($upload_path . 'index.html');
        if (!in_array($ext, $allowed_filetypes)) {
            echo json_encode(array(
                'status' => 'error',
                'msg' => '文件格式不允许' . $ext,
                'filename' => $file_strip
            ));
        } elseif (filesize($_FILES['jUploaderFile']['tmp_name']) > $max_filesize) {
            echo json_encode(array(
                'status' => 'error',
                'msg' => '文件太大',
                'filename' => $file_strip
            ));
        } elseif (!is_writable($upload_path)) {
            echo json_encode(array(
                'status' => 'error',
                'msg' => '文件夹不可读',
                'filename' => $file_strip
            ));
        } else {
            $pic = uniqid() . rand(1000, 9999) . $ext;
            if (move_uploaded_file($_FILES['jUploaderFile']['tmp_name'], $upload_path . $pic)) {
                echo json_encode(array(
                    'status' => 'success',
                    'msg' => '上传成功',
                    'filename' => $file_strip,
                    'realname' => date('Ymd') . '/' . $pic,
                ));
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'msg' => '上传失败，请重试',
                    'filename' => $file_strip
                ));
            }
        }
    }
    function reply_upload_delete() {
        $this->uid == NULL && exit('尚未登录');
        unlink('Public/upload/' . $this->post['name']);
    }
    function search() {
        $question = M('question');
        $keywords = get_keywords($this->get['words']);
        $result_qid = array();
        $seachwords = M('searchwords');
        foreach ($keywords as $k => $v) {
            $qid = $seachwords->where(array(
                'keywords' => $v
            ))->select();
            foreach ($qid as $k2 => $v2) {
                array_push($result_qid, $v2['qid']);
            }
        }
        $result_qid = array_count_values($result_qid);
        arsort($result_qid);
        $result_qid = array_keys($result_qid);
        $p = $this->get['p'] ? $this->get['p'] : 1;
        $page_arr = array_slice($result_qid, ($p - 1) * 10, 10);
        $count = count($result_qid);
        $Page = new Page($count, 10);
        $show = $Page->show();
        $this->assign('page', $show);
        $question_list =  $question->where(array(
				'id' => array(
					'in',
					$page_arr
				)
			))->select();
		$newkeywords=array();
		foreach($keywords as $k=>$v){
			$newkeywords[$v]='<span class="yellow">' . $v . '</span>';	
		}
		unset($keywords);
        $this->assign('top', '对“' . getsubstr($this->get['words'], 0, 12) . '”的搜索结果，共找到' . $count . '个结果，关键词已高亮显示');
		$this->assign('keywords',$newkeywords);
		$this->assign('question_list',$question_list);
        $this->assign('title', '搜索结果');
        $this->display();
    }
    function search_topic() {
        $map['name'] = array(
            'like',
            $this->get['q'] . '%'
        );
        $r = M('topic')->where($map)->field('name')->select();
        foreach ($r as $k => $v) {
            $va = $v['name'];
            echo "$va\n";
        }
    }
}
?>