<?php
/**
 * SVG Example - Hello, World!
 *
 * $Id: svg-example.php, 2004/06/05 13:55:31 Exp $
 */

$cd = '../../../..';
require_once $cd . '/include/config.inc.php';

$php_version   = PHP_VERSION;
$mysql_version = mysql_get_server_info();
$php_os        = PHP_OS;

if (mysql_select_db("$dbname")) {
    $q  = 'SELECT ' . $cfg['count_row_query'] . ' FROM ' . $log_table;
    $countsql = ($q);
    $res = mysql_query($countsql);
    $row = mysql_num_rows($res);
	
	$q2 = 'SELECT ' . $cfg['count_row_query']. ' FROM ' . $info_table;
	$countsql2 = ($q2);
	$res2 = mysql_query($countsql2);
	$row2 = mysql_num_rows($res2);
}
header("Content-type: application/xml");
echo '<?xml version="1.0" standalone="no"?>';
echo <<<EOSVG
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" 
"http://www.w3.org/TR/2000/CR-SVG-20001102/DTD/svg-20001102.dtd">
<svg xml:space="default" width="200" height="200">

  <!--//===========//  四角レイヤー (ピンク) //==============//-->
　<rect id="rect1" 
        style="fill:#FF9999; opacity:0.5; stroke:black; stroke-width:1px;" rx="0" ry="0" 
        x="100" y="300" width="300" height="70"> 
   <!-- // Move // -->
   <animate attributeName="x" attributeType="XML" 
            begin="0s" dur="5s" fill="freeze" from="0" to="3" />
   <animate attributeName="y" attributeType="XML"
            begin="0s" dur="5s" fill="freeze" from="0" to="15" />
   <!-- // Fade-In // -->
   <animate attributeName="opacity" attributeType="CSS" 
            from="0" to="0.5" dur="5s" repeatCount="0" />
   <!-- // Rotate // -->
   <animateTransform attributeName="transform" attributeType="XML"
            type="rotate" from="-90" to="0"
            dur="2s" fill="freeze" />
  </rect>

  <!--//===========//  四角レイヤー (イエロー) //==============//-->
　<rect id="rect1" 
        style="fill:#FFFF99; opacity:0.5; stroke:black; stroke-width:1px;" rx="0" ry="0" 
        x="100" y="300" width="300" height="70"> 
   <!-- // Move // -->
   <animate attributeName="x" attributeType="XML" 
            begin="0s" dur="5s" fill="freeze" from="0" to="50" />
   <animate attributeName="y" attributeType="XML"
            begin="0s" dur="5s" fill="freeze" from="0" to="160" />
   <!-- // Fade-In // -->
   <animate attributeName="opacity" attributeType="CSS" 
            from="0" to="0.5" dur="5s" repeatCount="0" />
   <!-- // Rotate // -->
   <animateTransform attributeName="transform" attributeType="XML"
            type="rotate" from="-90" to="0"
            dur="2s" fill="freeze" />
  </rect>


  <!--//===========//  四角レイヤー (ブルー) //==============//-->
　<rect id="rect1" 
        style="fill:#9999FF; opacity:0.5; stroke:black; stroke-width:1px;" rx="0" ry="0" 
        x="100" y="300" width="300" height="110"> 
   <!-- // Move // -->
   <animate attributeName="x" attributeType="XML" 
            begin="0s" dur="5s" fill="freeze" from="0" to="30" />
   <animate attributeName="y" attributeType="XML"
            begin="0s" dur="5s" fill="freeze" from="0" to="65" />
   <!-- // Fade-In // -->
   <animate attributeName="opacity" attributeType="CSS" 
            from="0" to="0.5" dur="5s" repeatCount="0" />
   <!-- // Rotate // -->
   <animateTransform attributeName="transform" attributeType="XML"
            type="rotate" from="-90" to="0"
            dur="2s" fill="freeze" />
  </rect>


  <!--//===========//  System Info //==============//-->
  <text x="500" y="50" font-size="24px" font-family="Lucida Grande, Verdana, sans-serif">
   <animate attributeType="XML" attributeName="x" begin="0s"
                        dur="5s" from="300" to="20" fill="freeze" />
   {$cfg['blog_title']}
  </text>
     
  <a xlink:href="http://www.php.net/">
  <text x="500" y="85" font-size="14px" font-family="Verdana">
   <animate attributeType="XML" attributeName="x" begin="2s"
                        dur="6s" from="300" to="60" fill="freeze" />
   PHP version : {$php_version}
  </text>
  </a>

  <a xlink:href="http://www.mysql.com/">
  <text x="500" y="105" font-size="14px" font-family="Verdana">
   <animate attributeType="XML" attributeName="x" begin="2s"
                        dur="7s" from="300" to="60" fill="freeze" />
   MySQL version : {$mysql_version}
  </text>
  </a>
  
  <text x="500" y="125" font-size="14px" font-family="Verdana">
   <animate attributeType="XML" attributeName="x" begin="2s"
                        dur="8s" from="300" to="60" fill="freeze" />
   Logs : {$row}
  </text>

  <text x="500" y="145" font-size="14px" font-family="Verdana">
   <animate attributeType="XML" attributeName="x" begin="2s"
                        dur="9s" from="300" to="60" fill="freeze" />
   Files : {$row2}
  </text>
  
  <text x="500" y="165" font-size="14px" font-family="Verdana">
   <animate attributeType="XML" attributeName="x" begin="2s"
                        dur="10s" from="300" to="60" fill="freeze" />
   Running on {$php_os}
  </text>
  
  
  <!--//===========//  Hello, World! //==============//-->
  <g transform="translate(250,210)">
   <!-- // H // -->
   <text x="-500" y="0" 
         style="font-family:Verdana; font-size:26px; color:#eeeeee" >
    <animate attributeName="x" attributeType="XML" 
             begin="1s" dur="1s" fill="freeze" from="-300" to="-180" />
    H
   </text>
   <!-- // e // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="2s" dur="1s" fill="freeze" from="-300" to="-155" />
    e
   </text>         
   <!-- // l // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="3s" dur="1s" fill="freeze" from="-300" to="-135" />
    l
   </text>
   <!-- // l // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px;color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="4s" dur="1s" fill="freeze" from="-300" to="-125" />
    l
   </text>
   <!-- // o // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px;color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="5s" dur="1s" fill="freeze" from="-300" to="-115" />
    o
   </text>
   <!-- // , // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="6s" dur="1s" fill="freeze" from="-300" to="-95" />
    ,
   </text>
   <!-- // w // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="7s" dur="1s" fill="freeze" from="-300" to="-80" />
    W
   </text>
   <!-- // o // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="8s" dur="1s" fill="freeze" from="-300" to="-50" />
    o
   </text>
   <!-- // r // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="9s" dur="1s" fill="freeze" from="-300" to="-30" />
    r
   </text>
   <!-- // l // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="10s" dur="1s" fill="freeze" from="-300" to="-15" />
    l
   </text>
   <!-- // d // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="11s" dur="1s" fill="freeze" from="-300" to="-5" />
    d
   </text>
   <!-- // ! // -->
   <text x="-500" y="0"
         style="font-family:Verdana; font-size:26px; color:#000000" >
    <animate attributeName="x" attributeType="XML" 
             begin="12s" dur="1s" fill="freeze" from="-300" to="25" />
    !
   </text>
   
   <animate attributeName="opacity" attributeType="CSS"  
            begin="13s" from="1" to="0" dur="2s"  fill="freeze" repeatCount="0" />
   <animate attributeName="opacity" attributeType="CSS" 
            begin="15s" from="0" to="1" dur="2s"  fill="freeze" repeatCount="0" />
   <animateTransform attributeName="transform" 
                     type="scale" 
                     values="1 1 ; 1 0 ; 0 0" 
                     additive="sum" 
                     begin="13s" 
                     dur="2s" 
                     repeatCount="0" />
   <animateTransform attributeName="transform" 
                     type="scale" 
                     values="1 0 ; 1 1 ; 1 1" 
                     additive="sum" 
                     begin="15s" 
                     dur="2s" 
                     repeatCount="0" />
 </g>
</svg>
EOSVG;
?>