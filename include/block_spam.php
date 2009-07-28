<?php
/**
 * =======================================
 * Block Spam
 * =======================================
 *
 * Based on :
 * X-Link-Indexer
 *  A simple links page management system.
 *  Copyright (C) 2003 nakamuxu.
 *
 * @author : nakamuxu
 * $Id: block_spam.php, 2004/06/05 18:52:33 Exp $
 */

// deny direct access to this file
// (access must have special query)
if (empty($_SERVER['QUERY_STRING']) || 
        (($_SERVER['QUERY_STRING'] != 'js_email_link') && ($_SERVER['QUERY_STRING'] != 'js_email_uri'))) {
    header('HTTP/1.0 404 Not Found');
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). '/index.php');
}

$cd = '..';

require_once './config.inc.php';

$org_str = 'mailto:'.$cfg['email'];
$force_encode_chars = array('@','.',':');
$percents = 70;
function is_special_char($char)
{
    global $force_encode_chars;
    $flag = FALSE;
    foreach ($force_encode_chars as $org) {
        if ($org == $char) {
            $flag = TRUE;
            break;
        }
    }
    return $flag;
}

$org_str = str_replace(' ', '', $org_str);
srand(100);
$encoded_address = $tmp = '';
$len = strlen($org_str);
for ($i=0; $i<$len; $i++) {
    $tmp = substr($org_str,$i,1);
    if ((is_special_char($tmp)) || (rand(1,100) <= $percents)) {
        $encoded_address .= '&#'.ord($tmp).';';
    } else {
        $encoded_address .= $tmp;
    }
}

$str_len = strlen($encoded_address);
$str_array = array();

$pos = 0;
while ($pos < $str_len) {
    $str_array[] = substr($encoded_address,$pos,$len);
    $pos += $len;
}

if ($_SERVER['QUERY_STRING'] == 'js_email_uri') {
    echo $encoded_address;
} elseif ($_SERVER['QUERY_STRING'] == 'js_email_link') {
    // build and echo JavaScript strings
    $js_email_link =<<<EOD

function js_email_link() {
    var opentag = String.fromCharCode(0x3C) + "a href=";
    var closetag = String.fromCharCode(0x3E) + "{$cfg['email_title']}" + String.fromCharCode(0x3C) + "/a" + String.fromCharCode(0x3E);

EOD;
    foreach($str_array as $key => $value) {
        $js_email_link .= '    var x'.$key.' = "'.$value.'";'."\n";
    }
    $js_email_link .= "\n".'    var totaltag = opentag + x0';
    for ($j=1;$j<count($str_array);$j++) {
        $js_email_link .= ' + x'.$j;
    }
    $js_email_link .= ' + closetag;'."\n".
                   '    document.write(totaltag);'."\n".'}'."\n";
    echo $js_email_link;
}
?>