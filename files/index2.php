<?php
/**
 * Binary File Downloader Index 2
 *
 * $Id: files/index2.php, 2004/12/20 10:53:54 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_files.inc.php';

session_control();

function display_binary_box2($row)
{
    global $cfg, $lang;

    $bin_type = $row['bintype'];//Check file types
    $bin_size = $row['binsize'] / 1024;//Convert "Byte" to "KB"
    settype($bin_size, "int");//Convert size to integer

	$output_comment = strip_tags($row['bincomment']);
	$pos = strpos($output_comment, "\n");
	if ($pos) {
		$output_comment = substr($output_comment, 0, $pos);
	}
	$contents =<<<EOD
<li><a href="http://{$_SERVER['HTTP_HOST']}{$cfg['root_path']}files/article.php?id={$row['id']}">{$row['bin_title']}</a><br />
{$output_comment}<br />
{$row['bin_mod']}</li>\n
EOD;
    return $contents;
}

// SQL
$cate_sql = "SELECT DISTINCT `bin_category` FROM `" . $info_table . "` WHERE `draft` = '0' ORDER BY `bin_category`";
$cate_res = mysql_query($cate_sql);

if ($cate_res) {

	if ($cfg['use_2_indexes'] == 'yes') {
	    $category_sort = '<p class="flip-link"><a href="./index.php">'.$lang['by_date'].'</a> <strong>'.$lang['by_category'].'</strong></p>';
	} else {
	    $category_sort = '';
	}

	$contents =<<<EOD
<div class="section">
<h2 id="archive-title">{$cfg['file_index_title']}</h2>
{$category_sort}
EOD;

	while ($row = mysql_fetch_array($cate_res)) {
		$sql = 'SELECT '.
               "`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, DATE_FORMAT(`bin_mod`,'%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`".
               ' FROM '.$info_table." WHERE (`draft` = '0') AND (`bin_category` = '".$row['bin_category']."') ORDER BY `bindate` DESC LIMIT ".($cfg['max_listup']+1);
		$res = mysql_query($sql);
		if (!$res) {
			die(mysql_error());
		}
		$row = convert_to_utf8($row);
		$contents .= '<div class="section">'. "\n".
		             '<h3>'.$row['bin_category']."</h3>\n<ul>\n";
		$i = 0;
		while ($row = mysql_fetch_array($res)) {
			if ($i >= $cfg['max_listup']) {
			    $bin_category = utf8_convert($row['bin_category']);
				$contents .= "</ul>\n".
				           '<ul>'.
				           '<li>'.
      				       '<a href="./category.php?k='.urlencode($bin_category).'&amp;d=&amp;p=0&amp;pn=1&amp;c=" title="'.$bin_category.'">'.
                           $lang['more'].
		      		       '</a>'.
		      		       '</li>';
			} else {
				$row = convert_to_utf8($row);
				$contents .= display_binary_box2($row, $data_table);
				$i++;
			}
		}
		$contents .= "</ul>\n</div>\n";
	}
	$contents .= "</div><!-- End .section -->\n";
} else {
    $contents .= "\n".
                 '<div class="section">'."\n".
                 '<h2>Welcome to ' . $cfg['blog_title'] . " !</h2>\n".
                 '<p>' . $lang['no_files'] . "</p>\n".
                 "</div>\n";
}


xhtml_output('file');

?>