<?php
/**
 * $Id: 2005/01/21 22:38:48 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}

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

/////////////////////////// PRESENTATION ////////////////////////////
echo <<<EOD
<!-- Begin of content -->
<h2><img src="./contents/resources/step2.png" width="37" height="37" alt="STEP-2" /> STEP 2</h2>
<h3>{$lang['settings']}</h3>
<form action="./index.php?id=step3" method="post">
<h4>(1) <span class="ok">{$lang['root_path_settings']}</span></h4>
<p>{$lang['root_path_ex']}</p>
<p class="command">
http://yourhost.com
<input type="text" name="root_path" value="/path/to/p_blog/" />
</p>
<hr />
<h4>(2) <span class="ok">{$lang['choose_default_lang']}</span></h4>
<p class="command">
<select name="default_lang">
<option value="en">English</option>
<option value="ja">Japanese</option>
</select>
</p>
<hr />
<h4>(3) <span class="ok">{$lang['choose_time_zone']}</span></h4>
<p class="command">GMT
<select type="select" name="tz_offset" />
<option value="12" >+12:00 ( NZST: New Zealand Standard )</option>
<option value="11" >+11:00</option>
<option value="10" >+10:00 ( GST: Guam Standard )</option>
<option value="9"  >+9:00 ( JST : Japan Standard )</option>
<option value="8"  >+8:00 ( CCT: China Coast )</option>
<option value="7"  >+7:00</option>
<option value="6"  >+6:00</option>
<option value="5"  >+5:00</option>
<option value="4"  >+4:00</option>
<option value="3"  >+3:00 ( BT: Baghdad )</option>
<option value="2"  >+2:00 ( EET: Eastern European )</option>
<option value="1"  >+1:00 ( CET: Central European )</option>
<option value="0"  selected="selected">0:00 ( GMT : Greenwitch, London)</option>
<option value="-1" >-1:00 ( WAT: West Africa, Cape Verde Island )</option>
<option value="-2" >-2:00</option>
<option value="-3" >-3:00 ( Brazil, Buenos Aires, Argentina)</option>
<option value="-4" >-4:00 ( AST: Atlantic Standard )</option>
<option value="-5" >-5:00 ( EST: Bogota, Lima, Peru, New York )</option>
<option value="-6" >-6:00 ( CST: Central Standard )</option>
<option value="-7" >-7:00 ( MST: Mountain Standard )</option>
<option value="-8" >-8:00 ( PST: Pacific Standard )</option>
<option value="-9" >-9:00 ( YST:  Yukon Standard )</option>
<option value="-10">-10:00 ( HST: Hawaii Standard )</option>
<option value="-11">-11:00</option>
<option value="-12">-12:00 ( IDLW: International Date Line West )</option>
</select>
</p>
<hr />

<h3><span class="important">{$lang['install_or_upgrade']}</span></h3>
<p>{$lang['install_ex']}</p>
<p class="command">
<input type="radio" name="install_type" value="install" checked="checked" />{$lang['install']}
<input type="radio" name="install_type" value="upgrade" />{$lang['upgrade']}
</p>
<p class="submit-button">
<input type="hidden" name="ex-lang" value="{$ex_lang}">
<input type="submit" value=" {$lang['start']}" />
</p>
</form>
<!-- End of content -->
EOD;
?>
