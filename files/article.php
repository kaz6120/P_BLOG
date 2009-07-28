<?php
/**
 * Display Binary Files Individually
 *
 * $Id: files/article.php, 2006-06-04 22:46:52 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_individual.inc.php';
require_once './include/fnc_files.inc.php';

session_control();

if (isset($_REQUEST['id']) && $_REQUEST['id'] !== NULL){

    $id = abs(intval($_REQUEST['id']));
    $id_next = abs(intval($_REQUEST['id'])) + 1;
    $id_prev = abs(intval($_REQUEST['id'])) - 1;
    if ($session_status == 'on') {
        $draft_id_sql = "`id` = '{$id}'";
        $draft_id_sql_next = "`id` = '" . $id_next . "'";
        $draft_id_sql_prev = "`id` = '" . $id_prev . "'";
    } else {
        $draft_id_sql = "(`draft` = '0') AND (`id` = '{$id}')";
        $draft_id_sql_next = "(`draft` = '0') AND (`id` >= '{$id_next}') ORDER BY `id` ASC LIMIT 1";
        $draft_id_sql_prev = "(`draft` = '0') AND (`id` <= '{$id_prev}') ORDER BY `id` DESC LIMIT 1";
    }

    $sql  = 'SELECT ' .
            "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, ".
            "DATE_FORMAT(`bin_mod`,'%Y-%m-%d %T') as `bin_mod`, `bin_category`, ".
            "`bincomment`, `bin_count`, `draft`" .
            ' FROM `'. $info_table . '`'.
            " WHERE " . $draft_id_sql;
    $sql_next = 'SELECT'.
                " `id`, `bin_title`".
                " FROM `{$info_table}` WHERE " . $draft_id_sql_next;
    $sql_prev = 'SELECT'.
                " `id`, `bin_title`".
                " FROM `{$info_table}` WHERE " . $draft_id_sql_prev;
    $res = mysql_query($sql);
    $row = mysql_fetch_array($res);
    $row_next  = mysql_fetch_array(mysql_query($sql_next));
    $row_prev  = mysql_fetch_array(mysql_query($sql_prev));
    if ($row_next || $row_prev) {
        if ($row_next && !$row_prev) {// First Entry
            $row_next = convert_to_utf8($row_next);
            $next_title = htmlspecialchars(preg_replace('/&quot;/', '"', strip_tags($row_next['bin_title'])));
            $next_entry =<<<EOD
<p class="flip-link">
<span class="next"><a href="./article.php?id={$row_next['id']}" title="&quot;{$next_title}&quot;">{$lang['next']}</a></span>
</p>
EOD;
        } elseif (!$row_next && $row_prev) {// Latest Entry
            $row_prev = convert_to_utf8($row_prev);
            $prev_title = htmlspecialchars(preg_replace('/&quot;/', '"', strip_tags($row_prev['bin_title'])));
            $next_entry =<<<EOD
<p class="flip-link">
<span class="prev"><a href="./article.php?id={$row_prev['id']}" title="&quot;{$prev_title}&quot;">{$lang['prev']}</a></span>
</p>
EOD;
        } else {
            $row_next = convert_to_utf8($row_next);
            $row_prev = convert_to_utf8($row_prev);
            $next_title = htmlspecialchars(preg_replace('/&quot;/', '"', strip_tags($row_next['bin_title'])));
            $prev_title = htmlspecialchars(preg_replace('/&quot;/', '"', strip_tags($row_prev['bin_title'])));
            $next_entry =<<<EOD
<p class="flip-link">
<span class="prev"><a href="./article.php?id={$row_prev['id']}" title="&quot;{$prev_title}&quot;">{$lang['prev']}</a></span>
<span class="next"><a href="./article.php?id={$row_next['id']}" title="&quot;{$next_title}&quot;">{$lang['next']}</a></span>
</p>
EOD;
        }
    } else {
        $next_entry = '';
    }
    if ($row) {
        $row = convert_to_utf8($row);
        $binary_box = display_binary_box($row);
        $contents =<<<EOD
<div class="section">
{$next_entry}
<h2>{$lang['file']} ID : {$id}</h2>
{$binary_box}
</div>
EOD;

    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'var/index.php?id=error404');
        exit;
    }
} else {
    $id_form = display_by_id_form('article_bin');
    $contents =<<<EOD
<div class="section">
{$id_form}
</div>
EOD;
}

xhtml_output('file');

?>
