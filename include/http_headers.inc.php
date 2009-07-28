<?php
/**
 * Output Header Info
 * 
 * $Id: http_headers.inc.php, 2005-12-06 11:59:40 Exp $ 
 */

if (stristr($_SERVER['PHP_SELF'], '.inc.php')) {
   // header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). '/index.php');
}

// Set CSS cookie
if (isset($_COOKIE[$cfg['css_cookie_name']])) {
    $style_num = $_COOKIE[$cfg['css_cookie_name']]; //get the value from Cookie
    setcookie($cfg['css_cookie_name'], $style_num, time()+$cfg['css_cookie_time'], "/");
}

// Set Selected CSS name into cookie
if (isset($_POST['style_num'])) {
    $style_num = $_POST['style_num']; //get the value from posted parameter
    setcookie($cfg['css_cookie_name'], $style_num, time()+$cfg['css_cookie_time'], "/");
}

// If CSS is not selected, set default CSS
if (empty($style_num)) {
   $style_num = $cfg['default_style'];
}


// FORUM Cookie
if (isset($_POST['p_blog_forum_cookie'])) {
    if (isset($_POST['user_name'])) {
        $user_name  = $_POST['user_name'];
        setcookie('p_blog_forum_user', $user_name,   time()+86400*365, "/");
    }
    if (isset($_POST['user_email'])) {
        $user_email = $_POST['user_email'];
        setcookie('p_blog_forum_email', $user_email, time()+86400*365, "/");
    }
    if (isset($_POST['user_uri'])) {
        $user_uri   = $_POST['user_uri'];
        setcookie('p_blog_forum_uri',   $user_uri,   time()+86400*365, "/");
    }
}


/**
 * Output Controls
 * @author : nakamuxu
 */
$request_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
if ($request_uri == ('http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $cfg['top_page']) or 
    $request_uri == ('http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'files/index.php') or
    $request_uri == ('http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'search.php') or 
    $request_uri == ('http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'search_plus.php') or 
    $request_uri == ('http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'files/search.php') or 
    $request_uri == ('http://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . 'forum/index.php')) {
    // Note: ob_gzhandler must be called before session_start()
    if ($cfg['gz_compress'] == 'yes') { ob_start("ob_gzhandler"); } else { ob_start(); }
    if ($request_uri == ('http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].$cfg['top_page'])) {
        $sql = 'SELECT UNIX_TIMESTAMP(`mod`) as `tstamp` FROM ' . $log_table . 
               " WHERE `draft` = '0'".
               ' ORDER BY `id` desc LIMIT ' . $cfg['pagemax'];  
    } elseif ($request_uri == ('http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'files/index.php')) {
        $sql = 'SELECT UNIX_TIMESTAMP(`bindate`) as `tstamp` FROM ' . $info_table . 
               " WHERE `draft` = '0'".
               ' ORDER BY `id` desc LIMIT ' . $cfg['pagemax']; 
    } elseif ($request_uri == ('http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'search.php')) {
        $sql = 'SELECT MAX(UNIX_TIMESTAMP(`mod`)) as `tstamp` FROM '.$log_table." WHERE `draft` = '0'";
    } elseif ($request_uri == ('http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'files/search.php')) {
        $sql = 'SELECT MAX(UNIX_TIMESTAMP(`bindate`)) as `tstamp` FROM ' . $info_table . " WHERE `draft` = '0'"; 
    } elseif ($request_uri == ('http://'.$_SERVER['HTTP_HOST'].$cfg['root_path'].'forum/index.php')) {
        $sql = 'SELECT MAX(UNIX_TIMESTAMP(`date`)) as `tstamp` FROM `' . $forum_table . "` WHERE `trash` = '0'"; 
    }
    $mod_db_time = 0; 
    if (isset($sql)) { 
        $res = mysql_query($sql);
        if (!$res) {
            die (mysql_error());
        }
        while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) { 
            $tmp_time = $row['tstamp']; 
            $mod_db_time = $mod_db_time > $tmp_time ? $mod_db_time : $tmp_time; 
        }
    }
    // modified time of this file
    $mod_cache_inc_file_time = filemtime($_SERVER['SCRIPT_FILENAME']) ? filemtime($_SERVER["SCRIPT_FILENAME"]) : filemtime($_SERVER['PATH_TRANSLATED']);
    $mod_parent_file_time = getlastmod(); // modified time of the parent file
    $mod_file_time = $mod_cache_inc_file_time > $mod_parent_file_time ? $mod_cache_inc_file_time : $mod_parent_file_time; // get the latest
    $mod_time = $mod_file_time > $mod_db_time ? $mod_file_time : $mod_db_time;
    $mod_gmt = gmdate('D, d M Y H:i:s', $mod_time) . ' GMT'; // convert to GMT format 
    // if it is same with "if_modified_since" from request header, stop output 
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $if_modified_since = preg_replace('/;.*$/', '', $_SERVER['HTTP_IF_MODIFIED_SINCE']); 
        if ($if_modified_since == $mod_gmt) { 
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified'); 
            ob_end_clean(); 
            exit; 
        }
    }
    header('Last-Modified: ' . $mod_gmt);
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
} else { 
    header('Expires: Tue, 24 Jan 1984 06:00:00 GMT');
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT'); 
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false); 
    header('Pragma: no-cache');
}
switch ($cfg['xml_version']) {
    case '1.1':
        // Based on W3C Note
        $media_type = 'application/xhtml+xml';
        break;
    case '1.1cn':
        // Content Negotiation
        if ((stristr($_SERVER["HTTP_ACCEPT"], 'application/xhtml+xml')) ||        
            (stristr($_SERVER["HTTP_USER_AGENT"], 'W3C_Validator')) ||
            (stristr($_SERVER["HTTP_USER_AGENT"], 'Another_HTML-lint'))) {
            $media_type = 'application/xhtml+xml';
        } else {
            $media_type = 'text/html';
        }
        break;
    default :
        $media_type = 'text/html';
        break;       
};
header('Content-Type: ' . $media_type . '; charset='.$cfg['charset']);
header("Vary: Accept");


/**
 * Check User Agent and Apply CSS
 */
if (preg_match('/rv:1/', $_SERVER['HTTP_USER_AGENT'])) {
    $form_length = '18';
    $text_cols   = '55';
    if (preg_match('/Macintosh/', $_SERVER['HTTP_USER_AGENT'])) {
        if (file_exists($cd . '/styles/' .$style_num . '/gecko_style.css')) {
            $style = 'gecko_style';
        } elseif (file_exists($cd . '/styles/' .$style_num . '/mac_gecko_style.css')) {
            $style = 'mac_gecko_style';
        } else {
            $style = 'default';
        }
    } else {
        $style = 'default';
    }
} elseif (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
    $form_length = '20';
    $text_cols   = '65';
    if (preg_match('/Mac_PowerPC/', $_SERVER['HTTP_USER_AGENT'])) {
        if (file_exists($cd . '/styles/' . $style_num . '/mac_ie_style.css')) {
            $style = 'mac_ie_style';
        } else {
            $style = 'default';
        }
    } elseif (preg_match('/Windows/', $_SERVER['HTTP_USER_AGENT'])) {
        if (file_exists($cd . '/styles/' . $style_num . '/win_ie_style.css')) {
            $style = 'win_ie_style';
        } else {
            $style = 'default';
        }
    }
} elseif (preg_match('/KHTML/', $_SERVER['HTTP_USER_AGENT'])) {
    $form_length = '18';
    $text_cols   = '60';
    if (file_exists($cd . '/styles/' . $style_num . '/khtml_style.css')) {
         $style = 'khtml_style';
    } else {
         $style = 'default';
    }
} elseif (preg_match('/Opera/', $_SERVER['HTTP_USER_AGENT'])) {
    $form_length = '18';
    $text_cols   = '55';
    if (file_exists($cd . '/styles/' . $style_num . '/opera_style.css')) {
         $style = 'opera_style';
    } else {
         $style = 'default';
    }
} else {
    $form_length = '18';
    $text_cols   = '55';
    $style       = 'default';
}


/**
 * Switch Language
 */
/* by P_BLOG Preference Setting */

if (isset($cfg['xml_lang'])) {
    if ($cfg['xml_lang'] == 'ja') {
        require_once $cd . '/lang/japanese.inc.php';
    } elseif ($cfg['xml_lang'] == 'en') {
        require_once $cd . '/lang/english.inc.php';
    } elseif ($cfg['xml_lang'] == 'it') {
        require_once $cd . '/lang/italian.inc.php';
    } elseif ($cfg['xml_lang'] == 'de') {
        require_once $cd . '/lang/german.inc.php';
    } elseif ($cfg['xml_lang'] == 'da') {
        require_once $cd . '/lang/danish.inc.php';
    } else {
        require_once $cd . '/lang/english.inc.php';
    }
}


/* by HTTP_ACCEPT_LANGUAGE */
/*
if (strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'ja')) {
    require_once $cd . '/lang/japanese.inc.php';
} elseif (strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'en')) {
    require_once $cd . '/lang/english.inc.php';
} elseif (strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'de')) {
    require_once $cd . '/lang/german.inc.php';
} elseif (strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'it')) {
    require_once $cd . '/lang/italian.inc.php';
} elseif (strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 'da')) {
    require_once $cd . '/lang/danish.inc.php';
} else {
    require_once $cd . '/lang/english.inc.php';
}
*/
?>