<?php
/**
 * Bar Graph Functions
 *
 * $Id: bar_graph.php, 2005/02/12 00:07:26 Exp $
 */


/**
 * Vertical Bar Graph (without Link)
 * For HITS PAR HOUR
 */
function bar_graph_vertical($array, $max_width) {
    global $cfg, $hitrow;
    $mutable_graph = "<tr>\n";
    if ($array) {
        foreach ($array as $value) {
            if ((isset($max_value) && ($value > $max_value)) || (!isset($max_value))) {
                $max_value = $value;
            }
        }
        $pixels_per_value = ($max_value == 0) ? 0 : ((double) $max_width) / $max_value;
        $counter = 0;
        foreach ($array as $name => $value) {
            $bar_width = $value * $pixels_per_value;
            $image_no = ($counter % 7) + 1;
            $mutable_graph .= '<td class="vertical">' .$value. "<br />\n".
                              '<img src="images/v_bar'.$image_no.'.png" width="10" height="'.$bar_width.'" alt="'.$value.'" />'.
                              "\n</td>\n";
            $counter++;
        }
        $mutable_graph .= "</tr><tr>\n";
        foreach ($array as $name => $value) {
            $hit_rate = $value / $hitrow * 100;
            $mutable_graph .= '<td class="rate">'.number_format($hit_rate, 1)."%</td>\n";
        }
        $mutable_graph .= "</tr><tr>\n";
        foreach ($array as $name => $value) {
            $mutable_graph .= '<td class="bottom">'.htmlspecialchars($name)."</td>\n";
        }
    } else {
        if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
            $no_data_msg = 'No data for this request';
        } else {
            $no_data_msg = 'データはありません。';
        }
        $mutable_graph .= '<tr><td class="vertical">'.$no_data_msg."</td></tr>\n".
                          '<tr><td class="rate">'    .$no_data_msg."</td></tr>\n".
                          '<tr><td class="bottom">'  .$no_data_msg."</td>\n";
    }
    $mutable_graph .= "</tr>\n</table>\n";
    return $mutable_graph;
}

/**
 * Horizontal Bar Graph (without Link)
 * For HOST/IP, USER_AGENT
 */
function bar_graph_hour($array, $max_width) 
{
    global $cfg, $hitrow;
    $mutable_graph = '<tr><th>Hour</th><th>%</th><th>Hits</th></tr>';
    if ($array) {
        foreach ($array as $value) {
            if ((isset($max_value) && ($value > $max_value)) || (!isset($max_value))) {
                $max_value = $value;
            }
        }
        $pixels_per_value = ($max_value == 0) ? 0 : ((double) $max_width) / $max_value;
        $counter = 0;
        foreach ($array as $name => $value) {
            $bar_width = $value * $pixels_per_value;
            $image_no = ($counter % 7) + 1;
            $hit_rate = $value / $hitrow * 100;
            $mutable_graph .= "<tr>\n".'<th>';
            if (($name == 'Unknown') or
                ($name == 'Direct or Unknown') or
                ($name == '...Others (less than ' .$cfg['referer_limit_num']. ' hits)')) {
                $mutable_graph .= $name."</th>\n";
            } elseif ($name == '') { // if name is compressed and optimized
                $mutable_graph .= "Archived</td>\n";
            } else {
                $mutable_graph .= htmlspecialchars($name).'</th>';
            }
            $mutable_graph .= '<td class="rate">'.number_format($hit_rate, 1)."%</td>\n".
                              '<td class="value">'."\n".
                              '<img src="images/bar'.$image_no.'.png" width="'.$bar_width.'" height="10" alt="'.$value.'" />'.
                              ' '.$value.'</td>'.
                              '</tr>';
            $counter++;
        }
    } else {
        if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
            $no_data_msg = 'No data for this request';
        } else {
            $no_data_msg = 'データはありません。';
        }
        $mutable_graph .= '<tr><td class="key">'.$no_data_msg."</td>\n".
                          '<td class="rate">'    .$no_data_msg."</td>\n".
                          '<td class="value">' .$no_data_msg."</td>\n</tr>";
    }
    $mutable_graph .= "\n</table>\n";
    return $mutable_graph;
}


/**
 * Horizontal Bar Graph (without Link)
 * For HOST/IP, USER_AGENT
 */
function bar_graph($array, $max_width) 
{
    global $cfg, $hitrow;
    $mutable_graph = '';
    if ($array) {
        foreach ($array as $value) {
            if ((isset($max_value) && ($value > $max_value)) || (!isset($max_value))) {
                $max_value = $value;
            }
        }
        $pixels_per_value = ($max_value == 0) ? 0 : ((double) $max_width) / $max_value;
        $counter = 0;
        foreach ($array as $name => $value) {
            $bar_width = $value * $pixels_per_value;
            $image_no = ($counter % 7) + 1;
            $hit_rate = $value / $hitrow * 100;
            $mutable_graph .= "<tr>\n".'<td class="key">';
            if (($name == 'Unknown') or
                ($name == 'Direct or Unknown') or
                ($name == '...Others (less than ' .$cfg['referer_limit_num']. ' hits)')) {
                $mutable_graph .= $name."</td>\n";
            } elseif ($name == '') { // if name is compressed and optimized
                $mutable_graph .= "Archived</td>\n";
            } else {
                $mutable_graph .= htmlspecialchars($name).'</td>';
            }
            $mutable_graph .= '<td class="rate">'.number_format($hit_rate, 1)."%</td>\n".
                              '<td class="value">'."\n".
                              '<img src="images/bar'.$image_no.'.png" width="'.$bar_width.'" height="10" alt="'.$value.'" />'.
                              ' '.$value.'</td>'.
                              '</tr>';
            $counter++;
        }
    } else {
        if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
            $no_data_msg = 'No data for this request';
        } else {
            $no_data_msg = 'データはありません。';
        }
        $mutable_graph .= '<tr><td class="key">'.$no_data_msg."</td>\n".
                          '<td class="rate">'    .$no_data_msg."</td>\n".
                          '<td class="value">' .$no_data_msg."</td>\n</tr>";
    }
    $mutable_graph .= "\n</table>\n";
    return $mutable_graph;
}


/**
 * Horizontal Bar Graph (with Link)
 * For RERERER GRAPH
 */
function bar_graph_with_link($array, $max_width) 
{
    global $cfg, $hitrow, $http;
    $mutable_graph = '';
    if ($array) {
        foreach ($array as $value) {
            if ((isset($max_value) && ($value > $max_value)) || (!isset($max_value))) {
                $max_value = $value;
            }
        }
        $pixels_per_value = ($max_value == 0) ? 0 : ((double) $max_width) / $max_value;
        $counter = 0;
        foreach ($array as $name => $value) {
            $bar_width = $value * $pixels_per_value;
            $image_no  = ($counter % 7) + 1;
            $hit_rate = $value / $hitrow * 100;
            
            if ($name == '') {  // if name is compressed and optimized
                $mutable_graph .= "<tr>\n".'<td class="key">'."\n"."Archived</td>\n";
            } elseif (!preg_match("/^".$http.":\/\//",$name)) {
                $mutable_graph .= "<tr>\n".'<td class="key">'."\n".$name."</td>\n";
            } else {                
                $html_name = htmlspecialchars($name);
                $name = substr($name, 0, 40);
                $name = htmlspecialchars($name);
                $mutable_graph .= "<tr>\n".'<td class="key">'."\n".'<a href="'.$html_name.'" title="'.$html_name.'">'.$name;
                $mutable_graph .= (strlen($name) >= 50) ? "...":"";
                $mutable_graph .= "</a></td>\n";
            }
            $mutable_graph .= '<td class="rate">'.number_format($hit_rate, 1)."%</td>\n".
                              '<td class="value">'."\n".
                              '<img src="images/bar'.$image_no.'.png" width="'.$bar_width.'" height="10" alt="'.$value.'" />'.
                              '&#160;'.$value."</td>\n".
                              '</tr>';
            $counter++;
        }
    } else {
        if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
            $no_data_msg = 'No data for this request';
        } else {
            $no_data_msg = 'データはありません。';
        }
        $mutable_graph .= '<tr><td class="key">'.$no_data_msg."</td>\n".
                          '<td class="rate">'    .$no_data_msg."</td>\n".
                          '<td class="value">' .$no_data_msg."</td>\n</tr>\n";
    }
    $mutable_graph .= "\n</table>\n";
    return $mutable_graph;
}


/**
 * Horizontal Bar Graph (Weekly)
 * For LAST 30 DAY HITS GRAPH
 */
function bar_graph_week($array, $max_width)
{
    global $cfg, $hitrow;
    $mutable_graph = '';
    if ($array) {
        foreach ($array as $value) {
            if ((isset($max_value) && ($value > $max_value)) || (!isset($max_value))) {
                $max_value = $value;
            }
        }
        $pixels_per_value = ($max_value == 0) ? 0 : ((double) $max_width) / $max_value;
        $counter = 0;
        foreach ($array as $name => $value) {
            $bar_width = $value * $pixels_per_value;
            $image_no = ($counter % 7) + 1;
            $mutable_graph .= "<tr>\n";
            if (preg_match('/Sunday/', $name)) {
                $mutable_graph .= '<td class="sunday">'.htmlspecialchars($name).'</td><td class="sunday">';
            } else {
                $mutable_graph .= '<td class="key">'.htmlspecialchars($name).'</td><td class="value">';
            }
            $mutable_graph .= '<img src="images/bar'.$image_no.'.png" width="'.intval($bar_width).'" height="10" alt="'.$value.'" />&#160;'.$value.'</td>'.
                              '</tr>';
            $counter++;
        }
    } else {
        if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
            $no_data_msg = 'No data for this request';
        } else {
            $no_data_msg = 'データはありません。';
        }
        $mutable_graph .= '<tr><td class="key">'.$no_data_msg."</td>\n".
                          '<td class="value">' .$no_data_msg."</td>\n</tr>";
    }
    $mutable_graph .= "\n</table>\n";
    return $mutable_graph;
}


/**
 * Horizontal Bar Graph (Total)
 * For MONTHLY, YEARLY GRAPH
 */
function bar_graph_total($array, $max_width)
{
    global $cfg, $year_hitrow_6, $year_hitrow_7;
    $mutable_graph = '';
    if ($array) {
        foreach ($array as $value) {
            if ((isset($max_value) && ($value > $max_value)) || (!isset($max_value))) {
                $max_value = $value;
            }
        }
        $pixels_per_value = ($max_value == 0) ? 0 : ((double) $max_width) / $max_value;
        $counter = 0;
        foreach ($array as $name => $value) {
            $bar_width = $value * $pixels_per_value;
            $image_no = ($counter % 7) + 1;
            $mutable_graph .= "<tr>\n".
                              '<td class="key">'.htmlspecialchars($name)."</td>\n".
                              '<td class="value"><img src="images/bar'.$image_no.'.png" width="'.intval($bar_width).'" height="10" alt="'.$value.'" />'.
                              '&#160;'.$value."\n</td>\n".
                              '</tr>';
            $counter++;
        }
    } else {
        if ($cfg['xml_lang'] == 'en' || $cfg['xml_lang'] != 'ja') {
            $no_data_msg = 'No data for this request';
        } else {
            $no_data_msg = 'データはありません。';
        }
        $mutable_graph .= '<tr><td class="key">'.$no_data_msg."</td>\n".
                          '<td class="value">' .$no_data_msg."</td>\n</tr>";
    }
    $mutable_graph .= "\n</table>\n";
    return   $mutable_graph;
}
?>