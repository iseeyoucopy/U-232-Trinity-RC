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
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once CLASS_DIR . 'page_verify.php';
require_once (INCL_DIR . 'user_functions.php');
require_once INCL_DIR . 'bbcode_functions.php';
require_once INCL_DIR . 'html_functions.php';
require_once INCL_DIR . 'comment_functions.php';
require_once (INCL_DIR . 'function_onlinetime.php');
require_once (CLASS_DIR . 'class_user_options.php');
require_once (CLASS_DIR . 'class_user_options_2.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('userdetails'));
if (function_exists('parked')) parked();
$newpage = new page_verify();
$newpage->create('mdk1@@9');
$stdhead = array(
    /* include css **/
    'css' => array(
    )
);
$stdfoot = array(
    /* include js **/
    'js' => array(
		'popup',
        'java_klappe',
        'flush_torrents'
    )
);
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if (!is_valid_id($id)) stderr($lang['userdetails_error'], "{$lang['userdetails_bad_id']}");
if (($user = $cache->get('user' . $id)) === false) {
    $user_fields_ar_int = array(
        'id',
        'added',
        'last_login',
        'last_access',
        'curr_ann_last_check',
        'curr_ann_id',
        'stylesheet',
        'class',
        'override_class',
        'language',
        'av_w',
        'av_h',
        'country',
        'warned',
        'torrentsperpage',
        'topicsperpage',
        'postsperpage',
        'reputation',
        'dst_in_use',
        'auto_correct_dst',
        'chatpost',
        'smile_until',
        'vip_until',
        'freeslots',
        'free_switch',
        'invites',
        'invitedby',
        'uploadpos',
        'forumpost',
        'downloadpos',
        'immunity',
        'leechwarn',
        'last_browse',
        'sig_w',
        'sig_h',
        'forum_access',
        'hit_and_run_total',
        'donoruntil',
        'donated',
        'vipclass_before',
        'passhint',
        'avatarpos',
        'sendpmpos',
        'invitedate',
        'anonymous_until',
        'pirate',
        'king',
        'parked_until',
        'bjwins',
        'bjlosses',
        'last_access_numb',
        'onlinetime',
        'hits',
        'comments',
        'categorie_icon',
        'perms',
        'mood',
        'pms_per_page',
        'watched_user',
        'game_access',
        'reputation',
        'opt1',
        'opt2',
        'can_leech',
        'wait_time',
        'torrents_limit',
        'peers_limit',
        'torrent_pass_version',
    );
    $user_fields_ar_float = array(
        'time_offset',
        'total_donated'
    );
    $user_fields_ar_str = array(
        'username',
        'passhash',
        'secret',
        'torrent_pass',
        'email',
        'status',
        'editsecret',
        'privacy',
        'info',
        'acceptpms',
        'ip',
        'avatar',
        'title',
        'notifs',
        'enabled',
        'donor',
        'deletepms',
        'savepms',
        'show_shout',
        'show_staffshout',
        'shoutboxbg',
        'vip_added',
        'invite_rights',
        'anonymous',
        'disable_reason',
        'clear_new_tag_manually',
        'signatures',
        'signature',
        'highspeed',
        'hnrwarn',
        'parked',
        'hintanswer',
        'support',
        'supportfor',
        'invitees',
        'invite_on',
        'subscription_pm',
        'gender',
        'viewscloud',
        'tenpercent',
        'avatars',
        'offavatar',
        'hidecur',
        'signature_post',
        'forum_post',
        'avatar_rights',
        'offensive_avatar',
        'view_offensive_avatar',
		'show_email',
        'gotgift',
        'hash1',
        'suspended',
        'warn_reason',
        'birthday',
        'got_blocks',
        'pm_on_delete',
        'commentpm',
        'split',
        'browser',
        'got_moods',
        'show_pm_avatar',
        'watched_user_reason',
        'staff_notes',
        'where_is',
        'browse_icons',
        'forum_mod',
        'forums_mod',
        'altnick',
        'forum_sort',
        'pm_forced'
    );
    $user_fields = implode(', ', array_merge($user_fields_ar_int, $user_fields_ar_float, $user_fields_ar_str));
    $r1 = sql_query("SELECT " . $user_fields . " FROM users WHERE id=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    $user = $r1->fetch_assoc() or stderr($lang['userdetails_error'], "{$lang['userdetails_no_user']}");
    foreach ($user_fields_ar_int as $i) $user[$i] = (int)$user[$i];
    foreach ($user_fields_ar_float as $i) $user[$i] = (float)$user[$i];
    foreach ($user_fields_ar_str as $i) $user[$i] = $user[$i];
    $cache->set('user' . $id, $user, $TRINITY20['expires']['user_cache']);
}
if ($user["status"] == "pending") 
	stderr($lang['userdetails_error'], $lang['userdetails_still_pending']);
// user stats
$What_Cache = (XBT_TRACKER == true ? 'user_stats_xbt_' : 'user_stats_');
if (($user_stats = $cache->get($What_Cache.$id)) === false) {
    $What_Expire = (XBT_TRACKER == true ? $TRINITY20['expires']['user_stats_xbt'] : $TRINITY20['expires']['user_stats']);
    $stats_fields_ar_int = array(
            'uploaded',
            'downloaded'
        );
        $stats_fields_ar_float = array(
            'seedbonus'
        );
        $stats_fields_ar_str = array(
            'modcomment',
            'bonuscomment'
        );
        $stats_fields = implode(', ', array_merge($stats_fields_ar_int, $stats_fields_ar_float, $stats_fields_ar_str));
    $sql_1 = sql_query('SELECT ' . $stats_fields . ' FROM users WHERE id= ' . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    $user_stats = $sql_1->fetch_assoc();
    foreach ($stats_fields_ar_int as $i) $user_stats[$i] = (int)$user_stats[$i];
    foreach ($stats_fields_ar_float as $i) $user_stats[$i] = (float)$user_stats[$i];
    foreach ($stats_fields_ar_str as $i) $user_stats[$i] = $user_stats[$i];
    $cache->set($What_Cache.$id, $user_stats, $What_Expire); // 5 mins
}
if (($user_status = $cache->get('user_status_' . $id)) === false) {
    $sql_2 = sql_query('SELECT * FROM ustatus WHERE userid = ' . sqlesc($id));
    if (mysqli_num_rows($sql_2)) $user_status = $sql_2->fetch_assoc();
    else $user_status = array(
        'last_status' => '',
        'last_update' => 0,
        'archive' => ''
    );
    $cache->set('user_status_' . $id, $user_status, $TRINITY20['expires']['user_status']); // 30 days
    
}
//=== delete H&R
if (isset($_GET['delete_hit_and_run']) && $CURUSER['class'] >= UC_STAFF) {
    $delete_me = isset($_GET['delete_hit_and_run']) ? intval($_GET['delete_hit_and_run']) : 0;
    if (!is_valid_id($delete_me)) stderr($lang['userdetails_error'], $lang['userdetails_bad_id']);
    if(XBT_TRACKER === false) {
    sql_query('UPDATE snatched SET hit_and_run = \'0\', mark_of_cain = \'no\' WHERE id = ' . sqlesc($delete_me)) or sqlerr(__FILE__, __LINE__);
    } else {
    sql_query('UPDATE xbt_peers SET hit_and_run = \'0\', mark_of_cain = \'no\' WHERE fid = ' . sqlesc($delete_me)) or sqlerr(__FILE__, __LINE__);
    }
    if (@mysqli_affected_rows($GLOBALS["___mysqli_ston"]) === 0) {
        stderr($lang['userdetails_error'], $lang['userdetails_notdeleted']);
    }
    header('Location: ?id=' . $id . '&completed=1');
    die();
}
if ($user['ip'] && ($CURUSER['class'] >= UC_STAFF || $user['id'] == $CURUSER['id'])) {
    $dom = @gethostbyaddr($user['ip']);
    $addr = ($dom == $user['ip'] || @gethostbyname($dom) != $user['ip']) ? $user['ip'] : $user['ip'] . ' (' . $dom . ')';
}
/* #$^$&%$&@ invincible! NO IP LOGGING..pdq **/
if ((($user['class'] == UC_MAX OR $user['id'] == $CURUSER['id']) || ($user['class'] < UC_MAX) && $CURUSER['class'] == UC_MAX) && isset($_GET['invincible'])) {
    require_once (INCL_DIR . 'invincible.php');
    if ($_GET['invincible'] == 'yes') 
		$HTMLOUT.= "". invincible($id). "";
    elseif ($_GET['invincible'] == 'remove_bypass') 
		$HTMLOUT.= invincible($id, true, false);
    else $HTMLOUT.= invincible($id, false);
} // End

/* #$^$&%$&@ stealth!..pdq **/
if ((($user['class'] >= UC_STAFF OR $user['id'] == $CURUSER['id']) || ($user['class'] < UC_STAFF) && $CURUSER['class'] >= UC_STAFF) && isset($_GET['stealth'])) {
    require_once (INCL_DIR . 'stealth.php');
    if ($_GET['stealth'] == 'yes') $HTMLOUT.= stealth($id);
    elseif ($_GET['stealth'] == 'no') $HTMLOUT.= stealth($id, false);
} // End
//==country by pdq
function countries()
{
    global $cache, $TRINITY20;
    if (($ret = $cache->get('countries::arr')) === false) {
        $res = sql_query("SELECT id, name, flagpic FROM countries ORDER BY name ASC") or sqlerr(__FILE__, __LINE__);
        while ($row = $res->fetch_assoc()) $ret[] = $row;
        $cache->set('countries::arr', $ret, $TRINITY20['expires']['user_flag']);
    }
    return $ret;
}
$country = '';
$countries = countries();
foreach ($countries as $cntry) if ($cntry['id'] == $user['country']) {
    $country = "<img src=\"{$TRINITY20['pic_base_url']}flag/{$cntry['flagpic']}\" alt=\"" . htmlsafechars($cntry['name']) . "\" style='margin-left: 8pt'>";
    break;
}
//==userhits update by pdq
if (!(isset($_GET["hit"])) && $CURUSER["id"] <> $user["id"]) {
    $res = sql_query("SELECT added FROM userhits WHERE userid =" . sqlesc($CURUSER['id']) . " AND hitid = " . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
    $row = mysqli_fetch_row($res);
	$row = isset($row) ? $row : '1';
    if (!($row[0]  > TIME_NOW - 3600)) {
        $hitnumber = $user['hits'] + 1;
        sql_query("UPDATE users SET hits = hits + 1 WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
        // do update hits userdetails cache
        $update['user_hits'] = ($user['hits'] + 1);
        $cache->update_row($keys['my_userid'] . $id, [
            'hits' => $update['user_hits']
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row('user' . $id, [
            'hits' => $update['user_hits']
        ], $TRINITY20['expires']['user_cache']);
        sql_query("INSERT INTO userhits (userid, hitid, number, added) VALUES(" . sqlesc($CURUSER['id']) . ", " . sqlesc($id) . ", " . sqlesc($hitnumber) . ", " . sqlesc(TIME_NOW) . ")") or sqlerr(__FILE__, __LINE__);
    }
}
//== Show PM Button - updated 2020 by iseeyoucopy
if ($CURUSER["id"] != $user["id"]) 
	{
	if ($CURUSER['class'] >= UC_STAFF) 
	$showpmbutton = 1;
	}
elseif ($user["acceptpms"] == "yes") {
    $r = sql_query("SELECT id FROM blocks WHERE userid=" . sqlesc($user['id']) . " AND blockid=" . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
    $showpmbutton = (mysqli_num_rows($r) == 1 ? 0 : 1);
} elseif ($user["acceptpms"] == "friends") {
    $r = sql_query("SELECT id FROM friends WHERE userid=" . sqlesc($user['id']) . " AND friendid=" . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
    $showpmbutton = (mysqli_num_rows($r) == 1 ? 1 : 0);
}
//== Add or Remove Friends - updated 2020 by iseeyoucopy
if (($friends = $cache->get('Friends_' . $id)) === false) {
	$r3 = sql_query("SELECT id FROM friends WHERE userid=" . sqlesc($CURUSER['id']) . " AND friendid=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
	$friends = mysqli_num_rows($r3);
	$cache->set('Friends_' . $id, $friends, 10);
}
if (($blocks = $cache->get('Blocks_' . $id)) === false) {
	$r4 = sql_query("SELECT id FROM blocks WHERE userid=" . sqlesc($CURUSER['id']) . " AND blockid=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
	$blocks = mysqli_num_rows($r4);
	$cache->set('Blocks_' . $id, $blocks, 10);
}
//== Join date 
$joindate = $user["added"];
if ($user['added'] == 0) 
	$joindate = "{$lang['userdetails_na']}";
else 
	$joindate = get_date($user['added'], '');

//==Last Seen 
	$lastseen = $user["last_access"];
	if ($lastseen == 0) 
		$lastseen = "{$lang['userdetails_never']}";
	else
		$lastseen = get_date($user['last_access'], '', 0, 1);
if (($shit_list = $cache->get('shit_list_' . $id)) === false) {
	$check_if_theyre_shitty = sql_query("SELECT suspect FROM shit_list WHERE userid=" . sqlesc($CURUSER['id']) . " AND suspect=" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
	list($shit_list) = mysqli_fetch_row($check_if_theyre_shitty);
	$cache->set('shit_list_' . $id, $shit_list, $TRINITY20['expires']['shit_list']);
	}
$HTMLOUT = $perms = $stealth = $suspended = $watched_user = '';
if (($user['anonymous'] == 'yes') && ($CURUSER['class'] < UC_STAFF && $user["id"] != $CURUSER["id"])) {
	
}
//== Start Suspended ==//
if ($CURUSER["id"] <> $user["id"] && $CURUSER['class'] >= UC_STAFF) 
	$suspended.= ($user['suspended'] == 'yes' ? '<i class="fas fa-exclamation-triangle"></i>'.$lang['userdetails_usersuspended'].'</b><i class="fas fa-exclamation-triangle"></i>' : '');
//== End Suspended ==//
$where_is_now = $user['where_is'] ?? '';
//== Avatar ==//
//$user_avatar = $user['avatar'] ? "<img class='img-polaroid' src='" . htmlsafechars($user["avatar"]) . "' width='42' height='42'>" : "<img class='img-polaroid' src='{$TRINITY20['pic_base_url']}forumicons/default_avatar.gif' width='42' height='42'>";
$perms.= ($CURUSER['class'] >= UC_STAFF ? (($user['perms'] & bt_options::PERMS_NO_IP) ? '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/super.gif" alt="'.$lang['userdetails_invincible'].'"  title="'.$lang['userdetails_invincible'].'">' : '') : '');
$stealth.= ($CURUSER['class'] >= UC_STAFF ? (($user['perms'] & bt_options::PERMS_STEALTH) ? '&nbsp;&nbsp;<img src="' . $TRINITY20['pic_base_url'] . 'smilies/ninja.gif" alt="'.$lang['userdetails_stelth'].'"  title="'.$lang['userdetails_stelth'].'">' : '') : '');
$enabled = $user["enabled"] == 'yes';
if ($user['opt1'] & user_options::PARKED) 
	$HTMLOUT.= "<p>{$lang['userdetails_parked']}</p>";
if (!$enabled) 
	$HTMLOUT.= "<p>{$lang['userdetails_disabled']}</p>";
$HTMLOUT .= ((isset($_GET['sn']) || isset($_GET['wu'])) ? '<div class="callout alert-callout-border success"><strong>'.$lang['userdetails_updated'].'</strong></div>' : '');
$HTMLOUT .= "<div class='callout'>
	" . format_username($user, true) . "" . ($user['last_access'] > TIME_NOW - 180 ? "<span class='label success float-right'>Online</span>" : "<span class='label alert float-right'>Offline</span>") . "<a data-toggle='userdetails-info'><i class='fas fa-info-circle'></i></a>
	<p class='float-right'>". $where_is_now ."</p>
	</div>
<div class='dropdown-pane' id='userdetails-info' data-dropdown data-hover='true' data-hover-pane='true'>
	<p><strong>{$lang['userdetails_class']}</strong> : " . get_user_class_name($user["class"]) . "<img src='" . get_user_class_image($user["class"]) . "' alt='" . get_user_class_name($user["class"]) . "' title='" . get_user_class_name($user["class"]) . "'></p>
	<p><strong>{$lang['userdetails_joined']}</strong> : {$joindate}</p>
	<p><strong>{$lang['userdetails_seen']}</strong> : {$lastseen}</p>
	<p>$suspended</p>
	". ($perms ? '<p>'.$lang['userdetails_is_invincible'] . $perms . '</p>' : '') ."
	". ($stealth ? '<p>'.$lang['userdetails_in_stelth'] . $stealth . '</p>' : '') ." 
	<p>Country : $country</p>
</div>";
$HTMLOUT .= "<div class='dropdown-pane' id='userdetails-actions' data-dropdown data-hover='true' data-hover-pane='true'>";
$HTMLOUT.= isset($showpmbutton) ? "<a href='pm_system.php?action=send_message&receiver=" . (int)$user["id"] . "'><dd><i class='fas fa-comment-alt'></i>{$lang['userdetails_msg_btn']}</dd></a>" : ''; 
$HTMLOUT.= ($CURUSER["id"] <> $user["id"] & $friends > 0) ? "<a href='friends.php?action=delete&amp;type=friend&amp;targetid=$id'><dd><i class='fas fa-user-times'></i>{$lang['userdetails_remove_friends']}</dd></a>" : "<a href='friends.php?action=add&amp;type=friend&amp;targetid=$id'><dd><i class='fas fa-user-plus'></i>{$lang['userdetails_add_friends']}</dd></a>";
$HTMLOUT.= ($CURUSER["id"] <> $user["id"] & $blocks > 0) ? "<a href='friends.php?action=delete&amp;type=block&amp;targetid=$id'><dd><i class='fas fa-user-times'></i>{$lang['userdetails_remove_blocks']}</dd></a>" : "<a href='friends.php?action=add&amp;type=block&amp;targetid=$id'><dd><i class='fas fa-user-lock'></i>{$lang['userdetails_add_blocks']}</dd></a>";
//=== Link to member contact mail - updated 2020 by iseeyoucopy
$HTMLOUT.= ($CURUSER['class'] >= UC_STAFF || $user['show_email'] === 'yes') ? '<a href="mailto:' . /*decrypt_email(*/htmlsafechars($user['email'])/*)*/ . '" target="_blank"><dd><i class="fas fa-envelope"></i>' . $lang['userdetails_send_email'] . '</dd></a>' : '';
//== Link Report User - updated 2020 by iseeyoucopy
$HTMLOUT.= "<a href='report.php?type=User&amp;id=" . (int)$user["id"] . "'><dd><i class='fas fa-comment-alt'></i>{$lang['userdetails_report']}</dd></a>"; 
//== Link to usercp  - updated 2020 by iseeyoucopy
$HTMLOUT.= "".($CURUSER['id'] == $user['id'] ? "<a href='{$TRINITY20['baseurl']}/usercp.php?action=default'><dd>{$lang['userdetails_editself']}</dd></a>" : "");
 $HTMLOUT.= ($CURUSER['id'] == $user['id'] ? "<a href='{$TRINITY20['baseurl']}/view_announce_history.php'><dd>{$lang['userdetails_announcements']}</dd></a>" : "") . "";
$HTMLOUT.= ($CURUSER['id'] != $user['id']) ? "<a href='{$TRINITY20['baseurl']}/bookmarks.php?action=viewsharemarks&amp;id=$id'><dd><i class='fas fa-bookmark'></i>{$lang['userdetails_sharemarks']}</dd></a>" : "";
//== links to make invincible method 1 (PERMS_NO_IP/ no log ip) - updated 2020 by iseeyoucopy
if ($CURUSER['class'] == UC_MAX)	
$HTMLOUT.= ($user['perms'] & bt_options::PERMS_NO_IP)  ? ' <a href="userdetails.php?id=' . $id . '&amp;invincible=no"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_invincible_remove'].'</dd></a>' : '<a href="userdetails.php?id=' . $id . '&amp;invincible=yes"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_make_invincible'].'</dd></a>';
//== links to make invincible method 2 (PERMS_BYPASS_BAN/cannot be banned) - updated 2020 by iseeyoucopy
if ($CURUSER['class'] == UC_MAX)
$HTMLOUT.= ($user['perms'] & bt_options::PERMS_BYPASS_BAN) ? '<a href="userdetails.php?id=' . $id . '&amp;invincible=remove_bypass"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_remove_bypass'].'</dd></a>' : '<a href="userdetails.php?id=' . $id . '&amp;invincible=yes"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_add_bypass'].'</dd></a>';
//== Links for Stealth mode - updated 2020 by iseeyoucopy
if ($CURUSER['class'] >= UC_STAFF)
$HTMLOUT.= ($user['perms'] & bt_options::PERMS_STEALTH) ? '<a href="userdetails.php?id=' . $id . '&amp;stealth=no"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_stelth_disable'].'</dd></a>' : '<a href="userdetails.php?id=' . $id . '&amp;stealth=yes"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_stelth_enable'].'</dd></a>';
$HTMLOUT.= ($CURUSER['class'] >= UC_STAFF && $CURUSER["id"] <> $user["id"]) ? "<a href='staffpanel.php?tool=shit_list&amp;action=shit_list&amp;action2=new&amp;shit_list_id=" . $id . "&amp;return_to=userdetails.php?id=" . $id . "'><i class='fas fa-poop'></i>{$lang['userdetails_shit3']}</a>" : '';
$HTMLOUT .= ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) ? '<a data-open="watched-user"><dd>' . $lang['userdetails_watched'] . '</dd></a>' : '';
$HTMLOUT .= ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) ? '<a data-open="staff-notes"><dd>' . $lang['userdetails_staffnotes'] . '</dd></a>' : '';
$HTMLOUT .= ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) ? '<a data-open="system-comments"><dd>'. $lang['userdetails_system'] .'</dd></a>' : '';
 $HTMLOUT .= " </div>";
// == Donor count down - updated 2020 by iseeyoucopy
if ($user["donor"] && $CURUSER["id"] == $user["id"] || $CURUSER["class"] == UC_SYSOP) {
    $donoruntil = htmlsafechars($user['donoruntil']);
    if ($donoruntil == '0') 
		$HTMLOUT.= "";
    else {
        $HTMLOUT.= "<b>{$lang['userdetails_donatedtill']} - " . get_date($user['donoruntil'], 'DATE') . "";
        $HTMLOUT.= " [ " . mkprettytime($donoruntil - TIME_NOW) . " ] {$lang['userdetails_togo']}...</b>{$lang['userdetails_renew']}<a class='altlink' href='{$TRINITY20['baseurl']}/donate.php'>{$lang['userdetails_here']}</a>";
    }
}
$HTMLOUT.= ($CURUSER['class'] >= UC_STAFF & $shit_list > 0) ? "<dd><b>{$lang['userdetails_shit1']} <a href='staffpanel.php?tool=shit_list&amp;action=shit_list'>{$lang['userdetails_here']}</a> {$lang['userdetails_shit2']}&nbsp;</b></dd>" : "";
    //== watched user stuff ==//
if ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF)
$HTMLOUT.= ($user['watched_user'] == 0) ? '' : '<p>'.$lang['userdetails_watchlist1'].' <a href="staffpanel.php?tool=watched_users" >'.$lang['userdetails_watchlist2'].'</a></p>';
//== make sure people can't see their own naughty history by snuggles ==//
if (($CURUSER['id'] !== $user['id']) && ($CURUSER['class'] >= UC_STAFF)) {
    //== watched user stuff ==//
    $HTMLOUT .= '<div class="reveal" id="watched-user" data-reveal>
		<div class="callout alert-callout-border warning">
			<p>'.($user['watched_user'] > 0 ? $lang['userdetails_watched_since'] . get_date($user['watched_user'], '') : $lang['userdetails_not_watched']) . '</p>
			<p style="color:red;font-size: small;">Note* '. $lang['userdetails_watch_change1']. '</p>
			<p style="color:red;font-size: small;">' .$lang['userdetails_watch_change2'].'</p>
		</div>
		<form method="post" action="member_input.php" name="notes_for_staff">
			<fieldset class="fieldset">
				<input name="id" type="hidden" value="' . $id . '">
				<input type="hidden" value="watched_user" name="action">
				<legend>'.$lang['userdetails_add_watch'].'</legend>
				<input type="radio" value="yes" name="add_to_watched_users"' . ($user['watched_user'] > 0 ? ' checked="checked"' : '') . ' id="watchedYes1"> 
				<label for="watchedYes1">'.$lang['userdetails_yes1'].'</label>
				<input type="radio" value="no" name="add_to_watched_users"' . ($user['watched_user'] == 0 ? ' checked="checked"' : '') . ' id="watchedNo1">
				<label for="watchedYes1">'.$lang['userdetails_no1'].'</label>
			</fieldset>
			<textarea id="watched_reason" cols="50" rows="6" name="watched_reason">' . htmlsafechars($user['watched_user_reason']) . '</textarea>
			<input id="watched_user_button" type="submit" value="'.$lang['userdetails_submit'].'" class="tiny button" name="watched_user_button">
		</form>
		<button class="close-button" data-close aria-label="Close modal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>';
    //== Staff Notes ==//
    $HTMLOUT.= '<div class="reveal" id="staff-notes" data-reveal>
			<form method="post" action="member_input.php" name="notes_for_staff">
				<input name="id" type="hidden" value="' . (int)$user['id'] . '">
				<input type="hidden" value="staff_notes" name="action" id="action">
				<textarea id="new_staff_note" cols="50" rows="6" name="new_staff_note">' . htmlsafechars($user['staff_notes']) . '</textarea>
				<input id="staff_notes_button" type="submit" value="'.$lang['userdetails_submit'].'" class="small button" name="staff_notes_button"/>
			</form>
			<button class="close-button" data-close aria-label="Close modal" type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>';
    //== System comments ==//
    if (!empty($user_stats['modcomment']))
		$HTMLOUT.= '<div class="reveal" id="system-comments" data-reveal>
		 ' . format_comment($user_stats['modcomment']) . '
		 </div>';
}
$HTMLOUT.= "<ul class='tabs' data-tabs id='userdetails-tabs{$user['id']}'>
	<li class='tabs-title is-active'><a href='#torrents' aria-selected='true'>{$lang['userdetails_torrents']}</a></li>
    <li class='tabs-title'><a href='#general'>{$lang['userdetails_general']}</a></li>
    <li class='tabs-title'><a href='#activity'>{$lang['userdetails_activity']}</a></li>
    <li class='tabs-title'><a href='#comments'>{$lang['userdetails_usercomments']}</a></li>
	<li class='tabs-title'><a href='#general' data-toggle='userdetails-actions'>Actions</a></li>
	" . ($CURUSER['class'] >= UC_STAFF && $user["class"] < $CURUSER['class'] ? '<li class="tabs-title"><a href="#general" data-toggle="edit_userModal">'.$lang['userdetails_edit_user'].'</a></li>' : '') . "</ul>";
$HTMLOUT.= "<div class='tabs-content' data-tabs-content='userdetails-tabs{$user['id']}'>";
$HTMLOUT.= "<div class='tabs-panel is-active' id='torrents'><table class='striped'>";
if (curuser::$blocks['userdetails_page'] & block_userdetails::FLUSH && $BLOCKS['userdetails_flush_on']) {
    require_once (BLOCK_DIR . 'userdetails/flush.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TRAFFIC && $BLOCKS['userdetails_traffic_on']) {
    require_once (BLOCK_DIR . 'userdetails/traffic.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHARE_RATIO && $BLOCKS['userdetails_share_ratio_on']) {
    require_once (BLOCK_DIR . 'userdetails/shareratio.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SEEDTIME_RATIO && $BLOCKS['userdetails_seedtime_ratio_on']) {
    require_once (BLOCK_DIR . 'userdetails/seedtimeratio.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK && $BLOCKS['userdetails_torrents_block_on']) {
    require_once (BLOCK_DIR . 'userdetails/torrents_block.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::COMPLETED && $BLOCKS['userdetails_completed_on']/* && XBT_TRACKER == false*/) {
    require_once (BLOCK_DIR . 'userdetails/completed.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::CONNECTABLE_PORT && $BLOCKS['userdetails_connectable_port_on']) {
    require_once (BLOCK_DIR . 'userdetails/connectable.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK && $BLOCKS['userdetails_torrents_block_on']) {
    require_once (BLOCK_DIR . 'userdetails/snatched_block.php');
}
$HTMLOUT.= "</table></div>";
$HTMLOUT .= "<div class='tabs-panel' id='general'><table class='striped'>";
//==Begin blocks
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHOWFRIENDS && $BLOCKS['userdetails_showfriends_on']){
require_once (BLOCK_DIR . 'userdetails/showfriends.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::LOGIN_LINK && $BLOCKS['userdetails_login_link_on']) {
    require_once (BLOCK_DIR . 'userdetails/loginlink.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::ONLINETIME && $BLOCKS['userdetails_online_time_on']) {
    require_once (BLOCK_DIR . 'userdetails/onlinetime.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::BROWSER && $BLOCKS['userdetails_browser_on']) {
    require_once (BLOCK_DIR . 'userdetails/browser.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::BIRTHDAY && $BLOCKS['userdetails_birthday_on']) {
    require_once (BLOCK_DIR . 'userdetails/birthday.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::IPHISTORY && $BLOCKS['userdetails_iphistory_on']) {
    require_once (BLOCK_DIR . 'userdetails/iphistory.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::GENDER && $BLOCKS['userdetails_gender_on']) {
    require_once (BLOCK_DIR . 'userdetails/gender.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERINFO && $BLOCKS['userdetails_userinfo_on']) {
    require_once (BLOCK_DIR . 'userdetails/userinfo.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERSTATUS && $BLOCKS['userdetails_user_status_on']) {
    require_once (BLOCK_DIR . 'userdetails/userstatus.php');
}
$HTMLOUT.= "</table></div>";

$HTMLOUT.= "<div class='tabs-panel' id='activity'><table class='striped'>";
if ($TRINITY20['mood_sys_on']) {
$moodname = (isset($mood['name'][$user['mood']]) ? htmlsafechars($mood['name'][$user['mood']]) : $lang['userdetails_neutral']);
$moodpic = (isset($mood['image'][$user['mood']]) ? htmlsafechars($mood['image'][$user['mood']]) : 'noexpression.gif');
$HTMLOUT.= '<tr><td class="rowhead">'.$lang['userdetails_currentmood'].'</td><td align="left"><span class="tool">
       <a href="javascript:;" onclick="PopUp(\'usermood.php\',\''.$lang['userdetails_mood'].'\',530,500,1,1);">
       <img src="' . $TRINITY20['pic_base_url'] . 'smilies/' . $moodpic . '" alt="' . $moodname . '" border="0">
       <span class="tip">' . htmlsafechars($user['username']) . ' ' . $moodname . ' !</span></a></span></td></tr>'; 
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SEEDBONUS && $BLOCKS['userdetails_seedbonus_on']) {
    require_once (BLOCK_DIR . 'userdetails/seedbonus.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::REPUTATION && $BLOCKS['userdetails_reputation_on'] && $TRINITY20['rep_sys_on']) {
    require_once (BLOCK_DIR . 'userdetails/reputation.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::PROFILE_HITS && $BLOCKS['userdetails_profile_hits_on']) {
    require_once (BLOCK_DIR . 'userdetails/userhits.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::FREESTUFFS && $BLOCKS['userdetails_freestuffs_on'] && XBT_TRACKER == false) {
    require_once (BLOCK_DIR . 'userdetails/freestuffs.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::INVITEDBY && $BLOCKS['userdetails_invitedby_on']) {
    require_once (BLOCK_DIR . 'userdetails/invitedby.php');
}
$HTMLOUT.= "</table></div>";
$HTMLOUT.= "<div class='tabs-panel' id='comments'>";

if (curuser::$blocks['userdetails_page'] & block_userdetails::USERCOMMENTS && $BLOCKS['userdetails_user_comments_on']) {
    require_once (BLOCK_DIR . 'userdetails/usercomments.php');
}
$HTMLOUT.= "</div></div>";

//==start edit userdetails
$HTMLOUT.= "<div class='reveal' id='edit_userModal' data-reveal>
 <button class='close-button' data-close aria-label='Close reveal' type='button'>
    <span aria-hidden='true'>&times;</span>
  </button>";
if ($CURUSER['class'] >= UC_STAFF && $user["class"] < $CURUSER['class']) {
    $HTMLOUT.= "<form method='post' action='staffpanel.php?tool=modtask'>";
    require_once CLASS_DIR . 'validator.php';
    $HTMLOUT.= validatorForm('ModTask_' . $user['id']);
    $postkey = PostKey(array(
        $user['id'],
        $CURUSER['id']
    ));
    $HTMLOUT.= "<input type='hidden' name='action' value='edituser'>";
    $HTMLOUT.= "<input type='hidden' name='userid' value='$id'>";
    $HTMLOUT.= "<input type='hidden' name='postkey' value='$postkey'>";
    $HTMLOUT.= "<input type='hidden' name='returnto' value='userdetails.php?id=$id'>";
    $HTMLOUT.= "<table class='table table-bordered'>";
	$HTMLOUT.= "<input placeholder='{$lang['userdetails_title']}' type='text'name='title' value='" . htmlsafechars($user['title']) . "'>";
    $avatar = htmlsafechars($user["avatar"]);
    $HTMLOUT.= "<input placeholder='{$lang['userdetails_avatar_url']}' type='text' name='avatar' value='$avatar'>
	<textarea placeholder='{$lang['userdetails_signature']}' cols='60' rows='2' name='signature'>" . htmlsafechars($user['signature']) . "</textarea>
	{$lang['userdetails_signature_rights']}
    <input name='signature_post' value='yes' type='radio'".($user['signature_post'] == "yes" ? "    checked='checked'" : "").">{$lang['userdetails_yes']}
    <input name='signature_post' value='no' type='radio'".($user['signature_post'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_disable_signature']}";
    

//== we do not want mods to be able to change user classes or amount donated...
    // === Donor mod time based by snuggles

    if ($CURUSER["class"] == UC_MAX) {
        $donor = $user["donor"] == "yes";
        $HTMLOUT.= "<b>{$lang['userdetails_donor']}</b>";
        if ($donor) {
            $donoruntil = (int)$user['donoruntil'];
            if ($donoruntil == '0') $HTMLOUT.= $lang['userdetails_arbitrary'];
            else {
                $HTMLOUT.= "<b>" . $lang['userdetails_donor2'] . "</b> " . get_date($user['donoruntil'], 'DATE') . " ";
                $HTMLOUT.= " [ " . mkprettytime($donoruntil - TIME_NOW) . " ] {$lang['userdetails_togo']}";
            }
        } else {
            $HTMLOUT.= "{$lang['userdetails_dfor']}<select name='donorlength'><option value='0'>------</option><option value='4'>1 {$lang['userdetails_month']}</option>" . "<option value='6'>6 {$lang['userdetails_weeks']}</option><option value='8'>2 {$lang['userdetails_months']}</option><option value='10'>10 {$lang['userdetails_weeks']}</option>" . "<option value='12'>3 {$lang['userdetails_months']}</option><option value='255'>{$lang['userdetails_unlimited']}</option></select>";
        }
        $HTMLOUT.= "<b>{$lang['userdetails_cdonation']}</b>
		<input placeholder='{$lang['userdetails_cdonation']}' type='text' name='donated' value=\"" . htmlsafechars($user["donated"]) . "\">" . "<b>{$lang['userdetails_tdonations']}</b>" . htmlsafechars($user["total_donated"]) . "";
        if ($donor) {
            $HTMLOUT.= "<div class='col-sm-2'><b>{$lang['userdetails_adonor']}</b><select name='donorlengthadd'><option value='0'>------</option><option value='4'>1 {$lang['userdetails_month']}</option>" . "<option value='6'>6 {$lang['userdetails_weeks']}</option><option value='8'>2 {$lang['userdetails_months']}</option><option value='10'>10 {$lang['userdetails_weeks']}</option>" . "<option value='12'>3 {$lang['userdetails_months']}</option><option value='255'>{$lang['userdetails_unlimited']}</option></select></div>";
            
	    $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_rdonor']}</b><input name='donor' value='no' type='checkbox'> [ {$lang['userdetails_bad']} ]</div>";
        }
        $HTMLOUT.= "<br>";
    }
    // ====End
    if ($CURUSER['class'] == UC_STAFF && $user["class"] > UC_VIP) $HTMLOUT.= "<inputtype='hidden' name='class' value='{$user['class']}'>";
    else {
        $HTMLOUT.= "<div class='col-sm-1 text-right'>Class</div><div class='col-sm-4'><select name='class'>";
        if ($CURUSER['class'] == UC_STAFF) $maxclass = UC_VIP;
        else $maxclass = $CURUSER['class'] - 1;
        for ($i = 0; $i <= $maxclass; ++$i) $HTMLOUT.= "<option value='$i'" . ($user["class"] == $i ? " selected='selected'" : "") . ">" . get_user_class_name($i) . "</option>";
        $HTMLOUT.= "</select></div>";
    }
    $supportfor = htmlsafechars($user["supportfor"]);
 
$HTMLOUT.= "<br><div class='row'><div class='col-sm-6'><textarea placeholder='{$lang['userdetails_supportfor']}' cols='60' rows='2' name='supportfor'>{$supportfor}</textarea></div>";

 $HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_support']}</div><div class='col-sm-3'><input type='radio' name='support' value='yes'".($user["support"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}<input type='radio' name='support' value='no'".($user["support"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</div></div>";


    $modcomment = htmlsafechars($user_stats["modcomment"]);
    if ($CURUSER["class"] < UC_SYSOP) {
        $HTMLOUT.= "<br><div class='col-sm-4'><p>{$lang['userdetails_comment']}</p><textarea class='shrink' placeholder='{$lang['userdetails_comment']}' cols='40' rows='6' name='modcomment' readonly='readonly'>$modcomment</textarea></div>";
    } else {
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-4'><p>{$lang['userdetails_comment']}</p><textarea class='shrink' placeholder='{$lang['userdetails_comment']}' cols='40' rows='6' name='modcomment'>$modcomment</textarea></div>";
    }
    $HTMLOUT.= "<div class='col-sm-4'><p>{$lang['userdetails_add_comment']}</p><textarea class='shrink' placeholder='{$lang['userdetails_add_comment']}' cols='40' rows='6' name='addcomment'></textarea></div>";
    //=== bonus comment
    $bonuscomment = htmlsafechars($user_stats["bonuscomment"]);
    $HTMLOUT.= "<div class='col-sm-4'><p>{$lang['userdetails_bonus_comment']}</p><textarea class='shrink' placeholder='{$lang['userdetails_bonus_comment']}' cols='40' rows='6' name='bonuscomment' readonly='readonly' style='background:purple;color:yellow;'>$bonuscomment</textarea></div></div>";
    //==end
   
 $HTMLOUT.= "<br><div class='col-sm-1'>{$lang['userdetails_enabled']}</div><div class='col-sm-2'><input name='enabled' value='yes' type='radio'" . ($enabled ? " checked='checked'" : "") . ">{$lang['userdetails_yes']} <input name='enabled' value='no' type='radio'" . (!$enabled ? " checked='checked'" : "") . ">{$lang['userdetails_no']}</div>";
    




if ($CURUSER['class'] >= UC_STAFF && XBT_TRACKER == false) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/free_slots.php');
}    
if ($CURUSER['class'] >= UC_ADMINISTRATOR && XBT_TRACKER == false) {
require_once (BLOCK_DIR . 'userdetails/edit_userdetails/free_switch.php');
    }
//==XBT - Can Leech
if ($CURUSER['class'] >= UC_ADMINISTRATOR && XBT_TRACKER == true) {
require_once (BLOCK_DIR . 'userdetails/edit_userdetails/can_leech.php');	
}
//==Download disable== editted for announce======//
if ($CURUSER['class'] >= UC_STAFF && XBT_TRACKER == false) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/download_disable.php');
}
//==Upload disable
if ($CURUSER['class'] >= UC_STAFF) {
    require_once (BLOCK_DIR . 'userdetails/edit_userdetails/upload_disable.php');
}
//==
//==Pm disable
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/pm_disable.php');
}
//==Shoutbox disable
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/shoutbox_disable.php');
}
//==Avatar disable
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/avatar_disable.php');
}
//==Immunity
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/immunity.php');
}
//==Leech Warnings
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/leech_warn.php');
}
//==Warnings
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/warn.php');
}
//==Games disable
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/game_disable.php');
}
$HTMLOUT.="<div style='display:inline-block;height:100px;'></div>";
//Adjust up/down
if ($CURUSER['class'] >= UC_ADMINISTRATOR) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/adjust_updown.php');
}
$HTMLOUT.="<div style='display:inline-block;height:50px;'></div>";
// == Wait time, peers limit and torrents limit
if ($CURUSER['class'] >= UC_STAFF && XBT_TRACKER == true) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/peer_limit.php');
}
//==High speed php announce
if ($CURUSER["class"] == UC_MAX && XBT_TRACKER == false) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/high_speed_ann.php');
}
$HTMLOUT.="<div style='display:inline-block;height:50px;'></div>";
//==Invites
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/invites.php');
// == seedbonus
if ($CURUSER['class'] >= UC_STAFF) {
require_once (BLOCK_DIR . 'userdetails/edit_userdetails/seedbonus.php');
}
// == rep
if ($CURUSER['class'] >= UC_STAFF) {
	require_once (BLOCK_DIR . 'userdetails/edit_userdetails/reputation.php');
}
$HTMLOUT.="<div style='display:inline-block;height:50px;'></div>";
//==new row
$HTMLOUT.= '<div class="row"><div class="col-sm-1">'.$lang['userdetails_hnr'].'<br><input class="form-control" type="text" name="hit_and_run_total" value="' . (int)$user['hit_and_run_total'] . '"></div>
                 
	<div class="col-sm-1">'.$lang['userdetails_suspended'].'<br><input name="suspended" value="yes" type="radio"'.($user['suspended'] == 'yes' ? ' checked="checked"' : '').'>'.$lang['userdetails_yes'].'
                     <input name="suspended" value="no" type="radio"'.($user['suspended'] == 'no' ? ' checked="checked"' : '').'></div><div class="col-sm-4">'.$lang['userdetails_no'].'
		 '.$lang['userdetails_suspended_reason'].'<input class="form-control" type="text" name="suspended_reason"></div>';
$HTMLOUT.= "<br><div class='row'>
	<div class='col-sm-2'>{$lang['userdetails_avatar_rights']}<br><input name='view_offensive_avatar' value='yes' type='radio'".($user['view_offensive_avatar'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                  <input name='view_offensive_avatar' value='no' type='radio'".($user['view_offensive_avatar'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']} </div>
                 
                <div class='col-sm-2'>{$lang['userdetails_offensive']}<br><input name='offensive_avatar' value='yes' type='radio'".($user['offensive_avatar'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                  <input name='offensive_avatar' value='no' type='radio'".($user['offensive_avatar'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']} </div>
               
                <div class='col-sm-2'>{$lang['userdetails_view_offensive']}<br>
                 <input name='avatar_rights' value='yes' type='radio'".($user['avatar_rights'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                  <input name='avatar_rights' value='no' type='radio'".($user['avatar_rights'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']} </div>";
 
//users parked
     $HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_park']}<br><input name='parked' value='yes' type='radio'".($user["parked"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']} <input name='parked' value='no' type='radio'".($user["parked"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</div>";
//end users parked     

//reset passkey
    $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_reset']}<br><input type='checkbox' name='reset_torrent_pass' value='1'><br><font class='small'>{$lang['userdetails_pass_msg']}</font></div></div>";
//end reset    

$HTMLOUT.="<div style='display:inline-block;height:50px;'></div>";

//==ANOTHER ROW

$HTMLOUT.= "
<div class='row'>

<div class='col-sm-2'>{$lang['userdetails_forum_rights']}<br><input name='forum_post' value='yes' type='radio'".($user['forum_post'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                     <input name='forum_post' value='no' type='radio'".($user['forum_post'] == "no" ? " checked='checked'" : "")."><br>{$lang['userdetails_forums_no']}</div>";
  

$HTMLOUT .="<div class=\"col-sm-2\">Forum Moderator<br><input name=\"forum_mod\" value=\"yes\" type=radio " . ($user["forum_mod"]=="yes" ? "checked=\"checked\"" : "") . ">Yes <input name=\"forum_mod\" value=\"no\" type=\"radio\" " . ($user["forum_mod"]=="no" ? "checked=\"checked\"" : "") . ">No</div>";
  

$q = sql_query("SELECT o.id as oid, o.name as oname, f.id as fid, f.name as fname FROM `over_forums` as o LEFT JOIN forums as f ON f.forum_id = o.id ") or sqlerr(__FILE__, __LINE__);
	if($q){
	while($a = $q->fetch_assoc()){
		$boo[$a['oname']][] = array($a['fid'],$a['fname']);
	$forum_list = "<ul id=\"browser\" class=\"filetree treeview-gray\" style=\"width:50%;text-align:left;\">";
	foreach($boo as $fo=>$foo) {
		$forum_list .="<li class=\"closed\"><span class=\"folder\">".$fo."</span>";
		$forum_list .="<ul>";
			foreach($foo as $fooo)
				$forum_list .= "<li><label for=\"forum_".$fooo[0]."\"><span class=\"file\" style=\"position:relative;width:200px;\"><b>".$fooo[1]."</b><div style=\"display:inline-block;width:15px;\"></div><input type=\"checkbox\" ".(stristr($user["forums_mod"],"[".$fooo[0]."]") ? "checked=\"checked\"" : "" )."style=\"right:0;top:0;position:absolute;\" name=\"forums[]\" id=\"forum_".$fooo[0]."\" value=\"".$fooo[0]."\"></span></label></li>";
		$forum_list .= "</ul></li>";	
	}
	$forum_list .= "</ul>";
  

$HTMLOUT .="<div class=\"col-sm-8\">Forums List<br>".$forum_list."</div></div>";
	}}
    $HTMLOUT.= "<br><br><div class='row'><div class='col-sm-offset-5'><input type='submit' class='btn btn-default' value='{$lang['userdetails_okay']}'></div></div>";
    $HTMLOUT.= "</table>";
    $HTMLOUT.= "</form>";
    }
$HTMLOUT.='</div>';
//=== End edit userdetails
echo stdhead("{$lang['userdetails_details']} " . $user["username"], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>