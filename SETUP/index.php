<?php
/**
 * P_BLOG SETUP
 *
 * $Id: SETUP/index.php, 2004/09/20 14:19:42 Exp $
 */

//=========================  USER CONFIG   =================================

$more_vars_dir   = 'SETUP';         // don't slash at the beginning and the end
$more_vars_title = 'P_BLOG SETUP';  // name your more vars section title
$cd              = '..';           // set the directory level from the top page
$style_num       = 'rich_green';    // CSS

require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/var/include/fnc_vars.inc.php';

//==========================================================================

if (isset($_REQUEST['ex-lang'])) {
    $ex_lang_query_1 = '?ex-lang='.$_REQUEST['ex-lang'];
    $ex_lang_query_2 = '&amp;ex-lang='.$_REQUEST['ex-lang'];
} else {
    $ex_lang_query_1 = '';
    $ex_lang_query_2 = '';
}

// Switch menu
switch($_GET['id']) {
    case 'step4':
        $step1_menu = 'menu';     $start_anchor1 = '<a href="./index.php'.$ex_lang_query_1.'" class="menu">';          $end_anchor1 = '</a>';
        $step2_menu = 'menu';     $start_anchor2 = '<a href="./index.php?id=step2'.$ex_lang_query_2.'" class="menu">'; $end_anchor2 = '</a>';
        $step3_menu = 'menu';     $start_anchor3 = '<a href="./index.php?id=step3'.$ex_lang_query_2.'" class="menu">'; $end_anchor3 = '</a>';    
        $step4_menu = 'cur-menu'; $start_anchor4 = ''; $end_anchor4 = '';
        break;
    case 'step3':
        $step1_menu = 'menu';     $start_anchor1 = '<a href="./index.php'.$ex_lang_query_1.'" class="menu">';          $end_anchor1 = '</a>';
        $step2_menu = 'menu';     $start_anchor2 = '<a href="./index.php?id=step2'.$ex_lang_query_2.'" class="menu">'; $end_anchor2 = '</a>';
        $step3_menu = 'cur-menu'; $start_anchor3 = ''; $end_anchor3 = '';
        $step4_menu = 'menu';     $start_anchor4 = '<a href="./index.php?id=step4'.$ex_lang_query_2.'" class="menu">'; $end_anchor4 = '</a>';    
        break;
    case 'step2':
        $step1_menu = 'menu';     $start_anchor1 = '<a href="./index.php'.$ex_lang_query_1.'" class="menu">';          $end_anchor1 = '</a>';
        $step2_menu = 'cur-menu'; $start_anchor2 = ''; $end_anchor2 = '';
        $step3_menu = 'menu';     $start_anchor3 = '<a href="./index.php?id=step3'.$ex_lang_query_2.'" class="menu">'; $end_anchor3 = '</a>';
        $step4_menu = 'menu';     $start_anchor4 = '<a href="./index.php?id=step4'.$ex_lang_query_2.'" class="menu">'; $end_anchor4 = '</a>';    
        break;
    default:
        $step1_menu = 'cur-menu'; $start_anchor1 = ''; $end_anchor1 = '';
        $step2_menu = 'menu';     $start_anchor2 = '<a href="./index.php?id=step2'.$ex_lang_query_2.'" class="menu">'; $end_anchor2 = '</a>';
        $step3_menu = 'menu';     $start_anchor3 = '<a href="./index.php?id=step3'.$ex_lang_query_2.'" class="menu">'; $end_anchor3 = '</a>';
        $step4_menu = 'menu';     $start_anchor4 = '<a href="./index.php?id=step4'.$ex_lang_query_2.'" class="menu">'; $end_anchor4 = '</a>';
        break;
}

/////////////////////////// PRESENTATION ////////////////////////////
echo <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<title>P_BLOG SETUP</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-style-type" content="text/css" />
<link rel="stylesheet" type="text/css" href="../styles/{$style_num}/{$style}.css" />
<link rev="made" href="mailto:webmaster@anti-spam.example.com" />
<link rel="start" href="./index.php" />
</head>
<body>
<!-- Begin #wrapper -->
<div id="wrapper">
<!-- Begin #header -->
<div id="header">
<h1 id="title"><a href="./index.php">P_BLOG SETUP</a></h1>
<span id="subtitle">- INSTALL &amp; UPDATE - </span>
</div>
<div id="content">
EOD;

display_var_contents();

echo<<<EOD
</div>
<!-- Begin #menu-box -->
<div id="menu-box">
<ul class="menu">
<li class="menu"><a href="../var/help/man/index.php?id=en_00" class="menu">Manual</a></li>
<li class="menu"><a href="../var/help/man/index.php?id=ja_00" class="menu">マニュアル</a></li>
</ul>
<br />
<ul class="menu">
<li class="{$step1_menu}">{$start_anchor1}STEP-1{$end_anchor1}</li>
<li class="{$step2_menu}">{$start_anchor2}STEP-2{$end_anchor2}</li>
<li class="{$step3_menu}">{$start_anchor3}STEP-3{$end_anchor3}</li>
<li class="{$step4_menu}">{$start_anchor4}STEP-4{$end_anchor4}</li>
</ul>
</div>
<!-- Begin #footer -->
<div id="footer">
<a href="http://pbx.homeunix.org/p_blog/index.php">
<img src="../images/p_blog_logo.png" width="88" height="31" alt="p_blog" class="logo" />
</a>
<address>
Copyright &copy 2004-2005 <a href="http://pbx.homeunix.org/p_blog/">P_BLOG Project</a>. All rights reserved.
</address>
</div>
</div>
</div>
<!-- End #wrapper -->
</body>
</html>
EOD;
?>