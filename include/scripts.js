/**
 * JavaScript for P_BLOG
 *
 * @sice    2005-09-04 19:31:47
 * modified 2006-08-17 13:02:28
 */

/**
 * Toggle Button DOM
 */
function toggle(targetId) {
    var target = document.getElementById(targetId);
    if (target.style.display == "block") {
        target.style.display = "none";
    } else {
        target.style.display = "block";
    }
    return false;
}


/**
 * ADMIN MODE TABLE STATUS
 */
function selectTables(check){
    var i;
    for (i = 0; i < document.forms.dbtables.tables.length; i++) {
        document.forms.dbtables.tables[i].checked = check;
    }
}



/**
 * INPUT CHECK
 *
 */
function inputCheck() {
    if (document.getElementById('article-title').value == '') {
        alert('タイトルが入力されていません。');
        document.getElementById('article-title').focus();
        return false;
    } else if (document.getElementById('category').value == '') {
        alert('カテゴリーが入力されていません。');
        document.getElementById('category').focus();
        return false;
    } else if (document.getElementById('comment').value == '') {
        alert('コメントが入力されていません。');
        document.getElementById('comment').focus();
        return false;
    }
    return true;
}

function inputCheck_e() {
    if (document.getElementById('article-title').value == '') {
        alert('No Title');
        document.getElementById('article-title').focus();
        return false;
    } else if (document.getElementById('category').value == '') {
        alert('No Category');
        document.getElementById('category').focus();
        return false;
    } else if (document.getElementById('comment').value == '') {
        alert('No Comment');
        document.forms.getElementById('comment').focus();
        return false;
    }
    return true;
}

function inputCheckBin() {
    if (document.getElementById('bin-title').value == '') {
        alert('タイトルが入力されていません。');
        document.getElementById('bin-title').focus();
        return false;
    } else if (document.getElementById('binfile').value == '') {
        alert('ファイルが選択されていません。');
        document.getElementById('binfile').focus();
        return false;
    } else if (document.getElementById('bin-category').value == '') {
        alert('カテゴリーが入力されていません。');
        document.getElementById('bin-category').focus();
        return false;
    } else if (document.getElementById('comment').value == '') {
        alert('コメントが入力されていません。');
        document.getElementById('comment').focus();
        return false;
    }
    return true;
}

function inputCheckBin_e() {
    if (document.getElementById('bin-title').value == '') {
        alert('No Title');
        document.getElementById('bin-title').focus();
        return false;
    } else if (document.getElementById('binfile').value == '') {
        alert('No File');
        document.getElementById('binfile').focus();
        return false;
    } else if (document.getElementById('bin-category').value == '') {
        alert('No Category');
        document.getElementById('bin-category').focus();
        return false;
    } else if (document.getElementById('comment').value == '') {
        alert('No Comment');
        document.getElementById('comment').focus();
        return false;
    }
    return true;
}

/*Input Check for Forum*/
function ForumInputCheck() {
    if (document.forms.addform.user_name.value == '') {
        alert('名前が入力されていません。');
        document.forms.addform.user_name.focus();
        return false;
    } else if (document.forms.addform.title.value == '') {
        alert('タイトルが入力されていません。');
        document.forms.addform.title.focus();
        return false;
    } else if (document.forms.addform.comment.value == '') {
        alert('コメントが入力されていません。');
        document.forms.addform.comment.focus();
        return false;
    } else if (document.forms.addform.user_pass.value == '') {
        alert('パスワードが入力されていません。');
        document.forms.addform.user_pass.focus();
        return false;
    }
    return true;
}

function ForumReplyCheck() {
    if (document.forms.addform.user_name.value == '') {
        alert('名前が入力されていません。');
        document.forms.addform.user_name.focus();
        return false;
    } else if (document.forms.addform.comment.value == '') {
        alert('コメントが入力されていません。');
        document.forms.addform.comment.focus();
        return false;
    } else if (document.forms.addform.user_pass.value == '') {
        alert('パスワードが入力されていません。');
        document.forms.addform.user_pass.focus();
        return false;
    }
    return true;
}

/**
 * CONFIRM DELETION
 *
 */
function confirmDelete() {
    if (document.forms.del.id.value != '') {
        if (! confirm('削除してもよろしいですか？')) {
	    document.forms.del.id.focus();
	    return false;
	}
    }
    return true;
}

function confirmDelete_e() {
    if (document.forms.del.id.value != '') {
        if (! confirm('OK to delete this?')) {
	    document.forms.del.id.focus();
	    return false;
	}
    }
    return true;
}


/**
 * TAG BUTTON FUNCTIONS
 *
 * @author Gabriele Caniglia <www.musimac.it>
 * @author kaz
 * @author Mory Gonzalez <http://www.portalshit.net/>
 */
/* --- BASE FUNCTION ---- */
function insertAtCursor(comment, myValue) {
    //IE support
    if (document.selection) {
        comment.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    } else if (comment.selectionStart || comment.selectionStart == '0') { //MOZILLA/NETSCAPE support
        var startPos = comment.selectionStart;
        var endPos = comment.selectionEnd;
        comment.value = comment.value.substring(0, startPos) + myValue + comment.value.substring(endPos, comment.value.length);
    } else {
        comment.value += myValue;
    }
}
/*
'tag_value' comes from the HTML markup (DOM API) and explains itself enough; :-)
'bin' is boolean: true for targetting the binaries form textarea, false for the logs form textarea;
*/
function Tag(tag_value, bin) {
	cmt_txt = bin ? document.forms.addform.bincomment : document.forms.addform.comment;
	var TagLookup = {
		'p'			:	'<p></p>',
		'div'		:	'<div class=""></div>',
		'span'		:	'<span class=""></span>',
		'strong'	:	'<strong></strong>',
		'img'		:	'<img src="./resources/" width="" height="" alt="" />',
		'a'			:	'<a href="" title=""></a>',
		'ul'		:	'<ul>\n<li></li>\n</ul>',
		'ol'		:	'<ol>\n<li></li>\n</ol>',
		'li' 		:   '<li></li>',
		'quote'		:	'<blockquote cite="http://" title="">\n<p></p>\n</blockquote>',
		'br'		:	'<br />',
		'code'      :   '<code></code>',
		'abbr'      :   '<abbr title=""></abbr>',
		'tag'		:	'&lt;&gt;'
	};
    cmt_txt.focus();
    insertAtCursor(cmt_txt, TagLookup[tag_value]);
}

/**
 * Wrap selected text with XHTML Tag
 *
 * @author kaz
 * @author Hiro
 */
function wrap(elem, cls_val, attr) {
    // Class definition
    if (cls_val != '') {
        cls = ' class="' + cls_val + '"';
    } else {
        cls = '';
    }
    // Attribute
    if (attr != '') {
        attr = ' ' + attr + '=""';
    } else {
        attr = '';
    }
    // Switch by UA 
    comment = document.getElementById('comment');
    if ((comment.selectionStart) && (!window.opera)) { // for Mozilla and Safari 1.3 or grater (by Hiro)
        var selLength = comment.textLength;
        var selStart  = comment.selectionStart;
        var selEnd    = comment.selectionEnd;
        if (selEnd == 1 || selEnd == 2) { selEnd = selLength; }
        var str1 = (comment.value).substring(0, selStart);
        var str2 = (comment.value).substring(selStart, selEnd);
        var str3 = (comment.value).substring(selEnd, selLength);
        comment.value = str1 + '<' + elem + cls + attr + '>' + str2 + '</' + elem + '>' + str3;
        comment.focus();
    } else if ((document.selection) && (!window.opera)) { // for WinIE
        var str = document.selection.createRange().text;
        comment.focus();
        var sel = document.selection.createRange();
        sel.text = "<" + elem + cls + attr + ">" + str + "</" + elem + ">";
        return;
    } else if (window.getSelection) { // for old Safari
        var str = window.getSelection();
        comment.value += '<' + elem + cls + attr + '>' + str + '</' + elem + '>';
        comment.focus();
    } else {
        comment.value += '<' + elem + cls + attr + '></' + elem + '>';
    }
}



function setFile(num) {
    var fid = document.getElementById('img' + num);
    var bid = document.getElementById('button' + num);
    var fv  = document.getElementById('myfile' + num).value;    
    var fp  = fv.replace(/\\/g, '/').split('/');
    var fn  = fp.length - 1;
    var f   = fp[fn];
    
    if (f.match(/.jpg/i) || f.match(/.png/i) || f.match(/.gif/i)) {
        fid.src = 'file:///' + fv;
    } else if (f.match(/.mp3/i)) {
        fid.src = '../styles/_shared/mp3.png';
        bid.value = 'Podcast';
    } else if ((f.match(/.m4/i)) || f.match(/.mp4/i)) {
        fid.src = '../styles/_shared/m4.png';
        bid.value = 'Podcast';
    } else if (f.match(/.mov/i)) {
        fid.src = '../styles/_shared/mov.png';
        bid.value = 'Podcast';
    } else if (f.match(/.wav/i)) {
        fid.src = '../styles/_shared/wav.png';
        bid.value = 'Podcast';
    } else {
        fid.src = '../styles/_shared/file_large.png';
        bid.value = 'File';
    }
}

function Attach(num) {

    // Get image size
    var targetFile = document.getElementById('img' + num);
    var fileWidth  = targetFile.width;
    var fileHeight = targetFile.height;

    if (fileWidth  == 0) { fileWidth  = ''; }
    if (fileHeight == 0) { fileHeight = ''; }
    
    var comment     = document.getElementById('comment');
    var fileValue   = document.getElementById('myfile' + num).value;    
    var filePointer = fileValue.replace(/\\/g, '/').split('/');
    var fileNumber  = filePointer.length - 1;
    var file        = filePointer[fileNumber];
    var attachCode  = '<img src="./resources/' 
                    + file + '" width="' + fileWidth + '" height="' + fileHeight 
                    + '" alt="" />';
    
    if (file.match(/.jpg/i) || file.match(/.png/i) || file.match(/.gif/i)) {
        attachCode  = '<img src="./resources/'
                    + file + '" width="' + fileWidth + '" height="' + fileHeight 
                    + '" alt="' + file + '" />';
    } else if (file.match(/.mp3/i) || 
               file.match(/.m4/i)  || 
               file.match(/.mp4/i) || 
               file.match(/.mov/i) || 
               file.match(/.wav/i)) {
        attachCode = '<!-- PODCAST=' + file + ' -->';
    } else {
        attachCode = '<a href="./resources/' + file + '">' + file + '</a>';
    }
    
    if (fileValue != '') { // If file value is not empty...
        // for Mozilla and Safari 1.3 or greater
        if ((comment.selectionStart) && (!window.opera)) {
            var selLength = comment.textLength;
            var selStart  = comment.selectionStart;
            var selEnd    = comment.selectionEnd;
            if (selEnd == 1 || selEnd == 2) { selEnd = selLength; }
            var str1 = (comment.value).substring(0, selStart);
            var str2 = (comment.value).substring(selStart, selEnd);
            var str3 = (comment.value).substring(selEnd, selLength);
            comment.value = str1 + attachCode + str3;
            comment.focus();
        } else if (document.selection) { // for WinIE
            var str = document.selection.createRange().text;
            document.getElementById('comment').focus();
            var sel = document.selection.createRange();
            sel.text = attachCode;
            return;
        } else if (window.getSelection) { // for Old Safari
            var str = window.getSelection();
            comment.value += attachCode;
            comment.focus();
        } else {
            comment.value += attachCode;
        }
    } else { // If file is not selected...
        var defaultImageTag = '<img src="./resources/" width="" height="" alt="" />';
        insertAtCursor(comment, defaultImageTag);
    }
}


/** 
 * Clickable cite attribute DOM
 *
 * @ author Simon Willson <http://simon.incutio.com/>
 */
function clickableCite() {
    q = document.getElementsByTagName('blockquote');
    for (i=0; i<q.length; i++) {
        cite = q[i].getAttribute('cite')
        if (cite) {
            newlink = document.createElement('a');
            newlink.setAttribute('href', cite);
            newlink.setAttribute('title', cite);
            newlink.appendChild(document.createTextNode('→ Source'));
            newdiv = document.createElement('div');
            newdiv.className = 'citesource';
            newdiv.appendChild(newlink);
            q[i].appendChild(newdiv);
        }
    }
}
window.onload = clickableCite;

/** 
 * Insert Smiley Icon Code
 *
 * @author kaz
 * @author Hiro
 */ 
function smiley(icon) {
    comment = document.getElementById("comment");
	icon = ' ' + icon + ' ';
	insertAtCursor(comment, icon); // Added by Hiro
	//comment.value += icon; // Commented out by Hiro
}


// minmax.js: make IE5+/Win support CSS min/max-width/height
// version 1.0, 08-Aug-2003
// written by Andrew Clover <and@doxdesk.com>, use freely
// http://www.doxdesk.com/software/js/minmax.html

/*@cc_on
@if (@_win32 && @_jscript_version>4)

var minmax_elements;

minmax_props= new Array(
  new Array('min-width', 'minWidth'),
  new Array('max-width', 'maxWidth'),
  new Array('min-height','minHeight'),
  new Array('max-height','maxHeight')
);

// Binding. Called on all new elements. If <body>, initialise; check all
// elements for minmax properties

function minmax_bind(el) {
  var i, em, ms;
  var st= el.style, cs= el.currentStyle;

  if (minmax_elements==window.undefined) {
    // initialise when body element has turned up, but only on IE
    if (!document.body || !document.body.currentStyle) return;
    minmax_elements= new Array();
    window.attachEvent('onresize', minmax_delayout);
    // make font size listener
    em= document.createElement('div');
    em.setAttribute('id', 'minmax_em');
    em.style.position= 'absolute'; em.style.visibility= 'hidden';
    em.style.fontSize= 'xx-large'; em.style.height= '5em';
    em.style.top='-5em'; em.style.left= '0';
    if (em.style.setExpression) {
      em.style.setExpression('width', 'minmax_checkFont()');
      document.body.insertBefore(em, document.body.firstChild);
    }
  }

  // transform hyphenated properties the browser has not caught to camelCase
  for (i= minmax_props.length; i-->0;)
    if (cs[minmax_props[i][0]])
      st[minmax_props[i][1]]= cs[minmax_props[i][0]];
  // add element with properties to list, store optimal size values
  for (i= minmax_props.length; i-->0;) {
    ms= cs[minmax_props[i][1]];
    if (ms && ms!='auto' && ms!='none' && ms!='0' && ms!='') {
      st.minmaxWidth= cs.width; st.minmaxHeight= cs.height;
      minmax_elements[minmax_elements.length]= el;
      // will need a layout later
      minmax_delayout();
      break;
  } }
}

// check for font size changes

var minmax_fontsize= 0;
function minmax_checkFont() {
  var fs= document.getElementById('minmax_em').offsetHeight;
  if (minmax_fontsize!=fs && minmax_fontsize!=0)
    minmax_delayout();
  minmax_fontsize= fs;
  return '5em';
}

// Layout. Called after window and font size-change. Go through elements we
// picked out earlier and set their size to the minimum, maximum and optimum,
// choosing whichever is appropriate

// Request re-layout at next available moment
var minmax_delaying= false;
function minmax_delayout() {
  if (minmax_delaying) return;
  minmax_delaying= true;
  window.setTimeout(minmax_layout, 0);
}

function minmax_stopdelaying() {
  minmax_delaying= false;
}

function minmax_layout() {
  window.setTimeout(minmax_stopdelaying, 100);
  var i, el, st, cs, optimal, inrange;
  for (i= minmax_elements.length; i-->0;) {
    el= minmax_elements[i]; st= el.style; cs= el.currentStyle;

    // horizontal size bounding
    st.width= st.minmaxWidth; optimal= el.offsetWidth;
    inrange= true;
    if (inrange && cs.minWidth && cs.minWidth!='0' && cs.minWidth!='auto' && cs.minWidth!='') {
      st.width= cs.minWidth;
      inrange= (el.offsetWidth<optimal);
    }
    if (inrange && cs.maxWidth && cs.maxWidth!='none' && cs.maxWidth!='auto' && cs.maxWidth!='') {
      st.width= cs.maxWidth;
      inrange= (el.offsetWidth>optimal);
    }
    if (inrange) st.width= st.minmaxWidth;

    // vertical size bounding
    st.height= st.minmaxHeight; optimal= el.offsetHeight;
    inrange= true;
    if (inrange && cs.minHeight && cs.minHeight!='0' && cs.minHeight!='auto' && cs.minHeight!='') {
      st.height= cs.minHeight;
      inrange= (el.offsetHeight<optimal);
    }
    if (inrange && cs.maxHeight && cs.maxHeight!='none' && cs.maxHeight!='auto' && cs.maxHeight!='') {
      st.height= cs.maxHeight;
      inrange= (el.offsetHeight>optimal);
    }
    if (inrange) st.height= st.minmaxHeight;
  }
}

// Scanning. Check document every so often until it has finished loading. Do
// nothing until <body> arrives, then call main init. Pass any new elements
// found on each scan to be bound   

var minmax_SCANDELAY= 500;

function minmax_scan() {
  var el;
  for (var i= 0; i<document.all.length; i++) {
    el= document.all[i];
    if (!el.minmax_bound) {
      el.minmax_bound= true;
      minmax_bind(el);
  } }
}

var minmax_scanner;
function minmax_stop() {
  window.clearInterval(minmax_scanner);
  minmax_scan();
}

minmax_scan();
minmax_scanner= window.setInterval(minmax_scan, minmax_SCANDELAY);
window.attachEvent('onload', minmax_stop);

@end @*/