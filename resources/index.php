<?php
/**
 * Resources directory index
 * 
 * $Id: resoures/index.php, 2005/02/05 17:53:10 Exp $
 */


$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';

session_control();

if ($session_status == 'on') {
    
    $sql = "SELECT `user_id` FROM `{$user_table}` WHERE `user_pass` = '". $_SESSION['user_pass'].
           "' AND `user_name` = '" .$_SESSION['user_name']. "' LIMIT 1";
    $res = mysql_query($sql);
    $row = mysql_num_rows($res);
    if ($row != 0) {
        if (isset($_POST['del'])) {
            $del = $_POST['del'];
            unlink($del);
        }
        $file_list = '';
        if ($dir = @opendir("./")) {
            while ($filename = readdir($dir)) {
                if ($filename != '.' && $filename != '..' && $filename != 'index.php' && $filename != '.DS_Store' && $filename != 'go_to_admin.php' && $filename != 'list.php') {
                    $fd = filectime($filename);
                    $fd = date("Y-m-d (D), G:i:s", $fd);
                    $ft = filetype($filename);
                    $fs = filesize($filename) / 1024;
                    $fs = round($fs, 1);
                    $width_height = getimagesize($filename);
                    if ($width_height == NULL) {
                        $width_height = '-';
                    } else {
                        $width_height = $width_height[0].'&times;'.$width_height[1];
                    }
                    $file_list .= '<tr>'."\n".
                                 '<td class="colored"><a href="'.$filename.'">' .$filename. '</a></td>'."\n".
                                 '<td class="colored">'.$width_height.'</td>'."\n".
                                 '<td class="colored">'.$fs.' KB</td>'."\n".
                                 '<td class="colored">'.$fd.'</td>'."\n".
                                 '<td class="colored">'."\n".
                                 ' <form method="post" action="./index.php">'."\n".
                                 '  <input type="submit" value="'.$lang['delete'].'" />'."\n".
                                 '   <input type="hidden" name="del" value="'.$filename.'" />'."\n".
                                 ' </form>'."\n".
                                 '</td>'."\n".
                                 "</tr>\n";
                }
            }
        }
        closedir($dir);
        $contents =<<<EOD
<h2 class="archive-title">Resources</h2>
<table summary="resources" class="colored">
<tr>
<th class="colored">File Name</th><th class="colored">W &#215; H</th>
<th class="colored">Size</th><th class="colored">Created</th>
<th class="colored">Delete</th>
</tr>
{$file_list}
</table>
EOD;
    } else {
        $contents = '';
    }
        
    
    xhtml_output('');
    
} else {
    header("HTTP/1.0 404 Not Found");
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
