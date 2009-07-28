<?php
echo <<<EOD
{$dtd}
<head>
{$content_type}<title>{$page_title}</title>
<script type="text/javascript" src="{$cd}/include/scripts.js"></script>
<link rel="stylesheet" type="text/css" href="{$cd}/styles/{$style_num}/{$style}.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$cd}/styles/_media/print.css" media="print" />
<link rel="start" href="{$cd}/index.php" title="Home" />
<link rel="search" href="{$cd}/search_plus.php" title="Search" />
<link rel="help" href="{$cd}/var/help/index.php" title="Help" />
{$alternate_link_rss}<link rev="made" href="mailto:webmaster@anti-spam.example.com" />
<script type="text/javascript" src="{$cd}/include/block_spam.php?js_email_link"></script>
</head>
<body>
<!-- Begin #wrapper -->
<div id="wrapper">
<!-- Begin #header -->
<div id="header">
<h1><a href="{$cd}/index.php" accesskey="t">{$cfg['blog_title']}</a></h1>
{$subtitle}</div>
<!-- End #header -->
<!-- Begin #content -->
<div id="content">
{$contents_top}{$contents}
</div>
<!-- End #content -->
<!-- Begin #menu-box -->
<div id="menu-box">
{$admin_sess_menu}
{$content_menu}
{$search_form}
{$menu_middle}
{$recent_entries}
{$recent_comments}
{$recent_trackbacks}
{$archive_by_date}
{$category_menu}
{$file_type_menu}
{$css_switch}
{$rss_button}
{$rss2_button}
{$menu_bottom}
</div>
<!-- End #menu-box -->
<!-- Begin #footer -->
<div id="footer">
{$footer_content}
</div><!-- End #footer -->
</div><!-- End #wrapper -->
</body>
</html>
EOD;
?>