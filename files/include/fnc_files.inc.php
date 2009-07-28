<?php
/**
 * Functions for FILES Page
 *
 * $Id: 2005-11-28 15:23:43 Exp $
 */
 
/**
 * File Type Menu for Binary File
 */
function type_name_array() 
{
    global $cfg, $info_table, $row;
    $sql = 'SELECT bintype FROM ' . $info_table;
    $res = mysql_query($sql);
    $rowArray = array();
    while ($row = mysql_fetch_array($res)) { 
        $row = convert_to_utf8($row);
        $token = strtok($row['bintype'], "/");
        while ($token) { 
            array_push($rowArray, trim($token));
            $token = strtok("/");
        }
    }
    if ($cfg['show_type_num'] == 'yes') {
        $rowArray = array_count_values($rowArray);
        ksort($rowArray, SORT_STRING);
    } else {
        $rowArray = array_unique($rowArray);
        sort($rowArray, SORT_STRING);
    }
    return $rowArray;
}

function display_type_menu() 
{
    global $cfg, $lang;
    $type_list = '';
    
    if ($cfg['show_filetype'] == 'yes') {
        if ($cfg['filetype_style'] == 1) {
            if ($cfg['show_type_num'] == 'yes') {
                foreach (type_name_array() as $str => $num) {
                    $type_list .= '<option value="'.htmlspecialchars($str).'">'.htmlspecialchars($str).' ('.$num.")</option>\n";
                }
            } else {
                foreach (type_name_array() as $str) { 
                    $type_list .= '<option>'.htmlspecialchars($str)."</option>\n";
                }
            }
            //////////////// Presentation! /////////////////
            $file_type_menu =<<<EOD
<form id="filetype" action="./search_plus.php" method="get"> 
<div class="category-menu"> 
<select name="k" tabindex="5" onchange="if(document.forms.filetype.k.value != '-'){this.form.submit();}" title="{$lang['category']}"> 
<option value="-"  selected="selected">{$lang['file_type']}</option> 
<option value="-" disabled="disabled"> - - - </option> 
{$type_list}</select>
<input type="hidden" name="f" value="3" />
<input type="hidden" name="ao" value="" /> 
<input type="hidden" name="ds" value="" />
<input type="hidden" name="d" value="" />
<input type="hidden" name="d1" value="" />
<input type="hidden" name="d2" value="" />
<input type="hidden" name="p" value="0" />
<input type="hidden" name="c" value="1" />
<input type="hidden" name="pn" value="1" />
</div>
<noscript> 
<div class="noscript"><input type="submit" accesskey="g" tabindex="5" value="go" /></div>
</noscript>
</form>
EOD;
        } elseif ($cfg['filetype_style'] == 2) {
            if ($cfg['show_type_num'] == 'yes') {
                foreach (type_name_array() as $str => $num) {  
                    $type_list .= '<li><a href="./search_plus.php?f=3&amp;k=' . rawurlencode($str) .
                                  '&amp;ao=&amp;ds=&amp;d=&amp;d1=&amp;d2='.
                                  '&amp;p=0&amp;c=1">' .htmlspecialchars($str). ' (' .$num. ")</a></li>\n";
                }
            } else {
                foreach (type_name_array() as $str) { 
                    $type_list .= '<li><a href="./search_plus.php?f=3&amp;k=' . rawurlencode($str) .
                                  '&amp;ao=&amp;ds=&amp;d=&amp;d1=&amp;d2='.
                                  '&amp;p=0">' .htmlspecialchars($str). "</a></li>\n";
                }
            }
            //////////////// Presentation! /////////////////
            $file_type_menu =<<<EOD
        
<div class="menu">
<h2>{$lang['file_type']}</h2>
<ul>
{$type_list}</ul>
</div>
EOD;
        }
    } else {
        $file_type_menu = '';
    }
    return $file_type_menu;
}



//================================================================
// CONTENT-BOX
//================================================================
/**
 * Article Box for Binary File Page
 */
function display_binary_box($row) 
{
    global $cfg, $lang, $cd, $session_status, $admin_dir, $http, $id;
    
    $bin_type = $row['bintype'];        //Check file types
    $bin_size = $row['binsize'] / 1024; // Convert "Byte" to "KB"
    $bin_size = ceil($bin_size);

    
    // Permanent Link
    if (empty($_GET['id'])) {
        $permalink  = '<a href="' .$cd. '/files/article.php?id=' . $row['id'] . '" title="'.
                      $lang['permalink_title_1'] . htmlspecialchars(strip_tags($row['binname'])) . $lang['permalink_title_2'].
                      '" rel="Bookmark" class="permalink">Permalink</a>';
        if (($_SERVER["SCRIPT_NAME"] != $cfg['root_path'].'files/search.php') &&
            ($_SERVER["SCRIPT_NAME"] != $cfg['root_path'].'files/search_plus.php')) {
            $read_more = '<p class="read-more"><a href="' . $cd . '/files/article.php?id=' . $row['id'] . '" title="' . $row['bin_title'] . '">' . $lang['more'] . '</a></p>';
            $row['bincomment'] = preg_replace('/<!-- ?more ?-->.*<!-- ?\/more ?-->/is', $read_more, $row['bincomment']);
            $row['bincomment'] = preg_replace('/<!-- ?more ?-->.*/is', $read_more, $row['bincomment']);
        }
    } else {
        $permalink = '';
    }
    
    // Convert Text to XHTML
    if (file_exists($cd . '/include/user_include/plugins/plg_isbn.inc.php')) {
        include_once $cd . '/include/user_include/plugins/plg_isbn.inc.php';
        $FKMM_isbn = new FKMM_isbn();
        $row['bincomment'] = $FKMM_isbn->convert_isbn($row['bincomment']);
    }
    
    // Convert Text to XHTML
    if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
        include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
        $FKMM_markdown = new FKMM_markdown();
        $row['bincomment'] = $FKMM_markdown->convert($row['bincomment']);
    } else {
        $row['bincomment'] = xhtml_auto_markup($row['bincomment']);
    }
    
    // Convert Enclosure
    if (file_exists($cd . '/rss/include/P_BLOG_RSS.class.php')) {
        include_once $cd . '/rss/include/P_BLOG_RSS.class.php';
        $p_rss = new P_BLOG_RSS;
        $row['bincomment'] = $p_rss->convertEnclosure($row['bincomment']);
    }
    
    // when in admin mode, change the directory of anchor image
    $row['bincomment'] = preg_replace('/src="\./', 'src="' . $cd, $row['bincomment']);
    
    // Smiley
    $row = smiley($row);
    
    // Article Title
    $file_title = $row['bin_title'];
    
    if ($cfg['show_date_title'] == 'yes') {
        switch($cfg['date_style']) {
            case '1':
                $df = 'Y/m/d';
                break;
            case '2':
                $df = 'M d, Y';
                break;
            default:
                $df = 'Y-m-d';
                break;
        }
        $row['bindate']  = date($df.' G:i:s', strtotime($row['bindate']));
        $row['bin_mod']  = date($df.' G:i:s', strtotime($row['bin_mod']));
    }
    if ($row['bindate'] != $row['bin_mod']) {
        $row['bindate']  = date('G:i:s', strtotime($row['bindate']));
        $mod_str = ', '.$lang['mod'].' @ '.$row['bin_mod'];
    } else {
        $row['bindate']  = date('G:i:s', strtotime($row['bindate']));
        $mod_str = '';
    }
    // Category
    $category_title = $lang['cat_title_1'] . $row['bin_category'] . $lang['cat_title_2'];
    $category = '<a href="'.$cd.'/files/category.php?k='.urlencode($row['bin_category']).'" title="'.$category_title.'">'.$row['bin_category'].'</a>';
    
    // Show date time
    if ($cfg['show_date_time'] == 'yes') {
        $date_time = '<div class="date">' . $lang['post'] . ' @ ' . $row['bindate'] . ' ' . $mod_str . ' | ' . $category . '</div>';
    } else {
        $date_time = '';
    } 
    
    // MD5
    if ($cfg['show_md5'] == 'yes') {
        $md5 = display_md5($row);
    }
    
    // Thumb nail display
    if ($cfg['show_thumb_nail'] == 'yes') {
        if ($bin_type == "image/jpeg" || 
            $bin_type == "image/png"  || 
            $bin_type == "image/gif") {
            
            $img_path = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'files/bin.php?id='.$row['id'];
            
            // Get image info
            $img_info = @getimagesize($img_path);

            if ($img_info[0] > $cfg['thumb_nail_w']) {
                $div_ratio = $img_info[0] / $cfg['thumb_nail_w'];
            } elseif ($img_info[1] > $cfg['thumb_nail_h']) {
                $div_ratio = $img_info[1] / $cfg['thumb_nail_w'];
            } else {
                $div_ratio = 1;
            }
 
            $img_w = round($img_info[0] / $div_ratio);
            $img_h = round($img_info[1] / $div_ratio);
            $img_alt = $row['binname'];
            
            // Switch Thumb-nailing by GD or just resizing.
            if (extension_loaded('gd') && file_exists($cd.'/include/gd_thumb.class.php')) {
                if ($img_info[2] == '1') { // If GIF, size = 90x90
                    $img_w = '90';
                    $img_h = '90';
                }
                $img_src_uri = $cd.'/include/gd_thumb.class.php?src='.$img_path;
            } else {
                $img_src_uri = $img_path;
            }

            if ($cfg['show_img_size'] == 'yes') {
                $image_size = '<br />( '.$img_info[0].' &times; '.$img_info[1]." px )\n</p>";
            } else {
                $image_size = "\n</p>";
            }
            
            $image =<<<EOD
<p>
<a href="{$img_path}">
<img src="{$img_src_uri}" width="{$img_w}" height="{$img_h}" alt="{$img_alt}" class="thumb-nail" />
</a>{$image_size}

EOD;

        } else {
            $image = '';
        }
    } else {
        $image = '';
    }
    
    if ($cfg['use_download_counter'] == 'yes') {
        $bin_script    = 'dl';
    } else {
        $bin_script    = 'bin';
    }
    
    $comment = $row['bincomment'] . "\n".
               '<p class="download">'.
               '<strong>'.
               '<a href="'.$http.'://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'files/' . $bin_script . '.php?id=' . $row['id'] . '">'.
               $row['binname'] .
               '</a>'.
               '</strong> ( ' . $bin_type . ' : ' . $bin_size . ' KB) </p>';


    if ($session_status == 'on') {
        if ($row['draft'] == '1') {
            $update_target = 'bin_draft_update';
        } else {
            $update_target = 'update';
        }
        $admin_button =<<<EOD
<form action="{$cd}/{$admin_dir}/{$update_target}.php" method="post">
<div class="submit-button">
<input type="hidden" name="id" value="{$row['id']}" />
<input type="hidden" name="mode" value="bin" />
<input type="hidden" name="post_username" value="" />
<input type="hidden" name="post_password" value="" />
<input type="submit" tabindex="1" accesskey="m" value="{$lang['mod_del']}" />
</div>
</form>
EOD;
    } else {
        $admin_button = '';
    }
    $row['bin_category'] = htmlspecialchars($row['bin_category']);
    
    // Article footer
    if (!empty($id)) { // When Permalink
        if ($admin_button != '') {
            $article_footer =<<<EOD
<div class="a-footer">
{$admin_button}
</div>
EOD;
        } else {
            $article_footer = '';
        }
    } else { // When Index
        $article_footer =<<<EOD
<div class="a-footer">
{$permalink}{$admin_button}
</div>
EOD;
    }
    
    // Presentation!
    $article_box = <<<EOD
    
<div class="section">
<h3 class="article-title">{$file_title}</h3>
{$date_time}
<div class="comment">
{$image}{$comment}
{$md5}</div>
{$article_footer}
</div><!-- End .section -->

EOD;
    return $article_box;
}


/**
 * MD5 Generator for binary file downloader
 */
function display_md5($row) 
{
    global $cfg, $data_table;
    $binid = $row['id'];
    $md5_sql = "SELECT md5(bindata) from {$data_table} WHERE masterid = {$binid}";
    if ($md5_res = mysql_query($md5_sql)) {
        $md5_row = mysql_fetch_array($md5_res);
        $md5 = '<p class="md5">MD5 : '.$md5_row[0]."</p>\n";
    } else {
        $md5 = '<p class="md5">MD5 : None</p>';
    }
    return $md5;
}


//////////////////////////////////////////////////////////////////////////////////////////////
// Deny direct access to this file

if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . "/index.php");
}
?>