<?php
/**
 * P_BLOG Upgrade
 *
 * $Id: 2004/07/03 17:26:55 Exp $
 */

echo <<<EOD
<h2><img src="./contents/resources/step3.png" width="37" height="37" alt="STEP-3" /> STEP 3</h2>
<h3>{$title}</h3>
<table class="colored" summary="resuts">
<tr>
<th>{$lang['step']}</th>
<th>{$lang['results']}</th><th>{$lang['status']}</th></tr>
<tr>
<td>STEP1</td>
<td>{$step1_res}</td><td>{$step1_status}</td></tr>
<tr>
<td>STEP2</td>
<td>{$step2_res}</td><td>{$step2_status}</td></tr>
<tr>
<td>STEP3</td>
<td>{$step3_res}</td><td>{$step3_status}</td></tr>
<tr>
<td>STEP4</td>
<td>{$step4_res}</td><td>{$step4_status}</td></tr>
<tr>
<td>STEP5</td>
<td>{$step5_res}</td><td>{$step5_status}</td></tr>
<tr>
<td>STEP6</td>
<td>{$step6_res}</td><td>{$step6_status}</td></tr>
<tr>
<td>STEP7</td>
<td>{$step7_res}</td><td>{$step7_status}</td></tr>
<tr>
<td>STEP8</td>
<td>{$step8_res}</td><td>{$step8_status}</td></tr>
<tr>
<td>STEP9</td>
<td>{$step9_res}</td><td>{$step9_status}</td></tr>
<tr>
<td>STEP10</td>
<td>{$step10_res}</td><td>{$step10_status}</td></tr>
<tr>
<td>STEP11</td>
<td>{$step11_res}</td><td>{$step11_status}</td></tr>
<tr>
<td>STEP12</td>
<td>{$step12_res}</td><td>{$step12_status}</td></tr>
<tr>
<td>STEP13</td>
<td>{$step13_res}</td><td>{$step13_status}</td></tr>
<tr>
<td>STEP14</td>
<td>{$step14_res}</td><td>{$step14_status}</td></tr>
<tr>
<td>STEP15</td>
<td>{$step15_res}</td><td>{$step15_status}</td></tr>
<tr>
<td>STEP16</td>
<td>{$step16_res}</td><td>{$step16_status}</td></tr>
</table>
<br />
<form action="./index.php?id=step4" method="post">
<p class="submit-button">
<input type="hidden" name="install_or_upgrade" value="{$title}" />
<input type="hidden" name="ex-lang" value="{$ex_lang}" />
<input type="submit" value=" {$lang['next']} &#187; " />
</p>
</form>
EOD;
?>