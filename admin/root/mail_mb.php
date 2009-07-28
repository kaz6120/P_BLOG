<?php
/**
 * P_BLOG Mail System
 *
 * $Id: admin/root/mail_mb.php, 2004/08/15 17:23:03 Exp $
 */

mb_language("Japanese");

if (isset($new_email, $new_user, $new_pass)) {
    //メールタイトル
    $subject = $cfg['blog_title'].' ユーザーID';

    //メール本文
    $message = '
あなたの '.$cfg['blog_title'].' ユーザーアカウントIDです。
大切に保管して下さい。

ユーザー ID : '.$new_user.'
パスワード : '.$new_pass.'
';
    //送信先
    $to = $new_email;
} elseif (isset($mod_user_email, $mod_user_name, $mod_user_pass)) {
    //メールタイトル
    $subject = $cfg['blog_title'].' ユーザーID Update';

    //メール本文
    $message = '
アカウント情報を更新しました。
あなたの新しい '.$cfg['blog_title'].' アカウントIDです。
大切に保管して下さい。

ユーザー ID : '.$mod_user_name.'
パスワード : '.$mod_user_pass.'
';
    //送信先
    $to = $mod_user_email;
}

//文字エンコードを変換
$subject = mb_convert_encoding($subject, "iso-2022-jp", "utf-8");
$message = mb_convert_encoding($message, "iso-2022-jp", "utf-8");


$header =  "From: {$cfg['sendmail_address']}\r\nX-Mailer: P_BLOG Mail System 1.0J";
$param  = "-f{$cfg['sendmail_address']}";

if (mb_send_mail($to, $subject, $message, $header, $param)) {
    $contents .= '<p>ユーザーアカウントIDを '.$to. 'に送信しました。</p>';
} else {
    $contents .= '<p>メール送信エラー</p>';
}

?>
