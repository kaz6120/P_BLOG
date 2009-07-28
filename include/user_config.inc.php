<?php
/**
 * P_BLOG User Configuration File
 *
 * $Id: include/user_config.inc.php, 2006-05-27 15:45:29 Exp $
 */

///////////////// BASE SETTINGS //////////////////////

$dbname          = 'p_blog';        // Your MySQL DB name
$log_table       = 'p_blog_log';    // Main LOG table name
$info_table      = 'p_bin';         // Meta data table for FILES
$data_table      = 'p_bin_data';    // Data table for FILES
$analyze_table   = 'p_anama';       // Access Analyzer table
$user_table      = 'p_blog_user';   // User table
$config_table    = 'p_config';      // Configuration table
$trackback_table = 'p_trackback';   // Trackback table
$forum_table     = 'p_forum';       // Forum table
$session_table   = 'p_session';     // Session table

$host            = 'localhost';     // MySQL hostname
$user            = 'mysql-user';    // Your MySQL user name.
$password        = 'mysql-pass';    // Your MySQL password.

$admin_dir       = 'admin';         // Admin Directory

// Comment & Trackback Spam Block Settings
$block_spam = array(
'tags' => '/^<\/?(?:h1|h2|h3|h4|h5|h6|a|p|pre|blockquote|div|hr)/i',
'keywords' => '/.*(buy|viagra|online|cheap|discount|low|xanax|hydrocodone|sex|casino)/i',
'deny_1byteonly' => 'no', // 'yes' or 'no'
'comment_field_name' => 'nROEN2', // ### PLEASE CHANGE ! ###
'uri_count' =>'5' // max uri count in comment
);

//////////////////// FINISH! //////////////////////


/**
 * Deny direct access to this file
 */
if (stristr($_SERVER['PHP_SELF'], '.inc.php')) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). '/index.php');
    exit;
}

?>
