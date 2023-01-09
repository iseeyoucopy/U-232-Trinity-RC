<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
require_once(__DIR__.'/emoticons.php');
function source_highlighter($source, $lang2geshi)
{
    require_once(__DIR__.'/geshi/geshi.php');
    $source = str_replace([
        "&#039;",
        "&gt;",
        "&lt;",
        "&quot;",
        "&amp;",
        "<br />",
    ], [
        "'",
        ">",
        "<",
        "\"",
        "&",
        "",
    ], $source);
    $lang2geshi = ($lang2geshi == 'html' ? 'html4strict' : $lang2geshi);
    $geshi = new GeSHi($source, $lang2geshi);
    $geshi->set_header_type(GESHI_HEADER_PRE_VALID);
    $geshi->set_overall_style('font: normal normal 100% monospace; color: #000066;', false);
    $geshi->set_line_style('color: #003030;', 'font-weight: bold; color: #006060;', true);
    $geshi->set_code_style('color: #000020;font-family:monospace; font-size:12px;line-height:13px;', true);
    $geshi->enable_classes(false);
    $geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
    $geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');
    $return = "<div class=\"codeblock phpcodeblock\"><div class=\"title\">PHP Code:<br /></div><div class=\"body\"><div dir=\"ltr\"><code>\n";
    $return .= $geshi->parse_code();
    $return .= "\n</code></div></div></div>\n";
    return $return;
}

function _MediaTag($content, $type)
{
    global $TRINITY20;
    if ($content == '' || $type == '') {
        return;
    }
    $return = '';
    switch ($type) {
        case 'youtube':
            $return = preg_replace("#^https?://(?:|www\.)youtube\.com/watch\?v=([^\s\'\"<>]+)+?$#i",
                '<iframe width="500" height="410" src="https://www.youtube.com/embed/\\1" frameborder="0" allowfullscreen></iframe>', $content);
            break;
        case 'liveleak':
            $return = preg_replace("#^http://(?:|www\.)liveleak\.com/view\?i=([_a-zA-Z0-9\-]+)+?$#i",
                "<object type='application/x-shockwave-flash' height='355' width='425' data='http://www.liveleak.com/e/\\1'><param name='movie' value='http://www.liveleak.com/e/\\1' /><param name='allowScriptAccess' value='sameDomain' /><param name='quality' value='best' /><param name='bgcolor' value='#FFFFFF' /><param name='scale' value='noScale' /><param name='salign' value='TL' /><param name='FlashVars' value='playerMode=embedded' /><param name='wmode' value='transparent' /></object>",
                $content);
            break;
        case 'GameTrailers':
            $return = preg_replace("#^http://(?:|www\\.)gametrailers\\.com/video/([\\-_a-zA-Z0-9\\-]+)+?/(\\d+)+?\$#i",
                "<object type='application/x-shockwave-flash' height='355' width='425' data='http://www.gametrailers.com/remote_wrap.php?mid=\\2'><param name='movie' value='http://www.gametrailers.com/remote_wrap.php?mid=\\2' /><param name='allowScriptAccess' value='sameDomain' /> <param name='allowFullScreen' value='true' /><param name='quality' value='high' /></object>",
                $content);
            break;
        case 'imdb':
            $return = preg_replace("#^http://(?:|www\.)imdb\.com/video/screenplay/([_a-zA-Z0-9\-]+)+?$#i",
                "<div class='\\1'><div style=\"padding: 3px; background-color: transparent; border: none; width:690px;\"><div style=\"text-transform: uppercase; border-bottom: 1px solid #CCCCCC; margin-bottom: 3px; font-size: 0.8em; font-weight: bold; display: block;\"><span onclick=\"if (this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display != '') { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = ''; this.innerHTML = '<b>Imdb Trailer: </b><a href=\'#\' onclick=\'return false;\'>hide</a>'; } else { this.parentNode.parentNode.getElementsByTagName('div')[1].getElementsByTagName('div')[0].style.display = 'none'; this.innerHTML = '<b>Imdb Trailer: </b><a href=\'#\' onclick=\'return false;\'>show</a>'; }\" ><b>Imdb Trailer: </b><a href=\"#\" onclick=\"return false;\">show</a></span></div><div class=\"quotecontent\"><div style=\"display: none;\"><iframe style='vertical-align: middle;' src='http://www.imdb.com/video/screenplay/\\1/player' scrolling='no' width='660' height='490' frameborder='0'></iframe></div></div></div></div>",
                $content);
            break;
        case 'vimeo':
            $return = preg_replace("#^http://(?:|www\\.)vimeo\\.com/(\\d+)+?\$#i", "<object type='application/x-shockwave-flash' width='425' height='355' data='http://vimeo.com/moogaloop.swf?clip_id=\\1&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1'>
    <param name='allowFullScreen' value='true' />
    <param name='allowScriptAccess' value='sameDomain' />
    <param name='movie' value='http://vimeo.com/moogaloop.swf?clip_id=\\1&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1' />
    <param name='quality' value='high' />
    </object>", $content);
            break;
        default:
            $return = 'not found';
    }
    return $return;
}

//Finds last occurrence of needle in haystack
//in PHP5 use strripos() instead of this
function _strlastpos($haystack, $needle, $offset = 0)
{
    $addLen = strlen($needle);
    $endPos = $offset - $addLen;
    while (true) {
        if (($newPos = strpos($haystack, (string)$needle, $endPos + $addLen)) === false) {
            break;
        }
        $endPos = $newPos;
    }
    return ($endPos >= 0) ? $endPos : false;
}

function validate_imgs($s)
{
    $start = "(http|https)://";
    $end = "+\.(?:jpe?g|png|gif)";
    preg_match_all("!".$start."(.*)".$end."!Ui", $s, $result);
    $array = $result[0];
    foreach ($array as $i => $array) {
        $headers = @get_headers($array);
        $headers0 = $headers[0] ?? '';
        if (strpos($headers0, "200") === false) {
            $s = str_replace(["[img]".$array."[/img]", "[img=".$array."]"], "", $s);
        }
    }
    return $s;
}

//=== new test for BBcode errors from http://codesnippets.joyent.com/posts/show/959 by berto
function check_BBcode($html)
{
    preg_match_all('#<(?!img|br|hr\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = is_countable($openedtags) ? count($openedtags) : 0;
    if ((is_countable($closedtags) ? count($closedtags) : 0) === $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i = 0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</'.$openedtags[$i].'>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
}

//==format quotes by Retro
function format_quotes($s)
{
    preg_match_all('/\\[quote.*?\\]/', $s, $result, PREG_PATTERN_ORDER);
    $openquotecount = is_countable($openquote = $result[0]) ? count($openquote = $result[0]) : 0;
    preg_match_all('/\\[\/quote\\]/', $s, $result, PREG_PATTERN_ORDER);
    $closequotecount = is_countable($closequote = $result[0]) ? count($closequote = $result[0]) : 0;
    if ($openquotecount !== $closequotecount) {
        return $s;
    } // quote mismatch. Return raw string...
    // Get position of opening quotes
    $openval = [];
    $pos = -1;
    foreach ($openquote as $val) {
        $openval[] = $pos = strpos($s, (string)$val, $pos + 1);
    }
    // Get position of closing quotes
    $closeval = [];
    $pos = -1;
    foreach ($closequote as $val) {
        $closeval[] = $pos = strpos($s, (string)$val, $pos + 1);
    }
    foreach ($openval as $i => $openval) {
        if ($openval > $closeval[$i]) {
            return $s;
        }
    } // Cannot close before opening. Return raw string...
    $s = str_replace("[quote]", "<blockquote><cite>Quote:</cite>", $s);
    $s = preg_replace("/\\[quote=(.+?)\\]/", "<blockquote><cite>\\1 wrote:</cite>", $s);
    return str_replace("[/quote]", "<br /></blockquote>", $s);
}

function islocal($link)
{
    global $TRINITY20;
    $flag = false;
    $limit = 60;
    $TRINITY20['url'] = str_replace([
        'http://',
        'www',
        'http://www',
        'https://',
        'https://www',
    ], '', $TRINITY20['baseurl']);
    if (stripos($link[0], '[url=') !== false) {
        $url = trim($link[1]);
        $title = trim($link[2]);
        if (stripos($link[2], '[img]') !== false) {
            $flag = true;
            $title = preg_replace("/\[img]((http|https):\/\/[^\s'\"<>]+(\.(jpg|gif|png)))\[\/img\]/i", "<img src=\"\\1\" alt=\"\" border=\"0\" />",
                $title);
        }
    } elseif (stripos($link[0], '[url]') !== false) {
        $url = $title = trim($link[1]);
    } else {
        $url = $title = trim($link[2]);
    }
    if (strlen($title) > $limit && $flag == false) {
        $l[0] = substr($title, 0, ($limit / 2));
        $l[1] = substr($title, strlen($title) - round($limit / 3));
        $lshort = $l[0]."...".$l[1];
    } else {
        $lshort = $title;
    }
    return "&nbsp;<a href=\"".((stripos($url,
                (string)$TRINITY20['url']) !== false) ? "" : "https://anonym.to/?").$url."\" target=\"_blank\">".$lshort."</a>";
}

function format_urls($s)
{
    return preg_replace_callback("/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^<>\s]+)/i", "islocal", $s);
}

function format_comment($text, $strip_html = true, $urls = true, $images = true)
{
    global $smilies, $staff_smilies, $customsmilies, $TRINITY20, $CURUSER;
    $s = $text;
    unset($text);
    $s = validate_imgs($s);
    $TRINITY20['url'] = str_replace(['http://', 'www', 'http://www', 'https://', 'https://www'], '', $TRINITY20['baseurl']);
    if (isset($_SERVER['HTTPS']) && (bool)$_SERVER['HTTPS'] == true) {
        $s = preg_replace('/http:\/\/((?:www\.)?'.$TRINITY20['url'].')/i', 'https://$1', $s);
    } else {
        $s = preg_replace('/https:\/\/((?:www\.)?'.$TRINITY20['url'].')/i', 'http://$1', $s);
    }
    // This fixes the extraneous ;) smilies problem. When there was an html escaped
    // char before a closing bracket - like >), "), ... - this would be encoded
    // to &xxx;), hence all the extra smilies. I created a new :wink: label, removed
    // the ;) one, and replace all genuine ;) by :wink: before escaping the body.
    // (What took us so long? :blush:)- wyz
    // fix messed up links
    $s = str_replace([';)', '&amp;'], [':wink:', '&'], $s);
    if ($strip_html) {
        $s = htmlspecialchars($s, ENT_QUOTES, charset());
    }
    if (preg_match("#function\s*\((.*?)\|\|#is", $s)) {
        $s = str_replace([":", "["], ["&#58;", "&#91;"], $s);
        $s = str_replace(["]", ")"], ["&#93;", "&#41;"], $s);
        $s = str_replace(["(", "{"], ["&#40;", "&#123;"], $s);
        $s = str_replace(["}", "$"], ["&#125;", "&#36;"], $s);
    }
    // BBCode to find...
    $bb_code_in = [
        '/\[b\]\s*((\s|.)+?)\s*\[\/b\]/i',
        '/\[i\]\s*((\s|.)+?)\s*\[\/i\]/i',
        '/\[u\]\s*((\s|.)+?)\s*\[\/u\]/i',
        '/\[email\](.*?)\[\/email\]/i',
        '/\[align=([a-zA-Z]+)\]((\s|.)+?)\[\/align\]/i',
        '/\[blockquote\]\s*((\s|.)+?)\s*\[\/blockquote\]/i',
        '/\[strike\]\s*((\s|.)+?)\s*\[\/strike\]/i',
        '/\[s\]\s*((\s|.)+?)\s*\[\/s\]/i',
        '/\[marquee\](.*?)\[\/marquee\]/i',
        '/\[collapse=(.*?)\]\s*((\s|.)+?)\s*\[\/collapse\]/i',
        '/\[size=([1-7])\]\s*((\s|.)+?)\s*\[\/size\]/i',
        '/\[color=([a-zA-Z]+)\]\s*((\s|.)+?)\s*\[\/color\]/i',
        '/\[color=(#[a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9][a-f0-9])\]\s*((\s|.)+?)\s*\[\/color\]/i',
        '/\[font=([a-zA-Z ,]+)\]((\s|.)+?)\[\/font\]/i',
        '/\[spoiler\]\s*((\s|.)+?)\s*\[\/spoiler\]/i',
        '/\[video=[^\s\'"<>]*youtube.com.*v=([^\s\'"<>]+)\]/ims',
        "/\[video=[^\s'\"<>]*video.google.com.*docid=(-?[0-9]+).*\]/ims",
        '/\[audio\](http:\/\/[^\s\'"<>]+(\.(mp3|aiff|wav)))\[\/audio\]/i',
        '/\[list=([0-9]+)\]((\s|.)+?)\[\/list\]/i',
        '/\[list\]((\s|.)+?)\[\/list\]/i',
        '/\[\*\]\s?(.*?)\n/i',
        '/\[li\]\s?(.*?)\n/i',
        '/\[hr\]/',
    ];
    // And replace them by...
    $bb_code_out = [
        '<span style="font-weight: bold;">\1</span>',
        '<span style="font-style: italic;">\1</span>',
        '<span style="text-decoration: underline;">\1</span>',
        '<a class="altlink" href="mailto:\1">\1</a>',
        '<div style="text-align: \1;">\2</div>',
        '<blockquote class="style"><span>\1</span></blockquote>',
        '<span style="text-decoration: line-through;">\1</span>',
        '<span style="text-decoration: line-through;">\1</span>',
        '<marquee class="style">\1</marquee>',
        '<div style="padding-top: 2px; white-space: nowrap"><span style="cursor: hand; cursor: pointer; border-bottom: 1px dotted" onclick="if (document.getElementById(\'collapseobj\1\').style.display==\'block\') {document.getElementById(\'collapseobj\1\').style.display=\'none\' } else { document.getElementById(\'collapseobj\1\').style.display=\'block\' }">\1</span></div><div id="collapseobj\1" style="display:none; padding-top: 2px; padding-left: 14px; margin-bottom:10px; padding-bottom: 2px; background-color: #FEFEF4;">\2</div>',
        '<span class="size\1">\2</span>',
        '<span style="color:\1;">\2</span>',
        '<span style="color:\1;">\2</span>',
        '<span style="font-family:\'\1\';">\2</span>',
        '<table cellspacing="0" cellpadding="10"><tr><td class="forum_head_dark" style="padding:5px">Spoiler! to view, roll over the spoiler box.</td></tr><tr><td class="spoiler"><a href="#">\\1</a></td></tr></table><br />',
        '<iframe width="500" height="410" src="https://www.youtube.com/embed/\\1" frameborder="0" allowfullscreen></iframe>',
        "<embed style=\"width:500px; height:410px;\" id=\"VideoPlayback\" align=\"middle\" type=\"application/x-shockwave-flash\" src=\"http://video.google.com/googleplayer.swf?docId=\\1\" allowScriptAccess=\"sameDomain\" quality=\"best\" bgcolor=\"#ffffff\" scale=\"noScale\" wmode=\"window\" salign=\"TL\"  FlashVars=\"playerMode=embedded\"> </embed>",
        '<span style="text-align: center;"><p>Audio From: \1</p><embed type="application/x-shockwave-flash" src="http://www.google.com/reader/ui/3247397568-audio-player.swf?audioUrl=\\1" width="400" height="27" allowscriptaccess="never" quality="best" bgcolor="#ffffff" wmode="window" flashvars="playerMode=embedded" /></span>',
        '<ol class="style" start="\1">\2</ol>',
        '<ul class="style">\1</ul>',
        '<li>\1</li>',
        '<li>\1</li>',
        '<hr />',
    ];
    $s = preg_replace($bb_code_in, $bb_code_out, $s);
    if ($urls) {
        $s = format_urls($s);
    }
    if (stripos($s, '[url') !== false && $urls) {
        $s = preg_replace_callback("/\[url=([^()<>\s]+?)\](.+?)\[\/url\]/is", "islocal", $s);
        // [url]http://www.example.com[/url]
        $s = preg_replace_callback("/\[url\]([^()<>\s]+?)\[\/url\]/is", "islocal", $s);
    }
    // Linebreaks
    $s = nl2br($s);
    // Dynamic Vars
    $s = dynamic_user_vars($s);
    // [pre]Preformatted[/pre]
    if (stripos($s, '[pre]') !== false) {
        $s = preg_replace("/\[pre\]((\s|.)+?)\[\/pre\]/i", "<tt><span style=\"white-space: nowrap;\">\\1</span></tt>", $s);
    }
    // [nfo]NFO-preformatted[/nfo]
    if (stripos($s, '[nfo]') !== false) {
        $s = preg_replace("/\[nfo\]((\s|.)+?)\[\/nfo\]/i",
            "<tt><span style=\"white-space: nowrap;\"><font face='MS Linedraw' size='2' style='font-size: 10pt; line-height:"."10pt'>\\1</font></span></tt>",
            $s);
    }
    //==Media tag
    if (stripos($s, '[media=') !== false) {
        $s = preg_replace_callback("#\[media=(youtube|liveleak|GameTrailers|vimeo|imdb)\](.+?)\[/media\]#is",
            fn($media_tag) => _MediaTag($media_tag[2], $media_tag[1]),
            $s);
    }

    if (stripos($s, '[img') !== false && $images) {
        // [img=http://www/image.gif]
        $s = preg_replace("/\[img\]((http|https):\/\/[^\s'\"<>]+(\.(jpg|gif|png|bmp)))\[\/img\]/i",
            "<a href=\"\\1\" rel=\"lightbox\"><img src=\"\\1\" border=\"0\" alt=\"\" style=\"max-width: 150px;\" /></a>", $s);
        // [img=http://www/image.gif]
        $s = preg_replace("/\[img=((http|https):\/\/[^\s'\"<>]+(\.(gif|jpg|png|bmp)))\]/i",
            "<a href=\"\\1\" rel=\"lightbox\"><img src=\"\\1\" border=\"0\" alt=\"\" style=\"max-width: 150px;\" /></a>", $s);
    }
    // [mcom]Text[/mcom]
    if (stripos($s, '[mcom]') !== false) {
        $s = preg_replace("/\[mcom\](.+?)\[\/mcom\]/is", "<div style=\"font-size: 18pt; line-height: 50%;\">
   <div style=\"border-color: red; background-color: red; color: white; text-align: center; font-weight: bold; font-size: large;\"><b>\\1</b></div></div>",
            $s);
    }
    // the [you] tag
    if (stripos($s, '[you]') !== false) {
        $s = preg_replace("/https?:\/\/[^\s'\"<>]*\[you\][^\s'\"<>]*/i", " ", $s);
        $s = preg_replace("/\[you\]/i", $CURUSER['username'], $s);
    }
    // [php]code[/php]
    if (stripos($s, '[php]') !== false) {
        $s = preg_replace_callback("#\[(php|sql|html)\](.+?)\[\/\\1\]#is",
            fn($source_highlight) => source_highlighter($source_highlight[2], $source_highlight[1]),
            $s);
    }
    // Maintain spacing
    $s = str_replace('  ', ' &nbsp;', $s);
    if (isset($smilies)) {
        foreach ($smilies as $code => $url) {
            $s = str_replace($code, "<img border='0' src=\"{$TRINITY20['pic_base_url']}smilies/{$url}\" alt=\"\" />", $s);
            //$s = str_replace($code, '<span id="'.$attr.'"></span>', $s);
        }
    }
    if (isset($staff_smilies)) {
        foreach ($staff_smilies as $code => $url) {
            $s = str_replace($code, "<img border='0' src=\"{$TRINITY20['pic_base_url']}smilies/{$url}\" alt=\"\" />", $s);
            //$s = str_replace($code, '<span id="'.$attr.'"></span>', $s);
        }
    }
    if (isset($customsmilies)) {
        foreach ($customsmilies as $code => $url) {
            $s = str_replace($code, "<img border='0' src=\"{$TRINITY20['pic_base_url']}smilies/{$url}\" alt=\"\" />", $s);
            //$s = str_replace($code, '<span id="'.$attr.'"></span>', $s);
        }
    }
    $s = format_quotes($s);
    return check_BBcode($s);
}

//=== smilie function
function get_smile()
{
    global $CURUSER;
    return $CURUSER["smile_until"];
}

////////////09 bbcode function by putyn///////////////
function textbbcode($form, $text, $content = "")
{
    global $CURUSER, $TRINITY20;
    $custombutton = '';
    if (get_smile() != '0') {
        $custombutton .= " <span style='font-weight:bold;font-size:8pt;'><a href=\"javascript:PopCustomSmiles('".$form."','".$text."')\">[ Custom Smilies ]</a></span>";
    }
    $smilebutton = "<a href=\"javascript:PopMoreSmiles('".$form."','".$text."')\">[ More Smilies ]</a>";
    $bbcodebody = <<<HTML
<script type="text/javascript">
	var textBBcode = "{$text}";
</script>
<script type="text/javascript" src="./scripts/textbbcode.js"></script>
<div id="hover_pick"></div>
<div id="pickerholder"></div>
<div class="grid-x grid-margin-x">
  <div class="cell">
    <div class="off-canvas-wrapper">
      <div class="off-canvas-absolute position-top" id="bbcode-canvas" data-off-canvas data-transition="push"> 
		<span id="clickableAwesomeFont"><i class="fas fa-bold" onclick="tag('b')" title="Bold" alt="B" ></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-italic" onclick="tag('i')" title="Italic" alt="I"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-underline" onclick="tag('u')" title="Underline" alt="U"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-strikethrough" onclick="tag('s')" title="Strike" alt="S"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-link" onclick="clink()" title="Link" alt="Link"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-image" onclick="cimage()" title="Image" alt="Image"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-palette" onclick="colorpicker();" title="Select Color" alt="Colors"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-envelope" onclick="mail()" title="Add email" alt="Email"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-code" onclick="tag('php')" title="Add code" alt="Code"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fas fa-quote-right" onclick="tag('quote')" title="Quote" alt="Quote"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fa fa-align-center" onclick="wrap('align','','center')" title="Align - center" alt="Center"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fa fa-align-left" onclick="wrap('align','','left')" title="Align - left" alt="Left"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fa fa-align-justify" onclick="wrap('align','','justify')" title="Align - justify" alt="justify"></i></span>&nbsp;&nbsp;
		<span id="clickableAwesomeFont"><i class="fa fa-align-right" onclick="wrap('align','','right')" title="Align - right" alt="Right"></i></span>&nbsp;&nbsp;
HTML;
    if ($CURUSER['class'] >= UC_MODERATOR) {
        $bbcodebody .= <<<HTML
		<span id="clickableAwesomeFont"><i class="fas fa-comment-alt" onclick="tag('mcom')" title="Mod comment" alt="Mod comment"></i></span>
HTML;
    }
    return $bbcodebody.<<<HTML
	<select class="input-group-field" name="fontfont" id="fontfont" onchange="font('font',this.value);" title="Font face">
          <option value="0">Font</option>
          <option value="Arial" style="font-family: Arial;">Arial</option>
          <option value="Arial Black" style="font-family: Arial Black;">Arial Black</option>
          <option value="Comic Sans MS" style="font-family: Comic Sans MS;">Comic Sans MS</option>
          <option value="Courier New" style="font-family: Courier New;">Courier New</option>
          <option value="Franklin Gothic Medium" style="font-family: Franklin Gothic Medium;">Franklin Gothic Medium</option>
          <option value="Georgia" style="font-family: Georgia;">Georgia</option>
          <option value="Helvetica" style="font-family: Helvetica;">Helvetica</option>
          <option value="Impact" style="font-family: Impact;">Impact</option>
          <option value="Lucida Console" style="font-family: Lucida Console;">Lucida Console</option>
          <option value="Lucida Sans Unicode" style="font-family: Lucida Sans Unicode;">Lucida Sans Unicode</option>
          <option value="Microsoft Sans Serif" style="font-family: Microsoft Sans Serif;">Microsoft Sans Serif</option>
          <option value="Palatino Linotype" style="font-family: Palatino Linotype;">Palatino Linotype</option>
          <option value="Tahoma" style="font-family: Tahoma;">Tahoma</option>
          <option value="Times New Roman" style="font-family: Times New Roman;">Times New Roman</option>
          <option value="Trebuchet MS" style="font-family: Trebuchet MS;">Trebuchet MS</option>
          <option value="Verdana" style="font-family: Verdana;">Verdana</option>
          <option value="Symbol" style="font-family: Symbol;">Symbol</option>
        </select>
        <select class="input-group-field" name="fontsize" id="fontsize" onchange="font('size',this.value);" title="Font size">
          <option value="0">Font Size</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
		<span style='font-weight:bold;font-size:8pt;'>{$smilebutton}</span>{$custombutton}
		  <a href="javascript:em(':-)');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/smile1.gif" width="18" height="18"></a>
		  <a href="javascript:em(':smile:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/smile2.gif" width="18" height="18"></a>
		  <a href="javascript:em(':-D');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/grin.gif" width="18" height="18"></a>
		  <a href="javascript:em(':w00t:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/w00t.gif" width="18" height="20"></a>
		  <a href="javascript:em(':-P');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/tongue.gif" width="20" height="20"></a>
		  <a href="javascript:em(';-)');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/wink.gif" width="20" height="20"></a>
		  <a href="javascript:em(':-|');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/noexpression.gif" width="18" height="18"></a>
		  <a href="javascript:em(':-/');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/confused.gif" width="18" height="18"></a>
		  <a href="javascript:em(':-(');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/sad.gif" width="18" height="18"></a>
		  <a href="javascript:em(':baby:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/baby.gif" width="20" height="22"></a>
		  <a href="javascript:em(':-O');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/ohmy.gif" width="18" height="18"></a>
		  <a href="javascript:em('|-)');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/sleeping.gif" width="20" height="27"></a>
		  <a href="javascript:em(':innocent:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/innocent.gif" width="18" height="22"></a>
		  <a href="javascript:em(':unsure:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/unsure.gif" width="20" height="20"></a>
		  <a href="javascript:em(':closedeyes:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/closedeyes.gif" width="20" height="20">
		  </a><a href="javascript:em(':cool:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/cool2.gif" width="20" height="20"></a>
		  <a href="javascript:em(':thumbsdown:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/thumbsdown.gif" width="27" height="18"></a>
		  <a href="javascript:em(':blush:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/blush.gif" width="20" height="20"></a>
		  <a href="javascript:em(':yes:');"><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/yes.gif" width="20" height="20" /></a><a href="javascript:em(':no:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/no.gif" width="20" height="20"></a>
          <a href="javascript:em(':love:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/love.gif" width="19" height="19"></a>
		  <a href="javascript:em(':?:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/question.gif" width="19" height="19"></a>
		  <a href="javascript:em(':!:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/excl.gif" width="20" height="20"></a>
		  <a href="javascript:em(':idea:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/idea.gif" width="19" height="19"></a>
		  <a href="javascript:em(':arrow:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/arrow.gif" width="20" height="20"></a>
		  <a href="javascript:em(':arrow2:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/arrow2.gif" width="20" height="20"></a>
		  <a href="javascript:em(':hmm:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/hmm.gif" width="20" height="20"></a>
		  <a href="javascript:em(':hmmm:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/hmmm.gif" width="25" height="23"></a>
		  <a href="javascript:em(':huh:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/huh.gif" width="20" height="20"></a>
		  <a href="javascript:em(':rolleyes:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/rolleyes.gif" width="20" height="20"></a>
		  <a href="javascript:em(':kiss:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/kiss.gif" width="18" height="18"></a>
		  <a href="javascript:em(':shifty:');" ><img border="0" alt="Smilies" src="{$TRINITY20['pic_base_url']}smilies/shifty.gif" width="20" height="20"></a>
      </div>
      <div class="off-canvas-content" data-off-canvas-content>
	  <a style="position: relative; top: 40px; left: -10px;" class="float-right" data-toggle="bbcode-canvas"><i class="fas fa-cogs"></i></a>
		<textarea style="height:300px;" placeholder="Body" id="{$text}" name="{$text}">{$content}</textarea>
      </div>
    </div>
  </div>
</div>
HTML;
}

function user_key_codes($key)
{
    return "/\[$key\]/i";
}

function dynamic_user_vars($text)
{
    global $CURUSER, $TRINITY20;
    if (!isset($CURUSER)) {
        return;
    }
    $zone = 0; // GMT
    //$zone = 3600 * -5; // EST
    $tim = TIME_NOW + $zone;
    $cu = $CURUSER;
    // unset any variables ya dun want to display, or can't display
    unset($cu['passhash'], $cu['secret'], $cu['editsecret'], $cu['torrent_pass'], $cu['modcomment']);
    $bbkeys = array_keys($cu);
    $bbkeys[] = 'curdate';
    $bbkeys[] = 'curtime';
    $bbvals = array_values($cu);
    $bbvals[] = gmdate('F jS, Y', $tim);
    $bbvals[] = gmdate('g:i A', $tim);
    $bbkeys = array_map('user_key_codes', $bbkeys);
    return @preg_replace($bbkeys, $bbvals, $text);
}

?>
