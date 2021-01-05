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
$TRINITY20['error_reports']['debugmode'] = 1;
if ($TRINITY20['error_reports']['debugmode'] == 1) {
    error_reporting(E_ALL); 
}else { 
    error_reporting(0); 
}
if (PHP_INT_SIZE < 8)
die('A 64bit or higher OS + Processor is required.');
define('EMAIL_CONFIRM', false);
define('SQL_DEBUG', 1);
define('XBT_TRACKER', false);
//==charset
$TRINITY20['char_set'] = 'UTF-8'; //also to be used site wide in meta tags
if (ini_get('default_charset') != $TRINITY20['char_set']) {
    ini_set('default_charset', $TRINITY20['char_set']);
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
$TRINITY20['time_adjust'] = 0;
$TRINITY20['time_offset'] = 0;
$TRINITY20['time_use_relative'] = 1;
$TRINITY20['time_use_relative_format'] = '{--}, h:i A';
$TRINITY20['time_joined'] = 'j-F y';
$TRINITY20['time_short'] = 'jS F Y - h:i A';
$TRINITY20['time_long'] = 'M j Y, h:i A';
$TRINITY20['time_ttable'] = 'd-m-Y, H:i:s';
$TRINITY20['time_tiny'] = '';
$TRINITY20['time_date'] = '';
//== DB setup
$TRINITY20['mysql_host'] = '#mysql_host';
$TRINITY20['mysql_user'] = '#mysql_user';
$TRINITY20['mysql_pass'] = '#mysql_pass';
$TRINITY20['mysql_db'] = '#mysql_db';
//== Cookie setup
$TRINITY20['cookie_prefix'] = '#cookie_prefix'; // This allows you to have multiple trackers, eg for demos, testing etc.
$TRINITY20['cookie_path'] = '#cookie_path'; // ATTENTION: You should never need this unless the above applies eg: /tbdev
$TRINITY20['cookie_domain'] = '#cookie_domain'; // set to eg: .somedomain.com or is subdomain set to: .sub.somedomain.com
$TRINITY20['domain'] = '#domain';
//== Memcache expires
$TRINITY20['expires']['latestuser'] = 0; // 0 = infinite
$TRINITY20['expires']['MyPeers_'] = 120; // 60 = 60 seconds
$TRINITY20['expires']['unread'] = 86400; // 86400 = 1 day
$TRINITY20['expires']['alerts'] = 0; // 0 = infinite
$TRINITY20['expires']['searchcloud'] = 0; // 0 = infinite
$TRINITY20['expires']['user_cache'] = 30 * 86400; // 30 days
$TRINITY20['expires']['curuser'] = 30 * 86400; // 30 days
$TRINITY20['expires']['u_status'] = 30 * 84600; // 30x86400 = 30 days
$TRINITY20['expires']['u_stats'] = 300; // 300 = 5 min
$TRINITY20['expires']['u_stats_xbt'] = 30; // 30 seconds
$TRINITY20['expires']['user_status'] = 30 * 84600; // 30x86400 = 30 days
$TRINITY20['expires']['user_stats'] = 300; // 300 = 5 min
$TRINITY20['expires']['user_stats_xbt'] = 30; // 30 seconds
$TRINITY20['expires']['MyPeers_xbt_'] = 30;
$TRINITY20['expires']['announcement'] = 600; // 600 = 10 min
$TRINITY20['expires']['shoutbox'] = 86400; // 86400 = 1 day
$TRINITY20['expires']['staff_shoutbox'] = 86400; // 86400 = 1 day
$TRINITY20['expires']['forum_posts'] = 0;
$TRINITY20['expires']['torrent_comments'] = 900; // 900 = 15 min
$TRINITY20['expires']['latestposts'] = 0; // 900 = 15 min
$TRINITY20['expires']['top5_torrents'] = 0; // 0 = infinite
$TRINITY20['expires']['last5_torrents'] = 0; // 0 = infinite
$TRINITY20['expires']['scroll_torrents'] = 0; // 0 = infinite
$TRINITY20['expires']['torrent_details'] = 30 * 86400; // = 30 days
$TRINITY20['expires']['torrent_details_text'] = 30 * 86400; // = 30 days
$TRINITY20['expires']['insertJumpTo'] = 30 * 86400; // = 30 days
$TRINITY20['expires']['get_all_boxes'] = 30 * 86400; // = 30 days
$TRINITY20['expires']['thumbsup'] = 0; // 0 = infinite
$TRINITY20['expires']['iphistory'] = 900; // 900 = 15 min
$TRINITY20['expires']['newpoll'] = 0; // 900 = 15 min
$TRINITY20['expires']['genrelist'] = 30 * 86400; // 30x86400 = 30 days
$TRINITY20['expires']['genrelist2'] = 30 * 86400; // 30x86400 = 30 days
$TRINITY20['expires']['poll_data'] = 900; // 300 = 5 min
$TRINITY20['expires']['torrent_data'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_flag'] = 86400 * 28; // 900 = 15 min
$TRINITY20['expires']['shit_list'] = 900; // 900 = 15 min
$TRINITY20['expires']['port_data'] = 900; // 900 = 15 min
$TRINITY20['expires']['port_data_xbt'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_peers'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_friends'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_hash'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_blocks'] = 900; // 900 = 15 min
$TRINITY20['expires']['hnr_data'] = 300; // 300 = 5 min
$TRINITY20['expires']['snatch_data'] = 300; // 300 = 5 min
$TRINITY20['expires']['user_snatches_data'] = 300; // 300 = 5 min
$TRINITY20['expires']['staff_snatches_data'] = 300; // 300 = 5 min
$TRINITY20['expires']['user_snatches_complete'] = 300; // 300 = 5 min
$TRINITY20['expires']['completed_torrents'] = 300; // 300 = 5 min
$TRINITY20['expires']['activeusers'] = 60; // 60 = 1 minutes
$TRINITY20['expires']['forum_users'] = 30; // 60 = 1 minutes
$TRINITY20['expires']['section_view'] = 30; // 60 = 1 minutes
$TRINITY20['expires']['child_boards'] = 900; // 60 = 1 minutes
$TRINITY20['expires']['sv_child_boards'] = 900; // 60 = 1 minutes
$TRINITY20['expires']['forum_insertJumpTo'] = 3600; // = 1 hour
$TRINITY20['expires']['last_post'] = 0; // infinite
$TRINITY20['expires']['sv_last_post'] = 0; // infinite
$TRINITY20['expires']['last_read_post'] = 0; // infinite
$TRINITY20['expires']['sv_last_read_post'] = 0; // infinite
$TRINITY20['expires']['last24'] = 3600; // 3600 = 1 hours
$TRINITY20['expires']['birthdayusers'] = 43200; //== 43200 = 12 hours
$TRINITY20['expires']['news_users'] = 3600; // 3600 = 1 hours
$TRINITY20['expires']['user_invitees'] = 900; // 900 = 15 min
$TRINITY20['expires']['ip_data'] = 900; // 900 = 15 min
$TRINITY20['expires']['latesttorrents'] = 0; // 0 = infinite
$TRINITY20['expires']['invited_by'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_torrents'] = 900; // 900 = 15 min
$TRINITY20['expires']['user_seedleech'] = 900; // 900 = 15 min
$TRINITY20['expires']['radio'] = 0; // 0 = infinite
$TRINITY20['expires']['total_funds'] = 0; // 0 = infinite
$TRINITY20['expires']['latest_news'] = 0; // 0 = infinite
$TRINITY20['expires']['site_stats'] = 300; // 300 = 5 min
$TRINITY20['expires']['share_ratio'] = 900; // 900 = 15 min
$TRINITY20['expires']['share_ratio_xbt'] = 900; // 900 = 15 min
$TRINITY20['expires']['checked_by'] = 0; // 0 = infinite
$TRINITY20['expires']['sanity'] = 0; // 0 = infinite
$TRINITY20['expires']['movieofweek'] = 300; // 604800 = 1 week
$TRINITY20['expires']['browse_where'] = 60; // 60 = 60 seconds
$TRINITY20['expires']['torrent_xbt_data'] = 300; // 300 = 5 min
$TRINITY20['expires']['ismoddin'] = 0; // 0 = infinite
$TRINITY20['expires']['faqs'] = 0;  // 0 = infinite
$TRINITY20['expires']['rules'] = 0; // 0 = infinite
$TRINITY20['expires']['torrent_pretime'] = 0; // 0 = infinite
$TRINITY20['expires']['req_limit'] = 3600; // 3600 = 1 hour
$TRINITY20['expires']['off_limit'] = 3600; // 3600 = 1 hour
$TRINITY20['expires']['staff_check'] = 3600; // 3600 = 1 hour
$TRINITY20['expires']['details_snatchlist'] = 300; // 300 = 5 min
//== Tracker configs
$TRINITY20['cipher_key']['key'] = 'fsdsf@sadadjk@@@@4453qaw0qw';
$TRINITY20['tracker_post_key'] = 'lsdflksfda4545frwe35@kk';
$TRINITY20['max_torrent_size'] = 3 * 1024 * 1024;
$TRINITY20['announce_interval'] = 60 * 30;
$TRINITY20['signup_timeout'] = 86400 * 3;
$TRINITY20['autoclean_interval'] = 1800;
$TRINITY20['minvotes'] = 1;
$TRINITY20['max_dead_torrent_time'] = 6 * 3600;
$TRINITY20['language'] = 1;
$TRINITY20['bot_id'] = 2;
$TRINITY20['bot_name'] = 'U232_bot';
$TRINITY20['bot_role'] = 8;          
$TRINITY20['staffpanel_online'] = 1;
$TRINITY20['wait_times'] = 0;
$TRINITY20['max_slots'] = 0;
$TRINITY20['crazy_hour'] = false; //== Off for XBT
$TRINITY20['happy_hour'] = false; //== Off for XBT
$TRINITY20['rep_sys_on'] = true;
$TRINITY20['achieve_sys_on'] = true;
$TRINITY20['mood_sys_on'] = true;
$TRINITY20['mods']['slots'] = true;
$TRINITY20['votesrequired'] = 15;
$TRINITY20['catsperrow'] = 7;
$TRINITY20['maxwidth'] = '90%';
//== Latest posts limit
$TRINITY20['latest_posts_limit'] = 5; //query limit for latest forum posts on index
//latest torrents limit
$TRINITY20['latest_torrents_limit'] = 5;
$TRINITY20['latest_torrents_limit_2'] = 5;
$TRINITY20['latest_torrents_limit_scroll'] = 20;
/** Settings **/
$TRINITY20['reports'] = 1; // 1/0 on/off
$TRINITY20['karma'] = 1; // 1/0 on/off
$TRINITY20['BBcode'] = 1; // 1/0 on/off
$TRINITY20['inviteusers'] = 10000;
$TRINITY20['flood_time'] = 900; //comment/forum/pm flood limit
$TRINITY20['readpost_expiry'] = 14 * 86400; // 14 days
$TRINITY20['shouts_to_show'] = 30;
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
$TRINITY20['cache'] = ROOT_DIR . 'cache';
$TRINITY20['backup_dir'] = INCL_DIR . 'backup';
$TRINITY20['sub_up_dir'] = ROOT_DIR . 'uploadsub';
$TRINITY20['dictbreaker'] = ROOT_DIR . 'dictbreaker';
$TRINITY20['torrent_dir'] = ROOT_DIR . 'torrents'; // must be writable for httpd user
$TRINITY20['flood_file'] = INCL_DIR . 'settings' . DIRECTORY_SEPARATOR . 'limitfile.txt';
$TRINITY20['nameblacklist'] = ROOT_DIR . 'cache' . DIRECTORY_SEPARATOR . 'nameblacklist.txt';
$TRINITY20['happyhour'] = CACHE_DIR . 'happyhour' . DIRECTORY_SEPARATOR . 'happyhour.txt';
$TRINITY20['sql_error_log'] = ROOT_DIR . 'sqlerr_logs' . DIRECTORY_SEPARATOR . 'sql_err_' . date('M_D_Y') . '.log';
$TRINITY20['php_error_log'] = ROOT_DIR . 'phperr_logs' . DIRECTORY_SEPARATOR . 'php_err_' . date('M_D_Y') . '.log';
ini_set("error_log", "". $TRINITY20['php_error_log'] .""); 
//== XBT or PHP announce
if (XBT_TRACKER == true) {
$TRINITY20['xbt_prefix'] = '#announce_urls:2710/';  
$TRINITY20['xbt_suffix'] = '/announce';
$TRINITY20['announce_urls'] = '#announce_urls:2710/announce';
} else {
$TRINITY20['announce_urls'] = '#announce_https';
}
if (isset($_SERVER["HTTP_HOST"]) &&  $_SERVER["HTTP_HOST"] == "") $_SERVER["HTTP_HOST"] = $_SERVER["SERVER_NAME"];
$TRINITY20['baseurl'] = 'http' . (isset($_SERVER['HTTPS']) && (bool)$_SERVER['HTTPS'] == true ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];
//== Email for sender/return path.
$TRINITY20['sub_max_size'] = 500 * 1024;
$TRINITY20['site_email'] = '#site_email';
$TRINITY20['site_name'] = "#site_name";
$TRINITY20['msg_alert'] = 1; // saves a query when off
$TRINITY20['report_alert'] = 1; // saves a query when off
$TRINITY20['staffmsg_alert'] = 1; // saves a query when off
$TRINITY20['uploadapp_alert'] = 1; // saves a query when off
$TRINITY20['bug_alert'] = 1; // saves a query when off
$TRINITY20['pic_base_url'] = "./pic/";
$TRINITY20['logo'] = "./pic/banner.png";
$TRINITY20['stylesheet'] = 1;
$TRINITY20['categorie_icon'] = 1;
$TRINITY20['comment_min_class'] = 4; //minim class to be checked when posting comments
$TRINITY20['comment_check'] = 1; //set it to 0 if you wanna allow commenting with out staff checking 
$TRINITY20['requests']['req_limit'] = 10;
$TRINITY20['offers']['off_limit'] = 10;
//for subs & youtube mode
$TRINITY20['movie_cats'] = array(
    3,
    5,
    6,
    10,
    11
);
$TRINITY20['tv_cats'] = array(
    11
);
$youtube_pattern = "/^http(s)?\:\/\/www\.youtube\.com\/watch\?v\=[\w-]{11}/i";
//== set this to size of user avatars
$TRINITY20['av_img_height'] = 100;
$TRINITY20['av_img_width'] = 100;
//== set this to size of user signatures
$TRINITY20['sig_img_height'] = 100;
$TRINITY20['sig_img_width'] = 500;
$TRINITY20['bucket_allowed'] = 0;
$TRINITY20['allowed_ext'] = array(
    'image/gif',
    'image/png',
    'image/jpg',
    'image/jpeg'
);
$TRINITY20['bucket_maxsize'] = 500 * 1024; //max size set to 500kb
//==Class check by pdq
$TRINITY20['site']['owner'] = 1;
//== Salt - change this
$TRINITY20['site']['salt2'] = 'jgutyxcjsaka';
//= Change staff pin daily or weekly
$TRINITY20['staff']['staff_pin'] = 'uFie0y3Ihjkij8'; // should be mix of u/l case and min 12 chars length
//= Change owner pin daily or weekly
$TRINITY20['staff']['owner_pin'] = 'jjko4kuogqhjj0'; // should be mix of u/l case and min 12 chars length
//== Staff forum ID for autopost
$TRINITY20['staff']['forumid'] = 2; // this forum ID should exist and be a staff forum
$TRINITY20['staff_forums'] = array(
    1,
    2
); // these forum ID's' should exist and be a staff forum's to stop autoshouts
$TRINITY20['variant'] = 'Codename Trinity';
define('TBVERSION', $TRINITY20['variant']);
?>
