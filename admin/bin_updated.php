<?php
/**
 * Update Meta Data Information of Binary File in MySQL
 *
 * $Id: admin/bin_updated.php, 2005/11/13 17:56:10 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

against_xss();

if ($session_status == 'on') {      
    if (isset($_POST['bin_title'], $_POST['binname'], $_POST['bin_category'], $_POST['bincomment'])) {
        // Get the parameters posted from "update.php"
        if ($_POST['bin_title'] == '') {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'. $lang['no_title'] . '</h3>'."\n";
        } elseif ($_POST['binname'] == '') {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'. $lang['no_f_name'] . '</h3>'."\n";
        } elseif ($_POST['bincomment'] == '') {
            $contents = "<h2>Ooops.</h2>\n".'<h3 class="warning">'. $lang['no_comment'] . '</h3>'."\n";
        } else {
            $id           = insert_safe(intval($_POST['id']));
            $bin_title    = insert_safe($_POST['bin_title']);
            $binname      = insert_safe($_POST['binname']);
            $bin_category = preg_replace('/,+$/', '', insert_safe($_POST['bin_category']));
            $bincomment   = insert_tag_safe($_POST['bincomment']);
            
            if ($cfg['enable_unicode'] == 'on') {
                mb_convert_variables($cfg['mysql_lang'], "auto", $bin_title, $binname, $bincomment, $bin_category);
            }
            
            // Update query
            $sql  = 'UPDATE ' . $info_table . 
                    " SET bin_title = '{$bin_title}', binname = '{$binname}', bin_category = '{$bin_category}', bincomment = '{$bincomment}'";
            if (isset($_POST['no_update_mod'])) {
                $bin_mod = $_POST['bin_mod'];
                $sql    .= ", bin_mod = '{$bin_mod}'";
            } else {
                $cmod = gmdate('YmdHis', time() + ($cfg['tz'] * 3600));
                $sql .= ", bin_mod = '{$cmod}'";
            }
            // Make private
            if (isset($_POST['private'])) {
                $sql .= ", `draft` = '1'";
            }
            
            $sql .= "WHERE id = '{$id}'";
            mysql_query($sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
            $new_id = $id;
            
            // (1) If new replace file is posted check the master id.
            // (2) Empty the data with target master id.
            // (3) Insert the data with new master id.

            if (!empty($_FILES['binfile']) && (isset($_POST['replace_file']))) {
            
                // Delete the old data...
                $del_sql = 'DELETE FROM ' . $data_table . ' WHERE masterid = ' . $id;
                if (!$del_res = mysql_query($del_sql)) {
                    die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                }
                 
                // ... and insert new data. IDs are new, but master id is still the same.
                $binfile  = $_FILES['binfile'];
                $bin_src  = $binfile["tmp_name"];
                $bin_type = $binfile["type"];
                $bin_name = $binfile["name"];
                $bin_size = filesize($bin_src); //get the size of it
                
                // 
                if (isset($_POST['no_update_mod'])) {
                    $bin_mod = $_POST['bin_mod'];
                    $mod_sql = ", bin_mod = '{$bin_mod}'";
                } else {
                    $mod_sql = '';
                }
            
                // Update the info table
                $update_sql = 'UPDATE ' . $info_table .
                              " SET binname = '{$bin_name}', bintype = '{$bin_type}', binsize = '{$bin_size}'" . $mod_sql .
                              " WHERE id = '{$id}'";
                mysql_query($update_sql) or die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                    
                $binaryid = $id;
                if (isset($bin_src, $bin_type, $bin_name, $bin_size)) {

                    $fp = fopen($bin_src, "rb");
                    while (!feof($fp)) {
                
                        $binarydata = addslashes(fread($fp, 655350)); //10*(Max-value-of-BLOB)
                        $sql2  = 'INSERT INTO ' . $data_table . ' (masterid, bindata) ' .
                                 'VALUES (' .$binaryid. ", '" .$binarydata. "')";
                        if (!mysql_query($sql2)) {
                            die ("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                        }
                    }
                    fclose($fp); //close the file...
                }
            }
            // End of uploading file
            
            $contents  = log_updated('files/index', 'bin_check');
            $contents .= file_uploaded();

            xhtml_output('');
        }
    } else{
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }

} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
