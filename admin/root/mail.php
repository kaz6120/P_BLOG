<?php
/**
 * P_BLOG Mail System
 *
 * $Id: admin/root/mail.php, 2004/08/15 17:22:57 Exp $
 */

if (isset($new_email, $new_user, $new_pass)) {
    $to = $new_email;
    $subject = $cfg['blog_title'] . ' User ID';
    $message = '
Your '.$cfg['blog_title'].' user account has been created.
Please keep this for reminder.

User ID : '.$new_user.'
Password : '.$new_pass.'
';
} elseif (isset($mod_user_email, $mod_user_name, $mod_user_pass)) {
    $to = $mod_user_email;
    $subject = $cfg['blog_title'] . ' User ID Updated';
    $message = '
Your '.$cfg['blog_title'].' user account has been created.
Please keep this for reminder.

User ID : '.$mod_user_name.'
Password : '.$mod_user_pass.'
';
}
$header =  "From: {$cfg['sendmail_address']}\r\nX-Mailer: P_BLOG Mail System 1.0";
$param  = "-f{$cfg['sendmail_address']}";

if (mail($to, $subject, $message, $header, $param)) {
    $contents .= '<p>Acount Info has been e-mailed to '.$to;
} else {
    $contents .= '<p>Mail sending error.</p>';
}

?>
