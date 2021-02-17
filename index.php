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
require_once INCL_DIR.'user_functions.php';
require_once INCL_DIR.'bbcode_functions.php';
require_once INCL_DIR.'html_functions.php';
require_once ROOT_DIR.'polls.php';
require_once(CLASS_DIR.'class_user_options.php');
require_once(CLASS_DIR.'class_user_options_2.php');
dbconn(true);
loggedinorreturn();
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].''.DIRECTORY_SEPARATOR.'html_functions'.DIRECTORY_SEPARATOR.'global_html_functions.php');
require_once(TEMPLATE_DIR.''.$CURUSER['stylesheet'].''.DIRECTORY_SEPARATOR.'html_functions'.DIRECTORY_SEPARATOR.'navigation_html_functions.php');

$stdhead = [
    /** include css **/
    'css' => [
        'bbcode',
    ],
];
$stdfoot = [
    /** include js **/
    'js' => [
        /*'gallery',*/
    ],
];

$lang = array_merge(load_language('global'), load_language('index'), load_language('chat'));

$HTMLOUT = '';
//==Global blocks by elephant
//==Curuser blocks by pdq
if (curuser::$blocks['index_page'] & block_index::IE_ALERT && $BLOCKS['ie_user_alert']) {
    $HTMLOUT .= "<div id='IE_ALERT'>";
    require_once(BLOCK_DIR.'index/ie_user.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::ANNOUNCEMENT && $BLOCKS['announcement_on']) {
    $HTMLOUT .= "<div id='ANNOUNCEMENT'>";
    require_once(BLOCK_DIR.'index/announcement.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::SHOUTBOX && $BLOCKS['shoutbox_on']) {

    $HTMLOUT .= "<div id='SHOUTBOX'>";
    if ($CURUSER['chatpost'] == 1) {
        require_once(BLOCK_DIR.'index/shoutbox.php');
    } else {
        $HTMLOUT .= "<div class='callout success'><h1 class='text-center' style='font-size: 1.50rem;'><b>You are banned on chat.</b></h1></div>";
    }
    $HTMLOUT .= "</div>";

}

if (curuser::$blocks['index_page'] & block_index::NEWS && $BLOCKS['news_on']) {
    $HTMLOUT .= "<div id='NEWS'>";
    require_once(BLOCK_DIR.'index/news.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::ADVERTISEMENTS && $BLOCKS['ads_on']) {
    $HTMLOUT .= "<div id='ADVERTISEMENTS'>";
    require_once(BLOCK_DIR.'index/advertise.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::FORUMPOSTS && $BLOCKS['forum_posts_on']) {
    $HTMLOUT .= "<div id='FORUMPOSTS'>";
    require_once(BLOCK_DIR.'index/forum_posts.php');
    $HTMLOUT .= "</div>";
}
if (curuser::$blocks['index_page'] & block_index::REQNOFF && $BLOCKS['requests_and_offers_on']) {
    $HTMLOUT .= "<div id='REQUESTS_AND_OFFERS'>";
    require_once(BLOCK_DIR.'index/req_n_off.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::STATS && $BLOCKS['stats_on']) {
    $HTMLOUT .= "<div id='STATS'>";
    require_once(BLOCK_DIR.'index/stats.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::ACTIVE_USERS && $BLOCKS['active_users_on']) {
    $HTMLOUT .= "<div id='ACTIVE_USERS'>";
    require_once(BLOCK_DIR.'index/active_users.php');
    $HTMLOUT .= "</div>";
}
if (curuser::$blocks['index_page'] & block_index::LAST_24_ACTIVE_USERS && $BLOCKS['active_24h_users_on']) {
    $HTMLOUT .= "<div id='LAST_24_ACTIVE_USERS'>";
    require_once(BLOCK_DIR.'index/active_24h_users.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::BIRTHDAY_ACTIVE_USERS && $BLOCKS['active_birthday_users_on']) {
    $HTMLOUT .= "<div id='BIRTHDAY_ACTIVE_USERS'>";
    require_once(BLOCK_DIR.'index/active_birthday_users.php');
    $HTMLOUT .= "</div>";
}
if (curuser::$blocks['index_page'] & block_index::LATEST_USER && $BLOCKS['latest_user_on']) {
    $HTMLOUT .= "<div id='LATEST_USER'>";
    require_once(BLOCK_DIR.'index/latest_user.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::ACTIVE_POLL && $BLOCKS['active_poll_on']) {
    $HTMLOUT .= "<div id='ACTIVE_POLL'>";
    require_once(BLOCK_DIR.'index/poll.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::XMAS_GIFT && $BLOCKS['xmas_gift_on']) {
    $HTMLOUT .= "<div id='XMAS_GIFT'>";
    require_once(BLOCK_DIR.'index/gift.php');
    $HTMLOUT .= "</div>";
}
/*
	if (curuser::$blocks['index_page'] & block_index::RADIO && $BLOCKS['radio_on']) {
$HTMLOUT .="<div id='RADIO'>";
    	require_once (BLOCK_DIR . 'index/radio.php');
$HTMLOUT .="</div>";
	}
*/
if (curuser::$blocks['index_page'] & block_index::TORRENTFREAK && $BLOCKS['torrentfreak_on']) {
    $HTMLOUT .= "<div id='TORRENTFREAK'>";
    require_once(BLOCK_DIR.'index/torrentfreak.php');
    $HTMLOUT .= "</div>";
}

if (curuser::$blocks['index_page'] & block_index::DISCLAIMER && $BLOCKS['disclaimer_on']) {
    require_once(BLOCK_DIR.'index/disclaimer.php');
}

if (curuser::$blocks['index_page'] & block_index::DONATION_PROGRESS && $BLOCKS['donation_progress_on']) {
    require_once(BLOCK_DIR.'index/donations.php');
}

echo stdhead('Home', true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
?>
