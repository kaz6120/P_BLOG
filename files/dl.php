<?php
/**
 * Binary File Download Counter
 *
 * $Id: files/dl.php, 2006-06-04 22:32:41 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //pull out the meta data from binary info table
    $sql = 'SELECT * FROM ' . $info_table . " WHERE id='" . $id . "'";
    if (!$res = mysql_query($sql)) {
        die('<h2>MySQL error</h2> ' . mysql_errno() . ' : ' . mysql_error());
    }
    if (mysql_num_rows($res) != 1) {
        die('<h2>MySQL error</h2> ' . mysql_errno() . ' : ' . mysql_error());
    }
    $bin_object = mysql_fetch_object($res);
    
    if ($bin_object->draft == 0) {
        // update downlaod counter
        $bin_mod = $bin_object->bin_mod;
        $dl_count_sql = 'UPDATE ' . $info_table 
                      . " SET bin_count = ifnull(bin_count, 0) + 1, bin_mod = '{$bin_mod}'"
                      . " WHERE id = '{$id}'";
        if (!$dl_count_res = mysql_query($dl_count_sql)) {
            die('<h2>MySQL error</h2> ' . mysql_errno() . ' : ' . mysql_error());
        }
        header('Location: ' . $http . '://' 
                            . $_SERVER['HTTP_HOST'] 
                            . $cfg['root_path'] 
                            . 'files/bin.php?id=' . $id . "");
    }
}
?>