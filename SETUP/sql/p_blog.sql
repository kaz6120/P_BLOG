-- 
-- P_BLOG SQL
-- 
-- Updated: 2006-01-19 09:39:43
-- 
-- Database: `p_blog`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `p_anama`
-- 

CREATE TABLE `p_anama` (
  `id` int(11) NOT NULL auto_increment,
  `ref` text NOT NULL,
  `browser` varchar(255) NOT NULL default '',
  `re_host` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_anama`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_bin`
-- 

CREATE TABLE `p_bin` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `bin_title` varchar(100) NOT NULL default '',
  `bintype` varchar(60) NOT NULL default 'application/octet-stream',
  `binname` varchar(100) NOT NULL default '',
  `binsize` bigint(20) unsigned NOT NULL default '1024',
  `bindate` datetime NOT NULL default '0000-00-00 00:00:00',
  `bin_mod` timestamp(14) NOT NULL,
  `bin_category` varchar(50) NOT NULL default '',
  `bincomment` longtext NOT NULL,
  `bin_count` int(11) NOT NULL default '0',
  `draft` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_bin`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_bin_data`
-- 

CREATE TABLE `p_bin_data` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `masterid` int(11) unsigned NOT NULL default '0',
  `bindata` mediumblob NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `master_id` (`masterid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_bin_data`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_blog_log`
-- 

CREATE TABLE `p_blog_log` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `href` varchar(255) NOT NULL default '',
  `category` varchar(50) NOT NULL default '',
  `comment` longtext NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `mod` timestamp(14) NOT NULL,
  `draft` tinyint(4) NOT NULL default '0',
  `ping_uri` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_blog_log`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_blog_user`
-- 

CREATE TABLE `p_blog_user` (
  `user_id` smallint(3) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL default '',
  `user_pass` varchar(32) NOT NULL default '',
  `user_mail` varchar(50) NOT NULL default '',
  `user_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_blog_user`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_config`
-- 

CREATE TABLE `p_config` (
  `config_key` varchar(64) NOT NULL default '',
  `config_value` mediumtext NOT NULL,
  PRIMARY KEY  (`config_key`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `p_config`
-- 

INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('blog_title', 'My Great Log');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('sub_title', '- Built for the Enthusiasts');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('root_path', '/path/to/p_blog/');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('top_page', 'index.php');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('charset', 'utf-8');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('xml_version', '1.0');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('xml_lang', 'en');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('enable_unicode', 'on');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('mysql_lang', 'Latin1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('count_row_query', 'id');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('tz', '');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_date_title', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_date_time', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('date_style', '2');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('pagemax', '7');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('page_flip_style', '1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_rss', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('date_order_desc', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_categories', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('category_style', '1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_cat_num', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_pre_recent_menu', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('pre_recent_max', '5');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('display_log_uri', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('file_index_title', 'Files');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_2_indexes', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_file_date_title', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('max_listup', '2');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_bin_categories', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('bin_category_style', '1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_bin_cat_num', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_filetype', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('filetype_style', '1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_type_num', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_thumb_nail', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('thumb_nail_w', '90');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('thumb_nail_h', '90');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_img_size', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_md5', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_download_counter', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_css_switch', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('css_cookie_name', 'p_blog_style');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('css_cookie_time', '15724800');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('default_style', 'rich_green');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('footer_type', '1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('email', 'webmaster@example.com');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('email_title', 'Send Comments');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('copyright', 'Copyright &copy; 2006 Me');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_generation_time', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('sendmail_account_id', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_feedback_form', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_email_link', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_comment_link', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('comment_style', '2');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('topic_max', '12');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('trackback', 'on');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('ping_server_list', 'http://rpc.weblogs.com/,\r\nhttp://rpc.blogrolling.com/pinger/,\r\nhttp://api.my.yahoo.com/RPC2,\r\nhttp://bulkfeeds.net/rpc,\r\nhttp://www.blogpeople.net/servlet/weblogUpdates,\r\nhttp://ping.bloggers.jp/rpc/,\r\nhttp://ping.cocolog-nifty.com/xmlrpc/,\r\nhttp://blog.goo.ne.jp/XMLRPC,\r\nhttp://ping.myblog.jp/');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('uploaddir', '../resources/');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('up_img_max', '3');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_analyzer', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('referer_limit_num', '1');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('enable_del_logs', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('del_magic_words', 'flush!');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('gz_compress', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('sendmail_address', 'yourname@example.com');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('use_session_db', 'yes');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('p_blog_sess_name', 'pblogsession');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('p_blog_root_sess_name', 'pblogrootsession');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('debug_mode', 'off');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('custom_ahl_path', '');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_recent_comment', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('recent_comment_max', '5');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('show_recent_trackback', 'no');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('recent_trackback_max', '5');
INSERT INTO `p_config` (`config_key`, `config_value`) VALUES ('enable_smiley', 'no');

-- --------------------------------------------------------

-- 
-- Table structure for table `p_forum`
-- 

CREATE TABLE `p_forum` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `tid` int(8) unsigned NOT NULL default '0',
  `parent_key` int(8) NOT NULL default '1',
  `title` varchar(100) NOT NULL default '',
  `comment` longtext NOT NULL,
  `user_name` varchar(50) NOT NULL default '',
  `user_pass` varchar(32) NOT NULL default '',
  `user_mail` varchar(50) NOT NULL default '',
  `user_uri` varchar(255) NOT NULL default '',
  `user_ico_num` int(8) NOT NULL default '0',
  `color` int(8) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `mod` timestamp(14) NOT NULL,
  `user_ip` varchar(255) NOT NULL default '',
  `refer_id` int(11) NOT NULL default '0',
  `trash` int(8) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `master_id` (`tid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_forum`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_session`
-- 

CREATE TABLE `p_session` (
  `id` varchar(32) NOT NULL default '',
  `sess_var` text NOT NULL,
  `sess_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `p_session`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `p_trackback`
-- 

CREATE TABLE `p_trackback` (
  `id` int(11) NOT NULL auto_increment,
  `blog_id` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `excerpt` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `blog_id` (`blog_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `p_trackback`
-- 

