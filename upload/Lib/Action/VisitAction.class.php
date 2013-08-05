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
class VisitAction extends BaseAction {
    function index() {
        $this->pro();
        $q = M('question');
        $question_list = $q->where(array(
            'uid' => $this->get['uid']
        ))->page($_GET['p'] . ',' . $this->setting['question_per_page'])->order('id desc')->select();
        $count = $q->where(array(
            'uid' => $this->get['uid']
        ))->count();
        $Page = new Page($count, $this->setting['question_per_page']);
        $this->assign('page', $show = $Page->show());
        $this->assign('question_list', $question_list);
        $this->assign('title', 'TA发表的' . $this->sign);
        $this->assign('no_list', 'TA暂未发表' . $this->sign);
        $this->display('Visit/list');
    }
    private function pro() {
        $u = M('user')->where(array(
            'id' => $this->get['uid']
        ))->select();
        (!$this->get['uid'] || !$u) && exit('该用户不存在');
        $this->uid == $u[0]['id'] && $this->redirect('User/index');
        $this->assign('he', $u[0]);
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
        $this->assign('title', 'Ta' . $no_list . '的' . $this->sign);
        $this->display('Visit/list');
    }
    function hisreply() {
        $this->pro();
        $my = M('answer')->where(array(
            'uid' => $this->get['uid']
        ))->select();
        $this->mylist($my, '回复');
    }
    function hisagainst() {
        $this->pro();
        $a = M('replyagainst');
        $my = $a->where(array(
            'actionid' => $this->uid
        ))->select();
        $this->mylist($my, '反对');
    }
    function hisagree() {
        $this->pro();
        $a = M('replyagree');
        $my = $a->where(array(
            'actionid' => $this->get['uid']
        ))->select();
        $this->mylist($my, '赞成');
    }
    function hisrecommend() {
        $this->pro();
        $a = M('recommend');
        $my = $a->where(array(
            'uid' => $this->get['uid']
        ))->select();
        $this->mylist($my, '推荐');
    }
    function hisfocus() {
        $this->pro();
        $question_list = M('focus')->where(array(
            'uid' => $this->get['uid']
        ))->limit($this->setting['question_per_page'])->select();
        $this->assign('question_list', $question_list);
        $this->assign('title', 'TA关注的' . $this->sign);
        $this->assign('no_list', 'TA暂无关注的' . $this->sign);
        $this->display('Visit/list');
    }
}
?>
