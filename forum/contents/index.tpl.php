<?php
/**
 * TOP OF THE FORUM 
 * 
 * $Id: index.tpl.php, 2005/01/30 23:40:21 Exp $
 */

$contents =<<<EOD
<ul class="flip-menu">
<li><span class="cur-tab">{$lang['topic_list']}</span></li>
<li><a href="./add.php" accesskey="n">{$lang['new_topic']}</a></li>
</ul>
<p class="search-res">
<span class="search-res">{$hit_row}</span> {$lang['topics']}&#160;
<span class="search-res">{$disp_page} - {$disp_rows}</span> / 
<span class="search-res">{$hit_row}</span></p>
<!-- topic table -->
<table id="forum-topic-table" summary="Topic List">
<tr>
<th abbr="Topic">{$lang['topic']}</th>
<th abbr="Number of Replies">{$lang['reply']}</th>
<th abbr="Author">{$lang['posted_by']}</th>
<th abbr="Last Post">{$lang['latest']}</th>
{$mod_del_button}</tr>
{$list}</table>
<!-- End #forum-topic-table -->
{$display_trash}
EOD;


// Default Page
$default =<<<EOD
<ul class="flip-menu">
<li><span class="cur-tab">{$lang['topic_list']}</span></li>
<li><a href="./add.php">{$lang['new_topic']}</a></li>
</ul>
<div class="section">
<h2>Welcome to {$cfg['blog_title']} Forum!</h2>
<p>{$lang['no_posts']}</p>
</div>
EOD;

?>
