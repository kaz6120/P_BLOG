<?php
/**
 * Display Articles Individually
 *
 * $Id: article.php, 2005-12-06 13:59:43 Exp $
 */

$cd = '.';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_individual.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/include/fnc_logs.inc.php';

session_control();

if (isset($_REQUEST['id']) && $_REQUEST['id'] !== null) {
    $id = abs(intval($_REQUEST['id']));
    $id_next = abs(intval($_REQUEST['id'])) + 1;
    $id_prev = abs(intval($_REQUEST['id'])) - 1;

    if ($session_status == 'on') {
        $draft_id_sql = "`id` = '" . $id . "'";
        $draft_id_sql_next = "`id` = '" . $id_next . "'";
        $draft_id_sql_prev = "`id` = '" . $id_prev . "'";
    } else {
        $draft_id_sql = "(`draft` = '0') AND (`id` = '{$id}')";
        $draft_id_sql_next = "(`draft` = '0') AND (`id` >= '{$id_next}') ORDER BY `id` ASC LIMIT 1";
        $draft_id_sql_prev = "(`draft` = '0') AND (`id` <= '{$id_prev}') ORDER BY `id` DESC LIMIT 1";
    }

    if (file_exists($cd . '/include/user_include/article_addition.inc.php')) {
        include_once $cd . '/include/user_include/article_addition.inc.php';
    } else {
        $article_addition = '';
    }

    $sql = 'SELECT'.
           " `id`, `href`, `name`, `date`, DATE_FORMAT(`mod`, '%Y-%m-%d %T') as `mod`, `comment`, `category`, `draft`".
           " FROM `{$log_table}` WHERE " . $draft_id_sql;
    $sql_next = 'SELECT'.
                " `id`, `name`".
                " FROM `{$log_table}` WHERE " . $draft_id_sql_next;
    $sql_prev = 'SELECT'.
                " `id`, `name`".
                " FROM `{$log_table}` WHERE " . $draft_id_sql_prev;
    $res       = mysql_query($sql);
    $row       = mysql_fetch_array($res);
    $row_next  = mysql_fetch_array(mysql_query($sql_next));
    $row_prev  = mysql_fetch_array(mysql_query($sql_prev));
    if ($row_next || $row_prev) {
        if ($row_next && !$row_prev) {// First Entry
            $row_next = convert_to_utf8($row_next);
            $next_title = htmlspecialchars(strip_tags($row_next['name']));
            $next_entry =<<<EOD
<p class="flip-link">
<span class="next"><a href="./article.php?id={$row_next['id']}" title="&quot;{$next_title}&quot;">{$lang['next']}</a></span>
</p>
EOD;
        } elseif (!$row_next && $row_prev) {// Latest Entry
            $row_prev = convert_to_utf8($row_prev);
            $prev_title = htmlspecialchars(strip_tags($row_prev['name']));
            $next_entry =<<<EOD
<p class="flip-link">
<span class="prev"><a href="./article.php?id={$row_prev['id']}" title="&quot;{$prev_title}&quot;">{$lang['prev']}</a></span>
</p>
EOD;
        } else {
            $row_next = convert_to_utf8($row_next);
            $row_prev = convert_to_utf8($row_prev);
            $next_title = htmlspecialchars(strip_tags($row_next['name']));
            $prev_title = htmlspecialchars(strip_tags($row_prev['name']));
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
        format_date($row_name = 'date');
        $title_date = $formatted_date;

        $article_box = display_article_box($row);
        $contents  =<<<EOD
<div class="section">
{$next_entry}
<h2 class="date-title">{$title_date}</h2>
{$article_box}
</div>
EOD;

    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'var/contents/index.php?id=error404');
        exit;
    }
} else {
    $id_form = display_by_id_form('article');
    $contents =<<<EOD
<div class="section">
{$id_form}
</div>
EOD;
}

xhtml_output('log');

?>