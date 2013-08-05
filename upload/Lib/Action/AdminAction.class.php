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
class AdminAction extends Action
{
	var $setting;
	function _initialize()
		{
			$set=M('setting')->select();
			$setting=array();
			foreach($set as $k=>$v)
				{
					$setting[$v['name']]=$v['value'];	
				}
			$this->setting=$setting;	
			$this->assign('setting',$setting);
			F('setting',$setting);
			$arr=explode(",",$setting['banwords']);
			$ban_arr=array();
			if($arr[0]!='')
				{
					foreach($arr as $k=>$v)
						{
							$star=function_exists('iconv_strlen')?str_repeat('*',iconv_strlen($v,'utf-8')):'***';
							$ban_arr[$v]=$star;		
						}
					F('banwords',$ban_arr);	
				}else{
						F('banwords',NULL);
					 }
		}
	private function tolog()
		{
			if(!isset($_COOKIE['qcs_auth']))
			{
				$this->redirect('Account/login');
			}else{
					$id=explode("\t",strcode($_COOKIE['qcs_auth'],$this->setting['auth_key'],'DECODE'));
					(!is_numeric($id[0])||$id[0]!=1)&&$this->redirect('Index/index');
				}
			if(Session::get('aid')!=1){$this->redirect('Admin/login');exit('Access Denied');}
		}		
	function login()
		{
			deldir('./Runtime/Cache/');	
			Session::get('aid')==1&&$this->redirect('Admin/main');
			if(isset($_COOKIE['qcs_auth']))
				{
					$id=explode("\t",strcode($_COOKIE['qcs_auth'],$this->setting['auth_key'],'DECODE'));
					(!is_numeric($id[0])||$id[0]!=1)&&$this->redirect('Index/index');
				}else{
						$this->redirect('Account/login');
					 }
			$this->display();	
		}
	function login_sub()
		{
			$_POST['admin_name']==NULL||$_POST['admin_pwd']==NULL&&exit;
			if(isset($_COOKIE['qcs_auth']))
				{
					$id=explode("\t",strcode($_COOKIE['qcs_auth'],$this->setting['auth_key'],'DECODE'));
					(!is_numeric($id[0])||$id[0]!=1)&&$this->redirect('Index/index');
				}else{
						$this->redirect('Account/login');
					 }
			if(M('user')->where(array('name'=>remove_xss($_POST['admin_name']),'pwd'=>pwd_encode($_POST['admin_pwd'])))->getField('id')==1)
				{
					Session::set('aid',1);
					$this->redirect('Admin/main');
				}else{
						$this->assign('script','<script>alert("您的输入有误，请重新输入")</script>');
						$this->display('Admin/login');
				     }
		}
	function off()
		{
			Session::set('aid',NULL);
			$this->redirect('Admin/login');
		}
	function env_check()
		{
			$user=M('user');
			$question=M('question');
			$topic=M('topic');
			$usercount=$user->count();
			$questioncount=$question->count();
			$solvedcount=$question->where(array('issolve'=>1))->count();
			$topiccount=$topic->count();
			$unedittopiccount=$topic->where(array('describe' => ''))->count();
			$c_num=M('category')->count();
			$this->assign('usercount',$usercount);
			$this->assign('questioncount',$questioncount);
			$this->assign('solvecount',$solvedcount);
			$this->assign('topiccount',$topiccount);
			$this->assign('unedittopiccount',$unedittopiccount);
			$this->assign('c_num',$c_num);
			$this->display();	
		}		
	function main()
		{
			$this->tolog();
			$this->display();
		}
	function basic_info()
		{
			$this->tolog();
			$this->display();	
		}
	function basic_info_receive()
		{
			$this->tolog();
			foreach($_POST as $k=>$v)
				{
					M('setting')->where(array('name'=>$k))->save(array('value'=>$v));
				}
			$this->redirect('Admin/basic_info');	
		}
	function site_cfg()
		{
			$this->tolog();
			$this->display();	
		}	
	function site_cfg_receive()
		{
			$this->tolog();
			foreach($_POST as $k=>$v)
				{
					M('setting')->where(array('name'=>$k))->save(array('value'=>$v));
				}
			$this->redirect('Admin/site_cfg');		
		}
	function rewrite_cfg()
		{
			$this->tolog();
			$this->display();
		}	
	function rewrite_cfg_receive()
		{
			$this->tolog();
			$str='<?php $basic_config	=	require \'config.inc.php\';$user_config = array (\'url_model\' => '.$_POST['url_model'].',\'url_html_suffix\' => \''.$_POST['url_html_suffix'].'\',);return array_merge($basic_config,$user_config);?>';
			file_put_contents(CONFIG_PATH.'/config.php',$str);	
			deldir('./Runtime');
			$this->redirect('Admin/rewrite_cfg');	
		}
	function ucenter_cfg()
		{
			$this->tolog();
			$this->display();	
		}
	function ucenter_cfg_receive()
		{
			$this->tolog();
			M('setting')->where(array('name'=>'ucenter_on'))->save(array('value'=>$_POST['ucenter_on']));
			if(trim($_POST['ucenter_info'])!='')
				{
					file_put_contents('./Conf/uc_config.php','<?php require("uc_config.inc.php");'.stripslashes($_POST['ucenter_info']));
				}
			$this->redirect('Admin/ucenter_cfg');			
		}		
	function category_cfg()
		{
			$this->tolog();	
			$max=M('category')->max('id');
			$this->assign('max',$max);
			$this->display();
		}	
	function category_cfg_receive()
		{
			$this->tolog();
			import("ORG.Net.UploadFile");
			$upload = new UploadFile(); // 实例化上传类
			$upload->maxSize  = 3145728 ; // 设置附件上传大小
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
			$upload->savePath =  './Public/thumbs/'; // 设置附件上传目录
			$upload->saveRule='uniqid';
			$upload->thumb=true;
			$upload->thumbMaxWidth=110;
			$upload->thumbMaxHeight=80;
			$upload->thumbRemoveOrigin=true;
			if(!$upload->upload()) { // 上传错误提示错误信息
			//$this->error($upload->getErrorMsg());
			}else{ // 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
			 }
			$cate_arr=array();
			$category=M('category');
			$category->execute('TRUNCATE TABLE __TABLE__');
			$order_arr=explode(',cate_',$_POST['cate_arr']);
			$n=count($order_arr);
			$thumb_arr=array();	
			foreach($info as $k=>$v)
				{
					$thumb_arr[$v['key']]=$v['savename'];
				}
			$cate_all=array();	
			for($i=0;$i<$n;$i++)
				{
					$thumb=array_key_exists($i,$thumb_arr)?$thumb_arr[$i]:$_POST['thumb_hidden'][$i];
					$arr=array('id'=>$order_arr[$i],'title'=>$_POST['cate'][$i],'color'=>$_POST['color'][$i],'describe'=>$_POST['describe'][$i],'thumb'=>$thumb);	
					array_push($cate_all,$arr);
					$category->add($arr);
				}
			F('category',$cate_all);	
			$this->redirect('Admin/category_cfg');		
		}		
	function filter_keywords()
		{
			$this->tolog();
			$this->display();	
		}
	function filter_keywords_receive()
		{
			$this->tolog();
			$arr=explode(",",$_POST['banwords']);
			$ban_arr=array();
			if($arr[0]!='')
				{
					foreach($arr as $k=>$v)
						{
							$star=function_exists('iconv_strlen')?str_repeat('*',iconv_strlen($v,'utf-8')):'***';
							$ban_arr[$v]=$star;		
						}
					F('banwords',$ban_arr);	
				}else{
						F('banwords',NULL);
					 }
			M('setting')->where(array('name'=>'banwords'))->save(array('value'=>$_POST['banwords']));	 
			$this->redirect('admin/filter_keywords');
		}
	function question_list()
		{
			$this->tolog();
			import("ORG.Util.Page"); 
			$a=M('question');
			$p=15;
			$list=$a->page($_GET['p'].','.$p)->select();
			$count= $a->count();
			$Page= new Page($count,$p); 
			$Page->setConfig('prev','&nbsp;<&nbsp;');
           	$Page->setConfig('next','&nbsp;>&nbsp;');
			$Page->setConfig('theme','%first%%prePage%%upPage%%linkPage%%downPage%%nextPage%%end%');
			$show=$Page->show(); 
			$this->assign('page',$show);
			$this->assign('category',F('category'));
			$this->assign('list',$list);
			$this->display();
		}			
	function del_question()
		{
			$this->tolog();
			if(D('Question')->relation(true)->delete($_POST['qid']))
				{
					echo '1';	
				}else{
						echo '0';
					 }
		}
	function del_dir()
		{
			$this->tolog();
			deldir($_POST['dir'])?print '清除全部缓存成功':'清除全部缓存失败';	
		}
	function del_file()
		{
			$this->tolog();
			unlink($_POST['file'])?print '清除缓存成功':'清除缓存失败';	
		}		
	function email_cfg()
		{
			$this->tolog();
			$this->display();	
		}
	function email_cfg_receive()
		{
			$this->tolog();
			foreach($_POST as $k=>$v)
				{
					M('setting')->where(array('name'=>$k))->save(array('value'=>$v));
				}
			$this->redirect('Admin/email_cfg');		
		}						
}
?>