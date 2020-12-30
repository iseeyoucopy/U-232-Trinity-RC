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
//==Error reporting... Turn off = 0 when live
$TRINITY['error_reports']['debugmode'] = 1;
if ($TRINITY['error_reports']['debugmode'] == 1) {
    error_reporting(E_ALL); 
}else { 
    error_reporting(0); 
}
const REQUIRED_PHP = 70000, REQUIRED_PHP_VERSION = '7.0';
if (PHP_VERSION_ID < REQUIRED_PHP)
die('PHP '.REQUIRED_PHP_VERSION.' or higher is required.');
if (PHP_INT_SIZE < 8)
die('A 64bit or higher OS + Processor is required.');
define('EMAIL_CONFIRM', false);
define('SQL_DEBUG', 1);
define('XBT_TRACKER', true);
//==charset
$TRINITY['char_set'] = 'UTF-8'; //also to be used site wide in meta tags
if (ini_get('default_charset') != $TRINITY['char_set']) {
    ini_set('default_charset', $TRINITY['char_set']);
}
//== Windows fix
if( !function_exists( 'sys_getloadavg' ) ){
  function sys_getloadavg(){
  return array( 0, 0, 0 );
  }
}
/* Compare php version for date/time stuff etc! */
date_default_timezone_set('Europe/London');
define('TIME_NOW', time());
define('TIME_DATE', (new DateTime())->format('Y-m-d H:i:s'));
$TRINITY['time_adjust'] = 0;
$TRINITY['time_offset'] = 0;
$TRINITY['time_use_relative'] = 1;
$TRINITY['time_use_relative_format'] = '{--}, h:i A';
$TRINITY['time_joined'] = 'j-F y';
$TRINITY['time_short'] = 'jS F Y - h:i A';
$TRINITY['time_long'] = 'M j Y, h:i A';
$TRINITY['time_ttable'] = 'd-m-Y, H:i:s';
$TRINITY['time_tiny'] = '';
$TRINITY['time_date'] = '';
//== DB setup
$TRINITY['mysql_host'] = '#mysql_host';
$TRINITY['mysql_user'] = '#mysql_user';
$TRINITY['mysql_pass'] = '#mysql_pass';
$TRINITY['mysql_db'] = '#mysql_db';
//== Cookie setup
$TRINITY['cookie_prefix'] = '#cookie_prefix'; // This allows you to have multiple trackers, eg for demos, testing etc.
$TRINITY['cookie_path'] = '#cookie_path'; // ATTENTION: You should never need this unless the above applies eg: /tbdev
$TRINITY['cookie_domain'] = '#cookie_domain'; // set to eg: .somedomain.com or is subdomain set to: .sub.somedomain.com
$TRINITY['domain'] = '#domain';
//== Memcache expires
$TRINITY['expires']['latestuser'] = 0; // 0 = infinite
$TRINITY['expires']['MyPeers_'] = 120; // 60 = 60 seconds
$TRINITY['expires']['unread'] = 86400; // 86400 = 1 day
$TRINITY['expires']['alerts'] = 0; // 0 = infinite
$TRINITY['expires']['searchcloud'] = 0; // 0 = infinite
$TRINITY['expires']['user_cache'] = 30 * 86400; // 30 days
$TRINITY['expires']['curuser'] = 30 * 86400; // 30 days
$TRINITY['expires']['u_status'] = 30 * 84600; // 30x86400 = 30 days
$TRINITY['expires']['u_stats'] = 300; // 300 = 5 min
$TRINITY['expires']['u_stats_xbt'] = 30; // 30 seconds
$TRINITY['expires']['user_status'] = 30 * 84600; // 30x86400 = 30 days
$TRINITY['expires']['user_stats'] = 300; // 300 = 5 min
$TRINITY['expires']['user_stats_xbt'] = 30; // 30 seconds
$TRINITY['expires']['MyPeers_xbt_'] = 30;
$TRINITY['expires']['announcement'] = 600; // 600 = 10 min
$TRINITY['expires']['shoutbox'] = 86400; // 86400 = 1 day
$TRINITY['expires']['staff_shoutbox'] = 86400; // 86400 = 1 day
$TRINITY['expires']['forum_posts'] = 0;
$TRINITY['expires']['torrent_comments'] = 900; // 900 = 15 min
$TRINITY['expires']['latestposts'] = 0; // 900 = 15 min
$TRINITY['expires']['top5_torrents'] = 0; // 0 = infinite
$TRINITY['expires']['last5_torrents'] = 0; // 0 = infinite
$TRINITY['expires']['scroll_torrents'] = 0; // 0 = infinite
$TRINITY['expires']['torrent_details'] = 30 * 86400; // = 30 days
$TRINITY['expires']['torrent_details_text'] = 30 * 86400; // = 30 days
$TRINITY['expires']['insertJumpTo'] = 30 * 86400; // = 30 days
$TRINITY['expires']['get_all_boxes'] = 30 * 86400; // = 30 days
$TRINITY['expires']['thumbsup'] = 0; // 0 = infinite
$TRINITY['expires']['iphistory'] = 900; // 900 = 15 min
$TRINITY['expires']['newpoll'] = 0; // 900 = 15 min
$TRINITY['expires']['genrelist'] = 30 * 86400; // 30x86400 = 30 days
$TRINITY['expires']['genrelist2'] = 30 * 86400; // 30x86400 = 30 days
$TRINITY['expires']['poll_data'] = 900; // 300 = 5 min
$TRINITY['expires']['torrent_data'] = 900; // 900 = 15 min
$TRINITY['expires']['user_flag'] = 86400 * 28; // 900 = 15 min
$TRINITY['expires']['shit_list'] = 900; // 900 = 15 min
$TRINITY['expires']['port_data'] = 900; // 900 = 15 min
$TRINITY['expires']['port_data_xbt'] = 900; // 900 = 15 min
$TRINITY['expires']['user_peers'] = 900; // 900 = 15 min
$TRINITY['expires']['user_friends'] = 900; // 900 = 15 min
$TRINITY['expires']['user_hash'] = 900; // 900 = 15 min
$TRINITY['expires']['user_blocks'] = 900; // 900 = 15 min
$TRINITY['expires']['hnr_data'] = 300; // 300 = 5 min
$TRINITY['expires']['snatch_data'] = 300; // 300 = 5 min
$TRINITY['expires']['user_snatches_data'] = 300; // 300 = 5 min
$TRINITY['expires']['staff_snatches_data'] = 300; // 300 = 5 min
$TRINITY['expires']['user_snatches_complete'] = 300; // 300 = 5 min
$TRINITY['expires']['completed_torrents'] = 300; // 300 = 5 min
$TRINITY['expires']['activeusers'] = 60; // 60 = 1 minutes
$TRINITY['expires']['forum_users'] = 30; // 60 = 1 minutes
$TRINITY['expires']['section_view'] = 30; // 60 = 1 minutes
$TRINITY['expires']['child_boards'] = 900; // 60 = 1 minutes
$TRINITY['expires']['sv_child_boards'] = 900; // 60 = 1 minutes
$TRINITY['expires']['forum_insertJumpTo'] = 3600; // = 1 hour
$TRINITY['expires']['last_post'] = 0; // infinite
$TRINITY['expires']['sv_last_post'] = 0; // infinite
$TRINITY['expires']['last_read_post'] = 0; // infinite
$TRINITY['expires']['sv_last_read_post'] = 0; // infinite
$TRINITY['expires']['last24'] = 3600; // 3600 = 1 hours
$TRINITY['expires']['activeircusers'] = 300; // 300 = 5 min
$TRINITY['expires']['birthdayusers'] = 43200; //== 43200 = 12 hours
$TRINITY['expires']['news_users'] = 3600; // 3600 = 1 hours
$TRINITY['expires']['user_invitees'] = 900; // 900 = 15 min
$TRINITY['expires']['ip_data'] = 900; // 900 = 15 min
$TRINITY['expires']['latesttorrents'] = 0; // 0 = infinite
$TRINITY['expires']['invited_by'] = 900; // 900 = 15 min
$TRINITY['expires']['user_torrents'] = 900; // 900 = 15 min
$TRINITY['expires']['user_seedleech'] = 900; // 900 = 15 min
$TRINITY['expires']['radio'] = 0; // 0 = infinite
$TRINITY['expires']['total_funds'] = 0; // 0 = infinite
$TRINITY['expires']['latest_news'] = 0; // 0 = infinite
$TRINITY['expires']['site_stats'] = 300; // 300 = 5 min
$TRINITY['expires']['share_ratio'] = 900; // 900 = 15 min
$TRINITY['expires']['share_ratio_xbt'] = 900; // 900 = 15 min
$TRINITY['expires']['checked_by'] = 0; // 0 = infinite
$TRINITY['expires']['sanity'] = 0; // 0 = infinite
$TRINITY['expires']['movieofweek'] = 300; // 604800 = 1 week
$TRINITY['expires']['browse_where'] = 60; // 60 = 60 seconds
$TRINITY['expires']['torrent_xbt_data'] = 300; // 300 = 5 min
$TRINITY['expires']['ismoddin'] = 0; // 0 = infinite
$TRINITY['expires']['faqs'] = 0;  // 0 = infinite
$TRINITY['expires']['rules'] = 0; // 0 = infinite
$TRINITY['expires']['torrent_pretime'] = 0; // 0 = infinite
$TRINITY['expires']['req_limit'] = 3600; // 3600 = 1 hour
$TRINITY['expires']['off_limit'] = 3600; // 3600 = 1 hour
$TRINITY['expires']['staff_check'] = 3600; // 3600 = 1 hour
$TRINITY['expires']['details_snatchlist'] = 300; // 300 = 5 min
//== Tracker configs
$TRINITY['cipher_key']['key'] = 'fsdsf@sadadjk@@@@4453qaw0qw';
$TRINITY['tracker_post_key'] = 'lsdflksfda4545frwe35@kk';
$TRINITY['max_torrent_size'] = 3 * 1024 * 1024;
$TRINITY['announce_interval'] = 60 * 30;
$TRINITY['signup_timeout'] = 86400 * 3;
$TRINITY['autoclean_interval'] = 1800;
$TRINITY['minvotes'] = 1;
$TRINITY['max_dead_torrent_time'] = 6 * 3600;
$TRINITY['language'] = 1;
$TRINITY['bot_id'] = 2;
$TRINITY['bot_name'] = 'U232_bot';
$TRINITY['bot_role'] = 8;
$TRINITY['staffpanel_online'] = 1;
$TRINITY['irc_autoshout_on'] = 1;
$TRINITY['wait_times'] = 0;
$TRINITY['max_slots'] = 0;
$TRINITY['crazy_hour'] = false; //== Off for XBT
$TRINITY['happy_hour'] = false; //== Off for XBT
$TRINITY['rep_sys_on'] = true;
$TRINITY['achieve_sys_on'] = true;
$TRINITY['mood_sys_on'] = true;
$TRINITY['mods']['slots'] = true;
$TRINITY['votesrequired'] = 15;
$TRINITY['catsperrow'] = 7;
$TRINITY['maxwidth'] = '90%';
//== Latest posts limit
$TRINITY['latest_posts_limit'] = 5; //query limit for latest forum posts on index
//latest torrents limit
$TRINITY['latest_torrents_limit'] = 5;
$TRINITY['latest_torrents_limit_2'] = 5;
$TRINITY['latest_torrents_limit_scroll'] = 20;
/** Settings **/
$TRINITY['reports'] = 1; // 1/0 on/off
$TRINITY['karma'] = 1; // 1/0 on/off
$TRINITY['BBcode'] = 1; // 1/0 on/off
$TRINITY['inviteusers'] = 10000;
$TRINITY['flood_time'] = 900; //comment/forum/pm flood limit
$TRINITY['readpost_expiry'] = 14 * 86400; // 14 days
$TRINITY['shouts_to_show'] = 30;
/** define dirs **/
define('INCL_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(INCL_DIR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
define('ADMIN_DIR', ROOT_DIR . 'admin' . DIRECTORY_SEPARATOR);
define('FORUM_DIR', ROOT_DIR . 'forums' . DIRECTORY_SEPARATOR);
define('PM_DIR', ROOT_DIR . 'pm_system' . DIRECTORY_SEPARATOR);
define('PIMP_DIR', ROOT_DIR . 'PimpMyLog' . DIRECTORY_SEPARATOR);
define('CACHE_DIR', ROOT_DIR . 'cache' . DIRECTORY_SEPARATOR);
define('MODS_DIR', ROOT_DIR . 'mods' . DIRECTORY_SEPARATOR);
define('LANG_DIR', ROOT_DIR . 'lang' . DIRECTORY_SEPARATOR);
define('TEMPLATE_DIR', ROOT_DIR . 'templates' . DIRECTORY_SEPARATOR);
define('BLOCK_DIR', ROOT_DIR . 'blocks' . DIRECTORY_SEPARATOR);
define('IMDB_DIR', ROOT_DIR . 'imdb' . DIRECTORY_SEPARATOR);
define('CLASS_DIR', INCL_DIR . 'class' . DIRECTORY_SEPARATOR);
define('CLEAN_DIR', INCL_DIR . 'cleanup' . DIRECTORY_SEPARATOR);
define('BITBUCKET_DIR', DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.'bucket');
define('AJAXCHAT_DIR', ROOT_DIR . 'chat' . DIRECTORY_SEPARATOR);
define('VENDOR_DIR', ROOT_DIR . 'vendor' . DIRECTORY_SEPARATOR);
$TRINITY['cache'] = ROOT_DIR . 'cache';
$TRINITY['backup_dir'] = INCL_DIR . 'backup';
$TRINITY['sub_up_dir'] = ROOT_DIR . 'uploadsub';
$TRINITY['dictbreaker'] = ROOT_DIR . 'dictbreaker';
$TRINITY['torrent_dir'] = ROOT_DIR . 'torrents'; // must be writable for httpd user
$TRINITY['flood_file'] = INCL_DIR . 'settings' . DIRECTORY_SEPARATOR . 'limitfile.txt';
$TRINITY['nameblacklist'] = ROOT_DIR . 'cache' . DIRECTORY_SEPARATOR . 'nameblacklist.txt';
$TRINITY['happyhour'] = CACHE_DIR . 'happyhour' . DIRECTORY_SEPARATOR . 'happyhour.txt';
$TRINITY['sql_error_log'] = ROOT_DIR . 'sqlerr_logs' . DIRECTORY_SEPARATOR . 'sql_err_' . date('M_D_Y') . '.log';
$TRINITY['php_error_log'] = ROOT_DIR . 'phperr_log' . DIRECTORY_SEPARATOR . 'php_err_' . date('M_D_Y') . '.log';
ini_set("error_log", "". $TRINITY['php_error_log'] .""); 
//== XBT or PHP announce
if (XBT_TRACKER == true) {
$TRINITY['xbt_prefix'] = '#announce_urls:2710/';  
$TRINITY['xbt_suffix'] = '/announce';
$TRINITY['announce_urls'][] = '#announce_urls:2710/announce';
} else {
$TRINITY['announce_urls'] = array();
$TRINITY['announce_urls'][] = '#announce_urls';
$TRINITY['announce_urls'][] = '#announce_https';
}
if (isset($_SERVER["HTTP_HOST"]) &&  $_SERVER["HTTP_HOST"] == "") $_SERVER["HTTP_HOST"] = $_SERVER["SERVER_NAME"];
$TRINITY['baseurl'] = 'http' . (isset($_SERVER['HTTPS']) && (bool)$_SERVER['HTTPS'] == true ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
//== Email for sender/return path.
$TRINITY['sub_max_size'] = 500 * 1024;
$TRINITY['site_email'] = '#site_email';
$TRINITY['site_name'] = "#site_name";
$TRINITY['msg_alert'] = 1; // saves a query when off
$TRINITY['report_alert'] = 1; // saves a query when off
$TRINITY['staffmsg_alert'] = 1; // saves a query when off
$TRINITY['uploadapp_alert'] = 1; // saves a query when off
$TRINITY['bug_alert'] = 1; // saves a query when off
$TRINITY['pic_base_url'] = "./pic/";
$TRINITY['logo'] = "./pic/banner.png";
$TRINITY['stylesheet'] = 1;
$TRINITY['categorie_icon'] = 1;
$TRINITY['comment_min_class'] = 4; //minim class to be checked when posting comments
$TRINITY['comment_check'] = 1; //set it to 0 if you wanna allow commenting with out staff checking 
$TRINITY['requests']['req_limit'] = 10;
$TRINITY['offers']['off_limit'] = 10;
//for subs & youtube mode
$TRINITY['movie_cats'] = array(
    3,
    5,
    6,
    10,
    11
);
$TRINITY['tv_cats'] = array(
    11
);
$youtube_pattern = "/^http(s)?\:\/\/www\.youtube\.com\/watch\?v\=[\w-]{11}/i";
//== set this to size of user avatars
$TRINITY['av_img_height'] = 100;
$TRINITY['av_img_width'] = 100;
//== set this to size of user signatures
$TRINITY['sig_img_height'] = 100;
$TRINITY['sig_img_width'] = 500;
$TRINITY['bucket_allowed'] = 0;
$TRINITY['allowed_ext'] = array(
    'image/gif',
    'image/png',
    'image/jpg',
    'image/jpeg'
);
$TRINITY['bucket_maxsize'] = 500 * 1024; //max size set to 500kb
//==Class check by pdq
$TRINITY['site']['owner'] = 1;
//== Salt - change this
$TRINITY['site']['salt2'] = 'jgutyxcjsaka';
//= Change staff pin daily or weekly
$TRINITY['staff']['staff_pin'] = 'uFie0y3Ihjkij8'; // should be mix of u/l case and min 12 chars length
//= Change owner pin daily or weekly
$TRINITY['staff']['owner_pin'] = 'jjko4kuogqhjj0'; // should be mix of u/l case and min 12 chars length
//== Staff forum ID for autopost
$TRINITY['staff']['forumid'] = 2; // this forum ID should exist and be a staff forum
$TRINITY['staff_forums'] = array(
    1,
    2
); // these forum ID's' should exist and be a staff forum's to stop autoshouts
$TRINITY['variant'] = 'Codename Trinity';
define('TBVERSION', $TRINITY['variant']);
?>
