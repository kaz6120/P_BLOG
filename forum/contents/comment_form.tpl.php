<?php
/**
 * Comment Form
 * 
 * $Id: comment_form.tpl.php, 2006-08-16 18:29:08 Exp $
 */

// Load Comment Type Class List
$comment_type_list = comment_class();

// Load Smiley List
$smiley_list = smiley_button();

//////////////////////////////// PRESENTATION ////////////////////////////////////////////
$comment_form =<<<EOD
<!-- Comment form -->
<form id="addform" action="{$action}" method="post" onsubmit="return ForumReplyCheck()">
<fieldset id="comment-form">
<legend accesskey="y">Post Your Comment</legend>
{$post_title}
<p class="MapsrOfDleif">
<textarea name="comment_title"></textarea>
<textarea name="name"></textarea>
<textarea name="mail"></textarea>
<textarea name="address"></textarea>
<textarea name="comment"></textarea>
<textarea name="url_key"></textarea>
</p>
<p>
<input type="text" id="user_name" name="user_name" value="{$user_name}" class="bordered" />
<label for="user_name">{$lang['name']} <span class="notice">*</span></label>
</p>
<p>
<input type="text" id="user_email" name="user_email" value="{$user_email}" class="bordered" />
<label for="user_email">E-Mail</label>
</p>
<p>
<input type="text" id="user_uri" name="user_uri" value="{$user_uri}" size="30" class="bordered" />
<label for="user_uri"><abbr title="Uniform Resource Identifier">URI</abbr></label>
</p>
<p>
<input type="checkbox" id="remember-me" name="p_blog_forum_cookie"{$checked} />
<label for="remember-me">Remember Me</label>
</p>
<p>
<input type="text" id="title" name="title" value="{$title}" size="30" class="bordered" />
<label for="title">{$lang['title']}</label>
</p>
<p>
<label for="comment">{$lang['comment']} <span class="notice">*</span> </label> {$comment_type_list}<br />
<textarea rows="10" cols="50" id="comment" name="{$comment_field_name}" onfocus="if (value == '{$lang['no_tags_allowed']}') { value = ''; }" onblur="if (value == '') { value = '{$lang['no_tags_allowed']}'; }">{$comment}</textarea>{$smiley_list}
</p>
<p>
<input type="password" id="user_pass" name="user_pass" value="" class="bordered" />
<label for="user_pass">{$lang['user_pass']} <span class="notice">*</span></label>
</p>
<p>{$lang['comment_notice']}</p>
<p id="comment-submit">
{$parent_key}
<input type="hidden" name="refer_id" value="{$refer_id}" />
<input type="submit" value="{$lang['post']}" />
</p>
</fieldset>
</form>
EOD;
?>
