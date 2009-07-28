<?php
/**
 * Insert Binary File into MySQL
 *
 * $Id: admin/bin_upload.php, 2005/11/13 17:41:50 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

session_control();

// No Header sendings

against_xss();

if ($session_status == 'on') {
    if (isset($_POST['bin_title'], $_POST['bin_category'], $_POST['bincomment'])) {
        $bin_title    = insert_safe($_POST['bin_title']);
        $bin_category = preg_replace('/,+$/', '', insert_safe($_POST['bin_category']));
        $bincomment   = insert_tag_safe($_POST['bincomment']);
        if ($cfg['enable_unicode'] == 'on'){
            mb_convert_variables($cfg['mysql_lang'], "auto", $bin_title, $bincomment, $bin_category);
        }
        $binfile    = $_FILES['binfile'];
        if (isset($binfile)) {
            clearstatcache(); //initialize
            $bin_src  = $binfile["tmp_name"];
            $bin_type = $binfile["type"];
            $bin_name = $binfile["name"];
            $bin_size = filesize($bin_src); //get the size of it
            if ((isset($_POST['bindate'])) && (preg_match("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", $_POST['bindate']))) {
                $bin_date = insert_safe($_POST['bindate']);
                $cmod     = preg_replace("/^([0-9]+)-([0-9]+)-([0-9]+).([0-9]+):([0-9]+):([0-9]+)$/", "$1$2$3$4$5$6", $bin_date);
            } else {
                $time     = filemtime($bin_src); //get the last access date of it
                $bin_date = gmdate("Y-m-d H:i:s", $time + ($cfg['tz'] * 3600)); //format the UNIX timestamp
                $cmod     = gmdate('YmdHis',      time() + ($cfg['tz'] * 3600));
            }            
            if (file_exists($bin_src)) { //if file exists...
            
                // put these info into the data-info table
                $sql  = 'INSERT INTO ' . $info_table .
                        " (`bin_title`, `bintype`, `binname`, `binsize`, `bindate`, `bin_mod`, `bin_category`, `bincomment`, `draft`)".
                        " VALUES ('{$bin_title}', '{$bin_type}', '{$bin_name}', '{$bin_size}', ".
                        "'{$bin_date}', '{$cmod}', '{$bin_category}', '{$bincomment}', '1')";
                if (!$res = mysql_query($sql)) {
                    die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                }
            
                // put data into the data table
                // $binaryid = mysql_insert_id($connect);
                $binaryid = mysql_insert_id();
            
                // open the file, put it in the file pointer(fp) with "r(read)" mode.
                $fp = fopen($bin_src, "rb");
                while (!feof($fp)) {
                
                    //  "addslash" the binary data before insert.
                    //  Max values of string-type fields are:
                    //    (1)BLOB= 65535 byte,
                    //    (2)MEDIUMBLOB= 16777215 byte(1.6MB),
                    //    (3)LONGBLOB= 4294967295 byte(4.2GB)
                    //
                    // *KEEP IT SMALL* to insert it safely.
                    // 10 times of BLOB size is my choice..
                    // 65535 byte*10=655350 byte↓
                    $binarydata = addslashes(fread($fp, 655350)); //10*(Max-value-of-BLOB)
			
                    $sql2  = 'INSERT INTO ' . $data_table . ' (`masterid`, `bindata`) ' .
                             "VALUES ('" .$binaryid. "', '" .$binarydata. "')";
                    if (!mysql_query($sql2)) {
                        die("<h2>MySQL error</h2> " . mysql_errno() . " : " . mysql_error());
                    }
                }
                fclose($fp); //close the file...
   
                // upload attachment files
                file_upload();
                
                $id = $binaryid;
                if ($id) {
                    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir . '/bin_draft_preview.php?id='.urlencode($id));
                    exit;
                }
            } //close "file_exists..."
        } //close "isset..."
    } else {
        header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
        exit;
    }
} else {
    header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'index.php');
    exit;
}
?>
