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
require_once CLASS_DIR.'page_verify.php';
require_once(INCL_DIR.'user_functions.php');
require_once INCL_DIR.'bbcode_functions.php';
require_once INCL_DIR.'html_functions.php';
require_once INCL_DIR.'comment_functions.php';
require_once(INCL_DIR.'function_onlinetime.php');
require_once(CLASS_DIR.'class_user_options.php');
require_once(CLASS_DIR.'class_user_options_2.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('userdetails'));
if (function_exists('parked')) {
    parked();
}
$newpage = new page_verify();
$newpage->create('mdk1@@9');
$stdhead = [
    /* include css **/
    'css' => [
    ],
];
$stdfoot = [
    /* include js **/
    'js' => [
        'popup',
        'flush_torrents',
    ],
];
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if (!is_valid_id($id)) {
    stderr($lang['userdetails_error'], "{$lang['userdetails_bad_id']}");
}
if (($user = $cache->get($keys['user'].$id)) === false) {
    $user_fields_ar_int = [
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
    ];
    $user_fields_ar_float = [
        'time_offset',
        'total_donated',
    ];
    $user_fields_ar_str = [
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
        'pm_forced',
    ];
    $user_fields = implode(', ', array_merge($user_fields_ar_int, $user_fields_ar_float, $user_fields_ar_str));
    ($r1 = sql_query("SELECT ".$user_fields." FROM users WHERE id=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    ($user = $r1->fetch_assoc()) || stderr($lang['userdetails_error'], "{$lang['userdetails_no_user']}");
    foreach ($user_fields_ar_int as $i) {
        $user[$i] = (int)$user[$i];
    }
    foreach ($user_fields_ar_float as $i) {
        $user[$i] = (float)$user[$i];
    }
    foreach ($user_fields_ar_str as $i) {
        $user[$i] = $user[$i];
    }
    $cache->set($keys['user'].$id, $user, $TRINITY20['expires']['user_cache']);
}
if ($user["status"] == "pending") {
    stderr($lang['userdetails_error'], $lang['userdetails_still_pending']);
}
// user stats
$What_Cache = (XBT_TRACKER == true ? 'user_stats_xbt_' : 'user_stats_');
if (($user_stats = $cache->get($What_Cache.$id)) === false) {
    $What_Expire = (XBT_TRACKER == true ? $TRINITY20['expires']['user_stats_xbt'] : $TRINITY20['expires']['user_stats']);
    $stats_fields_ar_int = [
        'uploaded',
        'downloaded',
    ];
    $stats_fields_ar_float = [
        'seedbonus',
    ];
    $stats_fields_ar_str = [
        'modcomment',
        'bonuscomment',
    ];
    $stats_fields = implode(', ', array_merge($stats_fields_ar_int, $stats_fields_ar_float, $stats_fields_ar_str));
    ($sql_1 = sql_query('SELECT '.$stats_fields.' FROM users WHERE id= '.sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $user_stats = $sql_1->fetch_assoc();
    foreach ($stats_fields_ar_int as $i) {
        $user_stats[$i] = (int)$user_stats[$i];
    }
    foreach ($stats_fields_ar_float as $i) {
        $user_stats[$i] = (float)$user_stats[$i];
    }
    foreach ($stats_fields_ar_str as $i) {
        $user_stats[$i] = $user_stats[$i];
    }
    $cache->set($What_Cache.$id, $user_stats, $What_Expire); // 5 mins
}
if (($user_status = $cache->get('user_status_'.$id)) === false) {
    $sql_2 = sql_query('SELECT * FROM ustatus WHERE userid = '.sqlesc($id));
    if ($sql_2->num_rows) {
        $user_status = $sql_2->fetch_assoc();
    } else {
        $user_status = [
            'last_status' => '',
            'last_update' => 0,
            'archive' => '',
        ];
    }
    $cache->set('user_status_'.$id, $user_status, $TRINITY20['expires']['user_status']); // 30 days

}
//=== delete H&R
if (isset($_GET['delete_hit_and_run']) && $CURUSER['class'] >= UC_STAFF) {
    $delete_me = isset($_GET['delete_hit_and_run']) ? (int)$_GET['delete_hit_and_run'] : 0;
    if (!is_valid_id($delete_me)) {
        stderr($lang['userdetails_error'], $lang['userdetails_bad_id']);
    }
    if (XBT_TRACKER === false) {
        sql_query('UPDATE snatched SET hit_and_run = \'0\', mark_of_cain = \'no\' WHERE id = '.sqlesc($delete_me)) || sqlerr(__FILE__, __LINE__);
    } else {
        sql_query('UPDATE xbt_peers SET hit_and_run = \'0\', mark_of_cain = \'no\' WHERE fid = '.sqlesc($delete_me)) || sqlerr(__FILE__, __LINE__);
    }
    if (@$mysqli->affected_rows === 0) {
        stderr($lang['userdetails_error'], $lang['userdetails_notdeleted']);
    }
    header('Location: ?id='.$id.'&completed=1');
    die();
}
/* #$^$&%$&@ invincible! NO IP LOGGING..pdq **/
if ((($user['class'] == UC_MAX || $user['id'] == $CURUSER['id']) || ($user['class'] < UC_MAX) && $CURUSER['class'] == UC_MAX) && isset($_GET['invincible'])) {
    require_once(INCL_DIR.'invincible.php');
    if ($_GET['invincible'] == 'yes') {
        $HTMLOUT .= "".invincible($id)."";
    } elseif ($_GET['invincible'] == 'remove_bypass') {
        $HTMLOUT .= invincible($id, true, false);
    } else {
        $HTMLOUT .= invincible($id, false);
    }
} // End

/* #$^$&%$&@ stealth!..pdq **/
if ((($user['class'] >= UC_STAFF || $user['id'] == $CURUSER['id']) || ($user['class'] < UC_STAFF) && $CURUSER['class'] >= UC_STAFF) && isset($_GET['stealth'])) {
    require_once(INCL_DIR.'stealth.php');
    if ($_GET['stealth'] == 'yes') {
        $HTMLOUT .= stealth($id);
    } elseif ($_GET['stealth'] == 'no') {
        $HTMLOUT .= stealth($id, false);
    }
} // End
//==country by pdq
function countries()
{
    global $cache, $TRINITY20;
    if (($ret = $cache->get('countries::arr')) === false) {
        ($res = sql_query("SELECT id, name, flagpic FROM countries ORDER BY name ASC")) || sqlerr(__FILE__, __LINE__);
        while ($row = $res->fetch_assoc()) {
            $ret = (array)$ret;
            $ret[] = $row;
        }
        $cache->set('countries::arr', $ret, $TRINITY20['expires']['user_flag']);
    }
    return $ret;
}

$country = '';
$countries = countries();
foreach ($countries as $cntry) {
    if (is_array($cntry)) {
        if ($cntry['id'] == $user['country']) {
            $country = "<img src='{$TRINITY20['pic_base_url']}flag/{$cntry['flagpic']}' alt='".htmlspecialchars($cntry['name'])."' style='margin-left: 8pt'>";
            break;
        }
    }
}
//==userhits update by pdq
if (!(isset($_GET["hit"])) && $CURUSER["id"] != $user["id"]) {
    ($res = sql_query("SELECT added FROM userhits WHERE userid =".sqlesc($CURUSER['id'])." AND hitid = ".sqlesc($id)." LIMIT 1")) || sqlerr(__FILE__,
        __LINE__);
    $row = $res->fetch_row();
    $row ??= '1';
    if (!$row[0] <= !(TIME_NOW - 3600)) {
        $hitnumber = $user['hits'] + 1;
        sql_query("UPDATE users SET hits = hits + 1 WHERE id = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        // do update hits userdetails cache
        $update['user_hits'] = ($user['hits'] + 1);
        $cache->update_row($keys['my_userid'].$id, [
            'hits' => $update['user_hits'],
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row($keys['user'].$id, [
            'hits' => $update['user_hits'],
        ], $TRINITY20['expires']['user_cache']);
        sql_query("INSERT INTO userhits (userid, hitid, number, added) VALUES(".sqlesc($CURUSER['id']).", ".sqlesc($id).", ".sqlesc($hitnumber).", ".sqlesc(TIME_NOW).")") || sqlerr(__FILE__,
            __LINE__);
    }
}
//== Show PM Button - updated 2020 by iseeyoucopy
if ($CURUSER["id"] != $user["id"]) {
    if ($CURUSER['class'] >= UC_STAFF) {
        $showpmbutton = 1;
    }
} elseif ($user["acceptpms"] == "yes") {
    ($r = sql_query("SELECT id FROM blocks WHERE userid=".sqlesc($user['id'])." AND blockid=".sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
    $showpmbutton = ($r->num_rows == 1 ? 0 : 1);
} elseif ($user["acceptpms"] == "friends") {
    ($r = sql_query("SELECT id FROM friends WHERE userid=".sqlesc($user['id'])." AND friendid=".sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
        __LINE__);
    $showpmbutton = ($r->num_rows == 1 ? 1 : 0);
}
//== Add or Remove Friends - updated 2020 by iseeyoucopy
if (($friends = $cache->get('Friends_'.$id)) === false) {
    ($r3 = sql_query("SELECT id FROM friends WHERE userid=".sqlesc($CURUSER['id'])." AND friendid=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $friends = $r3->num_rows;
    $cache->set('Friends_'.$id, $friends, 10);
}
if (($blocks = $cache->get('Blocks_'.$id)) === false) {
    ($r4 = sql_query("SELECT id FROM blocks WHERE userid=".sqlesc($CURUSER['id'])." AND blockid=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $blocks = $r4->num_rows;
    $cache->set('Blocks_'.$id, $blocks, 10);
}
//== Join date 
$joindate = $user["added"];
$joindate = $user['added'] == 0 ? "{$lang['userdetails_na']}" : get_date($user['added'], '');

//==Last Seen 
$lastseen = $user["last_access"];
$lastseen = $lastseen == 0 ? "{$lang['userdetails_never']}" : get_date($user['last_access'], '', 0, 1);
if (($shit_list = $cache->get('shit_list_'.$id)) === false) {
    ($check_if_theyre_shitty = sql_query("SELECT suspect FROM shit_list WHERE userid=".sqlesc($CURUSER['id'])." AND suspect=".sqlesc($id))) || sqlerr(__FILE__,
        __LINE__);
    [$shit_list] = $check_if_theyre_shitty->fetch_row();
    $cache->set('shit_list_'.$id, $shit_list, $TRINITY20['expires']['shit_list']);
}
$HTMLOUT = $perms = $stealth = $suspended = $watched_user = '';
if (($user['anonymous'] == 'yes') && ($CURUSER['class'] < UC_STAFF && $user["id"] != $CURUSER["id"])) {

}
//== Start Suspended ==//
if ($CURUSER["id"] != $user["id"] && $CURUSER['class'] >= UC_STAFF) {
    $suspended .= ($user['suspended'] == 'yes' ? '<i class="fas fa-exclamation-triangle"></i>'.$lang['userdetails_usersuspended'].'</b><i class="fas fa-exclamation-triangle"></i>' : '');
}
//== End Suspended ==//
$where_is_now = $user['where_is'] ?? '';
//== Avatar ==//
//$user_avatar = $user['avatar'] ? "<img class='img-polaroid' src='" . htmlspecialchars($user["avatar"]) . "' width='42' height='42'>" : "<img class='img-polaroid' src='{$TRINITY20['pic_base_url']}forumicons/default_avatar.gif' width='42' height='42'>";
$perms .= ($CURUSER['class'] >= UC_STAFF ? ((($user['perms'] & bt_options::PERMS_NO_IP) !== 0) ? '<img src="'.$TRINITY20['pic_base_url'].'smilies/super.gif" alt="'.$lang['userdetails_invincible'].'"  title="'.$lang['userdetails_invincible'].'">' : '') : '');
$stealth .= ($CURUSER['class'] >= UC_STAFF ? ((($user['perms'] & bt_options::PERMS_STEALTH) !== 0) ? '&nbsp;&nbsp;<img src="'.$TRINITY20['pic_base_url'].'smilies/ninja.gif" alt="'.$lang['userdetails_stelth'].'"  title="'.$lang['userdetails_stelth'].'">' : '') : '');
$enabled = $user["enabled"] == 'yes';
if (($user['opt1'] & user_options::PARKED) !== 0) {
    $HTMLOUT .= "<p>{$lang['userdetails_parked']}</p>";
}
if (!$enabled) {
    $HTMLOUT .= "<p>{$lang['userdetails_disabled']}</p>";
}
$HTMLOUT .= ((isset($_GET['sn']) || isset($_GET['wu'])) ? '<div class="callout alert-callout-border success"><strong>'.$lang['userdetails_updated'].'</strong></div>' : '');
$HTMLOUT .= "<div class='callout'>
	".format_username($user,
        true)."".($user['last_access'] > TIME_NOW - 180 ? "<span class='label success float-right'>Online</span>" : "<span class='label alert float-right'>Offline</span>")."<a data-toggle='userdetails-info'><i class='fas fa-info-circle'></i></a>
	<p class='float-right'>".$where_is_now."</p>
	</div>
<div class='dropdown-pane' id='userdetails-info' data-dropdown data-hover='true' data-hover-pane='true'>
	<p><strong>{$lang['userdetails_class']}</strong> : ".get_user_class_name($user["class"])."<img src='".get_user_class_image($user["class"])."' alt='".get_user_class_name($user["class"])."' title='".get_user_class_name($user["class"])."'></p>
	<p><strong>{$lang['userdetails_joined']}</strong> : {$joindate}</p>
	<p><strong>{$lang['userdetails_seen']}</strong> : {$lastseen}</p>
	<p>$suspended</p>
	".($perms !== '' ? '<p>'.$lang['userdetails_is_invincible'].$perms.'</p>' : '')."
	".($stealth !== '' ? '<p>'.$lang['userdetails_in_stelth'].$stealth.'</p>' : '')." 
	<p>Country : $country</p>
</div>";
$HTMLOUT .= "<div class='dropdown-pane' id='userdetails-actions' data-dropdown data-hover='true' data-hover-pane='true'>";
$HTMLOUT .= isset($showpmbutton) ? "<a href='pm_system.php?action=send_message&receiver=".(int)$user["id"]."'><dd><i class='fas fa-comment-alt'></i>{$lang['userdetails_msg_btn']}</dd></a>" : '';
$HTMLOUT .= (($CURUSER["id"] != $user["id"] & $friends > 0) !== 0) ? "<a href='friends.php?action=delete&amp;type=friend&amp;targetid=$id'><dd><i class='fas fa-user-times'></i>{$lang['userdetails_remove_friends']}</dd></a>" : "<a href='friends.php?action=add&amp;type=friend&amp;targetid=$id'><dd><i class='fas fa-user-plus'></i>{$lang['userdetails_add_friends']}</dd></a>";
$HTMLOUT .= (($CURUSER["id"] != $user["id"] & $blocks > 0) !== 0) ? "<a href='friends.php?action=delete&amp;type=block&amp;targetid=$id'><dd><i class='fas fa-user-times'></i>{$lang['userdetails_remove_blocks']}</dd></a>" : "<a href='friends.php?action=add&amp;type=block&amp;targetid=$id'><dd><i class='fas fa-user-lock'></i>{$lang['userdetails_add_blocks']}</dd></a>";
//=== Link to member contact mail - updated 2020 by iseeyoucopy
$HTMLOUT .= ($CURUSER['class'] >= UC_STAFF || $user['show_email'] === 'yes') ? '<a href="mailto:'. /*decrypt_email(*/
    htmlspecialchars($user['email'])/*)*/.'" target="_blank"><dd><i class="fas fa-envelope"></i>'.$lang['userdetails_send_email'].'</dd></a>' : '';
//== Link Report User - updated 2020 by iseeyoucopy
$HTMLOUT .= "<a href='report.php?type=User&amp;id=".(int)$user["id"]."'><dd><i class='fas fa-comment-alt'></i>{$lang['userdetails_report']}</dd></a>";
//== Link to usercp  - updated 2020 by iseeyoucopy
$HTMLOUT .= "".($CURUSER['id'] == $user['id'] ? "<a href='{$TRINITY20['baseurl']}/usercp.php?action=default'><dd>{$lang['userdetails_editself']}</dd></a>" : "");
$HTMLOUT .= ($CURUSER['id'] == $user['id'] ? "<a href='{$TRINITY20['baseurl']}/view_announce_history.php'><dd>{$lang['userdetails_announcements']}</dd></a>" : "")."";
$HTMLOUT .= ($CURUSER['id'] != $user['id']) ? "<a href='{$TRINITY20['baseurl']}/bookmarks.php?action=viewsharemarks&amp;id=$id'><dd><i class='fas fa-bookmark'></i>{$lang['userdetails_sharemarks']}</dd></a>" : "";
//== links to make invincible method 1 (PERMS_NO_IP/ no log ip) - updated 2020 by iseeyoucopy
if ($CURUSER['class'] == UC_MAX) {
    $HTMLOUT .= (($user['perms'] & bt_options::PERMS_NO_IP) !== 0) ? ' <a href="userdetails.php?id='.$id.'&amp;invincible=no"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_invincible_remove'].'</dd></a>' : '<a href="userdetails.php?id='.$id.'&amp;invincible=yes"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_make_invincible'].'</dd></a>';
}
//== links to make invincible method 2 (PERMS_BYPASS_BAN/cannot be banned) - updated 2020 by iseeyoucopy
if ($CURUSER['class'] == UC_MAX) {
    $HTMLOUT .= (($user['perms'] & bt_options::PERMS_BYPASS_BAN) !== 0) ? '<a href="userdetails.php?id='.$id.'&amp;invincible=remove_bypass"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_remove_bypass'].'</dd></a>' : '<a href="userdetails.php?id='.$id.'&amp;invincible=yes"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_add_bypass'].'</dd></a>';
}
//== Links for Stealth mode - updated 2020 by iseeyoucopy
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT .= (($user['perms'] & bt_options::PERMS_STEALTH) !== 0) ? '<a href="userdetails.php?id='.$id.'&amp;stealth=no"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_stelth_disable'].'</dd></a>' : '<a href="userdetails.php?id='.$id.'&amp;stealth=yes"><dd><i class="fas fa-user-secret"></i>'.$lang['userdetails_stelth_enable'].'</dd></a>';
}
$HTMLOUT .= ($CURUSER['class'] >= UC_STAFF && $CURUSER["id"] != $user["id"]) ? "<a href='staffpanel.php?tool=shit_list&amp;action=shit_list&amp;action2=new&amp;shit_list_id=".$id."&amp;return_to=userdetails.php?id=".$id."'><i class='fas fa-poop'></i>{$lang['userdetails_shit3']}</a>" : '';
$HTMLOUT .= ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) ? '<a data-open="watched-user"><dd>'.$lang['userdetails_watched'].'</dd></a>' : '';
$HTMLOUT .= ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) ? '<a data-open="staff-notes"><dd>'.$lang['userdetails_staffnotes'].'</dd></a>' : '';
$HTMLOUT .= ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) ? '<a data-open="system-comments"><dd>'.$lang['userdetails_system'].'</dd></a>' : '';
$HTMLOUT .= " </div>";
// == Donor count down - updated 2020 by iseeyoucopy
if ($user["donor"] && $CURUSER["id"] == $user["id"] || $CURUSER["class"] == UC_SYSOP) {
    $donoruntil = htmlspecialchars($user['donoruntil']);
    if ($donoruntil == '0') {
        $HTMLOUT .= "";
    } else {
        $HTMLOUT .= "<b>{$lang['userdetails_donatedtill']} - ".get_date($user['donoruntil'], 'DATE')."";
        $HTMLOUT .= " [ ".mkprettytime($donoruntil - TIME_NOW)." ] {$lang['userdetails_togo']}...</b>{$lang['userdetails_renew']}<a class='altlink' href='{$TRINITY20['baseurl']}/donate.php'>{$lang['userdetails_here']}</a>";
    }
}
$HTMLOUT .= (($CURUSER['class'] >= UC_STAFF & $shit_list > 0) !== 0) ? "<dd><b>{$lang['userdetails_shit1']} <a href='staffpanel.php?tool=shit_list&amp;action=shit_list'>{$lang['userdetails_here']}</a> {$lang['userdetails_shit2']}&nbsp;</b></dd>" : "";
//== watched user stuff ==//
if ($CURUSER['id'] !== $user['id'] && $CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT .= ($user['watched_user'] == 0) ? '' : '<p>'.$lang['userdetails_watchlist1'].' <a href="staffpanel.php?tool=watched_users" >'.$lang['userdetails_watchlist2'].'</a></p>';
}
//== make sure people can't see their own naughty history by snuggles ==//
if (($CURUSER['id'] !== $user['id']) && ($CURUSER['class'] >= UC_STAFF)) {
    //== watched user stuff ==//
    require_once(BLOCK_DIR.'userdetails/watched_user.php');
    //== Staff Notes ==//
    require_once(BLOCK_DIR.'userdetails/staff_notes.php');
    //== System comments ==//
}
$HTMLOUT .= "<ul class='tabs' data-tabs id='userdetails-tabs{$user['id']}'>
	<li class='tabs-title is-active'><a href='#torrents' aria-selected='true'>{$lang['userdetails_torrents']}</a></li>
    <li class='tabs-title'><a href='#general'>{$lang['userdetails_general']}</a></li>
    <li class='tabs-title'><a href='#activity'>{$lang['userdetails_activity']}</a></li>
    <li class='tabs-title'><a href='#comments'>{$lang['userdetails_usercomments']}</a></li>
	<li class='tabs-title'><a href='#general' data-toggle='userdetails-actions'>Actions</a></li>
	".($CURUSER['class'] >= UC_STAFF && $user["class"] < $CURUSER['class'] ? '<li class="tabs-title"><a href="#edit_user">'.$lang['userdetails_edit_user'].'</a></li>' : '')."</ul>";
$HTMLOUT .= "<div class='tabs-content' data-tabs-content='userdetails-tabs{$user['id']}'>";
$HTMLOUT .= "<div class='tabs-panel is-active' id='torrents'><table class='striped'>";
if (curuser::$blocks['userdetails_page'] & block_userdetails::FLUSH && $BLOCKS['userdetails_flush_on']) {
    require_once(BLOCK_DIR.'userdetails/flush.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TRAFFIC && $BLOCKS['userdetails_traffic_on']) {
    require_once(BLOCK_DIR.'userdetails/traffic.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHARE_RATIO && $BLOCKS['userdetails_share_ratio_on']) {
    require_once(BLOCK_DIR.'userdetails/shareratio.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SEEDTIME_RATIO && $BLOCKS['userdetails_seedtime_ratio_on']) {
    require_once(BLOCK_DIR.'userdetails/seedtimeratio.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK && $BLOCKS['userdetails_torrents_block_on']) {
    require_once(BLOCK_DIR.'userdetails/torrents_block.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::COMPLETED && $BLOCKS['userdetails_completed_on']/* && XBT_TRACKER == false*/) {
    require_once(BLOCK_DIR.'userdetails/completed.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::CONNECTABLE_PORT && $BLOCKS['userdetails_connectable_port_on']) {
    require_once(BLOCK_DIR.'userdetails/connectable.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK && $BLOCKS['userdetails_torrents_block_on']) {
    require_once(BLOCK_DIR.'userdetails/snatched_block.php');
}
$HTMLOUT .= "</table></div>";
$HTMLOUT .= "<div class='tabs-panel' id='general'><table class='striped'>";
//==Begin blocks
if (curuser::$blocks['userdetails_page'] & block_userdetails::SHOWFRIENDS && $BLOCKS['userdetails_showfriends_on']) {
    require_once(BLOCK_DIR.'userdetails/showfriends.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::LOGIN_LINK && $BLOCKS['userdetails_login_link_on']) {
    require_once(BLOCK_DIR.'userdetails/loginlink.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::ONLINETIME && $BLOCKS['userdetails_online_time_on']) {
    require_once(BLOCK_DIR.'userdetails/onlinetime.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::BROWSER && $BLOCKS['userdetails_browser_on']) {
    require_once(BLOCK_DIR.'userdetails/browser.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::BIRTHDAY && $BLOCKS['userdetails_birthday_on']) {
    require_once(BLOCK_DIR.'userdetails/birthday.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::IPHISTORY && $BLOCKS['userdetails_iphistory_on']) {
    require_once(BLOCK_DIR.'userdetails/iphistory.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::GENDER && $BLOCKS['userdetails_gender_on']) {
    require_once(BLOCK_DIR.'userdetails/gender.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::AVATAR && $BLOCKS['userdetails_avatar_on']) {
    require_once(BLOCK_DIR.'userdetails/avatar.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERINFO && $BLOCKS['userdetails_userinfo_on']) {
    require_once(BLOCK_DIR.'userdetails/userinfo.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::USERSTATUS && $BLOCKS['userdetails_user_status_on']) {
    require_once(BLOCK_DIR.'userdetails/userstatus.php');
}
$HTMLOUT .= "</table></div>";

$HTMLOUT .= "<div class='tabs-panel' id='activity'><table class='striped'>";
if ($TRINITY20['mood_sys_on']) {
    $moodname = (isset($mood['name'][$user['mood']]) ? htmlspecialchars($mood['name'][$user['mood']]) : $lang['userdetails_neutral']);
    $moodpic = (isset($mood['image'][$user['mood']]) ? htmlspecialchars($mood['image'][$user['mood']]) : 'noexpression.gif');
    $HTMLOUT .= '<tr><td class="rowhead">'.$lang['userdetails_currentmood'].'</td><td align="left"><span class="tool">
       <a href="javascript:;" onclick="PopUp(\'usermood.php\',\''.$lang['userdetails_mood'].'\',530,500,1,1);">
       <img src="'.$TRINITY20['pic_base_url'].'smilies/'.$moodpic.'" alt="'.$moodname.'" border="0">
       <span class="tip">'.htmlspecialchars($user['username']).' '.$moodname.' !</span></a></span></td></tr>';
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::SEEDBONUS && $BLOCKS['userdetails_seedbonus_on']) {
    require_once(BLOCK_DIR.'userdetails/seedbonus.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::REPUTATION && $BLOCKS['userdetails_reputation_on'] && $TRINITY20['rep_sys_on']) {
    require_once(BLOCK_DIR.'userdetails/reputation.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::PROFILE_HITS && $BLOCKS['userdetails_profile_hits_on']) {
    require_once(BLOCK_DIR.'userdetails/userhits.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::FREESTUFFS && $BLOCKS['userdetails_freestuffs_on'] && XBT_TRACKER == false) {
    require_once(BLOCK_DIR.'userdetails/freestuffs.php');
}
if (curuser::$blocks['userdetails_page'] & block_userdetails::INVITEDBY && $BLOCKS['userdetails_invitedby_on']) {
    require_once(BLOCK_DIR.'userdetails/invitedby.php');
}
$HTMLOUT .= "</table></div>";
$HTMLOUT .= "<div class='tabs-panel' id='comments'>";

if (curuser::$blocks['userdetails_page'] & block_userdetails::USERCOMMENTS && $BLOCKS['userdetails_user_comments_on']) {
    require_once(BLOCK_DIR.'userdetails/usercomments.php');
}
$HTMLOUT .= "</div>";

//==start edit userdetails
if ($CURUSER['class'] >= UC_STAFF && $user["class"] < $CURUSER['class']) {
    $HTMLOUT .= "<div class='tabs-panel' id='edit_user'>";
    $HTMLOUT .= "<form method='post' action='staffpanel.php?tool=modtask'>";
    require_once CLASS_DIR.'validator.php';
    $HTMLOUT .= validatorForm('ModTask_'.$user['id']);
    $postkey = PostKey([
        $user['id'],
        $CURUSER['id'],
    ]);
    $HTMLOUT .= "<input type='hidden' name='action' value='edituser'>
        <input type='hidden' name='userid' value='$id'>
        <input type='hidden' name='postkey' value='$postkey'>
        <input type='hidden' name='returnto' value='userdetails.php?id=$id'>
        <div class='grid-x  grid-margin-x callout primary'>";
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/other.php');
    if ($CURUSER["class"] == UC_MAX) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/donor.php');
    }
    if ($CURUSER['class'] >= UC_STAFF && XBT_TRACKER == false) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/free_slots.php');
    }
    if ($CURUSER['class'] >= UC_ADMINISTRATOR && XBT_TRACKER == false) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/free_switch.php');
    }
    if (XBT_TRACKER == true) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/can_leech.php');
    }
    if ($CURUSER['class'] >= UC_STAFF && XBT_TRACKER == false) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/download_disable.php');
    }
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/upload_disable.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/pm_disable.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/shoutbox_disable.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/avatar_disable.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/immunity.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/leech_warn.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/warn.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/game_disable.php');
    if ($CURUSER['class'] >= UC_ADMINISTRATOR) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/adjust_updown.php');
    }
    if ($CURUSER['class'] >= UC_STAFF && XBT_TRACKER == true) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/peer_limit.php');
    }
    if ($CURUSER["class"] == UC_MAX && XBT_TRACKER == false) {
        require_once(BLOCK_DIR.'userdetails/edit_userdetails/high_speed_ann.php');
    }
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/invites.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/seedbonus.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/reputation.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/hit_and_run.php');
    require_once(BLOCK_DIR.'userdetails/edit_userdetails/parked_and_passkey.php');
    //require_once (BLOCK_DIR . 'userdetails/edit_userdetails/forum_mod.php');
    $HTMLOUT .= "<div class='cell medium-12'><input type='submit' class='button' value='{$lang['userdetails_okay']}'></div>";
    $HTMLOUT .= "</form>";
    $HTMLOUT .= "</div>";
}
$HTMLOUT .= '</div>';
echo stdhead("{$lang['userdetails_details']} ".$user["username"], true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
?>
