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
require_once(INCL_DIR . 'html_functions.php');
require_once(INCL_DIR . 'user_functions.php');
dbconn(false);
loggedinorreturn();
//$lang = load_language('global');
$lang = array_merge(load_language('global'), load_language('user_blocks'));
$id = (isset($_GET['id']) ? $_GET['id'] : $CURUSER['id']);
if (!is_valid_id($id) || $CURUSER['class'] < UC_STAFF) {
    $id = $CURUSER['id'];
}
if ($CURUSER['got_blocks'] == 'no') {
    stderr($lang['gl_error'], $lang['user_b_err1']);
    die;
}
    //$cache->delete('blocks::' . $id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updateset = [];
    $setbits_index_page = $clrbits_index_page = $setbits_global_stdhead = $clrbits_global_stdhead = $setbits_userdetails_page = $clrbits_userdetails_page = 0;
    //==Index
    if (isset($_POST['ie_alert'])) {
        $setbits_index_page|= block_index::IE_ALERT;
    } else {
        $clrbits_index_page|= block_index::IE_ALERT;
    }
    if (isset($_POST['news'])) {
        $setbits_index_page|= block_index::NEWS;
    } else {
        $clrbits_index_page|= block_index::NEWS;
    }
    if (isset($_POST['shoutbox'])) {
        $setbits_index_page|= block_index::SHOUTBOX;
    } else {
        $clrbits_index_page|= block_index::SHOUTBOX;
    }
    if (isset($_POST['active_users'])) {
        $setbits_index_page|= block_index::ACTIVE_USERS;
    } else {
        $clrbits_index_page|= block_index::ACTIVE_USERS;
    }
    if (isset($_POST['last_24_active_users'])) {
        $setbits_index_page|= block_index::LAST_24_ACTIVE_USERS;
    } else {
        $clrbits_index_page|= block_index::LAST_24_ACTIVE_USERS;
    }
    if (isset($_POST['birthday_active_users'])) {
        $setbits_index_page|= block_index::BIRTHDAY_ACTIVE_USERS;
    } else {
        $clrbits_index_page|= block_index::BIRTHDAY_ACTIVE_USERS;
    }
    if (isset($_POST['stats'])) {
        $setbits_index_page|= block_index::STATS;
    } else {
        $clrbits_index_page|= block_index::STATS;
    }
    if (isset($_POST['disclaimer'])) {
        $setbits_index_page|= block_index::DISCLAIMER;
    } else {
        $clrbits_index_page|= block_index::DISCLAIMER;
    }
    if (isset($_POST['latest_user'])) {
        $setbits_index_page|= block_index::LATEST_USER;
    } else {
        $clrbits_index_page|= block_index::LATEST_USER;
    }
    if (isset($_POST['forumposts'])) {
        $setbits_index_page|= block_index::FORUMPOSTS;
    } else {
        $clrbits_index_page|= block_index::FORUMPOSTS;
    }
    if (isset($_POST['latest_torrents'])) {
        $setbits_index_page|= block_index::LATEST_TORRENTS;
    } else {
        $clrbits_index_page|= block_index::LATEST_TORRENTS;
    }
    if (isset($_POST['latest_torrents_scroll'])) {
        $setbits_index_page|= block_index::LATEST_TORRENTS_SCROLL;
    } else {
        $clrbits_index_page|= block_index::LATEST_TORRENTS_SCROLL;
    }
    if (isset($_POST['announcement'])) {
        $setbits_index_page|= block_index::ANNOUNCEMENT;
    } else {
        $clrbits_index_page|= block_index::ANNOUNCEMENT;
    }
    if (isset($_POST['donation_progress'])) {
        $setbits_index_page|= block_index::DONATION_PROGRESS;
    } else {
        $clrbits_index_page|= block_index::DONATION_PROGRESS;
    }
    if (isset($_POST['advertisements'])) {
        $setbits_index_page|= block_index::ADVERTISEMENTS;
    } else {
        $clrbits_index_page|= block_index::ADVERTISEMENTS;
    }
    if (isset($_POST['radio'])) {
        $setbits_index_page|= block_index::RADIO;
    } else {
        $clrbits_index_page|= block_index::RADIO;
    }
    if (isset($_POST['torrentfreak'])) {
        $setbits_index_page|= block_index::TORRENTFREAK;
    } else {
        $clrbits_index_page|= block_index::TORRENTFREAK;
    }
    if (isset($_POST['xmas_gift'])) {
        $setbits_index_page|= block_index::XMAS_GIFT;
    } else {
        $clrbits_index_page|= block_index::XMAS_GIFT;
    }
    if (isset($_POST['active_poll'])) {
        $setbits_index_page|= block_index::ACTIVE_POLL;
    } else {
        $clrbits_index_page|= block_index::ACTIVE_POLL;
    }
    if (isset($_POST['staff_shoutbox'])) {
        $setbits_index_page|= block_index::STAFF_SHOUT;
    } else {
        $clrbits_index_page|= block_index::STAFF_SHOUT;
    }
    if (isset($_POST['movie_ofthe_week'])) {
        $setbits_index_page|= block_index::MOVIEOFWEEK;
    } else {
        $clrbits_index_page|= block_index::MOVIEOFWEEK;
    }
    if (isset($_POST['requests_and_offers'])) {
        $setbits_index_page|= block_index::REQNOFF;
    } else {
        $clrbits_index_page|= block_index::REQNOFF;
    }
    //==Stdhead
    if (isset($_POST['stdhead_freeleech'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_FREELEECH;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_FREELEECH;
    }
    if (isset($_POST['stdhead_demotion'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_DEMOTION;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_DEMOTION;
    }
    if (isset($_POST['stdhead_newpm'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_NEWPM;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_NEWPM;
    }
    if (isset($_POST['stdhead_staff_message'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_STAFF_MESSAGE;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_STAFF_MESSAGE;
    }
    if (isset($_POST['stdhead_reports'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_REPORTS;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_REPORTS;
    }
    if (isset($_POST['stdhead_uploadapp'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_UPLOADAPP;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_UPLOADAPP;
    }
    if (isset($_POST['stdhead_happyhour'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_HAPPYHOUR;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_HAPPYHOUR;
    }
    if (isset($_POST['stdhead_crazyhour'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_CRAZYHOUR;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_CRAZYHOUR;
    }
    if (isset($_POST['stdhead_bugmessage'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_BUG_MESSAGE;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_BUG_MESSAGE;
    }
    if (isset($_POST['stdhead_freeleech_contribution'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION;
    }
    if (isset($_POST['stdhead_stafftools'])) {
        $setbits_global_stdhead|= block_stdhead::STDHEAD_STAFFTOOLS;
    } else {
        $clrbits_global_stdhead|= block_stdhead::STDHEAD_STAFFTOOLS;
    }
    //==Userdetails
    if (isset($_POST['userdetails_login_link'])) {
        $setbits_userdetails_page|= block_userdetails::LOGIN_LINK;
    } else {
        $clrbits_userdetails_page|= block_userdetails::LOGIN_LINK;
    }
    if (isset($_POST['userdetails_flush'])) {
        $setbits_userdetails_page|= block_userdetails::FLUSH;
    } else {
        $clrbits_userdetails_page|= block_userdetails::FLUSH;
    }
    if (isset($_POST['userdetails_joined'])) {
        $setbits_userdetails_page|= block_userdetails::JOINED;
    } else {
        $clrbits_userdetails_page|= block_userdetails::JOINED;
    }
    if (isset($_POST['userdetails_online_time'])) {
        $setbits_userdetails_page|= block_userdetails::ONLINETIME;
    } else {
        $clrbits_userdetails_page|= block_userdetails::ONLINETIME;
    }
    if (isset($_POST['userdetails_browser'])) {
        $setbits_userdetails_page|= block_userdetails::BROWSER;
    } else {
        $clrbits_userdetails_page|= block_userdetails::BROWSER;
    }
    if (isset($_POST['userdetails_reputation'])) {
        $setbits_userdetails_page|= block_userdetails::REPUTATION;
    } else {
        $clrbits_userdetails_page|= block_userdetails::REPUTATION;
    }
    if (isset($_POST['userdetails_user_hits'])) {
        $setbits_userdetails_page|= block_userdetails::PROFILE_HITS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::PROFILE_HITS;
    }
    if (isset($_POST['userdetails_birthday'])) {
        $setbits_userdetails_page|= block_userdetails::BIRTHDAY;
    } else {
        $clrbits_userdetails_page|= block_userdetails::BIRTHDAY;
    }
    if (isset($_POST['userdetails_birthday'])) {
        $setbits_userdetails_page|= block_userdetails::BIRTHDAY;
    } else {
        $clrbits_userdetails_page|= block_userdetails::BIRTHDAY;
    }
    if (isset($_POST['userdetails_contact_info'])) {
        $setbits_userdetails_page|= block_userdetails::CONTACT_INFO;
    } else {
        $clrbits_userdetails_page|= block_userdetails::CONTACT_INFO;
    }
    if (isset($_POST['userdetails_iphistory'])) {
        $setbits_userdetails_page|= block_userdetails::IPHISTORY;
    } else {
        $clrbits_userdetails_page|= block_userdetails::IPHISTORY;
    }
    if (isset($_POST['userdetails_traffic'])) {
        $setbits_userdetails_page|= block_userdetails::TRAFFIC;
    } else {
        $clrbits_userdetails_page|= block_userdetails::TRAFFIC;
    }
    if (isset($_POST['userdetails_share_ratio'])) {
        $setbits_userdetails_page|= block_userdetails::SHARE_RATIO;
    } else {
        $clrbits_userdetails_page|= block_userdetails::SHARE_RATIO;
    }
    if (isset($_POST['userdetails_seedtime_ratio'])) {
        $setbits_userdetails_page|= block_userdetails::SEEDTIME_RATIO;
    } else {
        $clrbits_userdetails_page|= block_userdetails::SEEDTIME_RATIO;
    }
    if (isset($_POST['userdetails_seedbonus'])) {
        $setbits_userdetails_page|= block_userdetails::SEEDBONUS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::SEEDBONUS;
    }
    if (isset($_POST['userdetails_connectable_port'])) {
        $setbits_userdetails_page|= block_userdetails::CONNECTABLE_PORT;
    } else {
        $clrbits_userdetails_page|= block_userdetails::CONNECTABLE_PORT;
    }
    if (isset($_POST['userdetails_avatar'])) {
        $setbits_userdetails_page|= block_userdetails::AVATAR;
    } else {
        $clrbits_userdetails_page|= block_userdetails::AVATAR;
    }
    if (isset($_POST['userdetails_userclass'])) {
        $setbits_userdetails_page|= block_userdetails::USERCLASS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::USERCLASS;
    }
    if (isset($_POST['userdetails_gender'])) {
        $setbits_userdetails_page|= block_userdetails::GENDER;
    } else {
        $clrbits_userdetails_page|= block_userdetails::GENDER;
    }
    if (isset($_POST['userdetails_freestuffs'])) {
        $setbits_userdetails_page|= block_userdetails::FREESTUFFS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::FREESTUFFS;
    }
    if (isset($_POST['userdetails_comments'])) {
        $setbits_userdetails_page|= block_userdetails::COMMENTS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::COMMENTS;
    }
    if (isset($_POST['userdetails_forumposts'])) {
        $setbits_userdetails_page|= block_userdetails::FORUMPOSTS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::FORUMPOSTS;
    }
    if (isset($_POST['userdetails_invitedby'])) {
        $setbits_userdetails_page|= block_userdetails::INVITEDBY;
    } else {
        $clrbits_userdetails_page|= block_userdetails::INVITEDBY;
    }
    if (isset($_POST['userdetails_torrents_block'])) {
        $setbits_userdetails_page|= block_userdetails::TORRENTS_BLOCK;
    } else {
        $clrbits_userdetails_page|= block_userdetails::TORRENTS_BLOCK;
    }
    if (isset($_POST['userdetails_completed'])) {
        $setbits_userdetails_page|= block_userdetails::COMPLETED;
    } else {
        $clrbits_userdetails_page|= block_userdetails::COMPLETED;
    }
    if (isset($_POST['userdetails_snatched_staff'])) {
        $setbits_userdetails_page|= block_userdetails::SNATCHED_STAFF;
    } else {
        $clrbits_userdetails_page|= block_userdetails::SNATCHED_STAFF;
    }
    if (isset($_POST['userdetails_userinfo'])) {
        $setbits_userdetails_page|= block_userdetails::USERINFO;
    } else {
        $clrbits_userdetails_page|= block_userdetails::USERINFO;
    }
    if (isset($_POST['userdetails_showpm'])) {
        $setbits_userdetails_page|= block_userdetails::SHOWPM;
    } else {
        $clrbits_userdetails_page|= block_userdetails::SHOWPM;
    }
    if (isset($_POST['userdetails_report_user'])) {
        $setbits_userdetails_page|= block_userdetails::REPORT_USER;
    } else {
        $clrbits_userdetails_page|= block_userdetails::REPORT_USER;
    }
    if (isset($_POST['userdetails_user_status'])) {
        $setbits_userdetails_page|= block_userdetails::USERSTATUS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::USERSTATUS;
    }
    if (isset($_POST['userdetails_user_comments'])) {
        $setbits_userdetails_page|= block_userdetails::USERCOMMENTS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::USERCOMMENTS;
    }
    if (isset($_POST['userdetails_showfriends'])) {
        $setbits_userdetails_page|= block_userdetails::SHOWFRIENDS;
    } else {
        $clrbits_userdetails_page|= block_userdetails::SHOWFRIENDS;
    }
    //== set n clear
    if ($setbits_index_page) {
        $updateset[] = 'index_page = (index_page | ' . $setbits_index_page . ')';
    }
    if ($clrbits_index_page) {
        $updateset[] = 'index_page = (index_page & ~' . $clrbits_index_page . ')';
    }
    if ($setbits_global_stdhead) {
        $updateset[] = 'global_stdhead = (global_stdhead | ' . $setbits_global_stdhead . ')';
    }
    if ($clrbits_global_stdhead) {
        $updateset[] = 'global_stdhead = (global_stdhead & ~' . $clrbits_global_stdhead . ')';
    }
    if ($setbits_userdetails_page) {
        $updateset[] = 'userdetails_page = (userdetails_page | ' . $setbits_userdetails_page . ')';
    }
    if ($clrbits_userdetails_page) {
        $updateset[] = 'userdetails_page = (userdetails_page & ~' . $clrbits_userdetails_page . ')';
    }
    if (count($updateset)) {
        sql_query('UPDATE user_blocks SET ' . implode(',', $updateset) . ' WHERE userid = ' . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    }
    $cache->delete('blocks::' . $id);
    header("Location: {$TRINITY20['baseurl']}/usercp.php?edited=1#user-block");
}
echo stdhead($lang['user_b_echo'], true) . $HTMLOUT . stdfoot();
