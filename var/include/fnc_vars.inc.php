<?php
/**
 * Main Functions of VARS
 *
 * $Id: 2005-11-29 14:41:18 Exp $
 */
 
// To use sanitize() function, load base functions.
include_once $cd . '/include/fnc_base.inc.php';


// Display Content Function
function display_var_contents() {

    global $cd, $cfg, $lang, $plugin;
    
    include_plugin($mode = '');
    
    $contents = '';
    if(isset($_GET['id'])){
        $id = sanitize($_GET['id']);
        $id = str_replace('/', '', $id);
    
        if (file_exists('./contents/' . $id . '.inc.php')) {        //foo.inc.php
	        include_once './contents/' . $id . '.inc.php';
        } elseif (file_exists('./contents/' . $id . '.php')) {       //foo.php
	        include_once './contents/' . $id . '.php';
        } elseif (file_exists('./contents/' . $id . '.inc')) {       //foo.inc
	        $rfp       = fopen(stripslashes('./contents/' . $id . '.inc'), "rb");
            $contents .= @fread($rfp, filesize('./contents/' . $id . '.inc'));
        } elseif (file_exists('./contents/' . $id . '.html')) {      //foo.html
	        $rfp       = fopen(stripslashes('./contents/' . $id . '.html'), "rb");
            $contents .= @fread($rfp, filesize('./contents/' . $id . '.html'));
        } elseif (file_exists('./contents/' . $id . '.txt')) {       //foo.txt
            $contents .= "<pre>\n";
	        $rfp       = fopen(stripslashes('./contents/' . $id . '.txt'), "rb");
            $contents .= @fread($rfp, filesize('./contents/' . $id . '.txt'));
            $contents .= "</pre>\n";
        } elseif (file_exists('./contents/' . $id . '.text')) {       //foo.txt
	        $rfp       = fopen(stripslashes('./contents/' . $id . '.text'), "rb");
            $text = @fread($rfp, filesize('./contents/' . $id . '.text'));
            if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
                include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
                $FKMM_markdown = new FKMM_markdown();
                $contents .= $FKMM_markdown->convert($text);
            } else {
                $contents .= $text;
            }
        } else {
            $contents .= "\n".'<h2 class="cate-title">'.$lang['no_matches']."</h2>\n";
        }
    } elseif (file_exists('./contents/default.inc.php')) { //default.inc.php
        include_once './contents/default.inc.php';
    } elseif (file_exists("./contents/default.php")) {     //default.php
	    include_once "./contents/default.php";
    } elseif (file_exists("./contents/default.inc")) {     //default.inc
	    $rfp       = fopen(stripslashes('./contents/default.inc'), "rb");
        $contents .= @fread($rfp, filesize('./contents/default.inc'));
    } elseif (file_exists("./contents/default.html")) {    //default.html
	    $rfp       = fopen(stripslashes('./contents/default.html'), "rb");
        $contents .= @fread($rfp, filesize('./contents/default.html'));
    } elseif (file_exists("./contents/default.txt")) {     //default.txt
        $contents .= "<pre>\n";
	    $rfp       = fopen(stripslashes('./contents/default.txt'), "rb");
        $contents .= @fread($rfp, filesize('./contents/default.txt'));
        $contents .= "</pre>\n";
    } elseif (file_exists("./contents/default.text")) {     //default.text
	    $rfp       = fopen(stripslashes('./contents/default.text'), "rb");
        $text = @fread($rfp, filesize('./contents/default.text'));
        if (file_exists($cd . '/include/user_include/plugins/plg_markdown.inc.php')) {
            include_once $cd . '/include/user_include/plugins/plg_markdown.inc.php';
            $FKMM_markdown = new FKMM_markdown();
            $contents .= $FKMM_markdown->convert($text);
        } else {
            $contents .= $text;
        }
    } elseif ((!file_exists("./contents/default.inc.php")) && 
              (!file_exists("./contents/default.php")) && 
              (!file_exists("./contents/default.inc")) &&
              (!file_exists("./contents/default.html")) &&
              (!file_exists("./contents/default.txt")) &&
              (!file_exists("./contents/default.text"))) {
        $contents .= '<h2>Please set the default page.</h2>';
    } else {
        $contents .= '<h2>ERROR.</h2>';
    }
    $contents .= '';

    return $contents;
}

?>