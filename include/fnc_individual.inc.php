<?php
/**
 * Functions for Individual page only
 *
 * $Id: fnc_individual.inc.php, 2004/08/13 16:31:16 Exp $
 */

//================================================================
// INDIVIDUAL ARTICLE PAGE
//================================================================
/**
 * Article URI Generator
 */
function display_article_uri($row, $id) 
{
    global $cfg, $lang;
    
    if ($cfg['display_log_uri'] == 'yes') {
    
        $row['name'] = htmlspecialchars($row['name']);
        $article_uri =<<<EOD
<fieldset> 
<legend accesskey="a">{$lang['log_uri']}</legend>
XHTML Code :<br />
<pre>
&lt;a href="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}article.php?id={$id}" title="{$cfg['blog_title']} : {$row['name']}" &gt;
{$row['name']}
&lt;/a&gt;
</pre>
</fieldset>
EOD;
    } else {
        $article_uri = '';
    }
    return $article_uri;
}




/**
 * Article URI Generator for Binary File
 */
function display_binary_uri($row, $id) 
{
    global $cfg, $lang;
    
    if ($cfg['display_log_uri'] == 'yes'){

        $row['binname'] = htmlspecialchars($row['binname']);
        $log_uri =<<<EOD
<fieldset> 
<legend accesskey="a">{$lang['log_uri']}</legend>
<form>
<p><input value="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}files/article.php?id={$id}" size="60" class="uri" /></p>
<h4>XHTML code:</h4>
<textarea rows="5" cols="60" class="uri-code" />
&lt;a href="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}files/article.php?id={$id}" title="{$cfg['blog_title']} : {$row['binname']}" &gt;
{$row['binname']}
&lt;/a&gt;
</textarea>
</form>
</fieldset>
EOD;
    } else {
        $log_uri = '';
    }
    return $log_uri;
}


/**
 * Display By ID
 */
function display_by_id_form($display_by_id_script) 
{
    $id_form =<<<EOD
<h2>Find By ID</h2>
<p>No ID specified. Please enter the ID.</p>
<form action="./{$display_by_id_script}.php" method="get">
<p>
<input type="text" name="id" />
<input type="submit" value="Go" />
</p>
</form>
EOD;
    return $id_form;
}




// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>