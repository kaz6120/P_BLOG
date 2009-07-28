<?php
/**
 * VARS - varialble content pages
 *
 * $Id: var/index.php, 2004/10/07 14:53:36 Exp $
 */

//=========================  USER CONFIG   =================================

$more_vars_dir   = 'var/feedback';     // don't slash at the beginning and the end
$more_vars_title = 'Feedback';     // name your more vars section title
$cd              = '../..';   // set the directory level from the top page

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