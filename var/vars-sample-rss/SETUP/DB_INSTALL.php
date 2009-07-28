<?php echo '<?xml version="1.0" encoding="utf-8" ?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja">
<head>
<title>DB Update Script</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="" type="text/css" />
<style type="text/css">
body {
    font-family:Trebuchet MS, "ヒラギノ角ゴ Pro W3", sans-serif;
    font-size:12px;
    margin:0px;
}
h1 {
    margin-top:10px; margin-left:0px;margin-bottom:0px;
    width:100%; height:32px;
    border-top:1px solid #669933;
    border-bottom:1px solid #669933;
    padding-top:5px; padding-bottom:5px;
    padding-left:12px;
    color:#666600;
    background:#99CC33;
}
h2 {
    width:100%; height:28px;
    margin-top:1em;
    padding-top:5px; padding-bottom:2px;
    padding-left:12px;
    border-bottom:1px dotted #608020;
    color:#608020;
    background:transparent;
}
h3 {
    padding-left:2em;
    color:#806020;
    background:transparent;
}
/*Anchor Tags*/
a:link {
    color:#6666CC; text-decoration:none;
    background-color:transparent;
}
a:visited {
    color:#806020; text-decoration:none;
    background-color:transparent;
}
a:hover {
    text-decoration:underline; color:#ff6600;
    font-style:normal; background-color:transparent;
}
</style>
<link rev="made" href="mailto:yourname@example.com" />
<link rel="start" href="" />
</head>
<body>
<h1>P_BLOG Update</h1>
<?php
$cd = '../../..';
require_once $cd . '/include/user_config.inc.php';
$report_error = 'no'; // yes or no

// ============================================================
// Connect
// ============================================================
mysql_connect("$host", "$user", "$password") or die ("<h2>MySQL Connection Error</h2>\n<h3>Why?： " .mysql_error()."</h3>\n");


// ============================================================
// Select Database
// ============================================================
$sql = 'USE ' . $dbname;
$res = mysql_query($sql);
if (!$res) {
    $sql1 = "CREATE DATABASE $dbname";
    $res1 = mysql_query($sql1);
    if (!$res1) {
        if ($report_error == 'yes') {
            die ("\n<h2>STEP1 : Create DB `$dbname` --- No.</h2>\n<h3>Why?： " .mysql_error()."</h3>\n");
        } else {
            echo "\n<h2>STEP1 : Create DB `$dbname` --- No.</h2>\n";
        }
    } else {
        echo "\n<h2>STEP1 : Create DB `$dbname` --- OK.</h2>\n";
    }
    $sql2 = "USE $dbname";
    $res2 = mysql_query($sql2);
    if (!$res2) {
        if ($report_error == 'yes') {
            die ("\n<h2>STEP2 : Select DB `$dbname`  --- No.</h2>\n<h3>Why?： " .mysql_error()."</h3>\n");
        } else {
            echo "\n<h2>STEP2 : Select DB `$dbname`  --- No.</h2>\n";
        }
    } else {
        echo "\n<h2>STEP2 : Select DB `$dbname`  --- OK.</h2>\n";
    }
} else {
    echo "\n<h2>STEP1 : Create DB `$dbname`  --- Skipped.</h2>\n".
         "\n<h2>STEP2 : Select DB `$dbname`  --- Skipped.</h2>\n";
}

// ============================================================
// UPDATE-1
// Create the downloaad counter field in file info table.
// ============================================================
$up_sql1 = "
CREATE TABLE `p_rss_box` (
  `r_id` int(11) NOT NULL auto_increment,
  `r_name` varchar(100) NOT NULL default '',
  `r_uri` varchar(255) NOT NULL default '',
  `r_category` varchar(50) NOT NULL default '',
  `r_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `r_mod` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`r_id`)
) TYPE = MYISAM
";
$up_res1 = mysql_query($up_sql1);
if (!$up_res1) {
    if ($report_error == 'yes') {
        die ("\n<h2>INSTALL-1 : Create Table `p_rss_box` --- No.</h2>\n<h3>Why?： " .mysql_error()."</h3>\n");
    } else {
        echo "\n<h2>INSTALL-1 : Create Table `p_rss_box` --- No.</h2>\n";
    }
} else {
    echo "\n<h2>INSTALL-1 : Create Table `p_box` in {$dbname}  --- OK.</h2>\n";
}


echo "<h2>LAST-STEP： FINISH!</h2>\n".
     '<h3>&gt;&gt;&nbsp;<a href="../index.php?id=rss_box">RSS BOX TOP</a></h3>'. "\n";
?>
</body>
</html>