<?php
/**
 * Save & Manage Session Files in MySQL
 *
 * $Id: admin/db_session.php, 2005/02/05 14:05:49 Exp $
 */


/**
 * Open DB Connection
 */
function open($save_path, $session_name) {
    global $dbname, $host, $user, $password;
    if (isset($dbname, $host, $user, $password)) {
        db_connect($dbname, $host, $user, $password);
    }
    return TRUE;
}


/**
 * Close DB Connection
 */
function close() {
    return TRUE;
}


/**
 * Read Session Info From DB
 */
function read($id) {
    global $session_table;
    $id  = addslashes($id);
    $sql = "SELECT `sess_var` FROM `{$session_table}` WHERE `id` = '{$id}'";
    $res = mysql_query($sql) or die(mysql_error());
    list($sess_var) = mysql_fetch_row($res);
    return $sess_var;
}



/**
 * Save Session Info into DB
 */
function write($id, $sess_var) {
    global $session_table;
    $id       = addslashes($id);
    $sess_var = addslashes($sess_var);
    $sql = "SELECT `id` FROM `{$session_table}` WHERE `id` = '{$id}'";
    $res = mysql_query($sql) or die(mysql_error());
    list($exist_id) = mysql_fetch_row($res);
    if ($exist_id) {
        $sql = "UPDATE `{$session_table}` SET `sess_var` = '{$sess_var}', `sess_date` = UNIX_TIMESTAMP(NOW()) WHERE `id` = '{$id}'";
    } else {
        $sql = "INSERT INTO `{$session_table}` VALUES ('{$id}', '{$sess_var}', UNIX_TIMESTAMP(NOW()))";
    }
    $res = mysql_query($sql);
    return TRUE;
}


/**
 * Delete Session Info
 * this function is called when execute session_destroy()
 */
function destroy($id) {
    global $session_table;
    $id  = addslashes($id);
    $sql = "DELETE FROM `{$session_table}` WHERE `id` = '{$id}'";
    $res = mysql_query($sql) or die(mysql_error());
    return TRUE;
}


/**
 * Garbage Collection
 */
function gc($maxlifetime) {
    global $session_table;
    $expiration_time = time() - $maxlifetime;
    $sql = "DELETE FROM `{$session_table}` WHERE `sess_date` < '" . $expiration_time . "'";
    $res = mysql_query($sql) or die(mysql_error());
    return TRUE;       
}

$maxlifetime = get_cfg_var("session.gc_maxlifetime");
//$maxlifetime = '10';
session_set_save_handler("open", "close", "read", "write", "destroy", "gc");
session_start();

?>