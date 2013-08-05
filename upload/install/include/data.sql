-- phpMyAdmin SQL Dump
-- version 3.5.0-rc2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 06 月 26 日 14:04
-- 服务器版本: 5.0.90-community-nt
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `r`
--

-- --------------------------------------------------------

--
-- 表的结构 `qcs_action`
--

CREATE TABLE IF NOT EXISTS `qcs_action` (
  `username` varchar(40) NOT NULL,
  `actionname` char(6) NOT NULL,
  `questiontitle` char(45) NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `qid` int(11) unsigned NOT NULL,
  `id` int(11) unsigned NOT NULL,
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_answer`
--

CREATE TABLE IF NOT EXISTS `qcs_answer` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `extra` text NOT NULL,
  `content` text NOT NULL,
  `img` char(30) NOT NULL,
  `imgwidth` smallint(5) unsigned NOT NULL,
  `imgheight` smallint(5) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `agreecount` int(11) unsigned NOT NULL default '0',
  `againstcount` int(11) unsigned NOT NULL default '0',
  `uselesscount` int(11) unsigned NOT NULL default '0',
  `attach` int(11) NOT NULL default '0',
  `bestanswer` tinyint(4) NOT NULL default '0',
  `uid` int(11) unsigned NOT NULL,
  `qid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_category`
--

CREATE TABLE IF NOT EXISTS `qcs_category` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `thumb` char(18) NOT NULL,
  `describe` text NOT NULL,
  `color` char(7) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qcs_category`
--

INSERT INTO `qcs_category` (`id`, `title`, `thumb`, `describe`, `color`) VALUES
(1, '默认分类一', '51a4a6bcc08ee.jpg', '这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容', '#80AAFF'),
(2, '默认分类二', '51a4a6bcce95c.jpg', '这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容', '#FF42F2'),
(3, '默认分类三', '51a4b7d276894.jpg', '这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容', '#72DB2C'),
(0, '默认分类四', '51a4a6bcca25a.jpg', '这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容，这是默认分类一的详细介绍，您可以在后台自行编辑该内容以便让网友能大致知道该板块的内容', '#F5EE25');

-- --------------------------------------------------------

--
-- 表的结构 `qcs_focus`
--

CREATE TABLE IF NOT EXISTS `qcs_focus` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `qid` int(11) unsigned NOT NULL,
  `quid` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `pubtime` int(11) unsigned NOT NULL,
  `answercount` int(11) unsigned NOT NULL,
  `newanswer` int(11) unsigned NOT NULL,
  `viewcount` int(11) unsigned NOT NULL,
  `focuscount` int(11) unsigned NOT NULL,
  `recommendcount` int(11) unsigned NOT NULL,
  `issolve` tinyint(4) NOT NULL default '0',
  `lastreply` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `qid` (`qid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_follow`
--

CREATE TABLE IF NOT EXISTS `qcs_follow` (
  `hisid` int(11) unsigned NOT NULL,
  `myid` int(11) unsigned NOT NULL,
  KEY `hisid` (`hisid`),
  KEY `myid` (`myid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_letter`
--

CREATE TABLE IF NOT EXISTS `qcs_letter` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `content` text NOT NULL,
  `from` int(11) unsigned NOT NULL,
  `to` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `lettercount` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_letterview`
--

CREATE TABLE IF NOT EXISTS `qcs_letterview` (
  `content` text NOT NULL,
  `from` int(11) unsigned NOT NULL,
  `to` int(11) unsigned NOT NULL,
  `addtime` int(11) unsigned NOT NULL,
  `letterid` int(11) unsigned NOT NULL,
  KEY `letterid` (`letterid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_newmsg`
--

CREATE TABLE IF NOT EXISTS `qcs_newmsg` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `letterid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_notice`
--

CREATE TABLE IF NOT EXISTS `qcs_notice` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(40) NOT NULL,
  `content` char(65) NOT NULL,
  `myid` int(11) unsigned NOT NULL,
  `qid` int(11) unsigned NOT NULL,
  `aid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=108 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_question`
--

CREATE TABLE IF NOT EXISTS `qcs_question` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `detail` text NOT NULL,
  `pinyin` text NOT NULL,
  `keywords` varchar(60) NOT NULL,
  `pubtime` int(11) unsigned NOT NULL,
  `lastreply` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `answercount` int(11) unsigned NOT NULL default '0',
  `viewcount` int(11) unsigned NOT NULL default '0',
  `focuscount` int(11) unsigned NOT NULL default '0',
  `recommendcount` int(11) unsigned NOT NULL default '0',
  `categoryid` int(11) unsigned NOT NULL,
  `issolve` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `issolve` (`issolve`),
  FULLTEXT KEY `pinyin` (`pinyin`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_recommend`
--

CREATE TABLE IF NOT EXISTS `qcs_recommend` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `qid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `qid` (`qid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_replyagainst`
--

CREATE TABLE IF NOT EXISTS `qcs_replyagainst` (
  `id` int(11) NOT NULL auto_increment,
  `qid` int(11) unsigned NOT NULL,
  `aid` int(11) unsigned NOT NULL COMMENT '回复ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `actionid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `qid` (`qid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='回复支持' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_replyagree`
--

CREATE TABLE IF NOT EXISTS `qcs_replyagree` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `qid` int(11) unsigned NOT NULL,
  `aid` int(11) unsigned NOT NULL COMMENT '回复ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `actionid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `qid` (`qid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='回复支持' AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_replyuseless`
--

CREATE TABLE IF NOT EXISTS `qcs_replyuseless` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `aid` int(11) unsigned NOT NULL COMMENT '回复ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `actionid` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='回复支持' AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_searchwords`
--

CREATE TABLE IF NOT EXISTS `qcs_searchwords` (
  `keywords` varchar(20) character set utf8 collate utf8_esperanto_ci NOT NULL,
  `qid` int(11) unsigned NOT NULL,
  KEY `keywords` (`keywords`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_setting`
--

CREATE TABLE IF NOT EXISTS `qcs_setting` (
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `qcs_setting`
--

INSERT INTO `qcs_setting` (`name`, `value`) VALUES
('reply_self', '1'),
('auth_key', ''),
('reply_min_wordcount', '5'),
('reply_per_page', '10'),
('agree_self', '1'),
('link_open', '_blank'),
('question_per_page', '10'),
('mail_host', 'smtp.126.com'),
('mail_mode', '2'),
('ismailverify', '0'),
('topic_per_page', '12'),
('side_list_count', '5'),
('side_list_cachetime', '600'),
('publish_add_score', '2'),
('reply_add_score', '1'),
('banwords', '傻逼,尼玛'),
('ucenter_on', '0'),
('site_name', 'QuoraCms'),
('site_description', 'QuoraCms是一款基于php+MySQL+jQuery的免费社会化问答程序，简称QCS，您不仅可以用QuoraCms来搭建仿quora的社会化问答网站，还能搭建论坛社区等等，在web2.0时代，QuoraCms定能满足您的需求'),
('site_keywords', '社会化问答程序,quoracms,QCS,免费社交程序,社会化问答CMS,仿quora'),
('icp', ''),
('register_code', '1'),
('mail_pwd', ''),
('mail_port', '25'),
('mail_addr', ''),
('register_close', '0'),
('is_quora', '1'),
('helpless_min_count', '2'),
('is_invite_register', '0'),
('invite_count_available', '5'),
('adopted_add_score', '4');

-- --------------------------------------------------------

--
-- 表的结构 `qcs_topic`
--

CREATE TABLE IF NOT EXISTS `qcs_topic` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `describe` text NOT NULL,
  `focuscount` int(11) unsigned NOT NULL default '0',
  `questioncount` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_topicfocus`
--

CREATE TABLE IF NOT EXISTS `qcs_topicfocus` (
  `id` int(11) NOT NULL auto_increment,
  `topicid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `newquestioncount` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `topicid` (`topicid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_tqid`
--

CREATE TABLE IF NOT EXISTS `qcs_tqid` (
  `topicname` varchar(64) NOT NULL,
  `topicid` int(11) unsigned NOT NULL,
  `questionid` int(11) unsigned NOT NULL,
  KEY `topicid` (`topicid`),
  KEY `questionid` (`questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `qcs_user`
--

CREATE TABLE IF NOT EXISTS `qcs_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(15) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pwd` char(32) NOT NULL,
  `regtime` int(11) unsigned NOT NULL,
  `newnotice` int(8) unsigned NOT NULL default '0',
  `newmsg` int(8) unsigned NOT NULL default '0',
  `score` int(11) unsigned NOT NULL default '0',
  `totalpub` int(11) unsigned NOT NULL,
  `totalreply` int(11) unsigned NOT NULL,
  `province` varchar(40) NOT NULL,
  `city` varchar(40) NOT NULL,
  `county` varchar(40) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `career` tinyint(4) NOT NULL,
  `tag` varchar(60) NOT NULL,
  `university` varchar(60) NOT NULL,
  `college` varchar(60) NOT NULL,
  `lovestate` tinyint(4) NOT NULL,
  `qqnumber` varchar(20) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `invitecode` char(20) NOT NULL,
  `invitecount` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `invitecode` (`invitecode`),
  KEY `email` (`email`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
