<?php
/**
 * Functions for search routines
 *
 * $Id: fnc_search.inc.php, 2005/01/29 11:39:45 Exp $
 */

//================================================================
// SEARCH ROUTINES
//================================================================

/**
 * Keyword Highlighting
 */
function highlight_keywords($mode)
{
    global $case, $keys, $row;
    
    if ($mode == 'log') {
        $file_title = '';
        $name       = $row['name'];
        $comment    = $row['comment'];
    } else {
        $file_title = $row['bin_title'];
        $name       = $row['binname'];
        $comment    = $row['bincomment'];
    }

    if ($case == 0) {
        $case_flag = 'i'; // Case-insensitive
    } else {
        $case_flag = '';  // Case-sensitive
    }
    
    for ($i = 0; $i < sizeof($keys); $i++) {
        if ($keys[$i] != "") {
            $file_title = preg_replace('/('.stripslashes($keys[$i]).')/'.$case_flag, '<span class="hl">$1</span>', $file_title);
            $name       = preg_replace('/('.stripslashes($keys[$i]).')/'.$case_flag, '<span class="hl">$1</span>', $name);
            $comment    = preg_replace('/<!-- ?more ?-->/is', '', $comment);
            $comment    = preg_replace('/('.stripslashes($keys[$i]).')/'.$case_flag, '<span class="hl">$1</span>', $comment);
            while (preg_match('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/'.$case_flag, $comment)){
                $file_title = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $file_title);
                $name       = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $name);
                $comment    = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $comment);
             }
        }
    }
    
    // Put the results back to the original variables
    if ($mode == 'log') {
        $row['bin_title']  = '';
        $row['name']       = $name;
        $row['comment']    = $comment;
    } else {
        $row['bin_title']  = $file_title;
        $row['binname']    = $name;
        $row['bincomment'] = $comment;    
    }
    
    return $row;
}



// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>