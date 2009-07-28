<?php
/**
 * VARS - varialble content pages
 *
 * $Id: var/index.php, 2004/11/28 11:40:29 Exp $
 */

//=========================  USER CONFIG   =================================

$more_vars_dir   = '';     // don't slash at the beginning and the end
$vars_page_title = '';     // name your more vars section title
$cd              = '..';   // set the directory level from the top page

//==========================================================================

require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once $cd . '/var/include/fnc_vars.inc.php';

session_control();

$var_contents = display_var_contents();

$contents  =<<<EOD
<div class="section">
{$var_contents}
</div>
EOD;

xhtml_output('');

?>