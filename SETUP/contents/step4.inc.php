<?php
/**
 * $Id: 2005/01/21 22:39:38 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}

// Load user config
require_once '../include/user_config.inc.php';

// Load constants definitions
require_once './include/constants.inc.php';

// Switch the language include file
if ((isset($_REQUEST['ex-lang'])) && ($_REQUEST['ex-lang'] == 'ja')) {
    require_once './lang/japanese.inc.php';
    $ex_lang = 'ja';
} else {
    require_once './lang/english.inc.php';
    $ex_lang = 'en';
}

if (isset($_POST['install_or_upgrade'])) {
    $title = $_POST['install_or_upgrade'];
}

/////////////////////////// PRESENTATION ////////////////////////////
echo <<<EOD
<h2><img src="./contents/resources/step4.png" width="37" height="37" alt="STEP-4" /> STEP 4</h2>
<h3>{$title}{$lang['finished']}</h3>
{$lang['install_fin_msg']}
EOD;
?>
