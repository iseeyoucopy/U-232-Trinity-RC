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
    $newpage->check('tamud');
}
if ($CURUSER['class'] < UC_UPLOADER || ($CURUSER["uploadpos"] == 0 || $CURUSER["uploadpos"] > 1 || $CURUSER['suspended'] == 'yes')) {
    header("Location: {$TRINITY20['baseurl']}/upload.php");
    exit();
}

$total_torrents = 0;

if (!isset($_FILES["file"])) {
    stderr($lang['takeupload_failed'], $lang['takeupload_no_formdata']);
}

$total_torrents = is_countable($_FILES['file']['name']) ? count($_FILES['file']['name']) : 0;
function file_list($arr, $id)
{
    foreach ($arr as $v) {
        $new[] = "($id,".sqlesc($v[0]).",".$v[1].")";
    }
    return join(",", $new);
}
$cats = "";
$res = sql_query("SELECT id, name FROM categories");
while ($arr = $res->fetch_assoc()) {
    $cats[$arr["id"]] = $arr["name"];
}
$processed = $successful = 0;

//parse _FILES into readable format
$file_list = [];
while ($processed < $total_torrents) {
    $file_list[$processed] = [];
    $file_list[$processed]['torrent'] = [];
    $file_list[$processed]['torrent']['name'] = $_FILES['file']['name'][$processed];
    $file_list[$processed]['torrent']['tmp_name'] = $_FILES['file']['tmp_name'][$processed];

    $file_list[$processed]['nfo'] = [];
    $file_list[$processed]['nfo']['name'] = $_FILES['nfo']['name'][$processed];
    $file_list[$processed]['nfo']['tmp_name'] = $_FILES['nfo']['tmp_name'][$processed];
    $file_list[$processed]['nfo']['size'] = $_FILES['nfo']['size'][$processed];

    $file_list[$processed]['type'] = $_POST['type'][$processed];

    $processed++;
}

$ids = [];

foreach ($file_list as $key => $f) {
    $fname = unesc($f['torrent']["name"]);

    if (empty($fname)) {
        continue;
    }

    if (!validfilename($fname)) {
        continue;
    }

    if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches)) {
        continue;
    }
    $shortfname = $torrent = $matches[1];

    $tmpname = $f['torrent']["tmp_name"];
    if (!is_uploaded_file($tmpname)) {
        continue;
    }
    if (!filesize($tmpname)) {
        continue;
    }

    $anonymous = "yes";
    $anon = "Anonymous";
    $allow_comments = "yes";
    $disallow = "No";
    $free = $freetorrent = $silver = $request = $offer = 0;
    $descr = $description = $subs = $youtube = $tags = $poster = $genre = '';
    $release_group = 'none';

    $nfo = sqlesc('');

    $nfofile = $f['nfo'];
    if ($nfofile['name'] == '' || $nfofile['size'] == 0 || $nfofile['size'] > 65535) {
        $nfo = sqlesc('');
    } else {
        $nfofilename = $nfofile['tmp_name'];
        if (@!is_uploaded_file($nfofilename)) {
            $nfo = sqlesc('');
        } else {
            $nfo = sqlesc(str_replace("\x0d\x0d\x0a", "\x0d\x0a", @file_get_contents($nfofilename)));
            $descr = str_replace("\x0d\x0d\x0a", "\x0d\x0a", @file_get_contents($nfofilename));
        }
    }


    $catid = (0 + $f["type"]);
    if (!is_valid_id($catid)) {
        continue;
    }


    $dict = bencdec::decode_file($tmpname, $TRINITY20['max_torrent_size'], bencdec::OPTION_EXTENDED_VALIDATION);
    if ($dict === false) {
        continue;
    }
    if (isset($dict['announce-list'])) {
        unset($dict['announce-list']);
    }
    $dict['info']['private'] = 1;
    if (!isset($dict['info'])) {
        continue;
    }
    $info =& $dict['info'];
    $infohash = pack("H*", sha1(bencdec::encode($info)));
    if (bencdec::get_type($info) != 'dictionary') {
        continue;
    }
    if (!isset($info['name']) || !isset($info['piece length']) || !isset($info['pieces'])) {
        continue;
    }
    if (bencdec::get_type($info['name']) != 'string' || bencdec::get_type($info['piece length']) != 'integer' || bencdec::get_type($info['pieces']) != 'string') {
        continue;
    }
    $dname = $info['name'];
    $plen = $info['piece length'];
    $pieces_len = strlen($info['pieces']);
    if ($pieces_len % 20 != 0) {
        continue;
    }
    if ($plen % 4096) {
        continue;
    }
    $filelist = [];
    if (isset($info['length'])) {
        if (bencdec::get_type($info['length']) != 'integer') {
            continue;
        }
        $totallen = $info['length'];
        $filelist[] = [
            $dname,
            $totallen,
        ];
        $type = 'single';
    } else {
        if (!isset($info['files'])) {
            continue;
        }
        if (bencdec::get_type($info['files']) != 'list') {
            continue;
        }
        $flist =& $info['files'];
        if ((is_countable($flist) ? count($flist) : 0) === 0) {
            continue;
        }
        $totallen = 0;
        foreach ($flist as $fn) {
            if (!isset($fn['length']) || !isset($fn['path'])) {
                continue;
            }
            if (bencdec::get_type($fn['length']) != 'integer' || bencdec::get_type($fn['path']) != 'list') {
                continue;
            }
            $ll = $fn['length'];
            $ff = $fn['path'];
            $totallen += $ll;
            $ffa = [];
            foreach ($ff as $ffe) {
                if (bencdec::get_type($ffe) != 'string') {
                    continue;
                }
                $ffa[] = $ffe;
            }
            if ((is_countable($ffa) ? count($ffa) : 0) === 0) {
                continue;
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
        continue;
    }
    //==
    $tmaker = (isset($dict['created by']) && !empty($dict['created by'])) ? sqlesc($dict['created by']) : sqlesc($lang['takeupload_unkown']);
    $dict['comment'] = ("In using this torrent you are bound by the {$TRINITY20['site_name']} Confidentiality Agreement By Law"); // change torrent comment
    // Replace punctuation characters with spaces
    $visible = (XBT_TRACKER == true ? "yes" : "no");
    $torrent = str_replace("_", " ", $torrent);
    $vip = "0";


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
                is_countable($filelist) ? count($filelist) : 0,
                $type,
                $offer,
                $request,
                $url,
                $subs,
                $descr,
                $descr,
                $description,
                $catid,
                $free,
                $silver,
                $dname,
                $youtube,
                $tags,
            ])).", ".TIME_NOW.", ".TIME_NOW.", ".TIME_NOW.", ".TIME_NOW.", $freetorrent, $nfo, $tmaker)");

    if (!$ret && $mysqli->errno) {
        continue;
    }

    if (XBT_TRACKER == false) {
        remove_torrent($infohash);
    }


    $id = $mysqli->insert_id;

    $ids[] = $id;
    if ($id > 0) {
        $successful += 1;
    }
    $messages = "{$TRINITY20['site_name']} New Torrent: $torrent Uploaded By: $anon ".mksize($totallen)." {$TRINITY20['baseurl']}/details.php?id=$id";
    $message = "New Torrent : Category = ".htmlsafechars($cats[$catid]).", [url={$TRINITY20['baseurl']}/details.php?id=$id] ".htmlsafechars($torrent)."[/url] Uploaded - Anonymous User";

    sql_query("DELETE FROM files WHERE torrent = ".sqlesc($id));

    sql_query("INSERT INTO files (torrent, filename, size) VALUES ".file_list($filelist, $id));

    $dir = $TRINITY20['torrent_dir'].'/'.$id.'.torrent';
    if (!bencdec::encode_file($dir, $dict)) {
        continue;
    }
    @unlink($tmpname);
    chmod($dir, 0664);


    if ($TRINITY20['autoshout_on'] == 1) {
        autoshout($message, $id);

    }

    /* RSS feeds */
    if (($fd1 = @fopen("rss.xml", "w")) && ($fd2 = fopen("rssdd.xml", "w"))) {
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
}


$cache->delete($keys['my_peers'].$CURUSER['id']);
//$cache->delete('lastest_tor_');  //
$cache->delete('last5_tor_');
$cache->delete('scroll_tor_');

//==

//==
if ($TRINITY20['seedbonus_on'] == 1) {
    $bonus_val = ($TRINITY20['bonus_per_upload'] * $successful);
    //===add karma
    sql_query("UPDATE users SET seedbonus=seedbonus+".sqlesc($bonus_val).", numuploads=numuploads+1 WHERE id = ".sqlesc($CURUSER["id"])) || sqlerr(__FILE__,
        __LINE__);
    //===end
    $update['seedbonus'] = ($CURUSER['seedbonus'] + $bonus_val);
    $cache->update_row($keys['user_stats'].$CURUSER["id"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row($keys['user_stats_'].$CURUSER["id"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['user_stats']);
}
$ids = implode('&id[]=', $ids);
header("Location: {$TRINITY20['baseurl']}/multidetails.php?id[]=".$ids);
?> 
