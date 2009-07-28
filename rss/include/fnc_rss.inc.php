<?php
/**
 * Function of RSS-1.0 generator
 *
 * $Id: 2006-06-15 12:27:00 Exp $
 */

function tz() {
    global $cfg;
    switch($cfg['tz']) {
        case '-12':
            $tz = '-1200';
            break;
        case '-11':
            $tz = '-1100';
            break;
        case '-10':
            $tz = '-1000';
            break;
        case '-9':
            $tz = '-0900';
            break;
        case '-8':
            $tz = '-0800';
            break;
        case '-7':
            $tz = '-0700';
            break;
        case '-6':
            $tz = '-0600';
            break;
        case '-5':
            $tz = '-0500';
            break;
        case '-4':
            $tz = '-0400';
            break;
        case '-3':
            $tz = '-0300';
            break;
        case '-2':
            $tz = '-0200';
            break;
        case '-1':
            $tz = '+0100';
            break;    
        case '12':
            $tz = '+1200';
            break;
        case '11':
            $tz = '+1100';
            break;
        case '10':
            $tz = '+1000';
            break;
        case '9':
            $tz = '+0900';
            break;
        case '8':
            $tz = '+0800';
            break;
        case '7':
            $tz = '+0700';
            break;
        case '6':
            $tz = '+0600';
            break;
        case '5':
            $tz = '+0500';
            break;
        case '4':
            $tz = '+0400';
            break;
        case '3':
            $tz = '+0300';
            break;
        case '2':
            $tz = '+0200';
            break;
        case '1':
            $tz = '+0100';
            break;
        default:
            $tz = '+0000';
            break;
    }
    return $tz;
}
?>
