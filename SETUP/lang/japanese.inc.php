<?php
/**
 * ローカライズ文字列 - 日本語
 *
 * $Id: lang/japanese.inc.php, 2005/01/21 22:21:41 Exp $
 */

if (stristr($_SERVER['PHP_SELF'], ".inc.php")){
	die("good-bye, world! :-P");
}

$lang['manual']         = 'マニュアル';
$lang['anchor']         = '<a href="' . $_SERVER['PHP_SELF'] . '"> English ';
$lang['title']          = 'P_BLOGへようこそ';
$lang['define_p_blog']  = 'P_BLOGはPHP+MySQLベースのWeblogシステムです。'.
                          '通常のログ管理機能の他、主な機能としてファイルアップローダー、アクセス解析、'.
                          'コメント／フォーラム機能、トラックバック機能、Ping送信機能、そして独自のコンテンツ拡張機能「Vars」等があります。</p>'.
                          '<p>P_BLOGは<abbr title="GNU Public License">GPL</abbr>に準拠したオープンソースの'.
                          '<abbr title="「フリー」の定義は「自由」で価格の問題ではありません。">フリーソフトウェア</abbr>で、'.
                          '<abbr title="World Wide Web Consortium">W3C</abbr>勧告に完全準拠したXHTML1.0 Strict 又は XHTML1.1を出力します。';
$lang['license']        = 'ライセンス';
$lang['check_env']      = '環境のチェック';
$lang['software']       = 'ソフトウェア';
$lang['requirements']   = '権限';
$lang['your_env']       = 'あなたの環境';
$lang['permissions']    = 'パーミッションチェック';
$lang['status']         = 'ステータス';
$lang['os_independent'] = 'OSに依存しません。';
$lang['check_perms']    = 'パーミッションのチェック';
$lang['target']         = 'ターゲット';
$lang['next']           = '次に進む';

// Non-editable variables from browser
$lang['set_dbname']     = '「user_config.inc.php」でデータベース名を設定して下さい。';
$lang['set_log_table']  = '「user_config.inc.php」でログテーブル名を設定して下さい。';
$lang['set_info_table'] = '「user_config.inc.php」でファイル情報用テーブル名を設定して下さい。';
$lang['set_data_table'] = '「user_config.inc.php」でファイルデータ用テーブル名を設定して下さい。';
$lang['set_ana_table']  = '「user_config.inc.php」でアクセス解析用テーブル名を設定して下さい。';
$lang['set_user_table'] = '「user_config.inc.php」でユーザー用テーブル名を設定して下さい。';
$lang['set_conf_table'] = '「user_config.inc.php」で環境設定用テーブル名を設定して下さい。';
$lang['set_tb_table']   = '「user_config.inc.php」でトラックバック用テーブル名を設定して下さい。';

// Editable variables from the browser
$lang['set_host_name']  = 'MySQLホスト名を設定して下さい。';
$lang['set_user_name']  = 'MySQLユーザー名を設定して下さい。';
$lang['set_user_pass']  = 'MySQLパスワードを設定して下さい。';
$lang['set_admin_dir']  = 'Adminディレクトリ名を「admin-dir」から変更して下さい。';
$lang['admin_dir_exp']  = '管理者用ディレクトリ名は「admin-dir」のままでは動作しないようになっていますので、別のものに変更して下さい。自分好みの管理ディレクトリ名にして舞台裏を隠すことでセキュリティ度が高まります。';
$lang['rewrite_dbname'] = 'データベース名を設定して下さい。';
$lang['rewrite_dbname_msg'] = '特に変更の必要がなければそのままでもOKです。';
$lang['connect_error']  = '現在、MySQLデータベースに接続していません。';
$lang['connect_error_msg'] = '下の三つの設定のうち一つでも条件に一致しない部分があると接続出来ません。';
$lang['rewrite_host']   = 'MySQLホスト名を再チェックしてみて下さい。';
$lang['rewrite_user']   = 'MySQLユーザー名を再チェックしてみて下さい。';
$lang['rewrite_pass']   = 'MySQLパスワードを再チェックしてみて下さい。';
$lang['perm_looks_ok']  = '読み書き権限があります。問題ありません。';
$lang['perm_looks_bad'] = '読み書き権限がありません。パーミッションを変更し、読み書き権限を与えてください。';
$lang['no_resoures']    = '「resources」ディレクトリがありません。';
$lang['no_user_inc']    = '「user_include」ディレクトリがありません。';
$lang['no_menu']        = '「menu.inc.php」ファイルがありません。';
$lang['no_css_rss']     = '「css_rss.inc.php」ファイルがありません。';
$lang['no_base_xhtml']  = '「base_xhtml.inc.php」ファイルがありません。';
$lang['no_user_conf']   = '「user_config.inc.php」ファイルがありません。';

$lang['save_settings']  = '設定を保存';
$lang['settings']       = '設定入力';
$lang['root_path_settings'] = 'ルートパスの設定';
$lang['root_path_ex']   = 'あなたのP_BLOGのドメイン名以下のトップレベル階層を指定して下さい。<br />ドメイン名を含む「http://yourdomain.com/」部分は入力する必要はありません。<br />最初と最後は「/ (スラッシュ)」を入れて下さい。';
$lang['choose_default_lang'] = 'デフォルト言語を選択';
$lang['choose_time_zone']    = 'タイムゾーンの設定';
$lang['install_or_upgrade'] = 'インストール・アップグレード';
$lang['install_ex']     = '新規インストールかアップグレードかを選択し、スタートボタンをクリックして下さい。';
$lang['install']        = '新規インストール';
$lang['upgrade']        = 'アップグレード';
$lang['start']          = 'スタート!';
$lang['back_to_step2']  = 'STEP-2に戻って下さい。';
$lang['step']           = 'ステップ';
$lang['results']        = '結果';
$lang['create_db']      = 'データベースを作成：';
$lang['select_db']      = 'データベースを選択';
$lang['create_table']   = 'テーブルを作成：';
$lang['create_field']  = '：フィールドを作成：';
$lang['installed_defaults'] = 'デフォルト値をインストール：';
$lang['finished']       = '完了';
$lang['install_fin_msg'] =<<<EOD
<p>インストール作業が完了しました。記事をポストするには、まず最初に通常の「記事の投稿・更新・削除」を行う管理ユーザーを作成する必要があります。
下記の手順に従って管理ユーザーのアカウントを作成して下さい。</p>
<ol>
<li>下のリンクをクリックし、アカウントマネージャに移動します。<br />
<div class="command"><p class="ref"> <a href="../{$admin_dir}/root/root_login.php">../{$admin_dir}/root/root_login.php</a></p></div></li>
<li>あなたのMySQLユーザー名とパスワードを入力してログインし、管理アカウントとパスワードを作成します。</li>
<li>作成した管理アカウントとパスワードを使って管理画面からログインして下さい。あなたが設定した管理画面のパスはこちらになります。<br />
<div class="command"><p class="ref"> <a href="../{$admin_dir}/login.php">../{$admin_dir}/login.php</a></p></div></li>
<li>上手くログイン出来たら今後はこの作成した管理アカウントとパスワードで環境設定、記事の投稿・更新・削除管理などを行います。(MySQLユーザー名とパスワードでは記事の投稿・更新・削除などは出来ません)</li>
</ol>
<div class="important">
<p>※セットアップ終了後はこの「SETUP」ディレクトリは削除して下さい。</p>
</div>
<p>P_BLOGをあなたのサイト構築にご活用頂ければ幸いです。<br />
フィードバック、バグレポートなど、お気軽にフォーラムやメールでポストして下さい。</p>
EOD;
                           
?>