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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once(CLASS_DIR.'page_verify.php');
require_once(CLASS_DIR.'class.bencdec.php');
require_once INCL_DIR.'function_memcache.php';
dbconn();
loggedinorreturn();
ini_set('upload_max_filesize', $TRINITY20['max_torrent_size']);
ini_set('memory_limit', '64M');
//smth putyn
//print_r($_POST);
//print_r($_GET);
//exit();
$auth_key = [
    '2d257f64005d740db092a6b91170ab5f',
];
$gotkey = isset($_POST['key']) && strlen($_POST['key']) == 32 && in_array($_POST['key'], $auth_key);
$lang = array_merge(load_language('global'), load_language('takeupload'));
if (!$gotkey) {
    $newpage = new page_verify();
    $newpage->check('taud');
}
if ($CURUSER['class'] < UC_UPLOADER || ($CURUSER["uploadpos"] == 0 || $CURUSER["uploadpos"] > 1 || $CURUSER['suspended'] == 'yes')) {
    header("Location: {$TRINITY20['baseurl']}/upload.php");
    exit();
}
if (!isset($_POST['descr'])) {
    stderr($lang['takeupload_failed'], 'No descrition added');
}
if (!isset($_POST['type'])) {
    stderr($lang['takeupload_failed'], 'No category selected');
}
if (!isset($_POST['name'])) {
    stderr($lang['takeupload_failed'], 'No name added');
}
if (!isset($_FILES["file"])) {
    stderr($lang['takeupload_failed'], $lang['takeupload_no_formdata']);
}

$f = $_FILES["file"];
$fname = unesc($f["name"]);
if (empty($fname)) {
    stderr($lang['takeupload_failed'], $lang['takeupload_no_filename']);
}
if (isset($_POST['uplver']) && $_POST['uplver'] == 'yes') {
    $anonymous = "yes";
    $anon = "Anonymous";
} else {
    $anonymous = "no";
    $anon = $CURUSER["username"];
}
if (isset($_POST['allow_comments']) && $_POST['allow_comments'] == 'yes') {
    $allow_comments = "no";
    $disallow = "Yes";
} else {
    $allow_comments = "yes";
    $disallow = "No";
}
if (isset($_POST["music"])) {
    $genre = implode(",", $_POST['music']);
} elseif (isset($_POST["movie"])) {
    $genre = implode(",", $_POST['movie']);
} elseif (isset($_POST["game"])) {
    $genre = implode(",", $_POST['game']);
} elseif (isset($_POST["apps"])) {
    $genre = implode(",", $_POST['apps']);
} else {
    $genre = '';
}
$nfo = sqlesc('');
/////////////////////// NFO FILE ////////////////////////
if (isset($_FILES['nfo']) && !empty($_FILES['nfo']['name'])) {
    $nfofile = $_FILES['nfo'];
    if ($nfofile['name'] == '') {
        stderr($lang['takeupload_failed'], $lang['takeupload_no_nfo']);
    }
    if ($nfofile['size'] == 0) {
        stderr($lang['takeupload_failed'], $lang['takeupload_0_byte']);
    }
    if ($nfofile['size'] > 65535) {
        stderr($lang['takeupload_failed'], $lang['takeupload_nfo_big']);
    }
    $nfofilename = $nfofile['tmp_name'];
    if (@!is_uploaded_file($nfofilename)) {
        stderr($lang['takeupload_failed'], $lang['takeupload_nfo_failed']);
    }
    $nfo = sqlesc(str_replace("\x0d\x0d\x0a", "\x0d\x0a", @file_get_contents($nfofilename)));
}
/////////////////////// NFO FILE END /////////////////////
/// Set Freeleech on Torrent Time Based
$free2 = 0;
if (isset($_POST['free_length']) && ($free_length = 0 + $_POST['free_length'])) {
    if ($free_length == 255) {
        $free2 = 1;
    } elseif ($free_length == 42) {
        $free2 = (86400 + TIME_NOW);
    } else {
        $free2 = (TIME_NOW + $free_length * 604800);
    }
}
/// end
/// Set Silver Torrent Time Based
$silver = 0;
if (isset($_POST['half_length']) && ($half_length = 0 + $_POST['half_length'])) {
    if ($half_length == 255) {
        $silver = 1;
    } elseif ($half_length == 42) {
        $silver = (86400 + TIME_NOW);
    } else {
        $silver = (TIME_NOW + $half_length * 604800);
    }
}
/// end
//==Xbt freetorrent
$freetorrent = (((isset($_POST['freetorrent']) && is_valid_id($_POST['freetorrent'])) ? (int)$_POST['freetorrent'] : 0));
$descr = strip_tags(isset($_POST['descr']) ? trim($_POST['descr']) : '');
if ($descr === '') {
    stderr($lang['takeupload_failed'], $lang['takeupload_no_descr']);
}
$description = strip_tags(isset($_POST['description']) ? trim($_POST['description']) : '');
if (isset($_POST['strip']) && $_POST['strip']) {
    require_once(INCL_DIR.'strip.php');
    $descr = preg_replace("/[^\\x20-\\x7e\\x0a\\x0d]/", " ", $descr);
    strip($descr);
    //$descr = preg_replace("/\n+/","\n",$descr);

}
$catid = (0 + $_POST["type"]);
if (!is_valid_id($catid)) {
    stderr($lang['takeupload_failed'], $lang['takeupload_no_cat']);
}
$request = (((isset($_POST['request']) && is_valid_id($_POST['request'])) ? (int)$_POST['request'] : 0));
$offer = (((isset($_POST['offer']) && is_valid_id($_POST['offer'])) ? (int)$_POST['offer'] : 0));
$subs = isset($_POST["subs"]) ? implode(",", $_POST['subs']) : "";
$release_group_array = [
    'scene' => 1,
    'p2p' => 1,
    'none' => 1,
];
$release_group = isset($_POST['release_group']) && isset($release_group_array[$_POST['release_group']]) ? $_POST['release_group'] : 'none';
$youtube = '';
if (isset($_POST['youtube']) && preg_match($youtube_pattern, $_POST['youtube'], $temp_youtube)) {
    $youtube = $temp_youtube[0];
}
$tags = strip_tags(isset($_POST['tags']) ? trim($_POST['tags']) : '');
if (!validfilename($fname)) {
    stderr($lang['takeupload_failed'], $lang['takeupload_invalid']);
}
if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches)) {
    stderr($lang['takeupload_failed'], $lang['takeupload_not_torrent']);
}
$shortfname = $torrent = $matches[1];
if (!empty($_POST["name"])) {
    $torrent = unesc($_POST["name"]);
}
$tmpname = $f["tmp_name"];
if (!is_uploaded_file($tmpname)) {
    stderr($lang['takeupload_failed'], $lang['takeupload_eek']);
}
if (!filesize($tmpname)) {
    stderr($lang['takeupload_failed'], $lang['takeupload_no_file']);
}
// bencdec by djGrrr <3
$dict = bencdec::decode_file($tmpname, $TRINITY20['max_torrent_size'], bencdec::OPTION_EXTENDED_VALIDATION);
if ($dict === false) {
    stderr('Error', 'What the hell did you upload? This is not a bencoded file!');
}
if (isset($dict['announce-list'])) {
    unset($dict['announce-list']);
}
$dict['info']['private'] = 1;
if (!isset($dict['info'])) {
    stderr('Error', 'invalid torrent, info dictionary does not exist');
}
$info =& $dict['info'];
$infohash = pack("H*", sha1(bencdec::encode($info)));
if (bencdec::get_type($info) != 'dictionary') {
    stderr('Error', 'invalid torrent, info is not a dictionary');
}
if (!isset($info['name']) || !isset($info['piece length']) || !isset($info['pieces'])) {
    stderr('Error', 'invalid torrent, missing parts of the info dictionary');
}
if (bencdec::get_type($info['name']) != 'string' || bencdec::get_type($info['piece length']) != 'integer' || bencdec::get_type($info['pieces']) != 'string') {
    stderr('Error', 'invalid torrent, invalid types in info dictionary');
}
$dname = $info['name'];
$plen = $info['piece length'];
$pieces_len = strlen($info['pieces']);
if ($pieces_len % 20 != 0) {
    stderr('Error', 'invalid pieces');
}
if ($plen % 4096 !== 0) {
    stderr('Error', 'piece size is not mod(4096), wtf kind of torrent is that?');
}
$filelist = [];
if (isset($info['length'])) {
    if (bencdec::get_type($info['length']) != 'integer') {
        stderr('Error', 'length must be an integer');
    }
    $totallen = $info['length'];
    $filelist[] = [
        $dname,
        $totallen,
    ];
    $type = 'single';
} else {
    if (!isset($info['files'])) {
        stderr('Error', 'missing both length and files');
    }
    if (bencdec::get_type($info['files']) != 'list') {
        stderr('Error', 'invalid files, not a list');
    }
    $flist =& $info['files'];
    if ((is_countable($flist) ? count($flist) : 0) === 0) {
        stderr('Error', 'no files');
    }
    $totallen = 0;
    foreach ($flist as $fn) {
        if (!isset($fn['length']) || !isset($fn['path'])) {
            stderr('Error', 'file info not found');
        }
        if (bencdec::get_type($fn['length']) != 'integer' || bencdec::get_type($fn['path']) != 'list') {
            stderr('Error', 'invalid file info');
        }
        $ll = $fn['length'];
        $ff = $fn['path'];
        $totallen += $ll;
        $ffa = [];
        foreach ($ff as $ffe) {
            if (bencdec::get_type($ffe) != 'string') {
                stderr('Error', 'filename type error');
            }
            $ffa[] = $ffe;
        }
        if (count($ffa) === 0) {
            stderr('Error', 'filename error');
        }
        $ffe = implode('/', $ffa);
        $filelist[] = [
            $ffe,
            $ll,
        ];
    }
    $type = 'multi';
}
$num_pieces = $pieces_len / 20;
$expected_pieces = (int)ceil($totallen / $plen);
if ($num_pieces != $expected_pieces) {
    stderr('Whoops', 'total file size and number of pieces do not match');
}
//==
$tmaker = (isset($dict['created by']) && !empty($dict['created by'])) ? sqlesc($dict['created by']) : sqlesc($lang['takeupload_unkown']);
$dict['comment'] = ("In using this torrent you are bound by the {$TRINITY20['site_name']} Confidentiality Agreement By Law"); // change torrent comment
// Replace punctuation characters with spaces
$visible = (XBT_TRACKER == true ? "yes" : "no");
$torrent = str_replace("_", " ", $torrent);
$vip = (isset($_POST["vip"]) ? "1" : "0");

//IMDB if entered in the form
$url = strip_tags(isset($_POST['url']) ? trim($_POST['url']) : '');
//If no IMDB entered lets look in the description for one
//if (!$url)
//  stderr($lang['takeupload_failed'], 'No IMDB Found');

//$imdb_info = get_imdb($url);
$poster = strip_tags(isset($_POST['poster']) ? trim($_POST['poster']) : '');
//END IMDB

$ret = sql_query("INSERT INTO torrents (search_text, filename, owner, username, visible, vip, release_group, newgenre, poster, anonymous, allow_comments, info_hash, name, size, numfiles, type, offer, request, url, subs, descr, ori_descr, description, category, free, silver, save_as, youtube, tags, added, last_action, mtime, ctime, freetorrent, nfo, client_created_by) VALUES (".implode(",",
        array_map("sqlesc", [
            searchfield("$shortfname $dname $torrent"),
            $fname,
            $CURUSER["id"],
            $CURUSER["username"],
            $visible,
            $vip,
            $release_group,
            $genre,
            $poster,
            $anonymous,
            $allow_comments,
            $infohash,
            $torrent,
            $totallen,
            count($filelist),
            $type,
            $offer,
            $request,
            $url,
            $subs,
            $descr,
            $descr,
            $description,
            0 + $_POST["type"],
            $free2,
            $silver,
            $dname,
            $youtube,
            $tags,
        ])).", ".TIME_NOW.", ".TIME_NOW.", ".TIME_NOW.", ".TIME_NOW.", $freetorrent, $nfo, $tmaker)");
if (!$ret) {
    if ($mysqli->errno) {
        stderr($lang['takeupload_failed'], $lang['takeupload_already']);
    }
    stderr($lang['takeupload_failed'], "mysql puked: ".$mysqli->error);
}
if (XBT_TRACKER == false) {
    remove_torrent($infohash);
}
$id = $mysqli->insert_id;
$cache->delete($cache_keys['my_peers'].$CURUSER['id']);
//$cache->delete($cache_keys['lastest_tor']);  //
$cache->delete($cache_keys['last5_tor']);
$cache->delete($cache_keys['scroll_tor']);

sql_query("DELETE FROM files WHERE torrent = ".sqlesc($id));
function file_list($arr, $id)
{
    foreach ($arr as $v) {
        $new[] = "($id,".sqlesc($v[0]).",".$v[1].")";
    }
    return implode(",", $new);
}

sql_query("INSERT INTO files (torrent, filename, size) VALUES ".file_list($filelist, $id));
//==
$dir = $TRINITY20['torrent_dir'].'/'.$id.'.torrent';
if (!bencdec::encode_file($dir, $dict)) {
    stderr('Error', 'Could not properly encode file');
}
@unlink($tmpname);
chmod($dir, 0664);
//==

//=== if it was an offer notify the folks who liked it :D
if ($offer > 0) {
    ($res_offer = sql_query('SELECT user_id FROM offer_votes WHERE vote = \'yes\' AND user_id != '.sqlesc($CURUSER['id']).' AND offer_id = '.sqlesc($offer))) || sqlerr(__FILE__,
        __LINE__);
    $subject = sqlesc('An offer you voted for has been uploaded!');
    $message = sqlesc("Hi, \n An offer you were interested in has been uploaded!!! \n\n Click  [url=".$TRINITY20['baseurl']."/details.php?id=".$id."]".htmlsafechars($torrent,
            ENT_QUOTES)."[/url] to see the torrent page!");
    while ($arr_offer = $res_offer->fetch_assoc()) {
        sql_query('INSERT INTO messages (sender, receiver, added, msg, subject, saved, location) 
    VALUES(0, '.sqlesc($arr_offer['user_id']).', '.TIME_NOW.', '.$message.', '.$subject.', \'yes\', 1)') || sqlerr(__FILE__, __LINE__);
        $cache->delete($cache_keys['inbox_new'].$arr_offer['user_id']);
        $cache->delete($cache_keys['inbox_new_sb'].$arr_offer['user_id']);
    }
    write_log('Offered torrent '.$id.' ('.htmlsafechars($torrent).') was uploaded by '.$CURUSER['username']);
    $filled = 1;
}
$filled = 0;
//=== if it was a request notify the folks who voted :D
if ($request > 0) {
    ($res_req = sql_query('SELECT user_id FROM request_votes WHERE vote = \'yes\' AND request_id = '.sqlesc($request))) || sqlerr(__FILE__, __LINE__);
    $subject = sqlesc('A  request you were interested in has been uploaded!');
    $message = sqlesc("Hi :D \n A request you were interested in has been uploaded!!! \n\n Click  [url=".$TRINITY20['baseurl']."/details.php?id=".$id."]".htmlsafechars($torrent,
            ENT_QUOTES)."[/url] to see the torrent page!");
    while ($arr_req = $res_req->fetch_assoc()) {
        sql_query('INSERT INTO messages (sender, receiver, added, msg, subject, saved, location) 
    VALUES(0, '.sqlesc($arr_req['user_id']).', '.TIME_NOW.', '.$message.', '.$subject.', \'yes\', 1)') || sqlerr(__FILE__, __LINE__);
        $cache->delete($cache_keys['inbox_new'].$arr_req['user_id']);
        $cache->delete($cache_keys['inbox_new_sb'].$arr_req['user_id']);
    }
    sql_query('UPDATE requests SET filled_by_user_id = '.sqlesc($CURUSER['id']).', filled_torrent_id = '.sqlesc($id).' WHERE id = '.sqlesc($request)) || sqlerr(__FILE__,
        __LINE__);
    sql_query("UPDATE usersachiev SET reqfilled = reqfilled + 1 WHERE id =".sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    write_log('Request for torrent '.$id.' ('.htmlsafechars($torrent).') was filled by '.$CURUSER['username']);
    $filled = 1;
}
if ($filled == 0) {
    write_log(sprintf($lang['takeupload_log'], $id, $torrent, $CURUSER['username']));
}
/* RSS feeds */
if (($fd1 = @fopen("rss.xml", "w")) && ($fd2 = fopen("rssdd.xml", "w"))) {
    $cats = "";
    $res = sql_query("SELECT id, name FROM categories");
    while ($arr = $res->fetch_assoc()) {
        $cats[$arr["id"]] = htmlsafechars($arr["name"] ?? '');
    }
    $s = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n<rss version=\"0.91\">\n<channel>\n"."<title>{$TRINITY20['site_name']}</title>\n<description>TRINITY20 is the best!</description>\n<link>{$TRINITY20['baseurl']}/</link>\n";
    @fwrite($fd1, $s);
    @fwrite($fd2, $s);
    ($r = sql_query("SELECT id,name,descr,filename,category FROM torrents ORDER BY added DESC LIMIT 15")) || sqlerr(__FILE__, __LINE__);
    while ($a = $r->fetch_assoc()) {
        $cat = $cats[$a["category"]];
        $s = "<item>\n<title>".htmlsafechars($a["name"]." ($cat)")."</title>\n"."<description>".htmlsafechars($a["descr"])."</description>\n";
        @fwrite($fd1, $s);
        @fwrite($fd2, $s);
        @fwrite($fd1, "<link>{$TRINITY20['baseurl']}/details.php?id=".(int)$a['id']."&amp;hit=1</link>\n</item>\n");
        $filename = htmlsafechars($a["filename"]);
        @fwrite($fd2, "<link>{$TRINITY20['baseurl']}/download.php?torrent=".(int)$a['id']."/$filename</link>\n</item>\n");
    }
    $s = "</channel>\n</rss>\n";
    @fwrite($fd1, $s);
    @fwrite($fd2, $s);
    @fclose($fd1);
    @fclose($fd2);
}
/* end rss */
$categorie = genrelist();
foreach ($categorie as $key => $value) {
    $change[$value['id']] = [
        'id' => $value['id'],
        'name' => $value['name'],
    ];
}
$Cat_Name['cat_name'] = htmlsafechars($change[$_POST['type']]['name']);
if ($TRINITY20['seedbonus_on'] == 1) {
    if (isset($_POST['uplver']) && $_POST['uplver'] == 'yes') {
        $message = "New Torrent : Category = ".htmlsafechars($Cat_Name['cat_name']).", [url={$TRINITY20['baseurl']}/details.php?id=$id] ".htmlsafechars($torrent)."[/url] Uploaded - Anonymous User";
    } else {
        $message = "New Torrent : Category = ".htmlsafechars($Cat_Name['cat_name']).", [url={$TRINITY20['baseurl']}/details.php?id=$id] ".htmlsafechars($torrent)."[/url] Uploaded by ".htmlsafechars($CURUSER["username"])."";
    }
    $messages = "{$TRINITY20['site_name']} New Torrent : Category = ".htmlsafechars($Cat_Name['cat_name']).", $torrent Uploaded By: $anon ".mksize($totallen)." {$TRINITY20['baseurl']}/details.php?id=$id";
    //===add karma
    sql_query("UPDATE users SET seedbonus=seedbonus+".sqlesc($TRINITY20['bonus_per_upload']).", numuploads=numuploads+1 WHERE id = ".sqlesc($CURUSER["id"])) || sqlerr(__FILE__,
        __LINE__);
    //===end
    $update['seedbonus'] = ($CURUSER['seedbonus'] + $TRINITY20['bonus_per_upload']);
    $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['user_stats']);
}
/*
if ($TRINITY20['autoshout_on'] == 1) {
    shout2($message, $id);
    $cache->delete('shoutbox_');
}
*/
header("Location: {$TRINITY20['baseurl']}/details.php?id=$id&uploaded=1");
?> 
