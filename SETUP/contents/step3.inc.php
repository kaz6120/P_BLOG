<?php
/**
 * Updated: 2006-01-19 09:40:18
 */

if (stristr($_SERVER['PHP_SELF'], '.inc.php')) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). '/index.php');
}

// Load user config
require_once '../include/user_config.inc.php';

// Load constants definitions
require_once './include/constants.inc.php';

// Load P_BLOG basic functions
require_once '../include/fnc_base.inc.php';

// Switch the language include file
if ((isset($_REQUEST['ex-lang'])) && ($_REQUEST['ex-lang'] == 'ja')) {
    require_once './lang/japanese.inc.php';
    $ex_lang = 'ja';
} else {
    require_once './lang/english.inc.php';
    $ex_lang = 'en';
}

// Connect to MySQL
mysql_connect($host, $user, $password) or die ("<h2>MySQL Connection Error</h2>\n<h3>Why?： " .mysql_error()."</h3>\n");

if (isset($_POST['install_type'], $_POST['root_path'], $_POST['default_lang'], $_POST['tz_offset'])) {
    $install_type = insert_safe($_POST['install_type']);
    $root_path    = insert_safe($_POST['root_path']);
    $default_lang = insert_safe($_POST['default_lang']);
    if ($default_lang == 'ja') {
        $mysql_internal_encode = 'EUC-JP';
    } else {
        $mysql_internal_encode = 'Latin1';
    }
    $tz_offset    = insert_safe($_POST['tz_offset']);

    // NOTE:
    // If you post the wrong root path, you won't be able to display the preferences.
    // If you post your root path as "/path/to/p_blog/", you can display it.
    // This means "/path/to/p_blog/" is safer than the wrong-root-path-posting.
    
    switch($install_type) {
        case 'upgrade':
            /////////////////////////////////// UPGRADE ///////////////////////////////////
            $title = $lang['upgrade'];
            // Select Database
            $sql = 'USE ' . $dbname;
            $res = mysql_query($sql);
            if ($res == FALSE) { // If database does not exists, create DB.
                $sql1 = 'CREATE DATABASE ' . $dbname;
                $res1 = mysql_query($sql1);
                if (!$res1) { // If DB creation fails...
                    $step1_res    = $lang['create_db'] . ' `' . $dbname . '`  --- <span class="important">No</span>.';
                    $step1_status = STATUS_RED;
                } else { // If DB creation success...
                    $step1_res    = $lang['create_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="ok">OK</span>.';
                    $step1_status = STATUS_GREEN;
                }
                // If DB created, then use it.
                $sql2 = 'USE ' . $dbname;
                $res2 = mysql_query($sql2);
                if (!$res2) {
                    $step2_res    = $lang['select_db'] . ' `' . $dbname . '`  --- <span class="important">No</span>.'.
                    $step2_status = STATUS_RED;
                } else {
                    $step2_res = $lang['select_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="ok">OK</span>.';
                    $step2_status = STATUS_GREEN;
                }
            } else {
                $step1_res    = $lang['create_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="important">Skipped</span>.';
                $step1_status = STATUS_YELLOW;
                $step2_res    = $lang['select_db'] . ' `<span class="ok">' . $dbname. '</span>`  --- <span class="important">Skipped</span>.';
                $step2_status = STATUS_YELLOW;
            }
            // ============================================================
            // Create the download counter field in file info table.
            // ============================================================
            $sql3 = 'ALTER TABLE `' . $info_table. '` ADD `bin_count` INT NOT NULL ;';
            $res3 = mysql_query($sql3);
            if (!$res3) {
                $step3_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_count</span>`  --- <span class="important">No</span>.';
                $step3_status = STATUS_RED;
            } else {
                $step3_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_count</span>`  --- <span class="ok">OK</span>.';
                $step3_status = STATUS_GREEN;
            }
            // ============================================================
            // Create modifed-date field in file info table.
            // ============================================================
            $sql4 = 'ALTER TABLE ' . $info_table . ' ADD `bin_mod` timestamp(14) AFTER `bindate`;';
            $res4 = mysql_query($sql4);
            if (!$res4) {
                $step4_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_mod</span>`  --- <span class="important">No</span>.';
                $step4_status = STATUS_RED;
            } else {
                $step4_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_mod</span>`  --- <span class="ok">OK</span>.';
                $step4_status = STATUS_GREEN;
            }
            // ============================================================
            // Create title field in file info table
            // ============================================================
            $sql5 = 'ALTER TABLE ' . $info_table . ' ADD `bin_title` VARCHAR (100)NOT NULL AFTER `id`;';
            $res5 = mysql_query($sql5);
            if (!$res5) {
                $step5_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_title</span>`  --- <span class="important">No</span>.';
                $step5_status = STATUS_RED;
            } else {
                $step5_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_title</span>`  --- <span class="ok">OK</span>.';
                $step5_status = STATUS_GREEN;
            }
            // ============================================================
            // Create category field in file info table
            // ============================================================
            $sql6 = 'ALTER TABLE ' . $info_table . ' ADD `bin_category` VARCHAR (50)NOT NULL AFTER `bin_mod`;';
            $res6 = mysql_query($sql6);
            if (!$res6) {
                $step6_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_category</span>`  --- <span class="important">No</span>.';
                $step6_status = STATUS_RED;
            } else {
                $step6_res    = $info_table . $lang['create_field'] . ' `<span class="ok">bin_category</span>`  --- <span class="ok">OK</span>.';
                $step6_status = STATUS_GREEN;
            }
            // ============================================================
            // Create Users Table
            // ============================================================
            $sql7 = "
CREATE TABLE `{$user_table}` (
  `user_id` smallint(3) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL default '',
  `user_pass` varchar(32) NOT NULL default '',
  `user_mail` varchar(50) NOT NULL default '',
  `user_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY (`user_name`)
)";
            $res7 = mysql_query($sql7);
            if (!$res7) {
                $step7_res    = $lang['create_table'] . ' `<span class="ok">' . $user_table . '</span>`  --- <span class="important">No</span>.';
                $step7_status = STATUS_RED;
            } else {
                $step7_res    = $lang['create_table'] . ' `<span class="ok">' . $user_table . '</span>`  --- <span class="ok">OK</span>.';
                $step7_status = STATUS_GREEN;
            }
            // ================================================
            // Create Session Table
            // ================================================
            $sql8 = "
CREATE TABLE `{$session_table}` (
   `id` VARCHAR(32) NOT NULL,
   `sess_var` TEXT NOT NULL,
   `sess_date` INT(11) NOT NULL,
   PRIMARY KEY (id)
)";
            $res8 = mysql_query($sql8);
            if (!$res8) {
                $step8_res    = $lang['create_table'] . ' `<span class="ok">' . $session_table . '</span>`  --- <span class="important">No</span>.';
                $step8_status = STATUS_RED;
            } else {
                $step8_res    = $lang['create_table'] . ' `<span class="ok">' . $session_table . '</span>`  --- <span class="ok">OK</span>.';
                $step8_status = STATUS_GREEN;
            }
            // ================================================
            // Create the draft field in log table.
            // ================================================
            $sql9 = 'ALTER TABLE `' . $log_table . '` ADD `draft` TINYINT NOT NULL ;';
            $res9 = mysql_query($sql9);
            if (!$res9) {
                $step9_res    = $log_table . $lang['create_field'] . ' `<span class="ok">draft</span>`  --- <span class="important">No</span>.';
                $step9_status = STATUS_RED;
            } else {
                $step9_res    = $log_table . $lang['create_field'] . ' `<span class="ok">draft</span>`  --- <span class="ok">OK</span>.';
                $step9_status = STATUS_GREEN;
            }
            // ================================================
            // Create the draft field in file info table.
            // ================================================
            $sql10 = 'ALTER TABLE `' . $info_table . '` ADD `draft` TINYINT NOT NULL ;';
            $res10 = mysql_query($sql10);
            if (!$res10) {
                $step10_res    = $info_table . $lang['create_field'] . ' `<span class="ok">draft</span>`  --- <span class="important">No</span>.';
                $step10_status = STATUS_RED;
            } else {
                $step10_res    = $info_table . $lang['create_field'] . ' `<span class="ok">draft</span>`  --- <span class="ok">OK</span>.';
                $step10_status = STATUS_GREEN;
            }
            // ================================================
            // Create Comment / Forum Table
            // ================================================
            $sql11 = "
CREATE TABLE `{$forum_table}` (
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
) TYPE = MYISAM
";            
            $res11 = mysql_query($sql11);
            if (!$res11) {
                $step11_res    = $lang['create_table'] . ' `<span class="ok">{$forum_table}</span>`  --- <span class="important">No</span>.';
                $step11_status = STATUS_RED;
            } else {
                $step11_res    = $lang['create_table'] . ' `<span class="ok">{$forum_table}</span>`  --- <span class="ok">OK</span>.';
                $step11_status = STATUS_GREEN;
            }
            // ================================================
            // Create Trackback Table
            // ================================================
            $sql12 = "
CREATE TABLE `{$trackback_table}` (
  `id` int(11) NOT NULL auto_increment,
  `blog_id` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `excerpt` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `blog_id` (`blog_id`)
) TYPE = MYISAM
";            
            $res12 = mysql_query($sql12);
            if (!$res12) {
                $step12_res    = $lang['create_table'] . ' `<span class="ok">' . $trackback_table .'</span>`  --- <span class="important">No</span>.';
                $step12_status = STATUS_RED;
            } else {
                $step12_res    = $lang['create_table'] . ' `<span class="ok">' . $trackback_table . '</span>`  --- <span class="ok">OK</span>.';
                $step12_status = STATUS_GREEN;
            }
            // ================================================
            // Create the ping_uri field in log table.
            // ================================================
            $sql13 = 'ALTER TABLE `' . $log_table . '` ADD `ping_uri` TEXT NOT NULL ;';
            $res13 = mysql_query($sql13);
            if (!$res13) {
                $step13_res    = $log_table . $lang['create_field'] . ' `<span class="ok">ping_uri</span>`  --- <span class="important">No</span>.';
                $step13_status = STATUS_RED;
            } else {
                $step13_res    = $log_table . $lang['create_field'] . ' `<span class="ok">ping_uri</span>`  --- <span class="ok">OK</span>.';
                $step13_status = STATUS_GREEN;
            }
            // ================================================
            // Create Config Table
            // ================================================
            $sql14 = "
CREATE TABLE `{$config_table}` (
  `config_key` varchar(64) NOT NULL default '',
  `config_value` mediumtext NOT NULL,
  PRIMARY KEY  (`config_key`)
) TYPE = MYISAM
";            
            $res14 = mysql_query($sql14);
            if (!$res14) {
                $step14_res    = $lang['create_table'] . ' `<span class="ok">' . $config_table .'</span>`  --- <span class="important">No</span>.';
                $step14_status = STATUS_RED;
            } else {
                $step14_res    = $lang['create_table'] . ' `<span class="ok">' . $config_table . '</span>`  --- <span class="ok">OK</span>.';
                $step14_status = STATUS_GREEN;
            }
            // ================================================
            // Insert Default Values into Config Table
            // ================================================
            $sql15 = "
INSERT INTO `{$config_table}` (`config_key`, `config_value`) VALUES 
('blog_title', 'My Great Log'),
('sub_title', '- Built for the Enthusiasts'),
('root_path', '{$root_path}'),
('top_page', 'index.php'),
('charset', 'utf-8'),
('xml_version', '1.0'),
('xml_lang', '{$default_lang}'),
('enable_unicode', 'on'),
('mysql_lang', '{$mysql_internal_encode}'),
('count_row_query', 'id'),
('tz', '{$tz_offset}'),
('show_date_title', 'yes'),
('show_date_time', 'yes'),
('date_style', '2'),
('pagemax', '7'),
('page_flip_style', '1'),
('use_rss', 'yes'),
('date_order_desc', 'yes'),
('show_categories', 'yes'),
('category_style', '1'),
('show_cat_num', 'yes'),
('show_pre_recent_menu', 'no'),
('pre_recent_max', '5'),
('display_log_uri', 'no'),
('file_index_title', 'Files'),
('use_2_indexes', 'yes'),
('show_file_date_title', 'yes'),
('max_listup', '2'),
('show_bin_categories', 'yes'),
('bin_category_style', '1'),
('show_bin_cat_num', 'yes'),
('show_filetype', 'no'),
('filetype_style', '1'),
('show_type_num', 'yes'),
('show_thumb_nail', 'yes'),
('thumb_nail_w', '90'),
('thumb_nail_h', '90'),
('show_img_size', 'yes'),
('show_md5', 'yes'),
('use_download_counter', 'yes'),
('use_css_switch', 'yes'),
('css_cookie_name', 'p_blog_style'),
('css_cookie_time', '15724800'),
('default_style', 'rich_green'),
('footer_type', '1'),
('email', 'webmaster@example.com'),
('email_title', 'Send Comments'),
('copyright', 'Copyright &copy\; Me'),
('show_generation_time', 'yes'),
('sendmail_account_id', 'no'),
('use_feedback_form', 'yes'),
('use_email_link', 'no'),
('use_comment_link', 'yes'),
('comment_style', '2'),
('topic_max', '12'),
('trackback', 'on'),
('ping_server_list', 'http://rpc.weblogs.com/,\r\nhttp://rpc.blogrolling.com/pinger/,\r\nhttp://api.my.yahoo.com/RPC2,\r\nhttp://bulkfeeds.net/rpc,\r\nhttp://www.blogpeople.net/servlet/weblogUpdates,\r\nhttp://ping.bloggers.jp/rpc/,\r\nhttp://ping.cocolog-nifty.com/xmlrpc/,\r\nhttp://blog.goo.ne.jp/XMLRPC,\r\nhttp://ping.myblog.jp/'),
('uploaddir', '../resources/'),
('up_img_max', '3'),
('use_analyzer', 'yes'),
('referer_limit_num', '1'),
('enable_del_logs', 'yes'),
('del_magic_words', 'flush!'),
('gz_compress', 'yes'),
('sendmail_address', 'yourname@example.com'),
('use_session_db', 'yes'),
('p_blog_sess_name', 'pblogsession'),
('p_blog_root_sess_name', 'pblogrootsession'),
('debug_mode', 'off'),
('custom_ahl_path', '')
";            
            $res15 = mysql_query($sql15);
            if (!$res15) {
                $step15_res    = $lang['installed_defaults'] . ' `<span class="ok">' . $config_table .'</span>`  --- <span class="important">No</span>.';
                $step15_status = STATUS_RED;
            } else {
                $step15_res    = $lang['installed_defaults'] . ' `<span class="ok">' . $config_table . '</span>`  --- <span class="ok">OK</span>.';
                $step15_status = STATUS_GREEN;
            }
            
            $sql16 = "
INSERT INTO `{$config_table}` ( `config_key` , `config_value` ) VALUES 
('show_recent_comment', 'no'), 
('recent_comment_max', '5'),
('show_recent_trackback', 'no'),
('recent_trackback_max', '5'),
('enable_smiley', 'no');
";
            $res16 = mysql_query($sql16);
            if (!$res16) {
                $step16_res    = $lang['installed_defaults'] . ' `<span class="ok">' . $config_table .'</span>`  --- <span class="important">No</span>.';
                $step16_status = STATUS_RED;
            } else {
                $step16_res    = $lang['installed_defaults'] . ' `<span class="ok">' . $config_table . '</span>`  --- <span class="ok">OK</span>.';
                $step16_status = STATUS_GREEN;
            }

            //　Include template file for presentation
            include_once './contents/step3_upgrade.tpl.php';
            break;
        default:
            /////////////////////////////////// INSTALL ///////////////////////////////////
            $title = $lang['install'];
            // Select Database
            $sql = 'USE ' . $dbname;
            $res = mysql_query($sql);
            if ($res == FALSE) { // If database does not exists, create DB.
                $sql1 = 'CREATE DATABASE ' . $dbname;
                $res1 = mysql_query($sql1);
                if (!$res1) { // If DB creation fails...
                    $step1_res    = $lang['create_db'] . ' `' . $dbname . '`  --- <span class="important">No</span>.';
                    $step1_status = STATUS_RED;
                } else { // If DB creation success...
                    $step1_res    = $lang['create_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="ok">OK</span>.';
                    $step1_status = STATUS_GREEN;
                }
                // If DB created, then use it.
                $sql2 = 'USE ' . $dbname;
                $res2 = mysql_query($sql2);
                if (!$res2) {
                    $step2_res    = $lang['select_db'] . ' `' . $dbname . '`  --- <span class="important">No</span>.'.
                    $step2_status = STATUS_RED;
                } else {
                    $step2_res = $lang['select_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="ok">OK</span>.';
                    $step2_status = STATUS_GREEN;
                }
            } else {
                // If old database is found, drop & initialize it. Then create a new database.
                /*
                $drop_sql = 'DROP DATABASE `' . $dbname . '`';
                $drop_res = mysql_query($drop_sql);
                if ($drop_res) {
                    $sql1 = 'CREATE DATABASE ' . $dbname;
                    $res1 = mysql_query($sql1);
                    if (!$res1) { // If DB creation fails...
                        $step1_res    = $lang['create_db'] . ' `' . $dbname . '`  --- <span class="important">No</span>.';
                        $step1_status = STATUS_RED;
                    } else { // If DB creation success...
                        $step1_res    = $lang['create_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="ok">OK</span>.';
                        $step1_status = STATUS_GREEN;
                    }
                    // If DB created, then use it.
                    $sql2 = 'USE ' . $dbname;
                    $res2 = mysql_query($sql2);
                    if (!$res2) {
                        $step2_res    = $lang['select_db'] . ' `' . $dbname . '`  --- <span class="important">No</span>.'.
                        $step2_status = STATUS_RED;
                    } else {
                        $step2_res = $lang['select_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="ok">OK</span>.';
                        $step2_status = STATUS_GREEN;
                    }
                } else {
                    $step1_res    = $lang['create_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="important">Skipped</span>.';
                    $step1_status = STATUS_YELLOW;
                    $step2_res    = $lang['select_db'] . ' `<span class="ok">' . $dbname. '</span>`  --- <span class="important">Skipped</span>.';
                    $step2_status = STATUS_YELLOW;
                }
                */
                
                // No initializing install. This one is safer than the above, but cannot re-install.
                $step1_res    = $lang['create_db'] . ' `<span class="ok">' . $dbname . '</span>`  --- <span class="important">Skipped</span>.';
                $step1_status = STATUS_YELLOW;
                $step2_res    = $lang['select_db'] . ' `<span class="ok">' . $dbname. '</span>`  --- <span class="important">Skipped</span>.';
                $step2_status = STATUS_YELLOW;
                
            }
            // ================================================
            // Create Log table SQL
            // ================================================
            $sql3 = "
CREATE TABLE `{$log_table}` (
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
)";
            $res3 = mysql_query($sql3);
            if (!$res3) {
                $step3_res    = $lang['create_table'] . ' `<span class="ok">' . $log_table. '</span>`  --- <span class="important">No</span>.';
                $step3_status = STATUS_RED;
            } else {
                $step3_res    = $lang['create_table'] . ' `<span class="ok">' . $log_table . '</span>`  --- <span class="ok">OK</span>.';
                $step3_status = STATUS_GREEN;
            }
            // ================================================
            // Create File Info Table
            // ================================================
            $sql4 = "
CREATE TABLE `{$info_table}` (
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
)";
            $res4 = mysql_query($sql4);
            if (!$res4) {
                $step4_res    = $lang['create_table'] . ' `<span class="ok">' . $info_table. '</span>`  --- <span class="important">No</span>.';
                $step4_status = STATUS_RED;
            } else {
                $step4_res    = $lang['create_table'] . ' `<span class="ok">' . $info_table . '</span>`  --- <span class="ok">OK</span>.';
                $step4_status = STATUS_GREEN;
            }
            // ================================================
            // Create Data Table
            // ================================================
            $sql5 = "
CREATE TABLE `{$data_table}` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `masterid` int(11) unsigned NOT NULL default '0',
  `bindata` mediumblob NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `master_id` (`masterid`)
)";
            $res5 = mysql_query($sql5);
            if (!$res5) {
                $step5_res    = $lang['create_table'] . ' `<span class="ok">' . $data_table. '</span>`  --- <span class="important">No</span>.';
                $step5_status = STATUS_RED;
            } else {
                $step5_res    = $lang['create_table'] . ' `<span class="ok">' . $data_table . '</span>`  --- <span class="ok">OK</span>.';
                $step5_status = STATUS_GREEN;
            }
            // ================================================
            // Create Analyzer Table
            // ================================================
            $sql6 = "
CREATE TABLE `{$analyze_table}` (
  `id` int(11) NOT NULL auto_increment,
  `ref` text NOT NULL,
  `browser` varchar(255) NOT NULL default '',
  `re_host` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)   
)";

            $res6 = mysql_query($sql6);
            if (!$res6) {
                $step6_res    = $lang['create_table'] . ' `<span class="ok">' . $analyze_table . '</span>`  --- <span class="important">No</span>.';
                $step6_status = STATUS_RED;
            } else {
                $step6_res    = $lang['create_table'] . ' `<span class="ok">' . $analyze_table . '</span>`  --- <span class="ok">OK</span>.';
                $step6_status = STATUS_GREEN;
            }
            // ================================================
            // Create Users Table
            // ================================================
            $sql7 = "
CREATE TABLE `{$user_table}` (
  `user_id` smallint(3) NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL default '',
  `user_pass` varchar(32) NOT NULL default '',
  `user_mail` varchar(50) NOT NULL default '',
  `user_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY (`user_name`)
)";
            $res7 = mysql_query($sql7);
            if (!$res7) {
                $step7_res    = $lang['create_table'] . ' `<span class="ok">' . $user_table . '</span>`  --- <span class="important">No</span>.';
                $step7_status = STATUS_RED;
            } else {
                $step7_res    = $lang['create_table'] . ' `<span class="ok">' . $user_table . '</span>`  --- <span class="ok">OK</span>.';
                $step7_status = STATUS_GREEN;
            }
            // ================================================
            // Create Session Table
            // ================================================
            $sql8 = "
CREATE TABLE `{$session_table}` (
   `id` VARCHAR(32) NOT NULL,
   `sess_var` TEXT NOT NULL,
   `sess_date` INT(11) NOT NULL,
   PRIMARY KEY (id)
)";
            $res8 = mysql_query($sql8);
            if (!$res8) {
                $step8_res    = $lang['create_table'] . ' `<span class="ok">' . $session_table . '</span>`  --- <span class="important">No</span>.';
                $step8_status = STATUS_RED;
            } else {
                $step8_res    = $lang['create_table'] . ' `<span class="ok">' . $session_table . '</span>`  --- <span class="ok">OK</span>.';
                $step8_status = STATUS_GREEN;
            }
            // ================================================
            // Create Comment / Forum Table
            // ================================================
            $sql9 = "
CREATE TABLE `{$forum_table}` (
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
) TYPE = MYISAM
";            
            $res9 = mysql_query($sql9);
            if (!$res9) {
                $step9_res    = $lang['create_table'] . ' `<span class="ok">'.$forum_table.'</span>`  --- <span class="important">No</span>.';
                $step9_status = STATUS_RED;
            } else {
                $step9_res    = $lang['create_table'] . ' `<span class="ok">'.$forum_table.'</span>`  --- <span class="ok">OK</span>.';
                $step9_status = STATUS_GREEN;
            }
            // ================================================
            // Create Trackback Table
            // ================================================
            $sql10 = "
CREATE TABLE `{$trackback_table}` (
  `id` int(11) NOT NULL auto_increment,
  `blog_id` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `excerpt` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `blog_id` (`blog_id`)
) TYPE = MYISAM
";            
            $res10 = mysql_query($sql10);
            if (!$res10) {
                $step10_res    = $lang['create_table'] . ' `<span class="ok">' . $trackback_table .'</span>`  --- <span class="important">No</span>.';
                $step10_status = STATUS_RED;
            } else {
                $step10_res    = $lang['create_table'] . ' `<span class="ok">' . $trackback_table . '</span>`  --- <span class="ok">OK</span>.';
                $step10_status = STATUS_GREEN;
            }
            // ================================================
            // Create Config Table
            // ================================================
            $sql11 = "
CREATE TABLE `{$config_table}` (
  `config_key` varchar(64) NOT NULL default '',
  `config_value` mediumtext NOT NULL,
  PRIMARY KEY  (`config_key`)
) TYPE = MYISAM
";            
            $res11 = mysql_query($sql11);
            if (!$res11) {
                $step11_res    = $lang['create_table'] . ' `<span class="ok">' . $config_table .'</span>`  --- <span class="important">No</span>.';
                $step11_status = STATUS_RED;
            } else {
                $step11_res    = $lang['create_table'] . ' `<span class="ok">' . $config_table . '</span>`  --- <span class="ok">OK</span>.';
                $step11_status = STATUS_GREEN;
            }
            // ================================================
            // Insert Default Values into Config Table
            // ================================================
            $sql12 = "
INSERT INTO `{$config_table}` (`config_key`, `config_value`) VALUES 
('blog_title', 'My Great Log'),
('sub_title', '- Built for the Enthusiasts'),
('root_path', '{$root_path}'),
('top_page', 'index.php'),
('charset', 'utf-8'),
('xml_version', '1.0'),
('xml_lang', '{$default_lang}'),
('enable_unicode', 'on'),
('mysql_lang', '{$mysql_internal_encode}'),
('count_row_query', 'id'),
('tz', '{$tz_offset}'),
('show_date_title', 'yes'),
('show_date_time', 'yes'),
('date_style', '2'),
('pagemax', '7'),
('page_flip_style', '1'),
('use_rss', 'yes'),
('date_order_desc', 'yes'),
('show_categories', 'yes'),
('category_style', '1'),
('show_cat_num', 'yes'),
('show_pre_recent_menu', 'no'),
('pre_recent_max', '5'),
('display_log_uri', 'no'),
('file_index_title', 'Files'),
('use_2_indexes', 'yes'),
('show_file_date_title', 'yes'),
('max_listup', '2'),
('show_bin_categories', 'yes'),
('bin_category_style', '1'),
('show_bin_cat_num', 'yes'),
('show_filetype', 'no'),
('filetype_style', '1'),
('show_type_num', 'yes'),
('show_thumb_nail', 'yes'),
('thumb_nail_w', '90'),
('thumb_nail_h', '90'),
('show_img_size', 'yes'),
('show_md5', 'yes'),
('use_download_counter', 'yes'),
('use_css_switch', 'yes'),
('css_cookie_name', 'p_blog_style'),
('css_cookie_time', '15724800'),
('default_style', 'rich_green'),
('footer_type', '1'),
('email', 'webmaster@example.com'),
('email_title', 'Send Comments'),
('copyright', 'Copyright &copy\; 2006 Me'),
('show_generation_time', 'yes'),
('sendmail_account_id', 'no'),
('use_feedback_form', 'yes'),
('use_email_link', 'no'),
('use_comment_link', 'yes'),
('comment_style', '2'),
('topic_max', '12'),
('trackback', 'on'),
('ping_server_list', 'http://rpc.weblogs.com/,\r\nhttp://rpc.blogrolling.com/pinger/,\r\nhttp://api.my.yahoo.com/RPC2,\r\nhttp://bulkfeeds.net/rpc,\r\nhttp://www.blogpeople.net/servlet/weblogUpdates,\r\nhttp://ping.bloggers.jp/rpc/,\r\nhttp://ping.cocolog-nifty.com/xmlrpc/,\r\nhttp://blog.goo.ne.jp/XMLRPC,\r\nhttp://ping.myblog.jp/'),
('uploaddir', '../resources/'),
('up_img_max', '3'),
('use_analyzer', 'yes'),
('referer_limit_num', '1'),
('enable_del_logs', 'yes'),
('del_magic_words', 'flush!'),
('gz_compress', 'yes'),
('sendmail_address', 'yourname@example.com'),
('use_session_db', 'yes'),
('p_blog_sess_name', 'pblogsession'),
('p_blog_root_sess_name', 'pblogrootsession'),
('debug_mode', 'off'),
('custom_ahl_path', ''),
('show_recent_comment', 'no'), 
('recent_comment_max', '5'),
('show_recent_trackback', 'no'),
('recent_trackback_max', '5'),
('enable_smiley', 'no')
";            
            $res12 = mysql_query($sql12);
            if (!$res12) {
                $step12_res    = $lang['installed_defaults'] . ' `<span class="ok">' . $config_table .'</span>`  --- <span class="important">No</span>.';
                $step12_status = STATUS_RED;
            } else {
                $step12_res    = $lang['installed_defaults'] . ' `<span class="ok">' . $config_table . '</span>`  --- <span class="ok">OK</span>.';
                $step12_status = STATUS_GREEN;
            }
            
            //　Include template file for presentation
            include_once './contents/step3_install.tpl.php';
            break;
    }
} else { // if accessed directly...
    echo <<<EOD
<h2><img src="./contents/resources/step3.png" width="37" height="37" alt="STEP-3" /> STEP 3</h2>
<h3>Oops.</h3>
<form method="post" action="./index.php?id=step2">
<p class="submit-button">
<input type="submit" value="&#171; {$lang['back_to_step2']}" />
</p>
</form>
EOD;
}
/////////////////////////// END OF PHP /////////////////////////////////
?>