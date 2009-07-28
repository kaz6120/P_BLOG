<?php
/**
 * MySQL Database Table Backup
 *--------------------------------------------------------------------------------------------
 * based on :
 *   PHP-X-Links - "a simple link repository"
 *   https://68.97.138.88/
 *   Copyright (C) 2003  Jesse Waite
 *  
 *   X-Link-Indexer
 *   A simple links page management system.
 *   Copyright (C) 2003 nakamuxu.
 *
 *   PhpMyAdmin 2.5.5-pl1
 *   http://www.phpmyadmin.net/
 *   Copyright (C) phpMyAdmin devel team
 *
 *--------------------------------------------------------------------------------------------
 * $Id: db_backup.php, 2004/12/29 20:15:11 Exp $
 */

$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

// time format
$date_fname_format = "ymd-Hi";


/**
 * dbDump()
 * database dump
 * 
 * @param array $tables 
 * @return 
 */
function dbDump($tables, $onfly_compression = FALSE)
{
    global $dbname, $host;
    
    $time_start = time();
    if ($onfly_compression) {
        $memory_limit = trim(@ini_get('memory_limit'));
        if (empty($memory_limit)) {
            $memory_limit = 2 * 1024 * 1024;// 2 MB as default
        }
        if (strtolower(substr($memory_limit, -1)) == 'm') {
            $memory_limit = (int)substr($memory_limit, 0, -1) * 1024 * 1024;
        } elseif (strtolower(substr($memory_limit, -1)) == 'k') {
            $memory_limit = (int)substr($memory_limit, 0, -1) * 1024;
        } elseif (strtolower(substr($memory_limit, -1)) == 'g') {
            $memory_limit = (int)substr($memory_limit, 0, -1) * 1024 * 1024 * 1024;
        } else {
            $memory_limit = (int)$memory_limit;
        }
    } else {
        $memory_limit = 0;
    }

    // Some of memory is needed for other thins and as treshold.
    // Nijel: During export I had allocated (see memory_get_usage function)
    //        approx 1.2MB so this comes from that.
    if ($memory_limit > 1500000) $memory_limit -= 1500000;

    // Some memory is needed for compression, assume 1/3
    $memory_limit *= 2/3;
    $buffer = '';
    $buffer_len = 0;
    $lines = '# P_BLOG SQL dump'."\n".
            '#'."\n".
            '# Created by J. Waite for Php-X-Links.'."\n".
            '# Modified by nakamuxu for X-Link-Indexer.'."\n".
            '# Modified by kaz for P_BLOG.'."\n".
            '#'."\n".
            '# Host: '.$host."\n".
            '# System: '.$_SERVER['SERVER_SOFTWARE']."\n".
            '# PHP version: '.phpversion()."\n".
            '#'."\n".
            '# Database: '.$dbname."\n".
            '# Created: '.date("Y-m-d H:i:s")."\n#\n";
    $search = array("\x00", "\x0a", "\x0d", "\x1a"); // ### Must be double-quoted !
    $replace = array('\0', '\n', '\r', '\Z');


    for ($i = 0; $i < count($tables); $i++) {
        $lines .= "\n".'#-------------------------------'."\n";
        $lines .= "\n".'#'."\n".'# Table structure for `' . $tables[$i] . "`\n#\n";
        $result = mysql_query('SHOW CREATE TABLE ' . $tables[$i]) or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
            $lines .= 'DROP TABLE IF EXISTS `' . $tables[$i] . '`;'."\n";
            $lines .= $row['Create Table'];
            $lines .= ";\n";
            $query = mysql_query("SELECT * FROM `" . $tables[$i] . "`");
            $num = mysql_num_rows($query);
            if ($num > 0) {
                $lines .= "\n#\n# Dumping data for table `" . $tables[$i] . "`\n# Records: " . $num . "\n#\n";
                $lines .= 'INSERT INTO `' . $tables[$i] . '` VALUES ';
                $n = 1;
                while ($r = mysql_fetch_row($query)) {
                    $lines .= '(';
                    $count = count($r);
                    for ($x = 0; $x < $count; $x++) {
                        $r[$x] = str_replace($search, $replace, addslashes($r[$x]));
                        $lines .= (is_numeric($r[$x])) ? $r[$x]: "'" . $r[$x] . "'";
                        $lines .= ($x != ($count - 1)) ? ',': '';
                    } 
                    $lines .= ')';
                    $lines .= ($n < $num) ? ",\n": ";\n";
                    $buffer .= $lines;
                    $buffer_len .= strlen($lines);
                    $lines = '';
                    if ($onfly_compression) {
                            if ($buffer_len > $memory_limit) {
                            echo gzencode($buffer);
                            $buffer = '';
                            $buffer_len = 0;
                        }
                    } else {
                        $time_now = time();
                        if ($time_start >= $time_now + 30) {
                            $time_start = $time_now;
                            header('X-xliPing: Pong');
                        }
                    }
                    $n++;
                }
            }
        }
    }
    if ($onfly_compression) {
        echo gzencode($buffer);
    } else {
        $buffer = gzencode($buffer);
        header('Content-Length: '.strlen($buffer));
        echo $buffer;
    }
    unset($buffer);
    unset($lines);
}

// Error Message
$error_div =<<<EOD
<div class="section">
<h2>{$lang['system_admin']}</h2>
<ul class="flip-menu">
<li><a href="./admin_top.php">{$lang['sys_env']}</a></li>
<li><a href="./preferences.php">{$lang['preferences']}</a></li>
<li><a href="./edit_menu.php">{$lang['edit_custom_file']}</a></li>
<li><a href="./db_status.php">{$lang['db_table_status']}</a></li>
</ul>
<div class="section">
<h3>Oops!</h3>
<p class="warning">{$lang['choose_table']}</p>
</div>
</div>
EOD;

session_control();

against_xss();

if ($session_status == 'on') {
    if (isset($_REQUEST['tables'])) {
        $date = date($date_fname_format);
        header('Content-type: application/x-download');
        //header('Content-type: application/octet-stream');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        if (preg_match('@MSIE ([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'])) {
			header('Content-Disposition: inline; filename="' . $dbname . '-' . $date . '.sql.gz');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
		} else {
			header('Content-Disposition: attachment; filename=' . $dbname . '-' . $date . '.sql.gz');
			header('Pragma: no-cache');
		}
		dbDump($_REQUEST['tables']);
		die();
	} else {
        $contents = $error_div;
        xhtml_output($contents);
    }
} else {
    die('<h1>Oops!</h1>');
}
?>