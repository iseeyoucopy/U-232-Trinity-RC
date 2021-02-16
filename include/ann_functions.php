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
$mysqli = new mysqli($TRINITY20['mysql_host'], $TRINITY20['mysql_user'], $TRINITY20['mysql_pass'], $TRINITY20['mysql_db']);
if ($mysqli->connect_errno !== 0) {
    err('Please call back later');
  }
//== Announce mysql error
function ann_sqlerr($file = '', $line = '')
{
    global $TRINITY20, $CURUSER, $mysqli;
    $error = $mysqli->error;
    $error_no = $mysqli->errno;
    if ($TRINITY20['ann_sql_error_log'] && ANN_SQL_DEBUG == 1) {
        $_ann_sql_err = "\n===================================================";
        $_ann_sql_err.= "\n Date: " . date('r');
        $_ann_sql_err.= "\n Error Number: " . $error_no;
        $_ann_sql_err.= "\n Error: " . $error;
        $_ann_sql_err.= "\n IP Address: " . $_SERVER['REMOTE_ADDR'];
        $_ann_sql_err.= "\n in file " . $file . " on line " . $line;
        $_ann_sql_err.= "\n URL:" . $_SERVER['REQUEST_URI'];
		$error_username = $CURUSER['username'] ?? '';
		$error_userid = $CURUSER['id'] ?? '';
        $_ann_sql_err.= "\n Username: {$error_username}[{$error_userid}]";
        if ($FH = @fopen($TRINITY20['ann_sql_error_log'], 'a')) {
            @fwrite($FH, $_ann_sql_err);
            @fclose($FH);
        }
    }
}
//==Announce Sql query logging
function ann_sql_query($a_query)
{
    global $a_query_stat, $TRINITY20, $mysqli;
    $a_query_start_time = microtime(true); // Start time
    $result = $mysqli->query($a_query);
    $a_query_end_time = microtime(true); // End time
    $a_querytime = ($a_query_end_time - $a_query_start_time);
    $a_query_stat[] = array(
        'seconds' => number_format($a_query_end_time - $a_query_start_time, 6) ,
        'query' => $a_query
    );
    if (((is_countable($a_query_stat) ? count($a_query_stat) : 0) > 0) && (ANN_SQL_LOGGING == 1)) {
    foreach ($a_query_stat as $key => $value) {
        $_ann_sql = "\n=============U-232 V5 Announce query logging=========";
        $_ann_sql.= "\n Query no : " . $key."+1";
        $_ann_sql.= "\n Executed in  : " . $value['seconds'];
        $_ann_sql.= "\n query ran : " . $value['query'];
        }
        if ($FH = @fopen($TRINITY20['ann_sql_log'], 'a')) {
            @fwrite($FH, $_ann_sql);
            @fclose($FH);
        }
    }
    return $result;
    }
//== Crazyhour by pdq
function crazyhour_announce()
{
    global $cache, $TRINITY20, $mysqli;
    $crazy_hour = (TIME_NOW + 3600);
    if (($cz['crazyhour'] = $cache->get('crazyhour')) === false) {
        ($cz['sql'] = ann_sql_query('SELECT var, amount FROM freeleech WHERE type = "crazyhour"')) || ann_sqlerr(__FILE__, __LINE__);
        $cz['crazyhour'] = array();
        if ($cz['sql']->num_rows !== 0) $cz['crazyhour'] = $cz['sql']->fetch_assoc();
        else {
            $cz['crazyhour']['var'] = random_int(TIME_NOW, (TIME_NOW + 86400));
            $cz['crazyhour']['amount'] = 0;
            ann_sql_query('UPDATE LOW_PRIORITY freeleech SET var = ' . $cz['crazyhour']['var'] . ', amount = ' . $cz['crazyhour']['amount'] . ' 
         WHERE type = "crazyhour"') || ann_sqlerr(__FILE__, __LINE__);
        }
        $cache->set('crazyhour', $cz['crazyhour'], 0);
    }
    if ($cz['crazyhour']['var'] < TIME_NOW) { // if crazyhour over
        if (($cz_lock = $cache->set('crazyhour_lock', 1, 10)) !== false) {
            $cz['crazyhour_new'] = mktime(23, 59, 59, date('m') , date('d') , date('y'));
            $cz['crazyhour']['var'] = random_int($cz['crazyhour_new'], ($cz['crazyhour_new'] + 86400));
            $cz['crazyhour']['amount'] = 0;
            $cz['remaining'] = ($cz['crazyhour']['var'] - TIME_NOW);
            ann_sql_query('UPDATE LOW_PRIORITY freeleech SET var = ' . $cz['crazyhour']['var'] . ', amount = ' . $cz['crazyhour']['amount'] . ' ' . 'WHERE type = "crazyhour"') || ann_sqlerr(__FILE__, __LINE__);
            $cache->set('crazyhour', $cz['crazyhour'], 0);
            // log, shoutbot
            $text = 'Next [color=orange][b]Crazyhour[/b][/color] is at ' . date('F j, g:i a', $cz['crazyhour']['var']);
            $text_parsed = 'Next <span style="font-weight:bold;color:orange;">Crazyhour</span> is at ' . date('F j, g:i a', $cz['crazyhour']['var']);
            ann_sql_query('INSERT LOW_PRIORITY INTO sitelog (added, txt) ' . 'VALUES(' . TIME_NOW . ', ' . ann_sqlesc($text_parsed) . ')') || ann_sqlerr(__FILE__, __LINE__);
            ann_sql_query('INSERT LOW_PRIORITY INTO shoutbox (userid, date, text, text_parsed) ' . 'VALUES (2, ' . TIME_NOW . ', ' . ann_sqlesc($text) . ', ' . ann_sqlesc($text_parsed) . ')') || ann_sqlerr(__FILE__, __LINE__);
            $cache->delete('shoutbox_');
        }
        return false;
    } elseif (($cz['crazyhour']['var'] < $crazy_hour) && ($cz['crazyhour']['var'] >= TIME_NOW)) { // if crazyhour
        if ($cz['crazyhour']['amount'] !== 1) {
            $cz['crazyhour']['amount'] = 1;
            if (($cz_lock = $cache->set('crazyhour_lock', 1, 10)) !== false) {
                ann_sql_query('UPDATE LOW_PRIORITY freeleech SET amount = ' . $cz['crazyhour']['amount'] . ' 
            WHERE type = "crazyhour"') || ann_sqlerr(__FILE__, __LINE__);
                $cache->set('crazyhour', $cz['crazyhour'], 0);
                // log, shoutbot
                $text = 'w00t! It\'s [color=orange][b]Crazyhour[/b][/color] :w00t:';
                $text_parsed = 'w00t! It\'s <span style="font-weight:bold;color:orange;">Crazyhour</span> <img src="pic/smilies/w00t.gif" alt=":w00t:" />';
                ann_sql_query('INSERT LOW_PRIORITY INTO sitelog (added, txt) 
            VALUES(' . TIME_NOW . ', ' . ann_sqlesc($text_parsed) . ')') || ann_sqlerr(__FILE__, __LINE__);
                ann_sql_query('INSERT LOW_PRIORITY INTO shoutbox (userid, date, text, text_parsed) ' . 'VALUES (2, ' . TIME_NOW . ', ' . ann_sqlesc($text) . ', ' . ann_sqlesc($text_parsed) . ')') || ann_sqlerr(__FILE__, __LINE__);
                $cache->delete('shoutbox_');
            }
        }
        return true;
    } else return false;
}

function freeleech_announce() {
   global $cache, $TRINITY20;  
   if (($fl['countdown'] = $cache->get('freeleech_countdown')) === false) { // pot_of_gold
      ($fl['sql'] = ann_sql_query('SELECT var, amount FROM freeleech WHERE type = "countdown"')) || ann_sqlerr(__FILE__, __LINE__);
      $fl['countdown'] = array();   
      if ($fl['sql']->num_rows() !== 0)
         $fl['countdown'] = $fl['sql']->fetch_assoc();
      else {
         $fl['countdown']['var'] = 0;
         //$fl['countdown']['amount'] = strtotime('next Monday');  // timestamp sunday
         $fl['countdown']['amount'] = 43200;  // timestamp test
         ann_sql_query('UPDATE LOW_PRIORITY freeleech SET var = '.ann_sqlesc($fl['freeleech']['var']).', amount = '.ann_sqlesc($fl['countdown']['amount']).' '.
                   'WHERE type = "countdown"') || ann_sqlerr(__FILE__, __LINE__);
      }
      $cache->set('freeleech_countdown', $fl['countdown'], 0);              
   }

   if (($fl['countdown']['var'] !== 0) && (TIME_NOW > ($fl['countdown']['var']))) { // end of freeleech sunday
      if (($fc_lock = $cache->set('freeleech_countdown_lock', 1, 10)) !==  false) {
      $fl['countdown']['var'] = 0;
      //$fl['countdown']['amount'] = strtotime('next Monday');  // timestamp sunday
      $fl['countdown']['amount'] = 43200;  // timestamp test
      ann_sql_query('UPDATE LOW_PRIORITY freeleech SET var = '.ann_sqlesc($fl['countdown']['var']).', amount = '.ann_sqlesc($fl['countdown']['amount']).' '.
                  'WHERE type = "countdown"') || ann_sqlerr(__FILE__, __LINE__);
      $cache->update_row('freeleech_countdown', ['var' => $fl['countdown']['var'], 'amount' => $fl['countdown']['amount']], 0);
      }
      return false;
   }
   elseif (TIME_NOW > ($fl['countdown']['amount'])) {
      if ($fl['countdown']['var'] == 0 && ($cz_lock = $cache->set('crazyhour_lock', 1, 10)) !== false) {
          //$fl['countdown']['var'] = strtotime('next Monday');
          $fl['countdown']['var'] = 43200;
          //'.$ahead_by.'
          //$ahead_by = readable_time(($fl['countdown']['var'] - 86400) - $fl['countdown']['amount']);
          ann_sql_query('UPDATE LOW_PRIORITY freeleech SET var = '.ann_sqlesc($fl['countdown']['var']).' '.
                      'WHERE type = "countdown"') || ann_sqlerr(__FILE__, __LINE__);
          $cache->update_row('freeleech_countdown', ['var' => $fl['countdown']['var']], 0);
          $free_message = 'It will last for xx ending on Monday 12:00 am GMT.';
          $text         = '[color=#33CCCC][b]Freeleech Activated![/b][/color]'."\n".$free_message;
          $text_parsed  = '<span style="color:#33CCCC;font-weight:bold;">Freeleech Activated!</span>'."\n".$free_message;
          // log, shoutbot
          ann_sql_query('INSERT LOW_PRIORITY INTO sitelog (added, txt) '. 
                      'VALUES('.ann_sqlesc(TIME_NOW).', '.ann_sqlesc($text_parsed).')') || ann_sqlerr(__FILE__, __LINE__);
          ann_sql_query('INSERT LOW_PRIORITY INTO shoutbox (userid, date, text, text_parsed) '.
                      'VALUES (2, '.TIME_NOW.', '.ann_sqlesc($text).', '.ann_sqlesc($text_parsed).')') || ann_sqlerr(__FILE__, __LINE__);
          $cache->delete('shoutbox_');
      }
      return true;
   }
   else
      return false;
}

function get_user_from_torrent_pass($torrent_pass)
{
    global $cache, $TRINITY20;
    if (strlen($torrent_pass) != 32 || !bin2hex($torrent_pass)) return false;
    $key = 'user::torrent_pass:::' . $torrent_pass;
    if (($user = $cache->get($key)) === false) {
        $user_fields_ar_int = array(
            'id',
            'uploaded',
            'downloaded',
            'class',
            'free_switch',
            'downloadpos',
            'perms'
        );
        $user_fields_ar_str = array(
            'ip',
            'enabled',
            'highspeed',
            'hnrwarn',
            'parked'
        );
        $user_fields = implode(', ', array_merge($user_fields_ar_int, $user_fields_ar_str));
        ($user_query = ann_sql_query("SELECT " . $user_fields . " FROM users WHERE torrent_pass=" . ann_sqlesc($torrent_pass) . " AND enabled = 'yes'")) || ann_sqlerr(__FILE__, __LINE__);
        $user = $user_query->fetch_assoc();
        foreach ($user_fields_ar_int as $i) $user[$i] = (int)$user[$i];
        foreach ($user_fields_ar_str as $i) $user[$i] = $user[$i];
        $cache->set($key, $user, $TRINITY20['expires']['user_passkey']);
    } elseif (!$user) return false;
    return $user;
}
// get torrentfromhash by pdq
function get_torrent_from_hash($info_hash)
{
    global $cache, $TRINITY20;
    $key = 'torrent::hash:::' . md5($info_hash);
    $ttll = 21600; // 21600;
    if (($torrent = $cache->get($key)) === false) {
        ($res = ann_sql_query('SELECT id, category, banned, free, silver, vip, seeders, leechers, times_completed, seeders + leechers AS numpeers, added AS ts, visible FROM torrents WHERE info_hash = ' . ann_sqlesc($info_hash))) || ann_sqlerr(__FILE__, __LINE__);
        if ($res->num_rows) {
            $torrent = $res->fetch_assoc();
            $torrent['id'] = (int)$torrent['id'];
            $torrent['free'] = (int)$torrent['free'];
            $torrent['silver'] = (int)$torrent['silver'];
            $torrent['category'] = (int)$torrent['category'];
            $torrent['numpeers'] = (int)$torrent['numpeers'];
            $torrent['seeders'] = (int)$torrent['seeders'];
            $torrent['leechers'] = (int)$torrent['leechers'];
            $torrent['times_completed'] = (int)$torrent['times_completed'];
            $torrent['ts'] = (int)$torrent['ts'];
            $cache->set($key, $torrent, $ttll);
            $seed_key = 'torrents::seeds:::' . $torrent['id'];
            $leech_key = 'torrents::leechs:::' . $torrent['id'];
            $comp_key = 'torrents::comps:::' . $torrent['id'];
            $cache->set($seed_key, $torrent['seeders'], $ttll);
            $cache->set($leech_key, $torrent['leechers'], $ttll);
            $cache->set($comp_key, $torrent['times_completed'], $ttll);
        } else {
            $cache->set($key, 0, 86400);
            return false;
        }
    } elseif (!$torrent) return false;
    else {
        $seed_key = 'torrents::seeds:::' . $torrent['id'];
        $leech_key = 'torrents::leechs:::' . $torrent['id'];
        $comp_key = 'torrents::comps:::' . $torrent['id'];
        $torrent['seeders'] = $cache->get($seed_key);
        $torrent['leechers'] = $cache->get($leech_key);
        $torrent['times_completed'] = $cache->get($comp_key);
        if ($torrent['seeders'] === false || $torrent['leechers'] === false || $torrent['times_completed'] === false) {
            ($res = ann_sql_query('SELECT seeders, leechers, times_completed FROM torrents WHERE id = ' . ann_sqlesc($torrent['id']))) || ann_sqlerr(__FILE__, __LINE__);
            if ($res->num_rows) {
                $torrentq = $res->fetch_assoc();
                $torrent['seeders'] = (int)$torrentq['seeders'];
                $torrent['leechers'] = (int)$torrentq['leechers'];
                $torrent['times_completed'] = (int)$torrentq['times_completed'];
                $cache->set($seed_key, $torrent['seeders'], $ttll);
                $cache->set($leech_key, $torrent['leechers'], $ttll);
                $cache->set($comp_key, $torrent['times_completed'], $ttll);
            } else {
                $cache->delete($key);
                return false;
            }
        }
    }
    return $torrent;
}
// adjusttorrentpeers by pdq
function adjust_torrent_peers($id, $seeds = 0, $leechers = 0, $completed = 0)
{
    global $cache;
    if (!is_int($id) || $id < 1) return false;
    if (!$seeds && !$leechers && !$completed) return false;
    $adjust = 0;
    $seed_key = 'torrents::seeds:::' . $id;
    $leech_key = 'torrents::leechs:::' . $id;
    $comp_key = 'torrents::comps:::' . $id;
    if ($seeds > 0) $adjust+= (bool)$cache->increment($seed_key, $seeds);
    elseif ($seeds < 0) $adjust+= (bool)$cache->decrement($seed_key, -$seeds);
    if ($leechers > 0) $adjust+= (bool)$cache->increment($leech_key, $leechers);
    elseif ($leechers < 0) $adjust+= (bool)$cache->decrement($leech_key, -$leechers);
    if ($completed > 0) $adjust+= (bool)$cache->increment($comp_key, $completed);
    return (bool)$adjust;
}
// happyhour by putyn
function get_happy($torrentid, $userid)
{
    global $cache;
    $keys['happyhour'] = $userid . '_happy';
    if (($happy = $cache->get($keys['happyhour'])) === false) {
        ($res_happy = ann_sql_query("SELECT id, userid, torrentid, multiplier from happyhour where userid=" . ann_sqlesc($userid))) || ann_sqlerr(__FILE__, __LINE__);
        $happy = array();
        if ($res_happy->num_rows) {
            while ($rowhappy = $res_happy->fetch_assoc()) 
                $happy[$rowhappy['torrentid']] = $rowhappy['multiplier'];
        }
        $cache->set($userid . '_happy', $happy, 0);
    }
    if (!empty($happy) && isset($happy[$torrentid])) return $happy[$torrentid];
    return 0;
}
// freeslots by pdq
function get_slots($torrentid, $userid)
{
    global $cache;
    $ttl_slot = 86400;
    $torrent['freeslot'] = $torrent['doubleslot'] = 0;
    if (($slot = $cache->get('fllslot_' . $userid)) === false) {
        ($res_slots = ann_sql_query('SELECT * FROM freeslots WHERE userid = ' . ann_sqlesc($userid))) || ann_sqlerr(__FILE__, __LINE__);
        $slot = array();
        if ($res_slots->num_rows) {
            while ($rowslot = $res_slots->fetch_assoc()) $slot[] = $rowslot;
        }
        $cache->set('fllslot_' . $userid, $slot, $ttl_slot);
    }
    if (!empty($slot)) foreach ($slot as $sl) {
        if ($sl['torrentid'] == $torrentid && $sl['free'] == 'yes') $torrent['freeslot'] = 1;
        if ($sl['torrentid'] == $torrentid && $sl['doubleup'] == 'yes') $torrent['doubleslot'] = 1;
    }
    return $torrent;
}
//=== detect abnormal uploads
function auto_enter_abnormal_upload($userid, $rate, $upthis, $diff, $torrentid, $client, $realip, $last_up)
{
    ann_sql_query('INSERT LOW_PRIORITY INTO cheaters (added, userid, client, rate, beforeup, upthis, timediff, userip, torrentid) VALUES(' . ann_sqlesc(TIME_NOW) . ', ' . ann_sqlesc($userid) . ', ' . ann_sqlesc($client) . ', ' . ann_sqlesc($rate) . ', ' . ann_sqlesc($last_up) . ', ' . ann_sqlesc($upthis) . ', ' . ann_sqlesc($diff) . ', ' . ann_sqlesc($realip) . ', ' . ann_sqlesc($torrentid) . ')') || ann_sqlerr(__FILE__, __LINE__);
}
function err($msg)
{
    benc_resp(array(
        'failure reason' => array(
            'type' => 'string',
            'value' => $msg
        )
    ));
    exit();
}
function benc_resp($d)
{
    benc_resp_raw(benc(array(
        'type' => 'dictionary',
        'value' => $d
    )));
}
function gzip()
{
    if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler') {
        @ob_start('ob_gzhandler');
    }
}
function benc_resp_raw($x)
{
    header("Content-Type: text/plain");
    header("Pragma: no-cache");
    echo ($x);
}
function benc($obj)
{
    if (!is_array($obj) || !isset($obj["type"]) || !isset($obj["value"])) return;
    $c = $obj["value"];
    switch ($obj["type"]) {
    case "string":
        return benc_str($c);
    case "integer":
        return benc_int($c);
    case "list":
        return benc_list($c);
    case "dictionary":
        return benc_dict($c);
    default:
        return;
    }
}
function benc_str($s)
{
    return strlen($s) . ":$s";
}
function benc_int($i)
{
    return "i" . $i . "e";
}
function benc_list($a)
{
    $s = "l";
    foreach ($a as $e) {
        $s.= benc($e);
    }
    return $s . "e";
}
function benc_dict($d)
{
    $s = "d";
    $keys = array_keys($d);
    sort($keys);
    foreach ($keys as $k) {
        $v = $d[$k];
        $s.= benc_str($k);
        $s.= benc($v);
    }
    return $s . "e";
}
function hash_where($name, $hash)
{
    $shhash = preg_replace('/ *$/s', "", $hash);
    return "($name = " . ann_sqlesc($hash) . " OR $name = " . ann_sqlesc($shhash) . ")";
}
function portblacklisted($port)
{
    //=== new portblacklisted ....... ==> direct connect 411 ot 413,  bittorrent 6881 to 6889, kazaa 1214, gnutella 6346 to 6347, emule 4662, winmx 6699, IRC bot based trojans 65535
    $portblacklisted = array(
        411,
        412,
        413,
        6881,
        6882,
        6883,
        6884,
        6885,
        6886,
        6887,
        6889,
        1214,
        6346,
        6347,
        4662,
        6699,
        65535
    );
    return in_array($port, $portblacklisted);
}
function ann_sqlesc($x)
{
    global $mysqli;
    if (is_int($x)) return (int)$x;
    return sprintf('\'%s\'', $mysqli->real_escape_string($x));
}
?>
