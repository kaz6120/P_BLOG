<?php
/**
 * Error Messages
 *
 * $Id: 2004/10/30 08:37:18 Exp $
 */
 
//================================================================
// ERROR MESSAGES
//================================================================
/**
 * Wrong User Error
 */
function wrong_user_error() 
{
    global $lang;
    $user_error_msg = "\n<h2>Ooops.</h2>\n".
                      '<h3 class="warning">'.$lang['wrong_user']."</h3>\n";
    return $user_error_msg;
}


/**
 * Bad Request Error
 */
function bad_req_error() 
{
    global $lang;
    $req_error_msg = "\n<h2>Ooops.</h2>\n".
                     '<h3 class="warning">'.$lang['bad_req']."</h3>\n";
    return $req_error_msg;
}


/**
 * No ID Error
 */
function no_id_error() 
{
    global $lang;
    $id_error_msg = "\n<h2>Ooops. ".$lang['no_id']."</h2>".
                    '<h3 class="warning">&#160;&#160;&rarr;&#160;&#160;'.
                    '<a href="./login.php"><strong>'.$lang['login'].'</strong></a> '.$lang['please']."</h3>\n";
    return $id_error_msg;
}


/**
 * No Keywords Error (Search Form)
 */
function no_keywords_error() 
{
    global $cfg, $lang, $style_num, $style,
           $cd, $begin_time_str, $request_uri, $admin;

    if ($cfg['xml_lang'] == 'ja') {
        $ref_msg = '<a href="'.$cd.'/var/help/index.php?id=how_to_search_ja">検索方法について</a>';
    } else {
        $ref_msg = '<a href="'.$cd.'/var/help/index.php?id=how_to_search_en">How To Search</a>';
    }

    $contents =<<<EOD
<h2>No Keywords.</h2>
<h3 class="warning">
{$lang['enter_keywords']}</h3>
<p class="ref">
{$ref_msg}
</p>
EOD;
    if (preg_match('/forum/', $request_uri)) {
        $contents .= '';
    } else {
        $contents .= '<p>&rarr; <a href="./search_plus.php">'.$lang['advanced_search']."</a>\n</p>\n";
    }
    
    return $contents;
}


/**
 * Keyword Error (Search Form)
 */
function keyword_error($mode) 
{
    global $cfg, $lang, $error_type, $style_num, $style,
           $cd, $begin_time_str, $admin;
           
    $contents = '';       
    switch($error_type) {
        case '1':
            $contents .= '<h2>No Keywords.</h2>';
            break;
        case '2':
            $contents .= '<h2>Too Short Keyword.</h2>';
            break;
        default:
            $contents .= '<h2>Error</h2>';
    }
    $contents .= '<h3 class="warning">';
    switch($error_type) {
        case '1':
            $contents .= $lang['enter_keywords']."</h3>\n";
            break;
        case '2':
            $contents .= $lang['too_short_keyword']."</h3>\n";
            break;
        default:
            $contents .= "</h3>\n";
    }
    $contents .= '<p class="ref">';
    if ($cfg['xml_lang'] == 'ja') {
        $contents .= '<a href="'.$cd.'/var/help/index.php?id=how_to_search_ja">検索方法について';
    } else {
        $contents .= '<a href="'.$cd.'/var/help/index.php?id=how_to_search_en">How To Search';
    }
    $contents .= "</a>\n</p>\n".
                 '<p class="ref"><a href="./search_plus.php">'.$lang['advanced_search']."</a>\n</p>\n";

    return $contents;
}


// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>