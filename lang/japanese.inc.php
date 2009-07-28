<?php
/**
 * ローカライズ文字列 - 日本語
 *
 * $Id: lang/japanese.inc.php, 2006/01/16 19:15:02 Exp $
 */

//====================
// インデックスページ
//====================
$lang['contents']    = 'コンテンツ';
$lang['prev_logs']   = '以前のログ';
$lang['by_date']     = '日付順';
$lang['by_category'] = 'カテゴリー別';
$lang['permalink']   = 'の恒久URI';

// Popup Tooltip Titles
$lang['permalink_title_1'] = 'この記事「';
$lang['permalink_title_2'] = '」の恒久URI';
$lang['view_tb_title_1']   = 'この記事「';
$lang['view_tb_title_2']   = '」へのトラックバックを閲覧';
$lang['view_com_title_1']  = 'この記事「';
$lang['view_com_title_2']  = '」へのコメントを閲覧';
$lang['post_com_title_1']  = 'この記事「';
$lang['post_com_title_2']  = '」へコメントする';
$lang['cat_title_1']       = 'カテゴリー「';
$lang['cat_title_2']       = '」を閲覧';
$lang['via_email_title_1'] = 'この記事「';
$lang['via_email_title_2'] = '」に関してメールを送る';

$lang['feedback']          = 'フィードバック';
$lang['help']              = 'ヘルプ';

$lang['validate_xhtml']    = 'このページのXHTMLを検証';
$lang['validate_css']      = 'このページのCSSを検証';
$lang['validate_ahl']      = 'このページのXHTMLをAnother HTML-lintで検証';
$lang['rss_of_this_page']  = 'このページのRSS';
 
$lang['more']              = 'もっと読む...';

//====================
//フォーム
//====================
//共通
$lang['go']          = '検索';
$lang['clear']       = 'やり直し';
$lang['archives']    = '過去ログ';
$lang['all']         = '全て';

//検索フォーム
$lang['title']       = 'タイトル';
$lang['category']    = 'カテゴリー';
$lang['tags']        = 'Tags';
$lang['comment']     = 'コメント';
$lang['uri']         = 'URI';
$lang['date']        = '日付';
$lang['advanced_search'] = '詳細検索';

$lang['search_field']        = '検索範囲';
$lang['select_search_field'] = '検索範囲を指定して下さい。';
$lang['enter_keywords']      = 'キーワードを入力して下さい。';
$lang['too_short_keyword']   = 'キーワードが短すぎます。';
$lang['show_archives']       = '過去ログを月別リストアップします。';
$lang['all_words']    = '全ての語を含む';
$lang['at_least_one'] = 'いずれかの語を含む';
$lang['case']         = '大文字小文字を';
$lang['insensitive']  = '区別しない';
$lang['sensitive']    = '区別する';
$lang['between']      = '範囲を指定';
$lang['and']          = ' から ';

//CSSスイッチ
$lang['switch_css']  = 'CSS 選択';
$lang['css_off']     = '切';

//アップロード
$lang['file_name']   = 'ファイル名';
$lang['file_type']   = 'ファイルタイプ';
$lang['file_size']   = 'ファイルサイズ';
$lang['temp_name']   = '一時ファイル名';
$lang['error_msg']   = 'エラー';
$lang['upload_ok']   = 'アップロード : OK';
$lang['no_files_added'] = 'アップロードファイル : なし';
$lang['resource_dir'] = 'リソースディレクトリ';

//データ表示
$lang['no_posts']    = '現在ログはありません。';
$lang['no_files']    = '現在ファイルはありません。';
$lang['recent']      = ' 最近の ';
$lang['previous']    = ' 少し前の ';
$lang['logs']        = ' 件';
$lang['files']       = ' ファイル';
$lang['post']        = 'ポスト';
$lang['mod']         = '修正';
$lang['log']         = 'ログ';
$lang['showing']     = '表示 : ';

//検索結果
$lang['all_data']    = '全アーカイブ';
$lang['keyword']     = 'キーワード';
$lang['hit_msg']     = '件 表示 :';
$lang['show_log']    = '件のログがあります。表示 :';
$lang['uri_hit']     = '<span class="search-res">URI</span>から検索、ヒット：';
$lang['categ_hit']   = '<span class="search-res">カテゴリー</span>から検索、ヒット：';
$lang['categ_list']  = 'このカテゴリーの登録数：';
$lang['tags_list']   = 'このTagsの登録数：';
$lang['comment_hit'] = '<span class="search-res">コメント</span>から検索、ヒット：';
$lang['title_hit']   = '<span class="search-res">タイトル</span>から検索、ヒット：';
$lang['title_comment_hit']   = '<span class="search-res">タイトル</span>と<span class="search-res">コメント</span>から検索、ヒット：';
$lang['binname_hit'] = '<span class="search-res">ファイル名</span>から検索、ヒット：';
$lang['bintype_hit'] = '<span class="search-res">ファイルタイプ</span>で検索、ヒット：';
$lang['bintype_list']= 'このファイルタイプの登録数：';
$lang['by_month']    = '検索月';
$lang['no_matches']  = '該当するデータはありません。';
$lang['search']      = '検索';
$lang['status_idle'] = '状態 : 待機中...';
$lang['prev']        = '前へ';
$lang['next']        = '次へ';

$lang['log_uri']     = 'この記事のURI';
$lang['flip_pages']  = 'ページ';
$lang['flip']        = '移動';

//================================
//管理者モード
//================================
$lang['admin']           = 'サイト管理';
$lang['analyze']         = 'アクセス解析';
$lang['edit']            = '編集';
$lang['select_mode']     = 'モード選択';
$lang['user_name']       = 'ユーザー名';
$lang['user_pass']       = 'パスワード';
$lang['login']           = 'ログイン';
$lang['logout']          = 'ログアウト';
$lang['login_user']      = 'ログインユーザー';
$lang['date_and_time']   = '日付と時刻';
$lang['use_custom_date'] = 'この日時を使う';
$lang['add_new']         = '新規ポスト';
$lang['mod_del']         = '修正・削除';
$lang['send_file']       = 'ファイルアップロード';
$lang['update_bin']      = 'バイナリデータ修正・削除';
$lang['binary']          = 'バイナリ';
$lang['no_update_timestamp'] = '更新時刻を変更しない';
$lang['make_private']    = 'この記事を非公開にする';
$lang['post_log']        = 'ログをポスト';
$lang['post_file']       = 'ファイルをポスト';
$lang['preview']         = 'プレビュー';
$lang['publish']         = '公開する';
$lang['published']       = 'このログは公開されました。';
$lang['file_published']  = 'このファイルは公開されました。';
$lang['draft']           = '下書き';
$lang['draft_list']      = '下書きリスト';
$lang['draft_update']    = '下書きを修正';
$lang['draft_destroy']   = '下書きを破棄';
$lang['use_draft_time']  = '下書き時刻をポスト時刻とする';
$lang['draft_mod_del']   = '下書きの修正・破棄';
$lang['replace_to']      = 'このファイルに差し替える';

//Root Mode
$lang['create_accounts'] = '新規アカウント作成';
$lang['account_manager'] = 'アカウントマネージャー';
$lang['root_user_only']      = 'ルートユーザー専用';
$lang['enter_mysql_account'] = 'MySQLアカウントを入力';
$lang['login_success']       = 'ログイン認証完了';
$lang['user_list']           = 'ユーザーリスト';
$lang['regist_date']         = '登録日';
$lang['new_user_account']    = '新規ユーザーアカウント';
$lang['invalid_name']        = '不正ユーザー名';
$lang['invalid_name_msg']    = 'ユーザー名の書式が正しくありません。</p><p>ユーザー名は英数字2文字以上、16文字以内にして下さい。';
$lang['invalid_pass']        = '不正パスワード';
$lang['invalid_pass_msg']    = 'パスワードの書式が正しくありません。</p><p>パスワードは英数字4文字以上、16文字以内にして下さい。';
$lang['invalid_email']       = '不正メールアドレス';
$lang['invalid_email_msg']   = 'メールアドレスの書式が正しくありません。';
$lang['no_emal_host_msg']    = '存在しないホスト名のメールアドレスです。';
$lang['create']              = ' 作成 ';
$lang['new_user_created']    = '新規ユーザー作成完了';
$lang['new_user_created_success'] = '新規ユーザーアカウントを作成しました。';
$lang['update_user_account'] = 'ユーザーアカウント更新';
$lang['enter_a_new_pass']    = '新しいパスワードを入力';
$lang['save']                = '保存';
$lang['account_updated']     = 'アカウント更新完了';
$lang['account_updated_msg'] = 'ユーザーアカウント情報を更新しました。';
$lang['account_deleted']     = 'アカウント削除完了';
$lang['account_deleted_msg'] = 'ユーザーアカウント情報を削除しました。';

//ユーザー認証エラーメッセージ
$lang['wrong_user']  = 'ユーザー認証出来ません。';
$lang['bad_req']     = '不正なリクエストです。';
$lang['please']      = 'して下さい。';

$lang['no_title']    = 'タイトルがありません。';
$lang['no_f_name']   = 'ファイル名がありません。';
$lang['no_uri']      = 'URIがありません。';
$lang['no_category'] = 'カテゴリーがありません。';
$lang['no_comment']  = 'コメントがありません。';

$lang['no_id']       = 'ID指定がありません。';
$lang['cant_find']   = 'データが見つかりません。';
$lang['log_added']   = '新規ログをポストしました。';
$lang['updated']     = 'を更新しました。';
$lang['deleted']     = 'を削除しました。';
$lang['del_failed']  = 'データ削除出来ませんでした。';

$lang['check']       = 'チェックする';
$lang['check_index'] = 'インデックスページをチェックする';
$lang['file']        = 'ファイル';
$lang['update']      = '更新';
$lang['delete']      = '削除';
$lang['send_img']    = '画像等をここへアップロード';
$lang['none']        = 'なし';

$lang['edit_custom_file'] = 'カスタムファイル編集';
$lang['load_file']   = 'ファイルを読み込む';
$lang['edit_file_default_msg']  = '
上のメニューで編集するファイルを読み込んで下さい。
該当ファイルが無い場合は「ファイルを読み込む」ボタンを押すと
自動的に生成されます。

コンテンツメニューリストの編集例：
-----------------------------------------
\'タイトル\'　=> \'パス\'


PHPヒアドキュメントの編集例：
-----------------------------------------
「<<<EOD」から「EOD;」の間に記述します。
例：
<<<EOD
(ここに記述する)
EOD;


カスタムメニューサンプル1：
-----------------------------------------
<div class="menu">
<h2>Sample</h2>
<ul>
<li>menu1</li>
<li>menu2</li>
<li>menu3</li>
</ul>
</div>

カスタムメニューサンプル2：
-----------------------------------------
<div class="menu">
<h2>
<a href="./"  
    onclick="toggle(\'middle-menu\'); return false;" 
    onkeypress="return false;"
    accesskey="m">
Sample</a>
</h2>
<div id="middle-menu" class="toggle">
<ul>
<li>menu1</li>
<li>menu2</li>
<li>menu3</li>
</ul>
</div>
</div>
';

$lang['contents_top']  = 'コンテンツトップ';
$lang['menu']          = 'コンテンツメニュー';
$lang['menu_middle']   = 'メニュー(中)';
$lang['css_rss']       = 'CSS　と　RSS';
$lang['menu_bottom']   = 'メニュー(下)';
$lang['article_addition'] = '各アーティクル下';
$lang['custom_footer'] = 'ユーザーカスタムフッタ';
$lang['base_xhtml']    = '基本 XHTML';
$lang['tag_buttons']    = 'タグボタン';

$lang['is_a_dir']         = ' はディレクトリです。';
$lang['is_not_writable']  = ' は書き込み権限がありません。';
$lang['file_not_created'] = 'ファイルが作成出来ませんでした';
$lang['file_created']  = 'ファイルが作成されました';

//================================
//システム情報
//================================
$lang['sys_env']     = 'システム環境';
$lang['db_info']     = 'データベース情報';
$lang['db_name']     = '接続されているデータベース';
$lang['log_entry']   = '登録されているログ数';
$lang['file_entry']  = '登録されているバイナリデータ数';
$lang['ui_info']     = 'UI情報';
$lang['css_cookie']  = 'CSS切り替えのクッキー有効期限(単位:秒)';
$lang['info']        = '情報';
$lang['manuals']     = 'マニュアル';

$lang['system_admin']    = 'システム管理';
$lang['preferences']     = '環境設定';
$lang['db_table_status'] = 'DBステータス';
$lang['optimized_msg_1'] = 'テーブル「';
$lang['optimized_msg_2'] = '」を最適化しました。';

$lang['table_name']    = 'テーブル名';
$lang['records']       = 'レコード数';
$lang['size']          = 'サイズ';
$lang['overhead']      = 'オーバーヘッド'; 
$lang['optimize']      = '最適化';
$lang['select_all']    = '全てを選択';
$lang['select_off']    = '選択を解除';
$lang['backup']        = 'バックアップ';
$lang['choose_table']  = 'テーブルを選択して下さい。';

//================================
//アクセス解析
//================================
$lang['year']          = '年';
$lang['month']         = '月';
$lang['day']           = '日';
$lang['total']         = '総計';
$lang['hits_per_hour'] = '時間別アクセス情報';
$lang['remote_host']   = 'ホスト・IPアドレス情報';
$lang['user_agent']    = 'ブラウザとOS情報';
$lang['referer']       = 'リンク元取得情報';
$lang['daily']         = 'ここ1ヶ月間の日別統計';
$lang['monthly']       = '月別統計';
$lang['yearly']        = '年別統計';
$lang['downloads']     = 'ダウンロード';
$lang['del_all_logs']  = '全てのログを削除';
$lang['logs_deleted']  = '全てのログを削除しました。';
$lang['magic_words']   = 'マジックワード';

//================================
//フォーラム／コメント
//================================
$lang['forum']         = 'フォーラム';
$lang['forum_admin']   = 'フォーラム管理';
$lang['topic_list']    = 'トピックリスト';
$lang['new_topic']     = '新規トピック';
$lang['topic']         = 'トピック';
$lang['thread']        = 'スレッド';
$lang['reply']         = 'レス';
$lang['back_to_topic'] = 'トピックに戻る';
$lang['name']          = '名前';
$lang['posted_by']     = '投稿者';
$lang['private']       = '公開されません';
$lang['optional']      = 'オプション';
$lang['comment_notice'] = '<span class="notice">*</span>は入力必須です。E-Mailは公開されません。';
$lang['last_modified'] = '最終更新';
$lang['topics']        = '件のトピックがあります。';
$lang['comments']      = '件のコメントがあります。';
$lang['comment_added'] = 'コメントを書き込みました。';
$lang['replied']       = 'レスを書き込みました。';
$lang['quote']         = '引用';
$lang['no_tbl_msg']    = 'フォーラム用のDBテーブルがありません。';
$lang['install_or_update_msg']    = 'データベースの新規インストール、又はアップデートを実行して下さい。';
$lang['install']       = '新規インストール';
$lang['no_tags_allowed'] = 'タグは使用出来ません。';
$lang['recent_comments'] = '最近のコメント';
$lang['latest']        = '最新ポスト';

//================================
// Trackback
//================================
$lang['trackbacks']    = 'Trackbacks';
$lang['tb_sendurl']    = 'トラックバックPingを送信 ';
$lang['tb_pingurl']    = 'Ping URI';
$lang['tb_response']   = 'レスポンス';
$lang['tb_ping_error'] = 'Pingが拒否されました。';
$lang['tb_ping_ok']    = 'Pingが受信されました。';
$lang['tb_ping_no_res'] = 'レスポンスがありません。';
$lang['send_update_ping'] = '更新Pingを送信';
$lang['recent_trackbacks'] = '最近のトラックバック';

//================================
// Feedback
//================================
$lang['no_name']            = '名前がありません。';
$lang['write_your_name']    = '名前を書いて下さい。';
$lang['no_email']           = 'メールアドレスがありません';
$lang['write_your_email']   = 'メールアドレスを書いて下さい。';
$lang['invalid_email']      = '不正なメールアドレスです';
$lang['check_your_email']   = 'メールアドレスを再チェックして下さい。';
$lang['no_msg']             = 'メッセージがありません';
$lang['write_your_msg']     = 'メッセージを書いてください。';
$lang['sender']             = '送信者';
$lang['send_this_mail']     = 'この内容で送信';
$lang['sent_feedback']      = 'フィードバックメールを送信しました。';
$lang['mail_sending_error'] = 'メール送信エラー';
$lang['feedback_is_off']    = 'フィードバックフォーム機能は現在オフになっています。';
$lang['message']            = 'メッセージ';

//================================
// Preferences Panel
//================================
$lang['base_settings'] = '基本設定';
$lang['site_name']     = 'サイト名';
$lang['subtitle']      = 'サブタイトル';
$lang['root_path']     = 'ルートパス';
$lang['root_path_ex']  = 'あなたのP_BLOGのドメイン名以下のトップレベル階層を指定して下さい。ドメイン名を含む「http://yourdomain.com/」部分は入力する必要はありません。';
$lang['root_path_ex2'] = '最初と最後は「/ (スラッシュ)」を入れて下さい。';
$lang['index_page']    = 'インデックスページ';
$lang['xhtml_version'] = 'XHTMLバージョン';
$lang['charset']       = '出力文字コード';
$lang['content_lang']  = 'コンテンツ(XML)言語';
$lang['convert_utf8']  = 'ユニコード変換';
$lang['mysql_encode']  = 'MySQL内部エンコード';
$lang['mysql_count_q'] = 'MySQLカウントクエリー';
$lang['tz_offset']     = 'タイムゾーン';
$lang['show_date']     = '日付タイトルを表示';
$lang['show_post_time'] = '記事の投稿時間を表示';
$lang['date_format']   = '日付フォーマット';
$lang['page_max']      = '最大記事表示数';
$lang['pager_style']   = 'ページャースタイル';
$lang['generate_rss']  = 'RSSを提供';
$lang['enable_smiley']  = 'スマイリー機能を使用';
$lang['article_archive_style'] = '記事のアーカイブスタイル';
$lang['monthly_archive_order'] = '月別アーカイブ並び順';
$lang['newest_first']  = '新しい順';
$lang['oldest_first']  = '古い順';
$lang['show_cat_menu'] = 'カテゴリーメニューを表示';
$lang['category_style'] = 'カテゴリーの表示スタイル';
$lang['show_cat_num']  = 'カテゴリー数を表示';
$lang['show_re_recent'] = '「少し前のn件」メニューを表示';
$lang['pre_recent_max'] = '「少し前のn件」表示数';
$lang['file_archive_style'] = 'ファイルアーカイブスタイル';
$lang['file_index_title']  = 'ファイルページのタイトル';
$lang['use_2_indexes'] = '2種類のファイルインデックスを使用';
$lang['show_f_date_title'] = '日付タイトルをファイルページに表示';
$lang['listup_cat_max']    = 'カテゴリー別表示数';
$lang['show_f_cat_menu']   = 'ファイルカテゴリーメニューを表示';
$lang['file_cat_style']    = 'ファイルカテゴリースタイル';
$lang['show_f_cat_num']    = 'ファイルカテゴリー数を表示';
$lang['show_f_type_menu']  = 'ファイルタイプメニューを表示';
$lang['file_type_style']   = 'ファイルタイプメニュースタイル';
$lang['show_f_type_num']   = 'ファイルタイプ数を表示';
$lang['show_thumb_nail']   = '画像をサムネイル風に表示';
$lang['thumb_nail_size']   = '画像の表示サイズ最大値';
$lang['show_img_size']     = '画像サイズを表示';
$lang['show_md5']          = 'ファイルのMD5値を表示';
$lang['use_dl_counter']    = 'ダウンロードカウンターを使用';
$lang['use_css_switch']    = 'CSS切り替えスイッチを使用';
$lang['css_cookie_name']   = 'CSSクッキー名';
$lang['default']           = 'デフォルト';
$lang['footer_settings']   = 'フッターの設定';
$lang['footer_style']      = 'フッタースタイル';
$lang['default_footer']       = '1. デフォルト (P_BLOGロゴと管理者E-Mail付き)';
$lang['p_blog_orig_w3_logos'] = '2. P_BLOGオリジナルW3Cロゴ付き';
$lang['w3_orig_logos']        = '3. W3Cオリジナルロゴ付き';
$lang['user_custom_footer']   = '4. ユーザーカスタムフッター';
$lang['spam_blocked_ex']      = 'このアドレスはスパムブロックされます。フッタースタイル「1」と組み合わせて使用出来ます。';
$lang['email_title']          = 'E-Mail リンクタイトル';
$lang['copyright']            = 'あなたの著作権表示';
$lang['page_gen_time']        = 'ページ生成時間を表示';
$lang['feedback_comment_tb']  = 'フィードバック／コメント／トラックバックの設定';
$lang['sendmail_address']     = 'Sendmail用メールアドレス';
$lang['sendmail_address_ex']  = 'SendmailまたはPostfixで使用するメールアドレスを記述します。';
$lang['use_feedback']         = 'フィードバックフォームを使用する';
$lang['use_feedback_ex']      = '使用にはあなたのサーバーでSendmailまたはPostfixが有効になっている必要があります。';
$lang['show_email_link']      = 'E-Mailリンクを表示';
$lang['show_email_link_ex']   = '各記事のフッタにフィードバックフォームへのリンクを表示します。';
$lang['acccept_comments']     = 'コメント機能を有効にする';
$lang['comment_style']        = 'コメントスタイル';
$lang['forum_style']          = 'フォーラムスタイル';
$lang['topic_max']            = 'トピック別最大表示数';
$lang['trackback']            = 'トラックバック機能を有効にする';
$lang['ping_server_list']     = 'Pingサーバーリスト';
$lang['ping_server_list_ex']  = 'URIはカンマ+改行で区切って下さい。';
$lang['show_recent_comment']  = '「最近のコメント」を表示';
$lang['recent_comment_max']   = '「最近のコメント」表示数';
$lang['show_recent_trackback']  = '「最近のトラックバック」を表示';
$lang['recent_trackback_max']   = '「最近のトラックバック」表示数';
$lang['admin_mod_settings']   = '管理モードの設定';
$lang['upload_dir']           = '画像アップロード用ディレクトリ';
$lang['updaad_max']           = '画像アップロード最大数';
$lang['use_access_analyzer']  = 'アクセス解析機能を有効にする';
$lang['group_refs']           = 'リファラをまとめる最小数';
$lang['del_log_button']       = 'アクセスログ削除ボタンを有効にする';
$lang['magic_words']          = 'ログ削除用マジックワード';
$lang['gz_compress']          = 'Gunzip圧縮出力する';
$lang['send_id']              = 'アカウントIDをメールで通知する';
$lang['use_sess_db']          = 'セッションDBを使用する';
$lang['sess_name']            = 'セッション名';
$lang['root_ses_name']        = 'ルートユーザセッション名';
$lang['dev_mode']             = '開発者モード設定';
$lang['debug_mode']           = 'デバッグモード';
$lang['custom']               = 'カスタム';

if (stristr($_SERVER['PHP_SELF'], ".inc.php")){
	die("hello, world! :-P");
}
?>