<?php
/**
 * Preferences
 *
 * $Id: admin/preferences.php, 2006-03-08 07:52:52 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

if ($session_status == 'on') {

    switch ($cfg['xml_version']) {
        case '1.1':
            $xml_ver_1    = '';
            $xml_ver_11   = 'checked="checked" ';
            $xml_ver_11cn = '';
            break;
        case '1.1cn':
            $xml_ver_1    = '';
            $xml_ver_11   = '';
            $xml_ver_11cn = 'checked="checked" ';
            break;
        default   :
            $xml_ver_1    = 'checked="checked" ';
            $xml_ver_11   = '';
            $xml_ver_11cn = '';
            break;
    }
    switch ($cfg['charset']) {
        case 'Shift_JIS':
            $utf_8      = '';
            $iso_8859_1 = '';
            $euc_jp     = '';
            $shift_jis  = 'selected="selected"';
            break;
        case 'euc-jp':
            $utf_8      = '';
            $iso_8859_1 = '';
            $euc_jp     = 'selected="selected"';
            $shift_jis  = '';
            break;
        default   :
            $utf_8      = 'selected="selected"';
            $iso_8859_1 = '';
            $euc_jp     = '';
            $shift_jis  = '';
            break;
    }
    switch ($cfg['xml_lang']) {
        case 'ja':
            $xml_lang_1 = '';
            $xml_lang_2 = 'selected="selected"';
            $xml_lang_3 = '';
            $xml_lang_4 = '';
            $xml_lang_5 = '';
            break;
        case 'it':
            $xml_lang_1 = '';
            $xml_lang_2 = '';
            $xml_lang_3 = 'selected="selected"';
            $xml_lang_4 = '';
            $xml_lang_5 = '';
            break;
        case 'de':
            $xml_lang_1 = '';
            $xml_lang_2 = '';
            $xml_lang_3 = '';
            $xml_lang_4 = 'selected="selected"';
            $xml_lang_5 = '';
            break;
        case 'da':
            $xml_lang_1 = '';
            $xml_lang_2 = '';
            $xml_lang_3 = '';
            $xml_lang_4 = '';
            $xml_lang_5 = 'selected="selected"';
            break;
        default:
            $xml_lang_1 = 'selected="selected"';
            $xml_lang_2 = '';
            $xml_lang_3 = '';
            $xml_lang_4 = '';
            $xml_lang_5 = '';
            break;
    }
    switch ($cfg['enable_unicode']) {
        case 'off':
            $unicode_on  = '';
            $unicode_off = 'checked="checked" ';
            break;
        default:
            $unicode_on  = 'checked="checked" ';
            $unicode_off = '';
            break;
    }
    switch ($cfg['mysql_lang']) {
        case 'EUC-JP':
            $mysql_lang_1 = '';
            $mysql_lang_2 = 'selected="selected"';
            $mysql_lang_3 = '';
            break;
        case 'UTF-8':
            $mysql_lang_1 = '';
            $mysql_lang_2 = '';
            $mysql_lang_3 = 'selected="selected"';
            break;
        default:
            $mysql_lang_1 = 'selected="selected"';
            $mysql_lang_2 = '';
            $mysql_lang_3 = '';
            break;
    }
    switch ($cfg['count_row_query']) {
        case 'found_rows()':
            $count_row_q_1 = '';
            $count_row_q_2 = 'selected="selected"';
            break;
        default:
            $count_row_q_1 = 'selected="selected"';
            $count_row_q_2 = '';
            break;
    }
    switch ($cfg['tz']) {
        case '12':
            $os12 = 'selected="selected"';
                        $os11 = ''; $os10 = ''; $os09 = ''; $os08 = ''; $os07 = ''; $os06 = '';  $os05 = ''; 
            $os04 = ''; $os03 = ''; $os02 = ''; $os01 = ''; $os00 = ''; $os_1 = ''; $os_2 = '';  $os_3 = '';
            $os_4 = ''; $os_5 = ''; $os_6 = ''; $os_7 = ''; $os_8 = ''; $os_9 = ''; $os_10 = ''; $os_11 = ''; $os_12 = '';
        case '11':
            $os11 = 'selected="selected"';
            $os12 = '';             $os10 = ''; $os09 = ''; $os08 = ''; $os07 = ''; $os06 = '';  $os05 = ''; 
            $os04 = ''; $os03 = ''; $os02 = ''; $os01 = ''; $os00 = ''; $os_1 = ''; $os_2 = '';  $os_3 = '';
            $os_4 = ''; $os_5 = ''; $os_6 = ''; $os_7 = ''; $os_8 = ''; $os_9 = ''; $os_10 = ''; $os_11 = ''; $os_12 = '';
            break;
        case '10':
            $os10 = 'selected="selected"';
            $os12 = ''; $os11 = '';             $os09 = ''; $os08 = ''; $os07 = ''; $os06 = '';  $os05 = ''; 
            $os04 = ''; $os03 = ''; $os02 = ''; $os01 = ''; $os00 = ''; $os_1 = ''; $os_2 = '';  $os_3 = '';
            $os_4 = ''; $os_5 = ''; $os_6 = ''; $os_7 = ''; $os_8 = ''; $os_9 = ''; $os_10 = ''; $os_11 = ''; $os_12 = '';
            break;
        case '9':
            $os09 = 'selected="selected"';
            $os12 = ''; $os11 = ''; $os10 = '';             $os08 = ''; $os07 = ''; $os06 = '';  $os05 = ''; 
            $os04 = ''; $os03 = ''; $os02 = ''; $os01 = ''; $os00 = ''; $os_1 = ''; $os_2 = '';  $os_3 = '';
            $os_4 = ''; $os_5 = ''; $os_6 = ''; $os_7 = ''; $os_8 = ''; $os_9 = ''; $os_10 = ''; $os_11 = ''; $os_12 = '';
            break;
        default :
            $os00 = 'selected="selected"';
            $os12 = ''; $os11 = ''; $os10 = ''; $os09 = ''; $os08 = ''; $os07 = ''; $os06 = '';  $os05 = ''; 
            $os04 = ''; $os03 = ''; $os02 = ''; $os01 = '';             $os_1 = ''; $os_2 = '';  $os_3 = '';
            $os_4 = ''; $os_5 = ''; $os_6 = ''; $os_7 = ''; $os_8 = ''; $os_9 = ''; $os_10 = ''; $os_11 = ''; $os_12 = '';
            break;
    }
    switch ($cfg['show_date_title']) {
        case 'no':
            $show_date_title_yes  = '';
            $show_date_title_no   = 'checked="checked" ';
            break;
        default:
            $show_date_title_yes  = 'checked="checked" ';
            $show_date_title_no   = '';
            break;
    }
    switch ($cfg['show_date_time']) {
        case 'no':
            $show_date_time_yes  = '';
            $show_date_time_no   = 'checked="checked" ';
            break;
        default:
            $show_date_time_yes  = 'checked="checked" ';
            $show_date_time_no   = '';
            break;
    }
    switch ($cfg['date_style']) {
        case '3':
            $date_style_1 = '';
            $date_style_2 = '';
            $date_style_3 = 'selected="selected"';
            break;
        case '2':
            $date_style_1 = '';
            $date_style_2 = 'selected="selected"';
            $date_style_3 = '';
            break;
        default:
            $date_style_1 = 'selected="selected"';
            $date_style_2 = '';
            $date_style_3 = '';
            break;
    }
    switch ($cfg['page_flip_style']) {
        case '2':
            $page_flip_style_1  = '';
            $page_flip_style_2  = 'checked="checked" ';
            break;
        default:
            $page_flip_style_1  = 'checked="checked" ';
            $page_flip_style_2  = '';
            break;
    }
    switch ($cfg['use_rss']) {
        case 'no':
            $use_rss_yes = '';
            $use_rss_no  = 'checked="checked" ';
            break;
        default:
            $use_rss_yes = 'checked="checked" ';
            $use_rss_no  = '';
            break;
    }
    switch ($cfg['enable_smiley']) {
        case 'no':
            $enable_smiley_yes = '';
            $enable_smiley_no  = 'checked="checked" ';
            $smiley = ':-)';
            break;
        default:
            $enable_smiley_yes = 'checked="checked" ';
            $enable_smiley_no  = '';
            if (file_exists($cd . '/images/smiley')) {
                $smiley = '<img src="'.$cd.'/images/smiley/smile.png" width="18" height="18" alt="smile" />';
            } else {
                $smiley = ':-)';
            }
            break;
    }
    switch ($cfg['date_order_desc']) {
        case 'no':
            $date_order_desc_yes = '';
            $date_order_desc_no  = 'checked="checked" ';
            break;
        default:
            $date_order_desc_yes = 'checked="checked" ';
            $date_order_desc_no  = '';
            break;
    }
    switch ($cfg['show_categories']) {
        case 'no':
            $show_categories_yes  = '';
            $show_categories_no   = 'checked="checked" ';
            break;
        default:
            $show_categories_yes  = 'checked="checked" ';
            $show_categories_no   = '';
            break;
    }
    switch ($cfg['category_style']) {
        case '3':
            $category_style_1  = '';
            $category_style_2  = '';
            $category_style_3  = 'checked="checked" ';
            break;
        case '2':
            $category_style_1  = '';
            $category_style_2  = 'checked="checked" ';
            $category_style_3  = '';
            break;
        default:
            $category_style_1  = 'checked="checked" ';
            $category_style_2  = '';
            $category_style_3  = '';
            break;
    }
    switch ($cfg['show_cat_num']) {
        case 'no':
            $show_cat_num_yes  = '';
            $show_cat_num_no   = 'checked="checked" ';
            break;
        default:
            $show_cat_num_yes  = 'checked="checked" ';
            $show_cat_num_no   = '';
            break;
    }
    switch ($cfg['show_pre_recent_menu']) {
        case 'no':
            $show_pre_recent_menu_yes  = '';
            $show_pre_recent_menu_no   = 'checked="checked" ';
            break;
        default:
            $show_pre_recent_menu_yes  = 'checked="checked" ';
            $show_pre_recent_menu_no   = '';
            break;
    }
    switch ($cfg['display_log_uri']) {
        case 'no':
            $display_log_uri_yes  = '';
            $display_log_uri_no   = 'checked="checked" ';
            break;
        default:
            $display_log_uri_yes  = 'checked="checked" ';
            $display_log_uri_no   = '';
            break;
    }
    switch ($cfg['use_2_indexes']) {
        case 'no':
            $use_2_indexes_yes  = '';
            $use_2_indexes_no   = 'checked="checked" ';
            break;
        default:
            $use_2_indexes_yes  = 'checked="checked" ';
            $use_2_indexes_no   = '';
            break;
    }
    switch ($cfg['show_file_date_title']) {
        case 'no':
            $show_file_date_title_yes  = '';
            $show_file_date_title_no   = 'checked="checked" ';
            break;
        default:
            $show_file_date_title_yes  = 'checked="checked" ';
            $show_file_date_title_no   = '';
            break;
    }
    switch ($cfg['show_bin_categories']) {
        case 'no':
            $show_bin_categories_yes  = '';
            $show_bin_categories_no   = 'checked="checked" ';
            break;
        default:
            $show_bin_categories_yes  = 'checked="checked" ';
            $show_bin_categories_no   = '';
            break;
    }
    switch ($cfg['bin_category_style']) {
        case '3':
            $bin_category_style_1  = '';
            $bin_category_style_2  = '';
            $bin_category_style_3  = 'checked="checked" ';
            break;
        case '2':
            $bin_category_style_1  = '';
            $bin_category_style_2  = 'checked="checked" ';
            $bin_category_style_3  = '';
            break;
        default:
            $bin_category_style_1  = 'checked="checked" ';
            $bin_category_style_2  = '';
            $bin_category_style_3  = '';
            break;
    }
    switch ($cfg['show_cat_num']) {
        case 'no':
            $show_bin_cat_num_yes  = '';
            $show_bin_cat_num_no   = 'checked="checked" ';
            break;
        default:
            $show_bin_cat_num_yes  = 'checked="checked" ';
            $show_bin_cat_num_no   = '';
            break;
    }
    switch ($cfg['show_filetype']) {
        case 'no':
            $show_filetype_yes  = '';
            $show_filetype_no   = 'checked="checked" ';
            break;
        default:
            $show_filetype_yes  = 'checked="checked" ';
            $show_filetype_no   = '';
            break;
    }
    switch ($cfg['filetype_style']) {
        case '2':
            $filetype_style_1  = '';
            $filetype_style_2  = 'checked="checked" ';
            break;
        default:
            $filetype_style_1  = 'checked="checked" ';
            $filetype_style_2  = '';
            break;
    }
    switch ($cfg['show_type_num']) {
        case 'no':
            $show_type_num_yes  = '';
            $show_type_num_no   = 'checked="checked" ';
            break;
        default:
            $show_type_num_yes  = 'checked="checked" ';
            $show_type_num_no   = '';
            break;
    }
    switch ($cfg['show_thumb_nail']) {
        case 'no':
            $show_thumb_nail_yes  = '';
            $show_thumb_nail_no   = 'checked="checked" ';
            break;
        default:
            $show_thumb_nail_yes  = 'checked="checked" ';
            $show_thumb_nail_no   = '';
            break;
    }
    switch ($cfg['show_img_size']) {
        case 'no':
            $show_img_size_yes  = '';
            $show_img_size_no   = 'checked="checked" ';
            break;
        default:
            $show_img_size_yes  = 'checked="checked" ';
            $show_img_size_no   = '';
            break;
    }
    switch ($cfg['show_md5']) {
        case 'no':
            $show_md5_yes  = '';
            $show_md5_no   = 'checked="checked" ';
            break;
        default:
            $show_md5_yes  = 'checked="checked" ';
            $show_md5_no   = '';
            break;
    }
    switch ($cfg['use_download_counter']) {
        case 'no':
            $use_download_counter_yes  = '';
            $use_download_counter_no   = 'checked="checked" ';
            break;
        default:
            $use_download_counter_yes  = 'checked="checked" ';
            $use_download_counter_no   = '';
            break;
    }
    switch ($cfg['footer_type']) {
        case '4':
            $footer_type_1 = '';
            $footer_type_2 = '';
            $footer_type_3 = '';
            $footer_type_4 = 'selected="selected"';
            break;
        case '3':
            $footer_type_1 = '';
            $footer_type_2 = '';
            $footer_type_3 = 'selected="selected"';
            $footer_type_4 = '';
            break;
        case '2':
            $footer_type_1 = '';
            $footer_type_2 = 'selected="selected"';
            $footer_type_3 = '';
            $footer_type_4 = '';
            break;
        default:
            $footer_type_1 = 'selected="selected"';
            $footer_type_2 = '';
            $footer_type_3 = '';
            $footer_type_4 = '';
            break;
    }
    switch ($cfg['show_generation_time']) {
        case 'no':
            $show_generation_time_yes  = '';
            $show_generation_time_no   = 'checked="checked" ';
            break;
        default:
            $show_generation_time_yes  = 'checked="checked" ';
            $show_generation_time_no   = '';
            break;
    }
    switch ($cfg['use_css_switch']) {
        case 'no':
            $css_switch_yes  = '';
            $css_switch_no   = 'checked="checked" ';
            break;
        default:
            $css_switch_yes  = 'checked="checked" ';
            $css_switch_no   = '';
            break;
    }
    switch ($cfg['use_feedback_form']) {
        case 'no':
            $use_feedback_form_yes  = '';
            $use_feedback_form_no   = 'checked="checked" ';
            break;
        default:
            $use_feedback_form_yes  = 'checked="checked" ';
            $use_feedback_form_no   = '';
            break;
    }
    switch ($cfg['use_email_link']) {
        case 'no':
            $use_email_link_yes  = '';
            $use_email_link_no   = 'checked="checked" ';
            break;
        default:
            $use_email_link_yes  = 'checked="checked" ';
            $use_email_link_no   = '';
            break;
    }
    switch ($cfg['use_comment_link']) {
        case 'no':
            $use_comment_link_yes  = '';
            $use_comment_link_no   = 'checked="checked" ';
            break;
        default:
            $use_comment_link_yes  = 'checked="checked" ';
            $use_comment_link_no   = '';
            break;
    }
    switch ($cfg['comment_style']) {
        case '1':
            $comment_style_1  = 'checked="checked" ';
            $comment_style_2  = '';
            break;
        default:
            $comment_style_1  = '';
            $comment_style_2  = 'checked="checked" ';
            break;
    }
    switch ($cfg['trackback']) {
        case 'off':
            $trackback_yes  = '';
            $trackback_no   = 'checked="checked" ';
            break;
        default:
            $trackback_yes  = 'checked="checked" ';
            $trackback_no   = '';
            break;
    }
    switch ($cfg['show_recent_comment']) {
        case 'no':
            $show_recent_comment_yes  = '';
            $show_recent_comment_no   = 'checked="checked" ';
            break;
        default:
            $show_recent_comment_yes  = 'checked="checked" ';
            $show_recent_comment_no   = '';
            break;
    }
    switch ($cfg['show_recent_trackback']) {
        case 'no':
            $show_recent_trackback_yes  = '';
            $show_recent_trackback_no   = 'checked="checked" ';
            break;
        default:
            $show_recent_trackback_yes  = 'checked="checked" ';
            $show_recent_trackback_no   = '';
            break;
    }
    switch ($cfg['use_analyzer']) {
        case 'no':
            $use_analyzer_yes  = '';
            $use_analyzer_no   = 'checked="checked" ';
            break;
        default:
            $use_analyzer_yes  = 'checked="checked" ';
            $use_analyzer_no   = '';
            break;
    }
    switch ($cfg['enable_del_logs']) {
        case 'no':
            $enable_del_logs_yes  = '';
            $enable_del_logs_no   = 'checked="checked" ';
            break;
        default:
            $enable_del_logs_yes  = 'checked="checked" ';
            $enable_del_logs_no   = '';
            break;
    }
    switch ($cfg['gz_compress']) {
        case 'no':
            $gz_compress_yes  = '';
            $gz_compress_no   = 'checked="checked" ';
            break;
        default:
            $gz_compress_yes  = 'checked="checked" ';
            $gz_compress_no   = '';
            break;
    }
    switch ($cfg['sendmail_account_id']) {
        case 'no':
            $sendmail_account_id_yes  = '';
            $sendmail_account_id_no   = 'checked="checked" ';
            break;
        default:
            $sendmail_account_id_yes  = 'checked="checked" ';
            $sendmail_account_id_no   = '';
            break;
    }
    switch ($cfg['use_session_db']) {
        case 'no':
            $use_session_db_yes  = '';
            $use_session_db_no   = 'checked="checked" ';
            break;
        default:
            $use_session_db_yes  = 'checked="checked" ';
            $use_session_db_no   = '';
            break;
    }
    switch ($cfg['debug_mode']) {
        case 'off':
            $debug_mode_on  = '';
            $debug_mode_off = 'checked="checked" ';
            break;
        default:
            $debug_mode_on  = 'checked="checked" ';
            $debug_mode_off = '';
            break;
    }
    $cfg['copyright'] = addslashes($cfg['copyright']);
    
    $contents =<<<EOD
<div class="section">
<h2>{$lang['system_admin']}</h2>
<ul class="flip-menu">
<li><a href="./admin_top.php">{$lang['sys_env']}</a></li>
<li><span class="cur-tab">{$lang['preferences']}</span></li>
<li><a href="./edit_menu.php">{$lang['edit_custom_file']}</a></li>
<li><a href="./db_status.php">{$lang['db_table_status']}</a></li>
</ul>
<form action="./preferences_save.php" method="post">
<table class="horizontal-graph" summary="Preferences">

<tr><th abbr="Base Settings" colspan="2">{$lang['base_settings']}</th></tr>

<tr>
<td class="key">{$lang['site_name']}</td>
<td class="value">
<input tabindex="1" accesskey="t" type="text" size="40" name="blog_title" value="{$cfg['blog_title']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['subtitle']}</td>
<td class="value">
<input tabindex="1" accesskey="t" type="text" size="40" name="sub_title" value="{$cfg['sub_title']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['root_path']}<br />
<div class="note">
{$lang['root_path_ex']}
</div>
</td>
<td class="value">http://yourdomain.com<input type="text" size="25" name="root_path" value="{$cfg['root_path']}" /><br />
<div class="note">
{$lang['root_path_ex2']}
</div>
</td>
</tr>

<tr>
<td class="key">{$lang['index_page']}</td>
<td class="value">
<input tabindex="1" accesskey="t" type="text" size="40" name="top_page" value="{$cfg['top_page']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['xhtml_version']}</td>
<td class="value">
<input tabindex="1" accesskey="x" type="radio" size="40" name="xml_version" value="1.0" id="xhtml1.0" {$xml_ver_1} /><label for="xhtml1.0">1.0 Strict</label><br />
<input tabindex="1" accesskey="x" type="radio" size="40" name="xml_version" value="1.1" id="xhtml1.1" {$xml_ver_11} /><label for="xhtml1.1">1.1</label>
<input tabindex="1" accesskey="x" type="radio" size="40" name="xml_version" value="1.1cn" id="xhtml1.1cn" {$xml_ver_11cn} /><label for="xhtml1.1cn">1.1 (Content Negotiation)</label>
</td>
</tr>

<tr>
<td class="key">{$lang['charset']}</td>
<td class="value">
<select tabindex="1" name="charset">
<option value="utf-8" {$utf_8}>UTF-8</option>
<option value="iso-8859-1" {$iso_8859_1}>ISO-8859-1</option>
<option value="euc-jp" {$euc_jp}>EUC-JP</option>
<option value="Shift_JIS" {$shift_jis}>Shift_JIS</option>
</select>
</td>
</tr>

<tr>
<td class="key">{$lang['content_lang']}</td>
<td class="value">
<select tabindex="1" name="xml_lang">
<option value="en" {$xml_lang_1}>English</option>
<option value="ja" {$xml_lang_2}>Japanese</option>
<option value="it" {$xml_lang_3}>Italian</option>
<option value="de" {$xml_lang_4}>German</option>
<option value="da" {$xml_lang_5}>Danish</option>
</select>
</td>
</tr>

<tr>
<td class="key">{$lang['convert_utf8']}</td>
<td class="value">
<input tabindex="1" accesskey="o" type="radio" name="enable_unicode" value="on" {$unicode_on}/>On
<input tabindex="1" accesskey="f" type="radio" name="enable_unicode" value="off" {$unicode_off}/>Off
</td>
</tr>

<tr>
<td class="key">{$lang['mysql_encode']}</td>
<td class="value">
<select tabindex="1" name="mysql_lang">
<option value="Latin1" {$mysql_lang_1}>Latin1</option>
<option value="EUC-JP" {$mysql_lang_2}>EUC-JP</option>
<option value="UTF-8" {$mysql_lang_3}>UTF-8</option>
</select>
</td>
</tr>

<tr>
<td class="key">{$lang['mysql_count_q']}</td>
<td class="value">
<select tabindex="1" name="count_row_query">
<option value="id" {$count_row_q_1}>id  (MySQL3.23.x or later)</option>
<option value="found_rows()" {$count_row_q_2}>found_rows (MySQL4.0 or later)</option>
</select>
</td>
</tr>

<tr>
<td class="key">{$lang['tz_offset']}</td>
<td class="value">
GMT
<select tabindex="1" name="tz">
<option value="12"  {$os12}>+12:00 ( NZST: New Zealand Standard )</option>
<option value="11"  {$os11}>+11:00</option>
<option value="10"  {$os10}>+10:00 ( GST: Guam Standard )</option>
<option value="9"   {$os09}>+9:00 ( JST : Japan Standard )</option>
<option value="8"   {$os08}>+8:00 ( CCT: China Coast )</option>
<option value="7"   {$os07}>+7:00</option>
<option value="6"   {$os06}>+6:00</option>
<option value="5"   {$os05}>+5:00</option>
<option value="4"   {$os04}>+4:00</option>
<option value="3"   {$os03}>+3:00 ( BT: Baghdad )</option>
<option value="2"   {$os02}>+2:00 ( EET: Eastern European )</option>
<option value="1"   {$os01}>+1:00 ( CET: Central European )</option>
<option value="0"   {$os00}>0:00 ( GMT : Greenwitch, London)</option>
<option value="-1"  {$os_1}>-1:00 ( WAT: West Africa, Cape Verde Island )</option>
<option value="-2"  {$os_2}>-2:00</option>
<option value="-3"  {$os_3}>-3:00 ( Brazil, Buenos Aires, Argentina)</option>
<option value="-4"  {$os_4}>-4:00 ( AST: Atlantic Standard )</option>
<option value="-5"  {$os_5}>-5:00 ( EST: Bogota, Lima, Peru, New York )</option>
<option value="-6"  {$os_6}>-6:00 ( CST: Central Standard )</option>
<option value="-7"  {$os_7}>-7:00 ( MST: Mountain Standard )</option>
<option value="-8"  {$os_8}>-8:00 ( PST: Pacific Standard )</option>
<option value="-9"  {$os_9}>-9:00 ( YST:  Yukon Standard )</option>
<option value="-10" {$os_10}>-10:00 ( HST: Hawaii Standard )</option>
<option value="-11" {$os_11}>-11:00</option>
<option value="-12" {$os_12}>-12:00 ( IDLW: International Date Line West )</option>
</select>
</td>
</tr>

<tr>
<td class="key">{$lang['show_date']}</td>
<td class="value">
<input type="radio" name="show_date_title" value="yes" {$show_date_title_yes}/>Yes
<input type="radio" name="show_date_title" value="no" {$show_date_title_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_post_time']}</td>
<td class="value">
<input type="radio" name="show_date_time" value="yes" {$show_date_time_yes}/>Yes
<input type="radio" name="show_date_time" value="no" {$show_date_time_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['date_format']}</td>
<td class="value">
<select tabindex="1" name="date_style">
<option value="1" {$date_style_1}>yyyy/mm/dd</option>
<option value="2" {$date_style_2}>Month dd, yyyy</option>
<option value="3" {$date_style_3}>yyyy-mm-dd</option>
</select>
</td>
</tr>

<tr>
<td class="key">{$lang['page_max']}</td>
<td class="value"><input type="text" size="3" name="pagemax" value="{$cfg['pagemax']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['pager_style']}</td>
<td class="value">
<input tabindex="1" accesskey="l" type="radio" name="page_flip_style" value="1" {$page_flip_style_1}/>Link
<input tabindex="1" accesskey="f" type="radio" name="page_flip_style" value="2" {$page_flip_style_2}/>Form
</td>
</tr>

<tr>
<td class="key">{$lang['generate_rss']}</td>
<td class="value">
<input type="radio" name="use_rss" value="yes" {$use_rss_yes}/>Yes
<input type="radio" name="use_rss" value="no" {$use_rss_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['enable_smiley']}</td>
<td class="value">
<input type="radio" name="enable_smiley" value="yes" {$enable_smiley_yes}/>Yes
<input type="radio" name="enable_smiley" value="no" {$enable_smiley_no}/>No
 &#8594; {$smiley}
</td>
</tr>

<tr>
<th abbr="Article Archive Style" colspan="2">{$lang['article_archive_style']}</th>
</tr>

<tr>
<td class="key">{$lang['monthly_archive_order']}</td>
<td class="value">
<input type="radio" name="date_order_desc" value="yes" {$date_order_desc_yes}/>{$lang['newest_first']}
<input tabindex="1" accesskey="o" type="radio" name="date_order_desc" value="no" {$date_order_desc_no}/>{$lang['oldest_first']}
</td>
</tr>

<tr>
<td class="key">{$lang['show_cat_menu']}</td>
<td class="value">
<input type="radio" name="show_categories" value="yes" {$show_categories_yes}/>Yes
<input type="radio" name="show_categories" value="no" {$show_categories_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['category_style']}</td>
<td class="value">
<input type="radio" name="category_style" value="2" {$category_style_2}/>Link
<input type="radio" name="category_style" value="1" {$category_style_1}/>Form
<input type="radio" name="category_style" value="3" {$category_style_3}/>Tags
</td>
</tr>

<tr>
<td class="key">{$lang['show_cat_num']}</td>
<td class="value">
<input type="radio" name="show_cat_num" value="yes" {$show_cat_num_yes}/>Yes
<input type="radio" name="show_cat_num" value="no" {$show_cat_num_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_re_recent']}</td>
<td class="value">
<input type="radio" name="show_pre_recent_menu" value="yes" {$show_pre_recent_menu_yes}/>Yes
<input type="radio" name="show_pre_recent_menu" value="no" {$show_pre_recent_menu_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['pre_recent_max']}</td>
<td class="value">
<input tabindex="1" accesskey="p" type="text" size="3" name="pre_recent_max" value="{$cfg['pre_recent_max']}" />
</td>
</tr>

<tr>
<th abbr="File Archive Style" colspan="2">{$lang['file_archive_style']}</th></tr>

<tr>
<td class="key">{$lang['file_index_title']}</td>
<td class="value">
<input type="text" size="20" name="file_index_title" value="{$cfg['file_index_title']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['use_2_indexes']}</td>
<td class="value">
<input type="radio" name="use_2_indexes" value="yes" {$use_2_indexes_yes}/>Yes
<input type="radio" name="use_2_indexes" value="no" {$use_2_indexes_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_f_date_title']}</td>
<td class="value">
<input type="radio" name="show_file_date_title" value="yes" {$show_file_date_title_yes}/>Yes
<input type="radio" name="show_file_date_title" value="no" {$show_file_date_title_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['listup_cat_max']}</td>
<td class="value">
<input type="text" size="3" name="max_listup" value="{$cfg['max_listup']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['show_f_cat_menu']}</td>
<td class="value">
<input type="radio" name="show_bin_categories" value="yes" {$show_categories_yes}/>Yes
<input type="radio" name="show_bin_categories" value="no" {$show_categories_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['file_cat_style']}</td>
<td class="value">
<input type="radio" name="bin_category_style" value="2" {$bin_category_style_2}/>Link
<input type="radio" name="bin_category_style" value="1" {$bin_category_style_1}/>Form
<input type="radio" name="bin_category_style" value="3" {$bin_category_style_3}/>Tags
</td>
</tr>

<tr>
<td class="key">{$lang['show_f_cat_num']}</td>
<td class="value">
<input type="radio" name="show_bin_cat_num" value="yes" {$show_bin_cat_num_yes}/>Yes
<input type="radio" name="show_bin_cat_num" value="no" {$show_bin_cat_num_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_f_type_menu']}</td>
<td class="value">
<input type="radio" name="show_filetype" value="yes" {$show_filetype_yes}/>Yes
<input type="radio" name="show_filetype" value="no" {$show_filetype_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['file_type_style']}</td>
<td class="value">
<input type="radio" name="filetype_style" value="2" {$filetype_style_2}/>List
<input type="radio" name="filetype_style" value="1" {$filetype_style_1}/>Form
</td>
</tr>

<tr>
<td class="key">{$lang['show_f_type_num']}</td>
<td class="value">
<input type="radio" name="show_type_num" value="yes" {$show_type_num_yes}/>Yes
<input type="radio" name="show_type_num" value="no" {$show_type_num_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_thumb_nail']}</td>
<td class="value">
<input type="radio" name="show_thumb_nail" value="yes" {$show_thumb_nail_yes}/>Yes
<input type="radio" name="show_thumb_nail" value="no" {$show_thumb_nail_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['thumb_nail_size']}</td>
<td class="value">
W : <input tabindex="1" accesskey="w" type="text" size="5" name="thumb_nail_w" value="{$cfg['thumb_nail_w']}" />
H : <input tabindex="1" accesskey="h" type="text" size="5" name="thumb_nail_h" value="{$cfg['thumb_nail_h']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['show_img_size']}</td>
<td class="value">
<input type="radio" name="show_img_size" value="yes" {$show_img_size_yes}/>Yes
<input type="radio" name="show_img_size" value="no" {$show_img_size_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_md5']}</td>
<td class="value">
<input type="radio" name="show_md5" value="yes" {$show_md5_yes}/>Yes
<input type="radio" name="show_md5" value="no" {$show_md5_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['use_dl_counter']}</td>
<td class="value">
<input type="radio" name="use_download_counter" value="yes" {$use_download_counter_yes}/>Yes
<input type="radio" name="use_download_counter" value="no" {$use_download_counter_no}/>No
</td>
</tr>

<tr>
<th abbr="CSS" colspan="2">CSS</th>
</tr>

<tr>
<td class="key">{$lang['use_css_switch']}</td>
<td class="value">
<input type="radio" name="use_css_switch" value="yes" {$css_switch_yes}/>Yes
<input type="radio" name="use_css_switch" value="no" {$css_switch_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['css_cookie_name']}</td>
<td class="value">
<input tabindex="1" accesskey="c" type="text" size="20" name="css_cookie_name" value="{$cfg['css_cookie_name']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['css_cookie']}</td>
<td class="value">
<input tabindex="1" accesskey="c" type="text" size="20" name="css_cookie_time" value="{$cfg['css_cookie_time']}" /> sec</td>
</tr>

<tr>
<td class="key">{$lang['default']} CSS</td>
<td class="value">
<input tabindex="1" accesskey="d" type="text" size="20" name="default_style" value="{$cfg['default_style']}" />
</td>
</tr>

<tr>
<th abbr="Footer Settings" colspan="2">{$lang['footer_settings']}</th>
</tr>

<tr>
<td class="key">{$lang['footer_style']}</td>
<td class="value">
<select tabindex="1" name="footer_type">
<option value="1" {$footer_type_1}>{$lang['default_footer']}</option>
<option value="2" {$footer_type_2}>{$lang['p_blog_orig_w3_logos']}</option>
<option value="3" {$footer_type_3}>{$lang['w3_orig_logos']}</option>
<option value="4" {$footer_type_4}>{$lang['user_custom_footer']}</option>
</select>
</td>
</tr>

<tr>
<td class="key">E-Mail<br />
<div class="note">
{$lang['spam_blocked_ex']}
</div>
</td>
<td class="value">
<input tabindex="1" accesskey="e" type="text" size="30" name="email" value="{$cfg['email']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['email_title']}</td>
<td class="value">
<input tabindex="1" accesskey="e" type="text" size="30" name="email_title" value="{$cfg['email_title']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['copyright']}</td>
<td class="value">
<input tabindex="1" accesskey="c" type="text" size="30" name="copyright" value="{$cfg['copyright']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['page_gen_time']}</td>
<td class="value">
<input type="radio" name="show_generation_time" value="yes" {$show_generation_time_yes}/>Yes
<input type="radio" name="show_generation_time" value="no" {$show_generation_time_no}/>No
</td>
</tr>

<tr>
<th abbr="Feedback, Comment, And Trackback" colspan="2">{$lang['feedback_comment_tb']}</th>
</tr>

<tr>
<td class="key">{$lang['sendmail_address']}<br />
<div class="note">
{$lang['sendmail_address_ex']}
</div>
</td>
<td class="value">
<input type="text" size="30" name="sendmail_address" value="{$cfg['sendmail_address']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['use_feedback']}<br />
<div class="note">
{$lang['use_feedback_ex']}
</div>
</td>
<td class="value">
<input type="radio" name="use_feedback_form" value="yes" {$use_feedback_form_yes}/>Yes
<input type="radio" name="use_feedback_form" value="no" {$use_feedback_form_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['show_email_link']}<br />
<div class="note">
{$lang['show_email_link_ex']}
</div>
</td>
<td class="value">
<input type="radio" name="use_email_link" value="yes" {$use_email_link_yes}/>Yes
<input type="radio" name="use_email_link" value="no" {$use_email_link_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['acccept_comments']}</td>
<td class="value">
<input type="radio" name="use_comment_link" value="yes" {$use_comment_link_yes}/>Yes
<input type="radio" name="use_comment_link" value="no" {$use_comment_link_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['comment_style']}</td>
<td class="value">
<input type="radio" name="comment_style" value="1" {$comment_style_1}/>{$lang['forum_style']}
<input type="radio" name="comment_style" value="2" {$comment_style_2}/>{$lang['comment_style']}
</td>
</tr>

<tr>
<td class="key">{$lang['topic_max']}</td>
<td class="value">
<input type="text" size="3" name="topic_max" value="{$cfg['topic_max']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['show_recent_comment']}</td>
<td class="value">
<input type="radio" name="show_recent_comment" value="yes" {$show_recent_comment_yes}/>Yes
<input type="radio" name="show_recent_comment" value="no" {$show_recent_comment_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['recent_comment_max']}</td>
<td class="value">
<input type="text" size="3" name="recent_comment_max" value="{$cfg['recent_comment_max']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['trackback']}</td>
<td class="value">
<input type="radio" name="trackback" value="on" {$trackback_yes}/>Yes
<input type="radio" name="trackback" value="off" {$trackback_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['ping_server_list']}<br />
<div class="note">
{$lang['ping_server_list_ex']}</div>
</td>
<td class="value">
<textarea tabindex="1" accesskey="p" name="ping_server_list" rows="7" cols="30">{$cfg['ping_server_list']}</textarea>
</td>
</tr>

<tr>
<td class="key">{$lang['show_recent_trackback']}</td>
<td class="value">
<input type="radio" name="show_recent_trackback" value="yes" {$show_recent_trackback_yes}/>Yes
<input type="radio" name="show_recent_trackback" value="no" {$show_recent_trackback_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['recent_trackback_max']}</td>
<td class="value">
<input tabindex="1" accesskey="p" type="text" size="3" name="recent_trackback_max" value="{$cfg['recent_trackback_max']}" />
</td>
</tr>

<tr>
<th abbr="Admin Mode Settings" colspan="2">{$lang['admin_mod_settings']}</th>
</tr>

<tr>
<td class="key">{$lang['upload_dir']}</td>
<td class="value">
<input type="text" size="30" name="uploaddir" value="{$cfg['uploaddir']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['updaad_max']}</td>
<td class="value">
<input type="text" size="3" name="up_img_max" value="{$cfg['up_img_max']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['use_access_analyzer']}</td>
<td class="value">
<input type="radio" name="use_analyzer" value="yes" {$use_analyzer_yes}/>Yes
<input type="radio" name="use_analyzer" value="no" {$use_analyzer_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['group_refs']}</td>
<td class="value">
<input type="text" size="3" name="referer_limit_num" value="{$cfg['referer_limit_num']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['del_log_button']}</td>
<td class="value">
<input type="radio" name="enable_del_logs" value="yes" {$enable_del_logs_yes}/>Yes
<input type="radio" name="enable_del_logs" value="no" {$enable_del_logs_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['magic_words']}</td>
<td class="value">
<input type="text" size="30" name="del_magic_words" value="{$cfg['del_magic_words']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['gz_compress']}</td>
<td class="value">
<input type="radio" name="gz_compress" value="yes" {$gz_compress_yes}/>Yes
<input type="radio" name="gz_compress" value="no" {$gz_compress_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['send_id']}</td>
<td class="value">
<input type="radio" name="sendmail_account_id" value="yes" {$sendmail_account_id_yes}/>Yes
<input type="radio" name="sendmail_account_id" value="no" {$sendmail_account_id_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['use_sess_db']}</td>
<td class="value">
<input type="radio" name="use_session_db" value="yes" {$use_session_db_yes}/>Yes
<input type="radio" name="use_session_db" value="no" {$use_session_db_no}/>No
</td>
</tr>

<tr>
<td class="key">{$lang['sess_name']}</td>
<td class="value">
<input type="text" size="30" name="p_blog_sess_name" value="{$cfg['p_blog_sess_name']}" />
</td>
</tr>

<tr>
<td class="key">{$lang['root_ses_name']}</td>
<td class="value">
<input tabindex="1" accesskey="p" type="text" size="30" name="p_blog_root_sess_name" value="{$cfg['p_blog_root_sess_name']}" />
</td>
</tr>

<tr><th abbr="Developer Mode" colspan="2">{$lang['dev_mode']}</th></tr>

<tr>
<td class="key">{$lang['debug_mode']}</td>
<td class="value">
<input type="radio" name="debug_mode" value="on" {$debug_mode_on}/>On
<input type="radio" name="debug_mode" value="off" {$debug_mode_off}/>Off
</td>
</tr>

<tr>
<td class="key">{$lang['custom']} <a href="http://openlab.ring.gr.jp/k16/htmllint/index.html">Another HTML-lint</a> URI</td>
<td class="value">
<input type="text" size="30" name="custom_ahl_path" value="{$cfg['custom_ahl_path']}" />
</td>
</tr>

</table>

<p class="submit-button">
<input class="button" tabindex="23" accesskey="s" type="submit" value="{$lang['save']} &lt;S&gt;" />
</p>
</form>
</div><!-- End .section -->
EOD;
    
    xhtml_output('');
    
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir. '/login.php');
    exit;
}
?>