<?php
/**
 * Edit Custom Presentation Files
 * 
 * $Id: admin/edit_menu.php, 2006/01/24 06:18:25 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

if ($session_status == 'on') {
    if (isset($_REQUEST['load_file'])) {
        $load_file = $_REQUEST['load_file'];
        switch ($load_file) {     
            case 'contents_top':
                $target_file = $cd . '/include/user_include/contents_top.inc.php';
                $select0 = '';
                $select1 = ' selected="selected"';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
            case 'menu':
                $target_file = $cd . '/include/user_include/menu.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = ' selected="selected"';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
            case 'menu_middle':
                $target_file = $cd . '/include/user_include/menu_middle.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = ' selected="selected"';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
            case 'css_rss':
                $target_file = $cd . '/include/user_include/css_rss.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = ' selected="selected"';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
            case 'menu_bottom':
                $target_file = $cd . '/include/user_include/menu_bottom.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = ' selected="selected"';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
            case 'article_addition':
                $target_file = $cd . '/include/user_include/article_addition.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = ' selected="selected"';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
            case 'user_footer':
                $target_file = $cd . '/include/user_include/user_footer.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = ' selected="selected"';
                $select8 = '';
                $select9 = '';
                break;
            case 'base_xhtml':
                $target_file = $cd . '/include/user_include/base_xhtml.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = ' selected="selected"';                
                $select9 = '';
                break;
            case 'tag_buttons':
                $target_file = $cd . '/include/user_include/tag_buttons.inc.php';
                $select0 = '';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = ' selected="selected"';                
                break;
            default:
                $load_file = '';
                $select0 = ' selected="selected"';
                $select1 = '';
                $select2 = '';
                $select3 = '';
                $select4 = '';
                $select5 = '';
                $select6 = '';
                $select7 = '';
                $select8 = '';
                $select9 = '';
                break;
        }
    } else {
        $load_file = '';
        $select0 = ' selected="selected"';
        $select1 = '';
        $select2 = '';
        $select3 = '';
        $select4 = '';
        $select5 = '';
        $select6 = '';
        $select7 = '';
        $select8 = '';
        $select9 = '';
    }

    $error_msg = '';
    //require_once $cd . '/include/http_headers.inc.php';
    if (isset($target_file)) {
        // File Writing
        if (file_exists($target_file)) {
            if (is_dir($target_file)) {
                $error_msg = '<h3 class="warning important">' . $target_file . $lang['is_a_dir'] . '</h3>';
            } elseif (!is_writable($target_file)) {
                $error_msg = '<h3 class="warning important">' . $target_file . $lang['is_not_writable'] . '</h3>';
            } else {
                // No Error Messages
                $error_msg = '';
                
                // Rewrite the file
                if (isset($_POST['txt'])) {
                    // Strip slashes to save it as it is.
                    $post_txt   = stripslashes($_POST['txt']);

                    // Line break code
                    $post_txt   = str_replace("\r\n", "\r", $post_txt); // Windows
                    $post_txt   = str_replace("\r",   "\n", $post_txt); // Mac
                
                    $wfp        = fopen($target_file, "wb");
                    flock($wfp, LOCK_EX);
                    $target_txt = fwrite($wfp, mb_convert_encoding($post_txt, $cfg['charset'], 'auto'));
//                    $target_txt = fwrite($wfp, $post_txt);
                    flock($wfp, LOCK_UN);
                    fclose($wfp);
                }
            }
        } else { // If the file doesn't exist, create the new file.
            if (!touch($target_file, 0744)) {
                $error_msg = '<h3 class="warning important">' . $lang['file_not_created'] . ' : ' . $target_file . '</h3>';
            } else {
                $error_msg = '<h3 class="warning important">' . $lang['file_created'] . ' : ' . $target_file . '</h3>';
                // Write
                if (empty($_POST['txt'])) {
                    switch($load_file) {
                        case 'contents_top':
                            $default_txt = 'contents_top.inc.default';
                            break;
                        case 'menu':
                            $default_txt = 'menu.inc.default';
                            break;
                        case 'menu_middle':
                            $default_txt = 'menu_middle.inc.default';
                            break;
                        case 'css_rss':
                            $default_txt = 'css_rss.inc.default';
                            break;
                        case 'menu_bottom':
                            $default_txt = 'menu_bottom.inc.default';
                            break;
                        case 'article_addition':
                            $default_txt = 'article_addition.inc.default';
                            break;
                        case 'user_footer':
                            $default_txt = 'user_footer.inc.default';
                            break;
                        case 'base_xhtml':
                            $default_txt = 'base_xhtml.inc.default';
                            break;
                        case 'tag_buttons':
                            $default_txt = 'tag_buttons.inc.default';
                            break;
                        default:
                            $default_txt = '';
                            break;
                    }
                    $rfp      = fopen(stripslashes($cd . '/include/user_include/_default_backup/' . $default_txt), "rb");
                    $post_txt = @fread($rfp, filesize($cd . '/include/user_include/_default_backup/' . $default_txt));
                    
                    $wfp        = fopen($target_file, "wb");
                    flock($wfp, LOCK_EX);
                    $target_txt = fwrite($wfp, mb_convert_encoding($post_txt, $cfg['charset'], 'auto'));
//                    $target_txt = fwrite($wfp, $post_txt);
                    flock($wfp, LOCK_UN);
                    fclose($wfp);
                }
            }
        }

        $file_txt = '';
        
        // Read
        if (PHP_VERSION >= 4.3) {
            $target_txt = file_get_contents($target_file);
            $file_txt   = $target_txt;
        } else {
            $rfp        = fopen(stripslashes($target_file), "rb");
            $target_txt = @fread($rfp, filesize($target_file));
            flock($rfp, LOCK_SH);
            $file_txt   = $target_txt;
            fputs($rfp, $target_txt);
            flock($rfp, LOCK_UN);
            fclose($rfp);
        }
    }
    
    if (isset($file_txt)) {
        $file_txt = htmlspecialchars($file_txt);
    } else {
        $file_txt = htmlspecialchars($lang['edit_file_default_msg']);
    }
    
    $hint = hint();
    
////////////////////////// PRESENTATION ////////////////////////////
     
    $contents =<<<EOD

<div class="section">
<h2>{$lang['system_admin']}</h2>
<ul class="flip-menu">
<li><a href="./admin_top.php">{$lang['sys_env']}</a></li>
<li><a href="./preferences.php">{$lang['preferences']}</a></li>
<li><span class="cur-tab">{$lang['edit_custom_file']}</span></li>
<li><a href="./db_status.php">{$lang['db_table_status']}</a></li>
</ul>
{$error_msg}
<form action="{$_SERVER['PHP_SELF']}" method="post">
<p>
<select name="load_file">
<option value="" disabled="disabled">---</option>
<option value="contents_top"{$select1}>{$lang['contents_top']}</option>
<option value="menu"{$select2}>{$lang['menu']}</option>
<option value="menu_middle"{$select3}>{$lang['menu_middle']}</option>
<option value="css_rss"{$select4}>{$lang['css_rss']}</option>
<option value="menu_bottom"{$select5}>{$lang['menu_bottom']}</option>
<option value="article_addition"{$select6}>{$lang['article_addition']}</option>
<option value="user_footer"{$select7}>{$lang['custom_footer']}</option>
<option value="base_xhtml"{$select8}>{$lang['base_xhtml']}</option>
<option value="" disabled="disabled">---</option>
<option value="tag_buttons"{$select9}>{$lang['tag_buttons']}</option>
</select>
<input type="submit" value="{$lang['load_file']}" />{$hint['custom_files']}
</p>
</form>
<form action="{$_SERVER['PHP_SELF']}" method="post">
<textarea name="txt" rows="20" cols="50">
{$file_txt}</textarea>
<p class="submit-button">
<input type="hidden" name="load_file" value="{$load_file}" />
<input type="submit" value="{$lang['save']}" />
</p>
</form>
</div>
EOD;
    
    xhtml_output('');
    
} else { // if session status is "Off"...
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>