<?php
/**
 * Update Draft Log
 *
 * $Id: admin/draft_updated.php, 2005/11/13 17:44:10 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['blog_title'],
              $_POST['sub_title'],
              $_POST['root_path'],
              $_POST['top_page'],
              $_POST['charset'],
              $_POST['xml_version'],
              $_POST['xml_lang'],
              $_POST['enable_unicode'],
              $_POST['mysql_lang'],
              $_POST['count_row_query'],
              $_POST['tz'],
              $_POST['show_date_title'],
              $_POST['show_date_time'],
              $_POST['date_style'],
              $_POST['pagemax'],
              $_POST['page_flip_style'],
              $_POST['use_rss'],
              $_POST['enable_smiley'],
              $_POST['date_order_desc'],
              $_POST['show_categories'],
              $_POST['category_style'],
              $_POST['show_cat_num'],
              $_POST['show_pre_recent_menu'],
              $_POST['pre_recent_max'],
              $_POST['file_index_title'],
              $_POST['use_2_indexes'],
              $_POST['show_file_date_title'],
              $_POST['max_listup'],
              $_POST['show_bin_categories'],
              $_POST['bin_category_style'],
              $_POST['show_bin_cat_num'],
              $_POST['show_filetype'],
              $_POST['filetype_style'],
              $_POST['show_type_num'],
              $_POST['show_thumb_nail'],
              $_POST['thumb_nail_w'],
              $_POST['thumb_nail_h'],
              $_POST['show_img_size'],
              $_POST['show_md5'],
              $_POST['use_download_counter'],
              $_POST['use_css_switch'],
              $_POST['css_cookie_name'],
              $_POST['css_cookie_time'],
              $_POST['default_style'],
              $_POST['footer_type'],
              $_POST['email'],
              $_POST['email_title'],
              $_POST['copyright'],
              $_POST['show_generation_time'],
              $_POST['sendmail_address'],
              $_POST['use_feedback_form'],
              $_POST['use_email_link'],
              $_POST['use_comment_link'],
              $_POST['comment_style'],
              $_POST['topic_max'],
              $_POST['show_recent_comment'],
              $_POST['recent_comment_max'],
              $_POST['trackback'],
              $_POST['ping_server_list'],
              $_POST['show_recent_trackback'],
              $_POST['recent_trackback_max'],              
              $_POST['uploaddir'],
              $_POST['up_img_max'],
              $_POST['use_analyzer'],
              $_POST['referer_limit_num'],
              $_POST['enable_del_logs'],
              $_POST['del_magic_words'],
              $_POST['gz_compress'],
              $_POST['sendmail_account_id'],
              $_POST['use_session_db'],
              $_POST['p_blog_sess_name'],
              $_POST['p_blog_root_sess_name'],
              $_POST['debug_mode'],
              $_POST['custom_ahl_path'])) {

         $blog_title            = insert_safe($_POST['blog_title']);
         $sub_title             = insert_safe($_POST['sub_title']);
         $root_path             = insert_safe($_POST['root_path']);
         $top_page              = insert_safe($_POST['top_page']);
         $xml_version           = insert_safe($_POST['xml_version']);
         $charset               = insert_safe($_POST['charset']);
         $xml_lang              = insert_safe($_POST['xml_lang']);
         $enable_unicode        = insert_safe($_POST['enable_unicode']);
         $mysql_lang            = insert_safe($_POST['mysql_lang']);
         $count_row_query       = insert_safe($_POST['count_row_query']);
         $tz                    = insert_safe($_POST['tz']);
         $show_date_title       = insert_safe($_POST['show_date_title']);
         $show_date_time        = insert_safe($_POST['show_date_time']);
         $date_style            = insert_safe($_POST['date_style']);
         $pagemax               = insert_safe($_POST['pagemax']);
         $page_flip_style       = insert_safe($_POST['page_flip_style']);
         $use_rss               = insert_safe($_POST['use_rss']);
         $enable_smiley         = insert_safe($_POST['enable_smiley']);
         $date_order_desc       = insert_safe($_POST['date_order_desc']);
         $show_categories       = insert_safe($_POST['show_categories']);
         $category_style        = insert_safe($_POST['category_style']);
         $show_cat_num          = insert_safe($_POST['show_cat_num']);
         $show_pre_recent_menu  = insert_safe($_POST['show_pre_recent_menu']);
         $pre_recent_max        = insert_safe($_POST['pre_recent_max']);
         $file_index_title      = insert_safe($_POST['file_index_title']);
         $use_2_indexes         = insert_safe($_POST['use_2_indexes']);
         $show_file_date_title  = insert_safe($_POST['show_file_date_title']);
         $max_listup            = insert_safe($_POST['max_listup']);
         $show_bin_categories   = insert_safe($_POST['show_bin_categories']);
         $bin_category_style    = insert_safe($_POST['bin_category_style']);
         $show_bin_cat_num      = insert_safe($_POST['show_bin_cat_num']);
         $show_filetype         = insert_safe($_POST['show_filetype']);
         $filetype_style        = insert_safe($_POST['filetype_style']);
         $show_type_num         = insert_safe($_POST['show_type_num']);
         $show_thumb_nail       = insert_safe($_POST['show_thumb_nail']);
         $thumb_nail_w          = insert_safe($_POST['thumb_nail_w']);
         $thumb_nail_h          = insert_safe($_POST['thumb_nail_h']);
         $show_img_size         = insert_safe($_POST['show_img_size']);
         $show_md5              = insert_safe($_POST['show_md5']);
         $use_download_counter  = insert_safe($_POST['use_download_counter']);
         $use_css_switch        = insert_safe($_POST['use_css_switch']);
         $css_cookie_name       = insert_safe($_POST['css_cookie_name']);
         $css_cookie_time       = insert_safe($_POST['css_cookie_time']);
         $default_style         = insert_safe($_POST['default_style']);
         $footer_type           = insert_safe($_POST['footer_type']);
         $email                 = insert_safe($_POST['email']);
         $email_title           = insert_safe($_POST['email_title']);
         $copyright             = insert_safe($_POST['copyright']);
         $show_generation_time  = insert_safe($_POST['show_generation_time']);
         $sendmail_address      = insert_safe($_POST['sendmail_address']);
         $use_feedback_form     = insert_safe($_POST['use_feedback_form']); 
         $use_email_link        = insert_safe($_POST['use_email_link']);
         $use_comment_link      = insert_safe($_POST['use_comment_link']);
         $comment_style         = insert_safe($_POST['comment_style']);
         $topic_max             = insert_safe($_POST['topic_max']);
         $show_recent_comment   = insert_safe($_POST['show_recent_comment']);
         $recent_comment_max    = insert_safe($_POST['recent_comment_max']);
         $trackback             = insert_safe($_POST['trackback']);
         $ping_server_list      = insert_safe($_POST['ping_server_list']);
         $show_recent_trackback = insert_safe($_POST['show_recent_trackback']);
         $recent_trackback_max  = insert_safe($_POST['recent_trackback_max']);
         $uploaddir             = insert_safe($_POST['uploaddir']);
         $up_img_max            = insert_safe($_POST['up_img_max']);
         $use_analyzer          = insert_safe($_POST['use_analyzer']);
         $referer_limit_num     = insert_safe($_POST['referer_limit_num']);
         $enable_del_logs       = insert_safe($_POST['enable_del_logs']);
         $del_magic_words       = insert_safe($_POST['del_magic_words']);
         $gz_compress           = insert_safe($_POST['gz_compress']);
         $sendmail_account_id   = insert_safe($_POST['sendmail_account_id']);
         $use_session_db        = insert_safe($_POST['use_session_db']);
         $p_blog_sess_name      = insert_safe($_POST['p_blog_sess_name']);
         $p_blog_root_sess_name = insert_safe($_POST['p_blog_root_sess_name']);
         $debug_mode            = insert_safe($_POST['debug_mode']);
         $custom_ahl_path       = insert_safe($_POST['custom_ahl_path']);
         
         if ($enable_unicode == 'on'){
             if (!extension_loaded('mbstring')) {
                 include_once $cd . '/include/mb_emulator/mb-emulator.php';
                 mb_convert_variables($mysql_lang, 'auto', $blog_title, $sub_title, $file_index_title, $email_title, $copyright);
             } else {
                 mb_convert_variables($mysql_lang, 'auto', $blog_title, $sub_title, $file_index_title, $email_title, $copyright);
             }
         }
        
         $sql_val = array(
                'blog_title'            => "{$blog_title}",
                'sub_title'             => "{$sub_title}",
                'root_path'             => "{$root_path}",
                'top_page'              => "{$top_page}",
                'xml_version'           => "{$xml_version}",
                'charset'               => "{$charset}",
                'xml_lang'              => "{$xml_lang}",
                'enable_unicode'        => "{$enable_unicode}",
                'mysql_lang'            => "{$mysql_lang}",
                'count_row_query'       => "{$count_row_query}",
                'tz'                    => "{$tz}",
                'show_date_title'       => "{$show_date_title}",
                'show_date_time'        => "{$show_date_time}",
                'date_style'            => "{$date_style}",
                'pagemax'               => "{$pagemax}",
                'page_flip_style'       => "{$page_flip_style}",
                'use_rss'               => "{$use_rss}",
                'enable_smiley'         => "{$enable_smiley}",
                'date_order_desc'       => "{$date_order_desc}",
                'show_categories'       => "{$show_categories}",
                'category_style'        => "{$category_style}",
                'show_cat_num'          => "{$show_cat_num}",
                'show_pre_recent_menu'  => "{$show_pre_recent_menu}",
                'pre_recent_max'        => "{$pre_recent_max}",
                'file_index_title'      => "{$file_index_title}",
                'use_2_indexes'         => "{$use_2_indexes}",
                'show_file_date_title'  => "{$show_file_date_title}",
                'max_listup'            => "{$max_listup}",
                'show_bin_categories'   => "{$show_bin_categories}",
                'bin_category_style'    => "{$bin_category_style}",
                'show_bin_cat_num'      => "{$show_bin_cat_num}",
                'show_filetype'         => "{$show_filetype}",
                'filetype_style'        => "{$filetype_style}",
                'show_type_num'         => "{$show_type_num}",
                'show_thumb_nail'       => "{$show_thumb_nail}",
                'thumb_nail_w'          => "{$thumb_nail_w}",
                'thumb_nail_h'          => "{$thumb_nail_h}",
                'show_img_size'         => "{$show_img_size}",
                'show_md5'              => "{$show_md5}",
                'use_download_counter'  => "{$use_download_counter}",
                'use_css_switch'        => "{$use_css_switch}",
                'css_cookie_name'       => "{$css_cookie_name}",
                'css_cookie_time'       => "{$css_cookie_time}",
                'default_style'         => "{$default_style}",
                'footer_type'           => "{$footer_type}",
                'email'                 => "{$email}",
                'email_title'           => "{$email_title}",
                'copyright'             => "{$copyright}",
                'show_generation_time'  => "{$show_generation_time}",
                'sendmail_address'      => "{$sendmail_address}",
                'use_feedback_form'     => "{$use_feedback_form}", 
                'use_email_link'        => "{$use_email_link}",
                'use_comment_link'      => "{$use_comment_link}",
                'comment_style'         => "{$comment_style}",
                'topic_max'             => "{$topic_max}",
                'show_recent_comment'   => "{$show_recent_comment}",
                'recent_comment_max'    => "{$recent_comment_max}",
                'trackback'             => "{$trackback}",
                'ping_server_list'      => "{$ping_server_list}",
                'show_recent_trackback' => "{$show_recent_trackback}",
                'recent_trackback_max'  => "{$recent_trackback_max}",
                'uploaddir'             => "{$uploaddir}",
                'up_img_max'            => "{$up_img_max}",
                'use_analyzer'          => "{$use_analyzer}",
                'referer_limit_num'     => "{$referer_limit_num}",
                'enable_del_logs'       => "{$enable_del_logs}",
                'del_magic_words'       => "{$del_magic_words}",
                'gz_compress'           => "{$gz_compress}",
                'sendmail_account_id'   => "{$sendmail_account_id}",
                'use_session_db'        => "{$use_session_db}",
                'p_blog_sess_name'      => "{$p_blog_sess_name}",
                'p_blog_root_sess_name' => "{$p_blog_root_sess_name}",
                'debug_mode'            => "{$debug_mode}",
                'custom_ahl_path'       => "{$custom_ahl_path}"
         );
         foreach ($sql_val as $key => $value) {
             $sql = 'UPDATE ' . $config_table . " SET `config_value` = '" . $value . "' WHERE `config_key` = '" . $key . "'";
             $res = mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
         }
         /*
         foreach ($sql_val as $key => $value) {
             $sql[] = 'UPDATE ' . $config_table . " SET config_value='" . $value . "' WHERE config_key='" . $key . "'";
         }
         for ($i = 0; $i < count($sql); $i++) {
             $res = mysql_query($sql[$i]) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
         }
         */
         if ($res) {
             if (($cfg['root_path'] == '/path/to/p_blog/') || (is_null($cfg['root_path']))) {
                 $request_uri = $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
                 $uri = parse_url($request_uri);
                 $uri = str_replace('preferences_save.php', 'preferences.php', $uri);
                 header('Location: ' . $http . '://' . $uri['host'] . $uri['path']);
                 exit;
             } else {
                 header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir . '/preferences.php');
                 exit;
             }
         }
         
    } else{ // if user auth failed...
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>