<?php
/**
 * P_BLOG Accesskey List
 *
 * $Id: 2004/10/26 13:39:07 Exp $
 */

if ($cfg['xml_lang'] == 'ja') {
    $_lang['accesskeys']   = 'アクセスキー';
    $_lang['navigation']   = 'コンテンツナビゲーション';
    $_lang['comment_form'] = 'コメントフォーム';
    $_lang['login_access'] = 'ログインとアクセス解析';
    $_lang['edit_draft']   = 'ドラフト記事の編集';
    $_lang['publish_edit'] = '記事の公開と編集';
} else {
    $_lang['accesskeys']   = 'Access Keys';
    $_lang['navigation']   = 'Content Navigation';
    $_lang['comment_form'] = 'Comment Form';
    $_lang['login_access'] = 'Login &amp; Access Analyze';
    $_lang['edit_draft']   = 'Edit Draft';
    $_lang['publish_edit'] = 'Publish &amp; Edit';
}

$contents =<<<EOD
<div class="section">
<h2>{$_lang['accesskeys']}</h2>
<dl>
<dt>Macintosh</dt>
<dd><p><kbd>ctrl</kbd> + <kbd><span class="important">Key</span></kbd></p></dd>
<dt>Windows / Linux</dt>
<dd><p><kbd>Alt</kbd> + <kbd><span class="important">Key</span></kbd></p></dd>
</dl>

<div class="section">
<h3 id="conent-navi-keys" class="article-title">{$_lang['navigation']}</h3>
<ul>
<li><kbd><span class="important">T</span></kbd> - Top Page Title</li>
<li><kbd><span class="important">F</span></kbd> - Feedback</li>
<li><kbd><span class="important">H</span></kbd> - Help</li>
<li><kbd><span class="important">K</span></kbd> - Keyword (Search Form)</li>
<li><kbd><span class="important">G</span></kbd> - Go (Search Form Submit Button)</li>
</ul>
</div>

<div class="section">
<h3 id="comment-form-keys" class="article-title">{$_lang['comment_form']}</h3>
<ul>
<li><kbd><span class="important">N</span></kbd> - Name</li>
<li><kbd><span class="important">E</span></kbd> - E-Mail</li>
<li><kbd><span class="important">U</span></kbd> - URI</li>
<li><kbd><span class="important">R</span></kbd> - Remember Me</li>
<li><kbd><span class="important">I</span></kbd> - title</li>
<li><kbd><span class="important">C</span></kbd> - Comment</li>
<li><kbd><span class="important">P</span></kbd> - Password</li>
<li><kbd><span class="important">S</span></kbd> - Post (Submit Button)</li>
</ul>
</div>

<div class="section">
<h3 id="admin-keys" class="article-title">{$lang['admin']}</h3>
<div class="section">
<h4>{$_lang['login_access']}</h4>
<ul>
<li><kbd><span class="important">U</span></kbd> - User Name</li>
<li><kbd><span class="important">P</span></kbd> - Passsword</li>
<li><kbd><span class="important">L</span></kbd> - Login</li>
<li><kbd><span class="important">A</span></kbd> - Access Analyze</li>
<li><kbd><span class="important">G</span></kbd> - Access Analyzer Go button</li>
</ul>
</div>
<div class="section">
<h4>{$_lang['edit_draft']}</h4>
<ul>
<li><kbd><span class="important">U</span></kbd> - Update</li>
<li><kbd><span class="important">P</span></kbd> - Preview</li>
</ul>
</div>
<div class="section">
<h4>{$_lang['publish_edit']}</h4>
<ul>
<li><kbd><span class="important">U</span></kbd> - Update</li>
<li><kbd><span class="important">P</span></kbd> - Publish</li>
</ul>
</div>
</div>

</div><!-- End .section Level-1 -->
EOD;
?>