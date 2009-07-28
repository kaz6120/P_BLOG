<?php
/**
 * Update Draft Files
 *
 * $Id: admin/bin_draft_updated.php, 2005/11/13 17:42:23 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/files/include/fnc_files.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['bin_title'], $_POST['binname'], $_POST['bin_category'], $_POST['bincomment'])) {
        // Get the parameters posted from "update.php"
        if ($_POST['bin_title'] == '') {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_title']."</h3>\n";
        } elseif ($_POST['binname'] == '') {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_f_name']."</h3>\n";
        } elseif ($_POST['bincomment'] == '') {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'.$lang['no_comment']."</h3>\n";
        } else {
            $id           = insert_safe(intval($_POST['id']));
            $bin_title    = insert_safe($_POST['bin_title']);
            $binname      = insert_safe($_POST['binname']);
            $bin_category = preg_replace('/,+$/', '', insert_safe($_POST['bin_category']));
            $bincomment   = insert_tag_safe($_POST['bincomment']);
            // User custom date & time
            if ((isset($_POST['date'])) &&
                (isset($_POST['custom_date']) == 'yes') &&
                (preg_match("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", $_POST['date']))) {
                $bindate  = insert_safe($_POST['date']);
                $cmod     = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $bindate);

                // Update old mod-time same as current datetime
                $new_date = "`bindate` = '" . $bindate . "'";
                $new_mod  = ", `bin_mod` = '$cmod'";

            } else {
                $fdate    = gmdate('Y-m-d H:i:s', time() + ($cfg['tz'] * 3600));
                $cmod     = gmdate('YmdHis',      time() + ($cfg['tz'] * 3600));
                
                // set current time (GMT + Offset) in SQL          
                $new_date = "`bindate` = '{$fdate}'";
                // sync "date" and "mod"
                $new_mod = ", `bin_mod` = '{$cmod}'";

            }
            if ($cfg['enable_unicode'] == 'on') {
                mb_convert_variables($cfg['mysql_lang'], "auto", $bin_title, $binname, $bincomment, $bin_category);
            }
            // update query
            $sql  = 'UPDATE ' . $info_table.
                    " SET `bin_title` = '{$bin_title}', `binname` = '{$binname}', `bin_category` = '{$bin_category}', `bincomment` = '{$bincomment}', "  . $new_date . $new_mod;
            $sql .= " WHERE `id` = '{$id}'";
            mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());

            // (1) If new replace file is posted check the master id.
            // (2) Empty the data with target master id.
            // (3) Insert the data with new master id.

            if ((!empty($_FILES['binfile'])) && (isset($_POST['replace_file']))) {

                // Delete the old data...
                $del_sql = 'DELETE FROM ' . $data_table . ' WHERE `masterid` = ' . $id;
                if (!$del_res = mysql_query($del_sql)) {
                    die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                }
                 
                // ... and insert new data. IDs are new, but master id is still the same.
                $binfile  = $_FILES['binfile'];
                $bin_src  = $binfile["tmp_name"];
                $bin_type = $binfile["type"];
                $bin_name = $binfile["name"];
                $bin_size = filesize($bin_src); //get the size of it
                
                // Update the info table
                $update_sql = 'UPDATE ' . $info_table .
                              " SET `binname` = '{$bin_name}', `bintype` = '{$bin_type}', `binsize` = '{$bin_size}'".
                              " WHERE `id` = '{$id}'";
                mysql_query($update_sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                    
                $binaryid = $id;
                if (isset($bin_src, $bin_type, $bin_name, $bin_size)) {
                    $fp = fopen($bin_src, "rb");
                    while (!feof($fp)) {
                
                        $binarydata = addslashes(fread($fp, 655350)); //10*(Max-value-of-BLOB)
                        $sql2  = 'INSERT INTO ' . $data_table . ' (`masterid`, `bindata`) ' .
                                 'VALUES (' .$binaryid. ", '" .$binarydata. "')";
                        if (!mysql_query($sql2)) {
                            die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                        }
                    }
                    fclose($fp); //close the file...
                }
            }
            // End of uploading file
            
            // Show the article
            $sql  = 'SELECT '.
                    '`id`, `bin_title`, `bintype`, `binname`, `binsize`, `bindate`, '.
                    "DATE_FORMAT(`bin_mod`,'%Y-%m-%d %T') as `bin_mod`, `bin_category`, `bincomment`, `bin_count`, `draft`".
                    ' FROM ' . $info_table .
                    " WHERE `id` = '{$id}'";
            $res = mysql_query($sql);
            $row = mysql_fetch_array($res);
            $row = convert_to_utf8($row);
            format_date($row_name = 'bindate');
            $title_date = $formatted_date;
            $contents  = '<div class="section">'."\n".
                         '<h2 class="date-title">'.$title_date."</h2>\n";
            $contents .= display_binary_box($row);
            
            // Reformat the modification time to "yyyymmddhms"
            $cmod  = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $row['bin_mod']);
            $contents .=<<<EOD

<form method="post" action="./bin_draft_publish.php">
<div class="submit-button">
<input type="hidden" name="draft" value="0" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="bin_mod" value="{$cmod}" />
<input type="submit" tabindex="1" accesskey="p" value="{$lang['publish']}" />
</div>
</form>
EOD;
            $contents .= file_uploaded();
            $contents .= "\n</div><!-- End .section -->\n";
        }

        xhtml_output('');

    } else{ // if user auth failed...
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
