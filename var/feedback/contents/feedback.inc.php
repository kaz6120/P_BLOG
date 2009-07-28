<?php
/**
 * P_BLOG Feedback System 1.3
 * 
 * $Id: 2006/03/13 22:30:53 Exp $
 */

//////////////////*  SETTINGS  *//////////////////////

// Target link
$action_target = 'index.php?id=feedback';

//////////////////*  SETTINGS  *//////////////////////

// Deny direct access
if (stristr($_SERVER['PHP_SELF'], ".inc.php")){
	die("hello, world! :-p");
}


if (isset($_POST['y_name'], $_POST['e_mail'], $_POST['subject'], $_POST['message'], $_GET['id'])) {
    if ($_POST['y_name'] == '') {
        $contents = '<h2>'.$lang['no_name'].'</h2>'.
                    '<p class="warning">' . $lang['write_your_name'] . '</p>';
    } elseif ($_POST['e_mail'] == '') {
        $contents = '<h2>' . $lang['no_email'] . '</h2>'.
                    '<p class="warning">' . $lang['write_your_email'] . '</p>';
    } elseif (($_POST['e_mail'] != '') && 
              (!preg_match('/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $_POST['e_mail']))) {
        $contents = '<h2>'.$lang['invalid_email'].'</h2>'.
                    '<p class="warning">' . $lang['check_your_email'] . '</p>';
    } elseif ($_POST['message'] == '') {
        $contents = '<h2>' . $lang['no_msg'] . '</h2>'.
                    '<p class="warning">' . $lang['write_your_msg'] . '</p>';
    } else {

        $y_name          = sanitize($_POST['y_name']);
        $e_mail          = sanitize($_POST['e_mail']);
        $message         = sanitize($_POST['message']);
        
        $mutable_message = $lang['sender'] . '：'.$y_name."\n\n".
                           "E-Mail: ".$e_mail."\n\n".
                           $lang['comment'].":\n\n".$message."\n";
        
        $subject = sanitize($_POST['subject']);
        
        // Preview
        if ((isset($_POST['preview']) && ($_POST['preview'] != ''))) {
            $contents = '<h2>'.$lang['preview'].'</h2>'.
                        '<h3>'  . $subject . '</h3>'.
                        '<pre>' . $mutable_message . '</pre>'.
                        '<form method="post" action="'.$action_target.'">'.
                        '<p>'.
                        '<input type="hidden" name="y_name" value="'  .$y_name.  '" />'.
                        '<input type="hidden" name="e_mail" value="'  .$e_mail.  '" />'.
                        '<input type="hidden" name="subject" value="' .$subject. '" />'.
                        '<input type="hidden" name="message" value="' .$message. '" />'.
                        '<input type="submit" name="ok" value="'.$lang['send_this_mail'].'" />'.
                        '</p>'.
                        '</form>';
        }
        // Send
        if ((isset($_POST['ok'])) && ($_POST['ok'] != '')) {
            //mb_language('ja');
            mb_language('uni');
            mb_internal_encoding('UTF-8');
            
            // Reference：
            // http://www.komonet.ne.jp/~php/chap15.htm
            
            $mutable_message  = $mutable_message;
            $mutable_message .= 
            "\n\n---------------------------------------------------".
            "---------------------------------------------------\n\n";
            $mutable_message .= "Browser：".$_SERVER['HTTP_USER_AGENT']."\n\n";
            if (!isset($_SERVER['REMOTE_HOST'])) {
                $_SERVER['REMOTE_HOST'] = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
                $re_host = $_SERVER['REMOTE_HOST'];
            } else {
                $re_host = $_SERVER['REMOTE_HOST'];
            }
            $mutable_message .= "Host：".$re_host."\n\n";
            $mutable_message .= "Date: ". date("F j, Y, g:i a");
            
            $message = (PHP_VERSION <= 4.3) 
                       ? unhtmlentities($mutable_message) 
                       : html_entity_decode($mutable_message);
//            $message = mb_convert_encoding(stripslashes($message), "ISO-2022-JP", "auto");
            $message = mb_convert_encoding(stripslashes($message), 'UTF-8', 'auto');
            
            $header          = "From: ".mb_encode_mimeheader($y_name)."<{$e_mail}>\n";
            $header         .= "X-Mailer: P_BLOG Feedback System ver." . P_BLOG_VERSION;
            $param           = "-f{$cfg['sendmail_address']}";
            if (mb_send_mail($cfg['sendmail_address'], $subject, $message, $header, $param)) {
                $contents = '<h2>Thank You.</h2>'.
                            '<p>'.$lang['sent_feedback'].'</p>';
            } else {
                $contents = '<h2>Oops.</h2>'.
                            '<p>'.$lang['mail_sending_error'].'</p>';
            }
        }
    }
} else { // Default
    if (isset($_GET['a_id']) && ($_GET['a_id'] != '')) {
        $a_id = $_GET['a_id'];
        $a_id = $_GET['a_id'];
        $cd = '../..';
        
        require $cd . '/include/user_config.inc.php';
        
        $sql = "SELECT `name` FROM `{$log_table}` WHERE `id` = '{$a_id}'";
        $res = mysql_query($sql);
        $row = mysql_fetch_array($res);
        $res_article_title = $row[0];
        $a_title = ($cfg['mysql_lang'] != 'UTF-8') 
                   ? mb_convert_encoding($res_article_title, "utf-8", $cfg['mysql_lang'])
                   : $res_article_title;
        $subject = 'Re : ' . $a_title;
    } else {
        $subject = $cfg['blog_title']. ' : Feedback';
    }
    
    $contents = '<h2>'.$subject.'</h2>';
    
    $contents .= ($cfg['sendmail_address'] == '' || 
                  $cfg['sendmail_address'] == 'yourname@example.com' || 
                  $cfg['use_feedback_form'] != 'yes')
                 ? '<p>'.$lang['feedback_is_off'].'</p>'
                 : '';
    
    $contents .=<<<EOD

<div class="section">
<form method="post" action="{$action_target}">
<p>
<input type="text" id="y_name" name="y_name" size="30" accesskey="n" tabindex="1" value="" class="bordered" />
<label for="y_name">{$lang['name']}</label>
</p>
<p>
<input type="text" id="e_mail" name="e_mail" size="30" accesskey="e" tabindex="2" value="" class="bordered" />
<label for="e_mail">E-Mail</label>
</p>
<p>
<textarea onfocus="if (value == '{$lang['message']}') { value = ''; }" 
          onblur="if (value == '') { value = '{$lang['message']}'; }" 
          id="message" name="message" 
          rows="20" cols="45" accesskey="m" tabindex="3">{$lang['message']}</textarea>
</p>
<p>
<input type="hidden" name="subject" value="{$subject}" />
<input type="submit" name="preview" tabindex="4" accesskey="p" value="{$lang['preview']}" />
</p>
</form>
</div>
EOD;

}

?>
