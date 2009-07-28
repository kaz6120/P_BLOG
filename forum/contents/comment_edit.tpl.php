<?php
/**
 * Edit comment
 * 
 * $Id: comment_edit.tpl.php, 2004/11/17 22:56:42 Exp $
 */

// Load Comment Type Class List
$comment_type_list = comment_class();

// Load Smiley List
$smiley_list = smiley_button();

//////////////////////////////// PRESENTATION ////////////////////////////////////////////
$contents .=<<<EOD

<div class="section">
<form id="addform" action="{$action}" method="post" onsubmit="return ForumInputCheck()">
<fieldset>
<legend>{$lang['edit']} : No.{$row['id']}</legend>
<p>{$lang['last_modified']} : {$row['mod']}</p>
<p>
<input type="text" accesskey="n" tabindex="2" id="user_name" name="user_name" value="{$row['user_name']}" class="bordered" />
<label for="user_name">{$lang['name']} <span class="notice">*</span></label>
</p>
{$user_ip}
<p>
<input type="text" accesskey="u" tabindex="4" id="user_uri" name="user_uri" value="{$row['user_uri']}" size="30" class="bordered" />
<label for="user_uri">URI</label>
</p>
<p>
<input type="text" accesskey="t" tabindex="5" id="title" name="title" value="{$row['title']}" size="30" class="bordered" />
<label for="title">{$lang['title']}</label>
</p>
<p>
<label for="comment">{$lang['comment']} <span class="notice">*</span> </label>{$comment_type_list}
<br />
<textarea accesskey="c" tabindex="7" rows="10" cols="50" id="comment" name="comment">{$row['comment']}</textarea>{$smiley_list}
</p>
<p>
{$input_pass}
<label for="user_pass">{$lang['user_pass']} <span class="notice">*</span></label>
</p>
<p>{$lang['comment_notice']}</p>
<p id="comment-submit">
<input type="radio" tabindex="6" accesskey="m" name="mod_del" value="0" checked="checked" />
{$lang['save']}
<input type="radio" tabindex="6" accesskey="d" name="mod_del" value="1" />
{$lang['delete']}
<input type="hidden" name="id" value="{$row['id']}" />
{$parent_id}
<input type="submit" accesskey="o" tabindex="10" value="OK" />
</p>
</fieldset>
</form>
</div>
EOD;
?>
