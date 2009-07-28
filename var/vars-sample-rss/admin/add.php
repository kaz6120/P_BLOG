<?php
/**
 * RSS link - add
 *
 * $Id: rss/admin/add.php, 2005/01/22 23:24:07 Exp $
 */

//require_once '../lib/php/XML/RSS.php';
$cd = '../../..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
        //echo '<h2 class="archive-title">ADD RSS Feed</h2>';

        if (isset($_POST['r_name'], $_POST['r_uri'], $_POST['r_category'])) {
            $r_name = $_POST['r_name'];
            $r_uri  = $_POST['r_uri'];
            $r_category = $_POST['r_category'];
            $sql = 'INSERT INTO p_rss_box(r_name, r_uri, r_category, r_date) '.
                   "VALUES('$r_name', '$r_uri', '$r_category', CURRENT_TIMESTAMP())";
            if (!mysql_query($sql)) {
                die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            } else {
                $contents =<<<EOD
<div class="section">
<h2>RSS has been fed.</h2>
<p class="ref">
<a href="{$_SERVER['PHP_SELF']}">BACK</a>
</p>
</div>
EOD;
            }
    

        } else {
            $contents =<<<EOD
<div class="section">
<ul class="flip-menu">
<li><span class="cur-tab">Feed RSS</span></li>
<li><a href="./modify.php">{$lang['mod_del']}</a></li>
</ul>
<h2>NEW RSS Feed</h2>
<form method="post" action="{$_SERVER['PHP_SELF']}">
<dl>
<dt>RSS NAME：</dt>
<dd><input type="text" tabindex="1" accesskey="n" name="r_name" value="" /></dd>
<dt>URI：</dt>
<dd><input type="text" tabindex="2" accesskey="u" name="r_uri" value="" size="40" /></dd>
<dt>CATEGORY：</dt>
<dd><input type="text" tabindex="3" accesskey="c" name="r_category" value="" /></dd>
</dl>
<p class="submit-button">
<input type="submit"  tabindex="5" accesskey="f" value="FEED THIS!" />
</p>
</form>
</div>
EOD;
        }
    } else {
        $contents = bad_req_error();
    }

xhtml_output('');
?>
