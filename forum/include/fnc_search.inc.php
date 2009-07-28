<?php
/**
 * Functions for search routines
 *
 * $Id: fnc_search.inc.php, 2004/07/24 12:23:37 Exp $
 */


/**
 * Keyword Highlighting
 */
function hit_key_highlight() 
{
    global $case, $i, $keys, $row;
    if ($case == 0) { // case-insensitive
        for ($i = 0; $i < sizeof($keys); $i++) {
            if ($keys[$i] != "") {
                $row['title']     = preg_replace('/('.stripslashes($keys[$i]).')/i', '<span class="hl">$1</span>', $row['title']);
                $row['comment']  = preg_replace('/('.stripslashes($keys[$i]).')/i', '<span class="hl">$1</span>', $row['comment']);
                while (preg_match('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', $row['comment'])){
                    $row['title']     = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $row['title']);
                    $row['comment']  = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $row['comment']);
                }
            }
        }
    } else { // case-sensitive
        for ($i = 0; $i < sizeof($keys); $i++) {
            if ($keys[$i] != "") {
                $row['title']     = preg_replace('/('.stripslashes($keys[$i]).')/', '<span class="hl">$1</span>', $row['title']);
                $row['comment']  = preg_replace('/('.stripslashes($keys[$i]).')/', '<span class="hl">$1</span>', $row['comment']);
                while (preg_match('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', $row['comment'])){
                    $row['title']     = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $row['title']);
                    $row['comment']  = preg_replace('/(<[^<>]*)(<span class="hl">)('.stripslashes($keys[$i]).')(<\/span>)/i', '$1$3', $row['comment']);
                }
            }
        }
    }
}



// Deny direct access to this file
if (stristr($_SERVER['PHP_SELF'], ".inc.php")) {
    header("HTTP/1.1 301 Moved Permanently");
    header('Location: http://' .$_SERVER['SERVER_NAME']. dirname($_SERVER['REQUEST_URI']). "/index.php");
}
?>