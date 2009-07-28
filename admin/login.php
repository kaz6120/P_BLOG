<?php
/**
 * Login to Admin Page (Admin Mode Main Page)
 *
 * $Id: admin/login.php, 2004/10/04 21:08:30 Exp $
 */


$cd = '..';
require_once $cd . '/include/config.inc.php';
require_once $cd . '/include/fnc_error_msgs.inc.php';
require_once $cd . '/include/http_headers.inc.php';
require_once './include/fnc_admin.inc.php';

if ($cfg['use_session_db'] == 'yes') {
    require_once './db_session.php';
} else {
    session_name($cfg['p_blog_sess_name']);
    session_start();
}

// Body
if (isset($_SESSION['admin_login'], $_POST['post_username'], $_POST['post_password'])) {
    if ($_SESSION['admin_login'] == '') {
        $_POST['post_password'] = md5($_POST['post_password']);
        $sql = 'SELECT user_id FROM ' . $user_table . 
               " WHERE user_pass = '". $_POST['post_password'] . "' AND user_name = '". $_POST['post_username'] ."' LIMIT 1";
        $res = mysql_query($sql);
        $row = mysql_num_rows($res);
        if ($row != 0) {  
            $_SESSION['user_name']   = $_POST['post_username'];
            $_SESSION['user_pass']   = $_POST['post_password'];
            $_SESSION['admin_login'] = TRUE;
        } else {
            if ((isset($_SESSION['admin_login'])) && ($_SESSION['admin_login'] != '')) {
                $session_on_off = 'on';
            } else {
                $session_on_off = 'off';
            }
            $contents = wrong_user_error();
            
            $admin    = 'yes';    
            xhtml_output('');
            exit;
        }
    }
    if ($_SESSION['admin_login'] == 1) {
        
        // Log-in success!  Move onto the admin top page.
        if (($cfg['root_path'] == '/path/to/p_blog/') || (is_null($cfg['root_path']))) {
            $request_uri = $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
            $uri = parse_url($request_uri);
            $uri = str_replace('login.php', 'admin_top.php', $uri);
            header('Location: ' . $http . '://' . $uri['host'] . $uri['path']);
            exit;
        } else {
            header('Location: ' . $http . '://' . $_SERVER['HTTP_HOST'] . $cfg['root_path'] . $admin_dir . '/admin_top.php');
            exit;
        }
        
    } else {
        if ((isset($_SESSION['admin_login'])) && ($_SESSION['admin_login'] != '')) {
            $session_on_off = 'on';
        } else {
            $session_on_off = 'off';
        }
        $contents = bad_req_error();    
        $admin    = 'yes';
        
        xhtml_output('');
        
        exit;
    }
} elseif (isset($_REQUEST['status']) && ($_REQUEST['status'] == 'logout')) {
    // Initialize session variables
    $_SESSION['admin_login'] = 0;
    $_SESSION['user_name']   = 0;
    $_SESSION['user_pass']   = 0;
    
    if ((isset($_SESSION['admin_login'])) && ($_SESSION['admin_login'] != '')) {
        $session_on_off = 'on';
    } else {
        $session_on_off = 'off';
    }
    
    $contents = login_form();
    $admin    = 'yes';    
    
    xhtml_output('');
    
} else {
    // Initialize session variables
    $_SESSION['admin_login'] = 0;
    $_SESSION['user_name']   = 0;
    $_SESSION['user_pass']   = 0;
    if ((isset($_SESSION['admin_login'])) && ($_SESSION['admin_login'] != '')) {
        $session_on_off = 'on';
    } else {
        $session_on_off = 'off';
    }
    
    $contents = login_form();
    $admin    = 'yes';    

    xhtml_output('');

}
?>