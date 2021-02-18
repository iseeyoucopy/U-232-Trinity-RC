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
require_once(INCL_DIR.'function_happyhour.php');
require_once(CLASS_DIR.'class.bencdec.php');
dbconn();
$lang = array_merge(load_language('global'), load_language('download'));
$T_Pass = isset($_GET['torrent_pass']) && strlen($_GET['torrent_pass']) == 32 ? $_GET['torrent_pass'] : '';
if (!empty($T_Pass)) {
    ($q0 = sql_query("SELECT * FROM users where torrent_pass = ".sqlesc($T_Pass))) || sqlerr(__FILE__, __LINE__);
    if ($q0->num_rows == 0) {
        die($lang['download_passkey']);
    }

    $CURUSER = $q0->fetch_assoc();
} else {
    loggedinorreturn();
}
if (function_exists('parked')) {
    parked();
}
$id = isset($_GET['torrent']) ? (int)$_GET['torrent'] : 0;
$zipuse = isset($_GET['zip']) && $_GET['zip'] == 1;
$text = isset($_GET['text']) && $_GET['text'] == 1;
if (!is_valid_id($id)) {
    stderr($lang['download_user_error'], $lang['download_no_id']);
}
($res = sql_query('SELECT name, owner, vip, category, filename, info_hash FROM torrents WHERE id = '.sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$row = $res->fetch_assoc();

($cres = sql_query('SELECT min_class FROM categories WHERE id = '.sqlesc($row['category']))) || sqlerr(__FILE__, __LINE__);
$crow = $cres->fetch_assoc();
if ($crow['min_class'] > $CURUSER['class']) {
    stderr($lang['download_user_error'], $lang['download_no_id']);
}
$fn = $TRINITY20['torrent_dir'].'/'.$id.'.torrent';
if (!$row || !is_file($fn) || !is_readable($fn)) {
    stderr('Err', 'There was an error with the file or with the query, please contact staff');
}
if (happyHour('check') && happyCheck('checkid', $row['category']) && XBT_TRACKER == false && $TRINITY20['happy_hour'] == true) {
    $multiplier = happyHour('multiplier');
    happyLog($CURUSER['id'], $id, $multiplier);
    sql_query('INSERT INTO happyhour (userid, torrentid, multiplier ) VALUES ('.sqlesc($CURUSER['id']).','.sqlesc($id).','.sqlesc($multiplier).')') || sqlerr(__FILE__,
        __LINE__);
    $cache->delete($CURUSER['id'].'_happy');
}

if (($CURUSER['seedbonus'] === 0 || $CURUSER['seedbonus'] < $TRINITY20['bonus_per_download'])) {
    stderr("Error", "Your dont have enough credit to download, trying seeding back some torrents =]");
}

if ($TRINITY20['seedbonus_on'] == 1 && $row['owner'] != $CURUSER['id']) {
    //===remove karma
    sql_query("UPDATE users SET seedbonus = seedbonus-".sqlesc($TRINITY20['bonus_per_download'])." WHERE id = ".sqlesc($CURUSER["id"])) || sqlerr(__FILE__,
        __LINE__);
    $update['seedbonus'] = ($CURUSER['seedbonus'] - $TRINITY20['bonus_per_download']);
    $update['seedbonus'] = ($CURUSER['seedbonus'] - $TRINITY20['bonus_per_download']);
    $cache->update_row($keys['user_stats'].$CURUSER['id'], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row('user_stats_'.$CURUSER['id'], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['user_stats']);
    //===end

}
if (($CURUSER['downloadpos'] == 0 || $CURUSER['can_leech'] == 0 || $CURUSER['downloadpos'] > 1 || $CURUSER['suspended'] == 'yes') && $CURUSER['id'] != $row['owner']) {
    stderr("Error", "Your download rights have been disabled.");
}

if ($row['vip'] == 1 && $CURUSER['class'] < UC_VIP) {
    stderr('VIP Access Required',
        'You must be a VIP In order to view details or download this torrent! You may become a Vip By Donating to our site. Donating ensures we stay online to provide you more Vip-Only Torrents!');
}
sql_query("UPDATE torrents SET hits = hits + 1 WHERE id = ".sqlesc($id));
/* free mod by pdq **/
/* freeslots/doubleseed by pdq **/
if (isset($_GET['slot'])) {
    $added = (TIME_NOW + 14 * 86400);
    $slots_sql = sql_query('SELECT * FROM freeslots WHERE torrentid = '.sqlesc($id).' AND userid = '.sqlesc($CURUSER['id']));
    $slot = $slots_sql->fetch_assoc();
    $used_slot = $slot['torrentid'] ?? 0 == $id && $slot['userid'] ?? '' == $CURUSER['id'];
    /* freeslot **/
    if ($_GET['slot'] == 'free') {
        if ($used_slot && $slot['free'] == 'yes') {
            stderr('Doh!', 'Freeleech slot already in use.');
        }
        if ($CURUSER['freeslots'] < 1) {
            stderr('Doh!', 'No Slots.');
        }
        $CURUSER['freeslots'] -= 1;
        sql_query('UPDATE users SET freeslots = freeslots - 1 WHERE id = '.sqlesc($CURUSER['id']).' LIMIT 1') || sqlerr(__FILE__, __LINE__);
        if ($used_slot && $slot['doubleup'] == 'yes') {
            sql_query('UPDATE freeslots SET free = "yes", addedfree = '.$added.' WHERE torrentid = '.$id.' AND userid = '.$CURUSER['id'].' AND doubleup = "yes"') || sqlerr(__FILE__,
                __LINE__);
        } elseif ($used_slot && $slot['doubleup'] == 'no') {
            sql_query('INSERT INTO freeslots (torrentid, userid, free, addedfree) VALUES ('.sqlesc($id).', '.sqlesc($CURUSER['id']).', "yes", '.$added.')') || sqlerr(__FILE__,
                __LINE__);
        } else {
            sql_query('INSERT INTO freeslots (torrentid, userid, free, addedfree) VALUES ('.sqlesc($id).', '.sqlesc($CURUSER['id']).', "yes", '.$added.')') || sqlerr(__FILE__,
                __LINE__);
        }
    } /* doubleslot **/
    elseif ($_GET['slot'] == 'double') {
        if ($used_slot && $slot['doubleup'] == 'yes') {
            stderr('Doh!', 'Doubleseed slot already in use.');
        }
        if ($CURUSER['freeslots'] < 1) {
            stderr('Doh!', 'No Slots.');
        }
        $CURUSER['freeslots'] -= 1;
        sql_query('UPDATE users SET freeslots = freeslots - 1 WHERE id = '.sqlesc($CURUSER['id']).' LIMIT 1') || sqlerr(__FILE__, __LINE__);
        if ($used_slot && $slot['free'] == 'yes') {
            sql_query('UPDATE freeslots SET doubleup = "yes", addedup = '.$added.' WHERE torrentid = '.sqlesc($id).' AND userid = '.sqlesc($CURUSER['id']).' AND free = "yes"') || sqlerr(__FILE__,
                __LINE__);
        } elseif ($used_slot && $slot['free'] == 'no') {
            sql_query('INSERT INTO freeslots (torrentid, userid, doubleup, addedup) VALUES ('.sqlesc($id).', '.sqlesc($CURUSER['id']).', "yes", '.$added.')') || sqlerr(__FILE__,
                __LINE__);
        } else {
            sql_query('INSERT INTO freeslots (torrentid, userid, doubleup, addedup) VALUES ('.sqlesc($id).', '.sqlesc($CURUSER['id']).', "yes", '.$added.')') || sqlerr(__FILE__,
                __LINE__);
        }
    } else {
        stderr('ERROR', 'What\'s up doc?');
    }
    $cache->delete('fllslot_'.$CURUSER['id']);
    make_freeslots($CURUSER['id'], 'fllslot_');
    $user['freeslots'] = ($CURUSER['freeslots'] - 1);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'freeslots' => $CURUSER['freeslots'],
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('user'.$CURUSER['id'], [
        'freeslots' => $user['freeslots'],
    ], $TRINITY20['expires']['user_cache']);
}
/* end **/
$cache->delete($keys['my_peers'].$CURUSER['id']);
$cache->delete('top5_tor_');
$cache->delete('last5_tor_');
$cache->delete('scroll_tor_');
if (!isset($CURUSER['torrent_pass']) || strlen($CURUSER['torrent_pass']) != 32) {
    ($xbt_config_query = sql_query("SELECT value FROM xbt_config WHERE name='torrent_pass_private_key'")) || sqlerr(__FILE__, __LINE__);
    $xbt_config = $xbt_config_query->fetch_row();
    $site_key = $xbt_config['0']; // the value of torrent_pass_private_key that is stored in the xbt_config table
    $info_hash = $row['info_hash']; // the torrent info_hash
    $torrent_pass_version = $CURUSER['torrent_pass_version']; // the torrent_pass_version that is stored in the users table for the user in question
    $uid = $CURUSER['id']; // the uid (userid) in the users table for the user in question
    $passkey = sprintf('%08x%s', $uid, substr(sha1(sprintf('%s %d %d %s', $site_key, $torrent_pass_version, $uid, $info_hash)), 0, 24));
    $CURUSER['torrent_pass'] = $passkey;
    sql_query('UPDATE users SET torrent_pass='.sqlesc($CURUSER['torrent_pass']).'WHERE id='.sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'torrent_pass' => $CURUSER['torrent_pass'],
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('user'.$CURUSER['id'], [
        'torrent_pass' => $CURUSER['torrent_pass'],
    ], $TRINITY20['expires']['user_cache']);
}
$dict = bencdec::decode_file($fn, $TRINITY20['max_torrent_size']);
if (XBT_TRACKER == true) {
    $dict['announce'] = $TRINITY20['xbt_prefix'].$CURUSER['torrent_pass'].$TRINITY20['xbt_suffix'];
} else {
    $dict['announce'] = $TRINITY20['announce_urls'].'?torrent_pass='.$CURUSER['torrent_pass'];
}
$dict['uid'] = (int)$CURUSER['id'];
$tor = bencdec::encode($dict);
if ($zipuse) {
    require_once(INCL_DIR.'phpzip.php');
    $row['name'] = str_replace([
        ' ',
        '.',
        '-',
    ], '_', $row['name']);
    $file_name = $TRINITY20['torrent_dir'].'/'.$row['name'].'.torrent';
    if (file_put_contents($file_name, $tor)) {
        $zip = new PHPZip();
        $files = [
            $file_name,
        ];
        $file_name = $TRINITY20['torrent_dir'].'/'.substr(md5(rawurlencode($row['name'])), 0, 6).'.zip';
        $zip->Zip($files, $file_name);
        $zip->forceDownload($file_name);
        unlink($TRINITY20['torrent_dir'].'/'.$row['name'].'.torrent');
        unlink($TRINITY20['torrent_dir'].'/'.$row['name'].'.zip');
    } else {
        stderr('Error', 'Can\'t create the new file, please contatct staff');
    }
} elseif ($text) {
    header('Content-Disposition: attachment; filename="['.$TRINITY20['site_name'].']'.$row['name'].'.txt"');
    header("Content-Type: text/plain");
    echo($tor);
} else {
    header('Content-Disposition: attachment; filename="['.$TRINITY20['site_name'].']'.$row['filename'].'"');
    header("Content-Type: application/x-bittorrent");
    echo($tor);
}
?>
