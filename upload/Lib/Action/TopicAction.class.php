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
class TopicAction extends BaseAction {
    private function tologin() {
        if ($this->uid == NULL) {
            exit($_POST['c'] . '<script>$("#alert_mid").html("请先登录");$("#top_tip").animate({top:0},400).delay(1000).animate({top:-36},400,function(){location.href="' . U('account/login') . '"});</script>');
        }
    }
    function index() {
        $topic = M('topic');
        $topic_list = $topic->page($this->get['p'] . ',' . $this->setting['topic_per_page'])->order('addtime DESC')->select();
        $count = $topic->count();
        $Page = new Page($count, $this->setting['topic_per_page']);
        if ($this->uid != NULL) {
            $topicfocus = M('topicfocus');
            $focus = $topicfocus->where(array(
                'uid' => $this->uid
            ))->select();
            $total_count = $topicfocus->where(array(
                'uid' => $this->uid
            ))->sum('newquestioncount');
            $my_focus = array();
            foreach ($focus as $k => $v) {
                if ($v['uid'] == $this->uid) {
                    $my_focus[] = $v['topicid'];
                }
            }
        }
        $this->assign('page', $Page->show());
        $this->assign('totalcount', $total_count);
        $this->assign('my_focus', $my_focus);
        $this->assign('topic_list', $topic_list);
        $this->assign('title', '所有话题-' . $this->setting['site_name']);
        $this->display();
    }
    function topic_edit() {
        $this->tologin();
        $des = F('banwords') ? (strtr($this->post['describe'], F('banwords'))) : ($this->post['describe']);
        echo !M('topic')->where(array(
            'id' => $this->post['topicid']
        ))->save(array(
            'describe' => $des
        )) ? '<script>tip("网络繁忙，请稍后重试！");</script>' : '<script>tip("话题编辑成功");</script>';
    }
    function topic_focus() {
        $this->tologin();
        $data['uid'] = $this->uid;
        $data['topicid'] = $this->post['topicid'];
        $topic = M('topicfocus');
        if ($topic->where($data)->count() == 0) {
            $topic->add($data);
            echo '取消关注';
        } else {
            $topic->where($data)->delete();
            echo '+ 关注';
        }
    }
    function me() {
        $this->uid == NULL && $this->redirect('Account/login');
        $topicfocus = M('topicfocus');
        $topic = M('topic');
        $total_count = $topicfocus->where(array(
            'uid' => $this->uid
        ))->sum('newquestioncount');
        $this->assign('totalcount', $total_count);
        $topicfocus_list = $topicfocus->order('newquestioncount DESC')->where(array(
            'uid' => $this->uid
        ))->page($_GET['p'] . ',' . $this->setting['topic_per_page'])->field('topicid')->select();
        $t_arr = array();
        foreach ($topicfocus_list as $k => $v) {
            array_push($t_arr, $v['topicid']);
        }
        $my_list = M('topic')->where(array(
            'id' => array(
                'in',
                $t_arr
            )
        ))->select();
        $count = count($t_arr);
        $Page = new Page($count, $this->setting['topic_per_page']);
        $this->assign('page', $Page->show());
        $this->assign('title', '我关注的话题');
        $this->assign('topic_list', $my_list);
        $this->display();
    }
    function view() {
        $topic = M('tqid')->where(array(
            'topicid' => $this->get['tid']
        ))->field('topicname,questionid')->select();
        $p = $this->get['p'] ? $this->get['p'] : 1;
        $page_arr = array_slice($topic, ($p - 1) * $this->setting['question_per_page'], $this->setting['question_per_page']);
        $v_arr = array();
        foreach ($page_arr as $k => $v) {
            array_push($v_arr, $v['questionid']);
        }
        $view_list = M('question')->where(array(
            'id' => array(
                'in',
                $v_arr
            )
        ))->select();
        $count = count($topic);
        $Page = new Page($count, $this->setting['question_per_page']);
        $this->assign('page', $Page->show());
        $t = M('topic');
        if (!S('unedit_topic')) {
            $unedit_topic = $t->where(array(
                'describe' => ''
            ))->limit(10)->select();
            foreach ($unedit_topic as $k => $v) {
                $unedit_result.= '<a href="' . U('topic/view?tid=' . $v['id']) . '" class="side_list">' . $v['name'] . '</a>';
            }
            S('unedit_topic', $unedit_result, $this->setting['side_list_cachetime']);
        }
        if (!S('hot_topic')) {
            $hot_topic = $t->order('questioncount DESC')->limit(5)->select();
            foreach ($hot_topic as $k => $v) {
                $hot_result.= '<a href="' . U('topic/view?tid=' . $v['id']) . '" class="side_list">' . $v['name'] . '</a>';
            }
            S('hot_topic', $hot_result, $this->setting['side_list_cachetime']);
        }
        $topic_info = $t->where(array(
            'id' => $this->get['tid']
        ))->select();
        $topicfocus = M('topicfocus');
        $topic_uid = $topicfocus->where(array(
            'topicid' => $this->get['tid']
        ))->select();
        $this->uid != NULL && ($topicfocus->where(array(
            'topicid' => $this->get['tid'],
            'uid' => $this->uid
        ))->count() != 0 && $topicfocus->where(array(
            'topicid' => $this->get['tid'],
            'uid' => $this->uid
        ))->setField('newquestioncount', 0)); // clear new questioncount added
        $this->assign('question_list', $view_list);
        $this->assign('topic_uid', $topic_uid);
        $this->assign('topic', $topic_info[0]);
        $this->assign('title', $topic[0]['topicname'] ? $topic[0]['topicname'] : '暂无该话题');
        $this->assign('keywords', $topic[0]['topicname']);
        $this->assign('description', $topic_info[0]['describe']);
        $this->display();
    }
    function topic_del() {
        if ($this->uid == 1) {
            if (D('Topic')->relation(true)->delete($this->post['topic_id'])) {
                echo '删除话题成功';
            } else {
                echo '删除话题失败';
            }
        }
    }
}
?>
                    
