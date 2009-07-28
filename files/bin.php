<?php
/**
 * Binary Generator
 *
 * $Id: files/bin.php, 2006-06-04 22:30:31 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';

session_control();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Pull out the meta data from binary info table
    $sql = 'SELECT * FROM ' . $info_table . ' WHERE id=' . $id;
    if ((!$res = mysql_query($sql)) || (mysql_num_rows($res) != 1)) {
        die('<h2>MySQL error</h2> ' . mysql_errno() . ' : ' . mysql_error());
    }
    
    $bin_object = mysql_fetch_object($res);
    
    

//   if ((($bin_object->draft == 1) && ($session_status == 'on')) ||
//       ($bin_object->draft == 0)) {
        // Pull out the data from binary table
        $sql2 = 'SELECT `id` FROM ' . $data_table 
              . " WHERE `masterid` = '{$id}' ORDER BY `id`";
        if (!$res2 = mysql_query($sql2)) {
            die('<h2>MySQL error</h2> ' . mysql_errno() . ' : ' . mysql_error());
        }
        $list = array();
    	while ($list_object = mysql_fetch_object($res2)) {
            $list[] = $list_object->id;
        }

        // Send Data to Browser
        header('Content-Type: ' . $bin_object->bintype);
        header('Content-Length: ' . $bin_object->binsize);
        $deposition = (preg_match('/(image|text)/', $bin_object->bintype)) 
                      ? 'inline' 
                      : 'attachment';
        header('Content-Disposition: '.$deposition.'; filename='.$bin_object->binname);
        for ($i = 0; $i < count($list); $i++) {	
            $sql3 = 'SELECT bindata FROM ' . $data_table . ' WHERE id=' . $list[$i];
            if (!$res3 = mysql_query($sql3)) {
                die('<h2>MySQL error</h2> ' . mysql_errno() . ' : ' . mysql_error());
            }
            $data_object = mysql_fetch_object($res3);
            echo $data_object->bindata;
        }
//    }
}
?>