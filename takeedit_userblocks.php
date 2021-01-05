<?php
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
dbconn();
loggedinorreturn();
$newpage = new page_verify();
$newpage->check('tkeepe');
$updateset = $user_block_cache = '';
$action = isset($_POST["action"]) ? htmlsafechars(trim($_POST["action"])) : '';
$update_block = $user_block_cache = array();
if ($action == "stdhead_options")
{
	if (isset($_POST["stdhead_freelech_on"]) && ($stdhead_freelech_on = $_POST["stdhead_freelech_on"])){
    $update_block[] = "stdhead_freelech_on = " . sqlesc($stdhead_freelech_on);
    $user_block_cache['stdhead_freelech_on'] = $stdhead_freelech_on;
    }
	if (isset($_POST["stdhead_demotion_on"]) && ($stdhead_demotion_on = $_POST["stdhead_demotion_on"])){
    $update_block[] = "stdhead_demotion_on = " . sqlesc($stdhead_demotion_on);
    $user_block_cache['stdhead_demotion_on'] = $stdhead_demotion_on;
    }
	if (isset($_POST["stdhead_newpm_on"]) && ($stdhead_newpm_on = $_POST["stdhead_newpm_on"])){
    $update_block[] = "stdhead_newpm_on = " . sqlesc($stdhead_newpm_on);
    $user_block_cache['stdhead_newpm_on'] = $stdhead_newpm_on;
    }
	if (isset($_POST["stdhead_staff_message_on"]) && ($stdhead_staff_message_on = $_POST["stdhead_staff_message_on"])){
    $update_block[] = "stdhead_staff_message_on = " . sqlesc($stdhead_staff_message_on);
    $user_block_cache['stdhead_staff_message_on'] = $stdhead_staff_message_on;
    }
	if (isset($_POST["stdhead_reports_on"]) && ($stdhead_reports_on = $_POST["stdhead_reports_on"])){
    $update_block[] = "stdhead_reports_on = " . sqlesc($stdhead_reports_on);
    $user_block_cache['stdhead_reports_on'] = $stdhead_reports_on;
    }
	if (isset($_POST["stdhead_uploadapp_on"]) && ($stdhead_uploadapp_on = $_POST["stdhead_uploadapp_on"])){
    $update_block[] = "stdhead_uploadapp_on = " . sqlesc($stdhead_uploadapp_on);
    $user_block_cache['stdhead_uploadapp_on'] = $stdhead_uploadapp_on;
    }
	if (isset($_POST["stdhead_uploadapp_on"]) && ($stdhead_uploadapp_on = $_POST["stdhead_uploadapp_on"])){
    $update_block[] = "stdhead_uploadapp_on = " . sqlesc($stdhead_uploadapp_on);
    $user_block_cache['stdhead_uploadapp_on'] = $stdhead_uploadapp_on;
    }
	if (isset($_POST["stdhead_crazyhour_on"]) && ($stdhead_crazyhour_on = $_POST["stdhead_crazyhour_on"])){
    $update_block[] = "stdhead_crazyhour_on = " . sqlesc($stdhead_crazyhour_on);
    $user_block_cache['stdhead_crazyhour_on'] = $stdhead_crazyhour_on;
    }
	if (isset($_POST["stdhead_bugmessage_on"]) && ($stdhead_bugmessage_on = $_POST["stdhead_bugmessage_on"])){
    $update_block[] = "stdhead_bugmessage_on = " . sqlesc($stdhead_bugmessage_on);
    $user_block_cache['stdhead_bugmessage_on'] = $stdhead_bugmessage_on;
    }
	if (isset($_POST["stdhead_freeleech_contribution_on"]) && ($stdhead_freeleech_contribution_on = $_POST["stdhead_freeleech_contribution_on"])){
    $update_block[] = "stdhead_freeleech_contribution_on = " . sqlesc($stdhead_freeleech_contribution_on);
    $user_block_cache['stdhead_freeleech_contribution_on'] = $stdhead_freeleech_contribution_on;
    }
	if (isset($_POST["stdhead_stafftools_on"]) && ($stdhead_stafftools_on = $_POST["stdhead_stafftools_on"])){
    $update_block[] = "stdhead_stafftools_on = " . sqlesc($stdhead_stafftools_on);
    $user_block_cache['stdhead_stafftools_on'] = $stdhead_stafftools_on;
    }
	$action = "stdhead_options";
} elseif ($action == "userdetails_options")
{
	if (isset($_POST["userdetails_login_link_on"]) && ($userdetails_login_link_on = $_POST["userdetails_login_link_on"])){
    $update_block[] = "userdetails_login_link_on = " . sqlesc($userdetails_login_link_on);
    $user_block_cache['userdetails_login_link_on'] = $userdetails_login_link_on;
    }
	if (isset($_POST["userdetails_flush_on"]) && ($userdetails_flush_on = $_POST["userdetails_flush_on"])){
    $update_block[] = "userdetails_flush_on = " . sqlesc($userdetails_flush_on);
    $user_block_cache['userdetails_flush_on'] = $userdetails_flush_on;
    }
	if (isset($_POST["userdetails_joined_on"]) && ($userdetails_joined_on = $_POST["userdetails_joined_on"])){
    $update_block[] = "userdetails_joined_on = " . sqlesc($userdetails_joined_on);
    $user_block_cache['userdetails_joined_on'] = $userdetails_joined_on;
    }
	if (isset($_POST["userdetails_online_time_on"]) && ($userdetails_online_time_on = $_POST["userdetails_online_time_on"])){
    $update_block[] = "userdetails_online_time_on = " . sqlesc($userdetails_online_time_on);
    $user_block_cache['userdetails_online_time_on'] = $userdetails_online_time_on;
    }
	if (isset($_POST["userdetails_browser_on"]) && ($userdetails_browser_on = $_POST["userdetails_browser_on"])){
    $update_block[] = "userdetails_browser_on = " . sqlesc($userdetails_browser_on);
    $user_block_cache['userdetails_browser_on'] = $userdetails_browser_on;
    }
	if (isset($_POST["userdetails_reputation_on"]) && ($userdetails_reputation_on = $_POST["userdetails_reputation_on"])){
    $update_block[] = "userdetails_reputation_on = " . sqlesc($userdetails_reputation_on);
    $user_block_cache['userdetails_reputation_on'] = $userdetails_reputation_on;
    }
	if (isset($_POST["userdetails_user_hits_on"]) && ($userdetails_user_hits_on = $_POST["userdetails_user_hits_on"])){
    $update_block[] = "userdetails_user_hits_on = " . sqlesc($userdetails_user_hits_on);
    $user_block_cache['userdetails_user_hits_on'] = $userdetails_user_hits_on;
    }
	if (isset($_POST["userdetails_birthday_on"]) && ($userdetails_birthday_on = $_POST["userdetails_birthday_on"])){
    $update_block[] = "userdetails_birthday_on = " . sqlesc($userdetails_birthday_on);
    $user_block_cache['userdetails_birthday_on'] = $userdetails_birthday_on;
    }
	if (isset($_POST["userdetails_contact_info_on"]) && ($userdetails_contact_info_on = $_POST["userdetails_contact_info_on"])){
    $update_block[] = "userdetails_contact_info_on = " . sqlesc($userdetails_contact_info_on);
    $user_block_cache['userdetails_contact_info_on'] = $userdetails_contact_info_on;
    }
	if (isset($_POST["userdetails_iphistory_on"]) && ($userdetails_iphistory_on = $_POST["userdetails_iphistory_on"])){
    $update_block[] = "userdetails_iphistory_on = " . sqlesc($userdetails_iphistory_on);
    $user_block_cache['userdetails_iphistory_on'] = $userdetails_iphistory_on;
    }
	if (isset($_POST["userdetails_traffic_on"]) && ($userdetails_traffic_on = $_POST["userdetails_traffic_on"])){
    $update_block[] = "userdetails_traffic_on = " . sqlesc($userdetails_traffic_on);
    $user_block_cache['userdetails_traffic_on'] = $userdetails_traffic_on;
    }
	if (isset($_POST["userdetails_share_ratio_on"]) && ($userdetails_share_ratio_on = $_POST["userdetails_share_ratio_on"])){
    $update_block[] = "userdetails_share_ratio_on = " . sqlesc($userdetails_share_ratio_on);
    $user_block_cache['userdetails_share_ratio_on'] = $userdetails_share_ratio_on;
    }
	if (isset($_POST["userdetails_seedtime_ratio_on"]) && ($userdetails_seedtime_ratio_on = $_POST["userdetails_seedtime_ratio_on"])){
    $update_block[] = "userdetails_seedtime_ratio_on = " . sqlesc($userdetails_seedtime_ratio_on);
    $user_block_cache['userdetails_seedtime_ratio_on'] = $userdetails_seedtime_ratio_on;
    }
	if (isset($_POST["userdetails_seedbonus_on"]) && ($userdetails_seedbonus_on = $_POST["userdetails_seedbonus_on"])){
    $update_block[] = "userdetails_seedbonus_on = " . sqlesc($userdetails_seedbonus_on);
    $user_block_cache['userdetails_seedbonus_on'] = $userdetails_seedbonus_on;
    }
	if (isset($_POST["userdetails_connectable_port_on"]) && ($userdetails_connectable_port_on = $_POST["userdetails_connectable_port_on"])){
    $update_block[] = "userdetails_connectable_port_on = " . sqlesc($userdetails_connectable_port_on);
    $user_block_cache['userdetails_connectable_port_on'] = $userdetails_connectable_port_on;
    }
	if (isset($_POST["userdetails_avatar_on"]) && ($userdetails_avatar_on = $_POST["userdetails_avatar_on"])){
    $update_block[] = "userdetails_avatar_on = " . sqlesc($userdetails_avatar_on);
    $user_block_cache['userdetails_avatar_on'] = $userdetails_avatar_on;
    }
	if (isset($_POST["userdetails_forumposts_on"]) && ($userdetails_forumposts_on = $_POST["userdetails_forumposts_on"])){
    $update_block[] = "userdetails_forumposts_on = " . sqlesc($userdetails_forumposts_on);
    $user_block_cache['userdetails_forumposts_on'] = $userdetails_forumposts_on;
    }
	if (isset($_POST["userdetails_gender_on"]) && ($userdetails_gender_on = $_POST["userdetails_gender_on"])){
    $update_block[] = "userdetails_gender_on = " . sqlesc($userdetails_gender_on);
    $user_block_cache['userdetails_gender_on'] = $userdetails_gender_on;
    }
	if (isset($_POST["userdetails_freestuffs_on"]) && ($userdetails_freestuffs_on = $_POST["userdetails_freestuffs_on"])){
    $update_block[] = "userdetails_freestuffs_on = " . sqlesc($userdetails_freestuffs_on);
    $user_block_cache['userdetails_freestuffs_on'] = $userdetails_freestuffs_on;
    }
	if (isset($_POST["userdetails_comments_on"]) && ($userdetails_comments_on = $_POST["userdetails_comments_on"])){
    $update_block[] = "userdetails_comments_on = " . sqlesc($userdetails_comments_on);
    $user_block_cache['userdetails_comments_on'] = $userdetails_comments_on;
    }
	if (isset($_POST["userdetails_invitedby_on"]) && ($userdetails_invitedby_on = $_POST["userdetails_invitedby_on"])){
    $update_block[] = "userdetails_invitedby_on = " . sqlesc($userdetails_invitedby_on);
    $user_block_cache['userdetails_invitedby_on'] = $userdetails_invitedby_on;
    }
	if (isset($_POST["userdetails_torrents_block_on"]) && ($userdetails_torrents_block_on = $_POST["userdetails_torrents_block_on"])){
    $update_block[] = "userdetails_torrents_block_on = " . sqlesc($userdetails_torrents_block_on);
    $user_block_cache['userdetails_torrents_block_on'] = $userdetails_torrents_block_on;
    }
	if (isset($_POST["userdetails_completed_on"]) && ($userdetails_completed_on = $_POST["userdetails_completed_on"])){
    $update_block[] = "userdetails_completed_on = " . sqlesc($userdetails_completed_on);
    $user_block_cache['userdetails_completed_on'] = $userdetails_completed_on;
    }
	if (isset($_POST["userdetails_snatched_staff_on"]) && ($userdetails_snatched_staff_on = $_POST["userdetails_snatched_staff_on"])){
    $update_block[] = "userdetails_snatched_staff_on = " . sqlesc($userdetails_snatched_staff_on);
    $user_block_cache['userdetails_snatched_staff_on'] = $userdetails_snatched_staff_on;
    }
	if (isset($_POST["userdetails_userinfo_on"]) && ($userdetails_userinfo_on = $_POST["userdetails_userinfo_on"])){
    $update_block[] = "userdetails_userinfo_on = " . sqlesc($userdetails_userinfo_on);
    $user_block_cache['userdetails_userinfo_on'] = $userdetails_userinfo_on;
    }
	if (isset($_POST["userdetails_showpm_on"]) && ($userdetails_showpm_on = $_POST["userdetails_showpm_on"])){
    $update_block[] = "userdetails_showpm_on = " . sqlesc($userdetails_showpm_on);
    $user_block_cache['userdetails_showpm_on'] = $userdetails_showpm_on;
    }
	if (isset($_POST["userdetails_report_user_on"]) && ($userdetails_report_user_on = $_POST["userdetails_report_user_on"])){
    $update_block[] = "userdetails_report_user_on = " . sqlesc($userdetails_report_user_on);
    $user_block_cache['userdetails_report_user_on'] = $userdetails_report_user_on;
    }
	if (isset($_POST["userdetails_user_status_on"]) && ($userdetails_user_status_on = $_POST["userdetails_user_status_on"])){
    $update_block[] = "userdetails_user_status_on = " . sqlesc($userdetails_user_status_on);
    $user_block_cache['userdetails_user_status_on'] = $userdetails_user_status_on;
    }
	if (isset($_POST["userdetails_user_comments_on"]) && ($userdetails_user_comments_on = $_POST["userdetails_user_comments_on"])){
    $update_block[] = "userdetails_user_comments_on = " . sqlesc($userdetails_user_comments_on);
    $user_block_cache['userdetails_user_comments_on'] = $userdetails_user_comments_on;
    }
	if (isset($_POST["userdetails_showfriends_on"]) && ($userdetails_showfriends_on = $_POST["userdetails_showfriends_on"])){
    $update_block[] = "userdetails_showfriends_on = " . sqlesc($userdetails_showfriends_on);
    $user_block_cache['userdetails_showfriends_on'] = $userdetails_showfriends_on;
    }	
	$action = "userdetails_options";
}
if ($action == "index_options")
    if (isset($_POST["index_ie_alert_on"]) && ($index_ie_alert_on = $_POST["index_ie_alert_on"]) != $USERBLOCKS["index_ie_alert_on"]){  
    $update_block[] = "index_ie_alert_on = " . sqlesc($index_ie_alert_on);
    $user_block_cache['index_ie_alert_on'] = $index_ie_alert_on;
	}
    if (isset($_POST["index_news_on"]) && ($index_news_on = $_POST["index_news_on"]) != $USERBLOCKS["index_news_on"]){  
    $update_block[] = "index_news_on = " . sqlesc($index_news_on);
    $user_block_cache['index_news_on'] = $index_news_on;
    }
	if (isset($_POST["index_shoutbox_on"]) && ($index_shoutbox_on = $_POST["index_shoutbox_on"]) != $USERBLOCKS["index_shoutbox_on"]){
    $update_block[] = "index_shoutbox_on = " . sqlesc($index_shoutbox_on);
    $user_block_cache['index_shoutbox_on'] = $index_shoutbox_on;
    }
	if (isset($_POST["index_staff_shoutbox_on"]) && ($index_staff_shoutbox_on = $_POST["index_staff_shoutbox_on"]) != $USERBLOCKS["index_staff_shoutbox_on"]){
    $update_block[] = "index_staff_shoutbox_on = " . sqlesc($index_staff_shoutbox_on);
    $user_block_cache['index_staff_shoutbox_on'] = $index_staff_shoutbox_on;
    }
    if (isset($_POST["index_active_users_on"]) && ($index_active_users_on = $_POST["index_active_users_on"]) != $USERBLOCKS["index_active_users_on"]){
    $update_block[] = "index_active_users_on = " . sqlesc($index_active_users_on);
    $user_block_cache['index_active_users_on'] = $index_active_users_on;
    }
	if (isset($_POST["index_last_24_active_users_on"]) && ($index_last_24_active_users_on = $_POST["index_last_24_active_users_on"]) != $USERBLOCKS["index_last_24_active_users_on"]){
    $update_block[] = "index_last_24_active_users_on = " . sqlesc($index_last_24_active_users_on);
    $user_block_cache['index_last_24_active_users_on'] = $index_last_24_active_users_on;
    }
    if (isset($_POST["index_latest_user_on"]) && ($index_latest_user_on = $_POST["index_latest_user_on"])){
    $update_block[] = "index_latest_user_on = " . sqlesc($index_latest_user_on);
    $user_block_cache['index_latest_user_on'] = $index_latest_user_on;
    }
	if (isset($_POST["index_birthday_active_users_on"]) && ($index_birthday_active_users_on = $_POST["index_birthday_active_users_on"])){
    $update_block[] = "index_birthday_active_users_on = " . sqlesc($index_birthday_active_users_on);
    $user_block_cache['index_birthday_active_users_on'] = $index_birthday_active_users_on;
    }
	if (isset($_POST["index_stats_on"]) && ($index_stats_on = $_POST["index_stats_on"])){
    $update_block[] = "index_stats_on = " . sqlesc($index_stats_on);
    $user_block_cache['index_stats_on'] = $index_stats_on;
    }
	if (isset($_POST["index_forumposts_on"]) && ($index_forumposts_on = $_POST["index_forumposts_on"])){
    $update_block[] = "index_forumposts_on = " . sqlesc($index_forumposts_on);
    $user_block_cache['index_forumposts_on'] = $index_forumposts_on;
    }
	if (isset($_POST["index_latest_torrents_on"]) && ($index_latest_torrents_on = $_POST["index_latest_torrents_on"])){
    $update_block[] = "index_latest_torrents_on = " . sqlesc($index_latest_torrents_on);
    $user_block_cache['index_latest_torrents_on'] = $index_latest_torrents_on;
    }
	if (isset($_POST["index_latest_torrents_scroll_on"]) && ($index_latest_torrents_scroll_on = $_POST["index_latest_torrents_scroll_on"])){
    $update_block[] = "index_latest_torrents_scroll_on = " . sqlesc($index_latest_torrents_scroll_on);
    $user_block_cache['index_latest_torrents_scroll_on'] = $index_latest_torrents_scroll_on;
    }
	if (isset($_POST["index_disclaimer_on"]) && ($index_disclaimer_on = $_POST["index_disclaimer_on"])){
    $update_block[] = "index_disclaimer_on = " . sqlesc($index_disclaimer_on);
    $user_block_cache['index_disclaimer_on'] = $index_disclaimer_on;
    }
	if (isset($_POST["index_announcement_on"]) && ($index_announcement_on = $_POST["index_announcement_on"])){
    $update_block[] = "index_announcement_on = " . sqlesc($index_announcement_on);
    $user_block_cache['index_announcement_on'] = $index_announcement_on;
    }
	if (isset($_POST["index_donation_progress_on"]) && ($index_donation_progress_on = $_POST["index_donation_progress_on"])){
    $update_block[] = "index_donation_progress_on = " . sqlesc($index_donation_progress_on);
    $user_block_cache['index_donation_progress_on'] = $index_donation_progress_on;
    }
	if (isset($_POST["index_advertisements_on"]) && ($index_advertisements_on = $_POST["index_advertisements_on"])){
    $update_block[] = "index_advertisements_on = " . sqlesc($index_advertisements_on);
    $user_block_cache['index_advertisements_on'] = $index_advertisements_on;
    }
	if (isset($_POST["index_radio_on"]) && ($index_radio_on = $_POST["index_radio_on"])){
    $update_block[] = "index_radio_on = " . sqlesc($index_radio_on);
    $user_block_cache['index_radio_on'] = $index_radio_on;
    }
	if (isset($_POST["index_torrentfreak_on"]) && ($index_torrentfreak_on = $_POST["index_torrentfreak_on"])){
    $update_block[] = "index_torrentfreak_on = " . sqlesc($index_torrentfreak_on);
    $user_block_cache['index_torrentfreak_on'] = $index_torrentfreak_on;
    }
	if (isset($_POST["index_xmas_gift_on"]) && ($index_xmas_gift_on = $_POST["index_xmas_gift_on"])){
    $update_block[] = "index_xmas_gift_on = " . sqlesc($index_xmas_gift_on);
    $user_block_cache['index_xmas_gift_on'] = $index_xmas_gift_on;
    }
	if (isset($_POST["index_active_poll_on"]) && ($index_active_poll_on = $_POST["index_active_poll_on"])){
    $update_block[] = "index_active_poll_on = " . sqlesc($index_active_poll_on);
    $user_block_cache['index_active_poll_on'] = $index_active_poll_on;
    }
	if (isset($_POST["index_movie_ofthe_week_on"]) && ($index_movie_ofthe_week_on = $_POST["index_movie_ofthe_week_on"])){
    $update_block[] = "index_movie_ofthe_week_on = " . sqlesc($index_movie_ofthe_week_on);
    $user_block_cache['index_movie_ofthe_week_on'] = $index_movie_ofthe_week_on;
    }
	if (isset($_POST["index_requests_and_offers_on"]) && ($index_requests_and_offers_on = $_POST["index_requests_and_offers_on"])){
    $update_block[] = "index_requests_and_offers_on = " . sqlesc($index_requests_and_offers_on);
    $user_block_cache['index_requests_and_offers_on'] = $index_requests_and_offers_on;
    }
    $action = "index_options";
    if ($user_block_cache) {
        $cache->update_row('MyBlockSettings::' . $CURUSER['id'] , $user_block_cache, $TRINITY20['expires']['u_stats']);
    }
if (sizeof($update_block) > 0) 
sql_query("UPDATE user_options SET " . implode(",", $update_block) . " WHERE userid = " . sqlesc($CURUSER["id"])) or sqlerr(__FILE__, __LINE__);
header("Location: {$TRINITY20['baseurl']}/user_blocks.php?edited=1&action=$action");

?>