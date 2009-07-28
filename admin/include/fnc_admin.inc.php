<?php
/**
 * Functions for Admin page only
 *
 * $Id: func_admin.inc.php, 2005-11-28 15:21:17 Exp $
 */

//================================================================
// ADMIN MODE TOP
//================================================================
/**
 * Admin Default Login Form
 */
function login_form() 
{
    global $cd, $cfg, $lang;
    
    if (file_exists("./root")) {
        $account_manager =<<<EOD
<!-- Account Manager Login -->
<p id="account-manager">
<a href="./go_to_root.php" title="{$lang['create_accounts']} ({$lang['root_user_only']})">
{$lang['account_manager']}
</a>
</p>
EOD;
    } else {
        $account_manager = '';
    }

    $contents =<<<EOD
<div class="section">
<h2>{$lang['admin']}</h2>
<form method="post" action="{$_SERVER['PHP_SELF']}">
<fieldset id="login">
<legend accesskey="a">{$lang['login']}</legend>
<p>
<label for="username">{$lang['user_name']} : </label>
<input type="text" value="" tabindex="1" accesskey="u" id="username" name="post_username" size="20" class="bordered" />
</p>
<p>
<label for="password">{$lang['user_pass']} : </label>
<input type="password" value="" tabindex="2" accesskey="p" id="password" name="post_password" size="20" class="bordered" />
</p>
<p>
<input type="submit" tabindex="3" accesskey="l" value="{$lang['login']}" />
</p>
</fieldset>
</form>
{$account_manager}
</div><!-- End .section -->
EOD;

    return $contents;
}




/**
 * Login Form to RSS Admin
 */
function display_login_rss() 
{
    global $lang;
    $contents =<<<EOD
<!-- RSS admin button -->
<form method="post" action="../var/rss_box/admin/add.php">
<p class="submit-button">
<input type="hidden" value="" tabindex="10" accesskey="u" name="post_username" size="20" class="bordered" />
<input type="hidden" value="" tabindex="11" accesskey="p" name="post_password" size="20" class="bordered" />
<input type="submit" tabindex="13" accesskey="r" value="RSS Links Admin" />
</p>
</form>
EOD;
    return $contents;
}


//================================================================
// POST NEW LOG & FILE
//================================================================
/**
 * Post New Log
 */
function display_add_log_form($post_password, $post_username, $text_cols)
{
    global $cfg, $lang, $cd, $category_name, $hint, $row;
    if ($cfg['xml_lang'] == 'ja') {
        $input_check = 'inputCheck()';
    } else {
        $input_check = 'inputCheck_e()';
    }
    if ($cfg['trackback'] == 'on') {
        $ping_uri = 'http://';
        $trackback_ping_form =<<<EOD
<p id="trackback-form">
<label for="send-ping-uri" accesskey="s">{$lang['tb_sendurl']}:</label><br />
<input type="text" id="send-ping-uri" name="send_ping_uri" size="40" tabindex="1" value="{$ping_uri}" class="bordered" />
<select name="encode">
<option value="UTF-8" selected="selected">UTF-8</option>
<option value="EUC-JP">EUC-JP</option>
<option value="SJIS">Shift_JIS</option>
</select>
</p>
<p>
{$lang['send_update_ping']} : 
<input type="radio" tabindex="1" id="send-ping-no" name="send_update_ping" value="no" checked="checked" /><label for="send-ping-no">No</label>
<input type="radio" tabindex="1" id="send-ping-yes" name="send_update_ping" value="yes" /><label for="send-ping-yes">Yes</label>
</p>
EOD;
    } else {
        $trackback_ping_form = '';
    }
    // Set current time
    $ctime = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
    
    // Category selecter
    $category_list = category_list();
    
    // Tag buttons
    $tag_buttons = display_tag_buttons();
    
    // Upload file form
    $upload_file_form = display_upload_file_form();
    
    // Hint
    $hint = hint();
    
    // OK, generate the page
    $contents =<<<EOD
<h2>{$lang['add_new']}</h2>
<div class="section">
<form id="addform" action="./draft_insert.php" method="post" enctype="multipart/form-data" onsubmit="return {$input_check}">
<p><input type="hidden" name="MAX_FILE_SIZE" value="102400000" /></p>
<p>
<label for="date">{$lang['date_and_time']} : </label><br />
<input type="text" id="date" name="date" size="20" tabindex="1" value="{$ctime}" class="bordered" />
<input type="checkbox" id="custom-date" name="custom_date" tabindex="1" />
<label for="custom-date">{$lang['use_custom_date']}</label>
<br />
<label for="article-title">{$lang['title']} : </label><br />
<input type="text" id="article-title" name="name" size="40" tabindex="1" value="" class="bordered" /><br />
<label for="article-uri">URI{$hint['href']} : </label><br />
<input type="text" id="article-uri" name="href" size="40" tabindex="1" value="http://" class="bordered" />
</p>
<p>
<label for="category">{$lang['category']}{$hint['cagegory']} : </label><br />
<input type="text" id="category" name="category" size="40" value="{$category_name}" tabindex="1" class="bordered" />
<select tabindex="1" title="{$lang['category']}" onchange="document.forms.addform.category.value += this.options[this.selectedIndex].value+ ',';"> 
<option value=""  selected="selected">{$lang['category']}</option> 
<option value="-" disabled="disabled">- - -</option> 
{$category_list}</select>
</p>
<p><label for="comment">{$lang['comment']}{$hint['comment']} : </label><br />
{$tag_buttons}<br />
<textarea id="comment" name="comment" rows="20" cols="{$text_cols}" tabindex="1"></textarea>
</p>
{$upload_file_form}
{$trackback_ping_form}
<p class="submit-button">
<input class="button" tabindex="1" type="submit" value="{$lang['preview']}" />
</p>
</form>
</div>
EOD;
    return $contents;
}

/** 
 * Post New Binary File To MySQL
 */
function display_up_file_form() 
{
    global $cfg, $lang, $info_table, $text_cols;
    if ($cfg['xml_lang'] == 'ja') {
        $input_check = 'inputCheckBin()';
    } else {
        $input_check = 'inputCheckBin_e()';
    }
    // Current time
    $ctime = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
    
    $category_list = add_bin_categories();
    
    $tag_buttons = display_tag_buttons();
    
    // Upload file form
    $upload_file_form = display_upload_file_form();
    
    $contents =<<<EOD
<h2>{$lang['send_file']}</h2>
<div class="section">
<form id="addform" method="post" action="./bin_draft_insert.php" enctype="multipart/form-data" onsubmit="return {$input_check}">
<p><input type="hidden" name="MAX_FILE_SIZE" value="102400000" /></p>
<p>
<label for="bin-date">{$lang['date_and_time']} : </label><br />
<input type="text" id="bin-date" name="bindate" size="20" tabindex="1" value="{$ctime}" class="bordered" /><br />
<label for="bin-title">{$lang['title']} :</label><br />
<input type="text" id="bin-title" name="bin_title" size="40" tabindex="1" value="" class="bordered" /><br />
</p>
<p><input type="file" id="binfile" name="binfile" size="40" /></p>
{$category_list}
<p><label for="comment">{$lang['comment']} : </label><br />
{$tag_buttons}
<br />
<textarea id="comment" name="bincomment" rows="20" cols="{$text_cols}" accesskey="t" tabindex="1"></textarea>
</p>
{$upload_file_form}
<p class="submit-button">
<input class="button" tabindex="1" accesskey="g" type="submit" value="{$lang['preview']}" />
</p>
</form>
</div>
EOD;
    return $contents;
}


/**
 * Choose Category
 */
function add_categories() 
{
    global $cfg, $lang, $row, $log_table, $hint, $category_name;
    $category_name = $row['category'];
    $categories =<<<EOD
<p>
<label for="category">{$lang['category']}{$hint['cagegory']} : </label><br />
<input type="text" id="category" name="category" size="40" value="{$category_name}" tabindex="1" class="bordered" />
<select tabindex="1" title="{$lang['category']}" onchange="document.forms.addform.category.value += this.options[this.selectedIndex].value+ ',';"> 
<option value=""  selected="selected">{$lang['category']}</option> 
<option value="-" disabled="disabled">- - -</option> 
EOD;
    if ($cfg['show_cat_num'] == 'yes') {
        foreach (cat_name_array($log_table) as $str=>$num) {
            $categories .= '<option value="'.htmlspecialchars($str).'">'.htmlspecialchars($str).' ('.$num.")</option>\n";
        }
    } else {
        foreach (cat_name_array($log_table) as $str) { 
            $categories .= '<option>'.htmlspecialchars($str)."</option>\n";
        }
    }
    $categories .=<<<EOD
</select>
</p>
EOD;
    return $categories;
}

function category_list() 
{
    global $cfg, $lang, $row, $log_table, $hint;
    
    $category_name = $row['category'];
    
    $category_list = '';
    if ($cfg['show_cat_num'] == 'yes') {
        foreach (cat_name_array($log_table) as $str=>$num) {
            $category_list .= '<option value="'.htmlspecialchars($str).'">'.htmlspecialchars($str).' ('.$num.")</option>\n";
        }
    } else {
        foreach (cat_name_array($log_table) as $str) { 
            $category_list .= '<option>'.htmlspecialchars($str)."</option>\n";
        }
    }
    return $category_list;
}

/**
 * Choose Cateogry for Binary File
 */
function add_bin_categories() 
{
    global $cfg, $lang, $row, $info_table;
    $category_name = $row['bin_category'];
    $bin_categories =<<<EOD
<p>
<label for="bin-category">{$lang['category']} : </label><br />
<input type="text" id="bin-category" name="bin_category" size="40" value="{$category_name}" tabindex="1" class="bordered" />
<select tabindex="1" title="{$lang['category']}" onchange="document.forms.addform.bin_category.value += this.options[this.selectedIndex].value+ ',';"> 
<option value=""  selected="selected">{$lang['category']}</option>
<option value="-" disabled="disabled"> - - - </option> 
EOD;
    if ($cfg['show_bin_cat_num'] == 'yes') {
        foreach (cat_name_array($info_table) as $str => $num) {
            $bin_categories .= '<option value="'.htmlspecialchars($str).'">'.htmlspecialchars($str).' ('.$num.")</option>\n";
        }
    } else {
        foreach(cat_name_array($info_table) as $str) { 
            $bin_categories .= '<option>'.htmlspecialchars($str)."</option>\n";
        }
    }
    $bin_categories .=<<<EOD
</select>
</p>
EOD;
    return $bin_categories;
}


/**
 * Tag Buttons
 */
function display_tag_buttons() 
{
    global $cfg, $cd;
    $smiley_list = smiley_button();
    include_once $cd . '/include/user_include/tag_buttons.inc.php';
    return $tag_buttons;
}


/**
 * Attachment File Uploader to "resources" direcoty
 */
function display_upload_file_form() 
{
    global $cfg, $lang;
    
    $hint = hint();

    if (preg_match('/rv:1/', $_SERVER['HTTP_USER_AGENT'])) {
        $preview = 'off';
    } else {
        $preview = 'on';
    }

    // show uplodoad file choosers
    $upload_files = '';
    for ($i = 1; $i < $cfg['up_img_max']+1; $i++) {
        if ($preview == 'off') {
            $image_preview = '<img id="img' . $i . '" src="" alt="Preview Image" class="preview" />';
        } else {
            $image_preview = '<br /><img id="img' . $i . '" src="../styles/_shared/file.png" alt="Preview Image" class="preview" />';
        }
        $upload_files .=<<<EOD
<tr>
<td>
<label for="myfile{$i}">{$lang['file']}{$i}</label> : 
<input type="file" id="myfile{$i}" name="myfile[{$i}]" onchange="setFile({$i});" size="35" tabindex="6" class="bordered" />
<input type="button" id="button{$i}" onclick="Attach({$i});" onkeypress="Attach({$i});" value="img" />{$image_preview}
</td>
</tr>
EOD;

    }
    
    $upload_file_form =<<<EOD
<table summary="Attach Files">
<thead>
<tr>
<th abbr="Upload attached files" colspan="2">
{$lang['send_img']}　:　<a href="{$cfg['uploaddir']}"><strong>{$cfg['uploaddir']}</strong></a> {$hint['file_upload']}
</th>
</tr>
</thead>
<tbody>
{$upload_files}
</tbody>
</table>
EOD;
    return $upload_file_form;
}



/**
 * Update Posted Log
 */

function update_log_form($mode) 
{
    global $cfg, $lang, $row, $text_cols, $log_table;
    
    $row['id']       = htmlspecialchars($row['id']);
    $row['name']     = htmlspecialchars($row['name']);
    $row['href']     = htmlspecialchars($row['href']);
    $row['category'] = htmlspecialchars($row['category']);
    $row['comment']  = htmlspecialchars($row['comment']);
    $row['mod']      = htmlspecialchars($row['mod']);
    $row['ping_uri'] = htmlspecialchars($row['ping_uri']);

    if ($cfg['xml_lang'] == 'ja') {
        $input_check = 'inputCheck()';
        $confirm_delete = 'confirmDelete()';
    } else {
        $input_check = 'inputCheck_e()';
        $confirm_delete = 'confirmDelete_e()';
    }
    
    if ($cfg['trackback'] == 'on') {
        if ($row['ping_uri'] == '') {
            $ping_uri = 'http://';
        } else {
            $ping_uri = $row['ping_uri'];
        }
        $trackback_ping_form =<<<EOD
<p id="trackback-form">
<label for="send-ping-uri">{$lang['tb_sendurl']}:</label><br />
<input type="text" id="send-ping-uri" name="send_ping_uri" size="40" accesskey="z" tabindex="1" value="{$ping_uri}" class="bordered" />
<select name="encode" tabindex="1">
<option value="UTF-8" selected="selected">UTF-8</option>
<option value="EUC-JP">EUC-JP</option>
<option value="SJIS">Shift_JIS</option>
</select>
</p>
<p>
{$lang['send_update_ping']} : 
<input type="radio" tabindex="1" name="send_update_ping" value="no" checked="checked" />No
<input type="radio" tabindex="1" name="send_update_ping" value="yes" />Yes
</p>
EOD;
    } else {
        $trackback_ping_form = '';
    }
    
    if ($mode == 'draft') {
        $date   = htmlspecialchars($row['date']);
        $dform  =<<<EOD
<label for="date-and-time">{$lang['date_and_time']} :</label><br />
<input type="text" id="date-and-time" name="date" tabindex="1" value="{$date}" size="20" class="bordered" />
<input type="checkbox" id="custom-date" name="custom_date" tabindex="1" /><label for="custom-date">{$lang['use_custom_date']}</label><br />
EOD;
        $target = 'draft_updated';
        $draft_status        = $lang['draft'];
        $no_change_mod_time  = '';
        $make_private        = '';
        $update_submit_title = $lang['draft_update'];
        $delete_submit_title = $lang['draft_destroy'];
    } else {
        $date   = '';
        $dform  = '';
        $target = 'updated';
        $draft_status        = '';
        $no_change_mod_time  = '<input type="checkbox" name="no_update_mod" tabindex="1" value="yes" checked="checked" /> '.
                               $lang['no_update_timestamp'];
        $make_private        = '<p><input type="checkbox" name="private" tabindex="1" value="1" /> '.
                               $lang['make_private'] . '</p>';
        $update_submit_title = $lang['update'];
        $delete_submit_title = $lang['delete'];
    }
    
    // Set variables
    $id = $row['id'];
    $name = $row['name'];
    $href = $row['href'];
    $comment = $row['comment'];
    $mod = $row['mod'];
    $categories = add_categories();
    $tag_buttons = display_tag_buttons();
    $upload_file_form = display_upload_file_form();
    $hint = hint();
    
    $contents =<<<EOD
<div class="section">
<h2>{$draft_status} {$lang['update']}　:　{$lang['log']}ID {$id}</h2>
<div class="section">
<form id="addform" action="./{$target}.php" method="post" enctype="multipart/form-data">
<p>
{$dform}
<label for="article-title">{$lang['title']} :</label><br />
<input type="text" name="name" id="article-title" tabindex="1" value="{$name}" size="40" class="bordered" /><br />
<label for="article-title-uri">URI{$hint['href']} : </label><br />
<input type="text" name="href" id="article-title-uri" tabindex="1" value="{$href}" size="40" class="bordered" /><br />
</p>
{$categories}
<p>
<label for="comment">{$lang['comment']}{$hint['comment']} : </label><br />
{$tag_buttons}
<br />
<textarea id="comment" name="comment" tabindex="1" rows="20" cols="{$text_cols}" >{$comment}</textarea><br />
</p>
{$upload_file_form}
<p>
{$no_change_mod_time}
<input type="hidden" name="mod" value="{$mod}" />
</p>
{$make_private}{$trackback_ping_form}
<div class="submit-button">
<input type="hidden" name="id" value="{$id}" />
<input class="backbutton" tabindex="1" accesskey="u" type="submit" value="{$update_submit_title}" />
</div>
</form>
<form id="del" action="./delete.php" method="post" onsubmit="return {$confirm_delete}">
<div class="submit-button">
<input type="hidden" name="id" value="{$id}" />
<input tabindex="2" accesskey="d" type="submit" value="{$delete_submit_title}" />
</div>
</form>

</div><!-- End .section -->
</div><!-- End .section -->
EOD;
    return $contents;
} 


/**
 * Update Binary File Info
 */
function update_bin_form($mode) 
{
    global $cfg, $lang, $row, $category_name, $text_cols,
           $post_password, $post_username, $info_table;
    $row['bin_title']    = htmlspecialchars($row['bin_title']);
    $row['binname']      = htmlspecialchars($row['binname']);
    $row['bin_category'] = htmlspecialchars($row['bin_category']);
    $row['bincomment']   = htmlspecialchars($row['bincomment']);
    $row['bin_mod']      = htmlspecialchars($row['bin_mod']);
    if ($cfg['xml_lang'] == 'ja') {
        $input_check = 'inputCheckBin()';
        $confirm_delete = 'confirmDelete()';
    } else {
        $input_check = 'inputCheckBin_e()';
        $confirm_delete = 'confirmDelete_e()';
    }
    if ($mode == 'draft') {
        $date   = htmlspecialchars($row['bindate']);
        $dform  = '<label for="date_and_time">' . $lang['date_and_time'] . ' :</label><br />'.
                  '<input type="text" id="date_and_time" name="date" tabindex="2" value="' . $date . '" size="20" class="bordered" />'.
                  '<input type="checkbox" id="custom-date" name="custom_date" tabindex="3" />'.
                  '<label for="custom-date">' . $lang['use_custom_date'] . '</label><br />';
        $target = 'bin_draft_updated';
        $draft_status        = $lang['draft'];
        $no_change_mod_time  = '';
        $make_private        = '';
        $update_submit_title = $lang['draft_update'];
        $delete_submit_title = $lang['draft_destroy'];
    } else {
        $date   = '';
        $dform  = '';
        $target = 'bin_updated';
        $draft_status        = '';
        $no_change_mod_time  = '<input type="checkbox" name="no_update_mod" tabindex="1" checked="checked" /> ' .
                               $lang['no_update_timestamp'];
        $make_private        = '<p><input type="checkbox" name="private" tabindex="1" value="1" /> '.
                               $lang['make_private'] . '</p>';
        $update_submit_title = $lang['update'];
        $delete_submit_title = $lang['delete'];
    }
    
    // Set variables
    $id = $row['id'];
    $bin_title = $row['bin_title'];
    $binname = $row['binname'];
    $bincomment = $row['bincomment'];
    $bin_mod = $row['bin_mod'];
    $bintype = $row['bintype'];
    $binsize = $row['binsize'];
    $bin_categories = add_bin_categories();
    $bin_tag_buttons = display_tag_buttons();
    $upload_file_form = display_upload_file_form();
    
    $contents =<<<EOD
<div class="section">
<h2>{$draft_status} {$lang['update']} : {$lang['file']} ID{$id}</h2>
<div class="section">
<form id="addform" action="./{$target}.php" method="post" enctype="multipart/form-data">
<p>
{$dform}
{$lang['title']} :<br />
<input type="text" name="bin_title" tabindex="1" value="{$bin_title}" size="40" class="bordered" /><br />
</p>
<p>{$lang['file_name']} :<br />
<input type="text" name="binname" tabindex="1" value="{$binname}" size="40" class="bordered" /><br />
{$lang['file_type']} : {$bintype}<br />
{$lang['file_size']} : {$binsize} byte
</p>
<p>
<input type="checkbox" tabindex="1" name="replace_file" /> {$lang['replace_to']} : <input type="file" name="binfile" tabindex="1" />
</p>
{$bin_categories}
<p>
{$lang['comment']} : <br />
{$bin_tag_buttons}
<br />
<textarea id="comment" name="bincomment" tabindex="1" rows="20" cols="{$text_cols}" >{$bincomment}</textarea>
</p>
{$upload_file_form}
<p>
{$no_change_mod_time}
<input type="hidden" name="bin_mod" value="{$bin_mod}" />
</p>
{$make_private}
<p class="submit-button">
<input type="hidden" name="id" value="{$id}" />
<input tabindex="1" class="backbutton" type="submit" value="{$update_submit_title}" />
</p>
</form>
<form id="del" action="./bin_delete.php" method="post" onsubmit="return {$confirm_delete}">
<p class="submit-button">
<input type="hidden" name="id" value="{$id}" />
<input tabindex="1" type="submit" value="{$delete_submit_title}" />
</p>
</form>
</div>
</div>
EOD;
    return $contents;
}


/**
 * Attachment Binary File Uploaded
 */
function file_uploaded() 
{
    global $cfg, $lang;
    // upload binary resources to the specified "resources" directory
    $file_uploaded = '<table summary="Uploaded Files">'.
                     '<tr><th colspan="3" abbr="Resource Directory">'.$lang['resource_dir'].' : '.
                     '<strong><a href="'.$cfg['uploaddir'].'">'.$cfg['uploaddir']."</a></strong></th></tr>\n";
    for ($i = 1; $i < $cfg['up_img_max']+1; $i++){
        if(isset($_FILES['myfile'])) {
            if (move_uploaded_file($_FILES['myfile']['tmp_name'][$i],
                                   $cfg['uploaddir'] . $_FILES['myfile']['name'][$i])) {
                $file_uploaded .= '<tr><td>'.$lang['file'].$i.'</td><td>'.$lang['upload_ok']."</td>\n".
                                  '<td>'.
                                  $lang['file_name'].' : '.$_FILES['myfile']['name'][$i].'<br />'.
                                  $lang['file_type'].' : '.$_FILES['myfile']['type'][$i].'<br />'.
                                  $lang['file_size'].' : '.$_FILES['myfile']['size'][$i].' bytes<br />'.
                                  $lang['temp_name'].' : '.$_FILES['myfile']['tmp_name'][$i].'<br />'.
                                  $lang['error_msg'].' : '.$_FILES['myfile']['error'][$i].'<br />'.
                                  '</td></tr>';
            } else {
                $file_uploaded .= '<tr><td>'.$lang['file'].$i.'</td><td>'.$lang['no_files_added'].'</td>'.
                                  '<td>0</td></tr>'; 
            }
        }
    }
    $file_uploaded .= '</table>';
    return $file_uploaded;
}

function file_upload() 
{
    global $cfg, $lang;
    // upload binary resources to the specified "resources" directory
    for ($i = 1; $i < $cfg['up_img_max']+1; $i++){
        if(isset($_FILES['myfile'])) {
            move_uploaded_file($_FILES['myfile']['tmp_name'][$i], $cfg['uploaddir'] . $_FILES['myfile']['name'][$i]);
        }
    }
}

//================================================================
// SUCCESS MESSAGES
//================================================================
/**
 * New Log added successfully
 */
function log_added($index_page, $article_mode) 
{
    global $lang, $new_id;
    $added_msg = '<h2>'.$lang['log_added']."</h2>\n".
                 '<h3 class="ref"><a href="./'.$article_mode.'.php?id='.$new_id.'">'.$lang['check']."</a></h3>\n".
                 '<h3 class="ref"><a href="../'.$index_page.'.php">'.$lang['check_index']."</a></h3>\n";
    return $added_msg;
}


/**
 * Log updated successfully
 */
function log_updated($index_page, $article_mode) 
{
    global $lang, $new_id;
    if ($index_page == 'index') {
        $file_mode = $lang['log'];
    } elseif ($index_page == 'files/index') {
        $file_mode = $lang['file'];
    }
    $updated_msg = '<h2>'.$file_mode.' ID:'.$new_id.$lang['updated']."</h2>\n".
                   '<h3 class="ref"><a href="./'.$article_mode.'.php?id='.$new_id.'">'.$lang['check']."</a></h3>\n".
                   '<h3 class="ref"><a href="../'.$index_page.'.php">'.$lang['check_index']."</a></h3>\n";
    return $updated_msg;
}


function preview_mod_del($target, $article_mode) 
{
    global $lang, $id;
    if (($target == 'draft_update') or ($target == 'bin_draft_update')) {
        $legend_str = $lang['draft_mod_del'];
        $update_submit_title = $lang['draft_update'];
        $delete_submit_title = $lang['draft_destroy'];
    } else {
        $legend_str = $lang['mod_del'];
    }
    echo '<form action="'.$target.'.php" method="post">'."\n".
         "<fieldset>\n".
         '<legend accesskey="u">'.$legend_str."</legend>\n".
         '<input type="submit" value="go" />'."\n".
         '<input type="hidden" name="id" value="'.$id.'" />'."\n".
         '<input type="hidden" name="mode" value="'.$article_mode.'" />'."\n".
         "</fieldset>\n".
         "</form>\n";
}


/**
 * Generate "Hint" Link
 */
function hint()
{
    global $cd, $cfg;

    if (file_exists($cd . '/var/help/man/index.php')) {
        if ($cfg['xml_lang'] == 'ja') {
            $man_lang = 'ja';
        } else {
            $man_lang = 'en';
        }
        $hint['title']        = '<a class="hint" href="' . $cd . '/var/help/man/index.php?id='.$man_lang.'_02#title" title="Hint for title">?</a>';
        $hint['href']         = '<a class="hint" href="' . $cd . '/var/help/man/index.php?id='.$man_lang.'_02#uri" title="Hint for URI">?</a>';
        $hint['cagegory']     = '<a class="hint" href="' . $cd . '/var/help/man/index.php?id='.$man_lang.'_02#category" title="Hint for category">?</a>';
        $hint['comment']      = '<a class="hint" href="' . $cd . '/var/help/man/index.php?id='.$man_lang.'_02#comment" title="Hint for comment">?</a>';
        $hint['file_upload']  = '<a class="hint" href="' . $cd . '/var/help/man/index.php?id='.$man_lang.'_02#file-upload" title="Hint for file uploading">?</a>';
        $hint['custom_files'] = '<a class="hint" href="' . $cd . '/var/help/man/index.php?id='.$man_lang.'_04" title="Hint for customizing">?</a>';
    } else {
        $hint['title']    = '';
        $hint['href']     = '';
        $hint['cagegory'] = '';
        $hint['comment']  = '';
        $hint['file_upload'] = '';
        $hint['custom_files'] = '';
    }
    return $hint;
}

// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>