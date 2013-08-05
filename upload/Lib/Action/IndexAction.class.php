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
class IndexAction extends BaseAction
{
	function index()
		{
			$this->assign('title',$this->setting['site_name']);
			$this->display();	
		}	
    function all()
		{
			$count=NULL;
			if($this->uid!=NULL)
				{
					$focus=M('focus');
					$question_list=$focus->where(array('uid'=>$this->uid))->order('newanswer Desc,lastreply DESC')->page($this->get['p'].','.$this->setting['question_per_page'])->select();
					$count= $focus->where(array('uid'=>$this->uid))->count();
				}else{
						$question=M('question');
						$question_list=$question->page($this->get['p'].','.$this->setting['question_per_page'])->order('lastreply DESC')->select();
						$count= $question->count();
					 }		 
			$this->assign('question_list',$question_list);
			$Page= new Page($count,$this->setting['question_per_page']); 
			$show=$Page->show();
			if($this->isAjax())
				{
					foreach($question_list as $k=>$v)
							{
								$uid=$this->uid!=NULL?$v['quid']:$v['uid'];
								$solve=$v['issolve']==1?'已解决':'未解决';
								echo '
									<div class="q_wrapper">
										<div class="avatar_wrapper" >
											<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').get_avatar($uid,'min').'" class="avatar" />
											<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/avatar_cover.png" class="avatar_cover" /> 
											<input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" />
										</div>
										<div class="q_list">
										<a href="'.U('Question/view?qid='.$v['id']).'" target="'.$this->setting['link_open'].'">'.$v['title'].'</a>
											<div class="opt">
												<div class="q_option_left"></div>
												<div class="q_option_mid">
													时间：'.time_mode($v['pubtime']).'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													浏览:'.$v['viewcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													回复:'.$v['answercount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													关注:'.$v['focuscount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													推荐:'.$v['recommendcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													状态：'.$solve.'
												</div>
												<div class="q_option_right"></div>
												<div class="clear"></div>
											</div>
										</div>
									</div>
								';
							}
			echo '<div id="page" align="center">'.$show.'</div>';
				}else{
					$this->assign('page',$show);
					$this->assign('keywords',$this->setting['site_keywords']);		
					$this->assign('description',$this->setting['site_description']);		 
					$this->assign('title',$this->setting['site_name']);
					$this->assign('cid',$this->get['cid']);
					$this->display();
				}
		}
	function clist()
		{
			$question=M('question');
			$question_list=$question->where(array('categoryid'=>$this->get['cid']))->page($_GET['p'].','.$this->setting['question_per_page'])->order('lastreply DESC')->select();
			$category=array();
			foreach(F('category') as $k=>$v)
				{
					$category[$v['id']]=$v['title'];
				}
			$this->assign('question_list',$question_list);
			$this->assign('category_title',$category[$this->get['cid']]);
			$count= $question->where(array('categoryid'=>$this->get['cid']))->count();
			$Page= new Page($count,$this->setting['question_per_page']); 
			$show=$Page->show(); 		
			if($this->isAjax())
				{
					foreach($question_list as $k=>$v)
							{
								 $solve=$v['issolve']==1?'已解决':'未解决';
								echo '
									<div class="q_wrapper">
										<div class="avatar_wrapper" >
											<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').get_avatar($v['uid'],'min').'" class="avatar" />
											<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/avatar_cover.png" class="avatar_cover" /> 
											<input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" />
										</div>
										<div class="q_list">
										<a href="'.U('Question/view?qid='.$v['id']).'" target="'.$this->setting['link_open'].'">'.$v['title'].'</a>
											<div class="opt">
												<div class="q_option_left"></div>
												<div class="q_option_mid">
													时间：'.time_mode($v['pubtime']).'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													浏览:'.$v['viewcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													回复:'.$v['answercount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													关注:'.$v['focuscount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													推荐:'.$v['recommendcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
													状态：'.$solve.'
												</div>
												<div class="q_option_right"></div>
												<div class="clear"></div>
											</div>
										</div>
									</div>
								';
							}
					echo '<div id="page" align="center">'.$show.'</div>';	
				}else{
						$this->assign('page',$show);
						$this->assign('title',$category[$this->get['cid']].'-'.$this->setting['site_name']);	
						$this->assign('keywords',$category[$this->get['cid']].','.$this->setting['site_name']);		
						$this->assign('description',$this->setting['site_description']);
						$this->display();
					 }
					
		}		
	function focus_question()
		{
			!$this->isAjax()&&exit;
			if($this->uid==NULL){
				exit('参数错误！');
				}
			$question=M('focus');	
			$question_list=$question->page($this->get['p'].','.$this->setting['question_per_page'])->where(array('uid'=>$this->uid))->limit($this->setting['question_per_page'])->order('lastreply DESC')->select();
				if($question_list==NULL)
					{
						echo '<div id="question_error">暂无关注的'.$this->sign.'</div>';	
					}else{
						$count= $question->where(array('uid'=>$this->uid))->count();
				foreach($question_list as $k=>$v)
                	{
						$Page= new Page($count,$this->setting['question_per_page']); 
						$show=$Page->show();
						$solve=$v['issolve']==1?'已解决':'未解决';
						$newanswer_class=$v['newanswer']<10?($v['newanswer']>0?'lessnine':'none'):'morenine';
                        $newanswer=$v['newanswer']<100?$v['newanswer']:'99+';
                    	echo '
                        	<div class="q_wrapper">
                                <div class="avatar_wrapper" >
                                    <img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').get_avatar($v['quid'],'min').'" class="avatar" />
                                    <img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/avatar_cover.png" class="avatar_cover" />
									<input type="hidden" value="'.$v['quid'].'" class="avatar_hidden" /> 
                                </div>
                                <div class="q_list">
                                <a href="'.U('Question/view?qid='.$v['qid']).'" target="'.$this->setting['link_open'].'">'.$v['title'].'</a>
                                    <div class="opt">
                                        <div class="q_option_left"></div>
                                        <div class="q_option_mid">
                                            时间：'.time_mode($v['pubtime']).'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            浏览:'.$v['viewcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                           	回复:'.$v['answercount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            关注:'.$v['focuscount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            推荐:'.$v['recommendcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            状态：'.$solve.'
                                        </div>
                                        <div class="q_option_right"><div class="'.$newanswer_class.'">'.$newanswer.'</div></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
					echo '<div id="page" align="center">'.$show.'</div>';
				}
		}
	private function print_list($question_list,$count)
		{
			if($question_list==NULL)
					{
						echo '<div id="question_error">暂无'.$this->sign.'列表</div>';	
					}else{
					$Page= new Page($count,$this->setting['question_per_page']); 
					$show=$Page->show(); 		
					foreach($question_list as $k=>$v)
						{
						 $solve=$v['issolve']==1?'已解决':'未解决';
                    	echo '
                        	<div class="q_wrapper">
                                <div class="avatar_wrapper" >
                                    <img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').get_avatar($v['uid'],'min').'" class="avatar" />
                                    <img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/avatar_cover.png" class="avatar_cover" /> 
									<input type="hidden" value="'.$v['uid'].'" class="avatar_hidden" />
                                </div>
                                <div class="q_list">
                                <a href="'.U('Question/view?qid='.$v['id']).'" target="'.$this->setting['link_open'].'">'.$v['title'].'</a>
                                    <div class="opt">
                                        <div class="q_option_left"></div>
                                        <div class="q_option_mid">
                                            时间：'.time_mode($v['pubtime']).'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            浏览:'.$v['viewcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                           	回复:'.$v['answercount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            关注:'.$v['focuscount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            推荐:'.$v['recommendcount'].'<img src="'.C('TMPL_PARSE_STRING.__PUBLIC__').'/css/devider.png" />
                                            状态：'.$solve.'
                                        </div>
                                        <div class="q_option_right"></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
				echo '<div id="page" align="center">'.$show.'</div>';
				}
		}	
	function all_question()
		{
			!$this->isAjax()&&exit;
			$question=M('question');
			if($this->get['cid']==NULL)
				{
					$question_list=$question->page($this->get['p'].','.$this->setting['question_per_page'])->order('lastreply DESC')->select();	
					$count= $question->count();
					
				}else{
						$question_list=$question->where(array('categoryid'=>$this->get['cid']))->page($_GET['p'].','.$this->setting['question_per_page'])->order('lastreply DESC')->select();
						$count= $question->where(array('categoryid'=>$this->get['cid']))->count();
					 }
			$this->print_list($question_list,$count);		
		}
	function time_question()
		{
			!$this->isAjax()&&exit;
			$question=M('question');
			if($this->get['cid']==NULL)
				{
					$question_list=$question->page($this->get['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->select();	
					$count=$question->count();
				}else{
						if($this->get['y']==1)
							{
								$question_list=$question->where(array('categoryid'=>$this->get['cid']))->page($_GET['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->order('pubtime ASC')->select();	
							}else{
									$question_list=$question->where(array('categoryid'=>$this->get['cid']))->page($_GET['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->order('pubtime DESC')->select();
								 }
						$count= $question->where(array('categoryid'=>$this->get['cid']))->count();		 
					 }
				$this->print_list($question_list,$count);	 
		}		
	function hot_question()
		{
			$question=M('question');
			if($this->get['cid']==NULL)
				{
					$question_list=$question->order('answercount DESC')->page($this->get['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->select();
					$count= $question->count();
				}else{
						$question_list=$question->where(array('categoryid'=>$this->get['cid']))->order('answercount DESC')->page($_GET['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->select();
						$count= $question->where(array('categoryid'=>$this->get['cid']))->count();
				}
			$this->print_list($question_list,$count);	
		}
	function recommend_question()
		{
			!$this->isAjax()&&exit;
			$question=M('question');
			if($this->get['cid']==NULL)
				{
					$question_list=$question->order('recommendcount DESC')->page($this->get['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->select();
					$count= $question->count();
				}else{
						$question_list=$question->where(array('categoryid'=>$this->get['cid']))->order('recommendcount DESC')->page($_GET['p'].','.$this->setting['question_per_page'])->limit($this->setting['question_per_page'])->select();
						$count= $question->where(array('categoryid'=>$this->get['cid']))->count();
					 }
			$this->print_list($question_list,$count);		
	}		
}
?>