<?php
/**
 * Manual Trackback Ping
 *
 * @author: kaz
 * $Id: tb_manual.php, 2005/01/27 06:48:34 Exp $
 */
////////////////////////////////////////////////////////////////////////////
$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/user_config.inc.php';


if (!empty($_GET['id'])) {
    $id = intval($_GET['id']);
    // generate target article title
    $q = "SELECT `name` FROM {$log_table} WHERE `id` = '{$id}'";
    $res = mysql_query($q);
    $row = mysql_fetch_array($res);
    $row = convert_to_utf8($row);
    $target_article = '<a href="' . $cd .'/article.php?id=' . $id . '">' . $row['name'] . '</a>';
} else {
    $id = 'no_id';
    $target_article = 'No Ariticle ID specified.';
}

// generate trackback ping URI
$target_tb_uri = 'http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'trackback/tb.php?id=' . $id;

////////////////////////////////////////////////////////////////////////////

$contents =<<<EOD
<div class="section">
<h2>Trackback Ping URI</h2>
<div class="section">
<h3>&#187; {$target_article}</h3>
<p class="trackback-uri">{$target_tb_uri}</p>
</div>
</div>
<div class="section">
<h2>Manual Trackback Ping</h2>
<form action="./tb.php?id={$id}" method="post" enctype="application/x-www-form-urlencoded">
<p>
<input type="text" id="uri" name="url" size="40" value="http://" tabindex="1" accesskey="u" />
<label for="uri">Permalink URI</label>
</p>
<p>
<input type="text" id="title" name="title" size="40" value="" tabindex="2" accesskey="v" />
<label for="title">Title</label>
</p>
<p>
<textarea id="excerpt" name="excerpt" cols="40" rows="10" tabindex="3" accesskey="e">--</textarea>
<label for="excerpt">Excerpt</label>
</p>
<p>
<input type="text" id="blog_name" name="blog_name" size="40" value="" tabindex="4" accesskey="b" />
<label for="blog_name">Blog Name</label>
</p>
<p class="submit-button">
<input type="submit" value="Send Trackback" tabindex="5" accesskey="s" />
</p>
</form>
</div>
EOD;

xhtml_output('');

?>