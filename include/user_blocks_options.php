<?php


	///User Blocks without Bitwise by iseeyoucopy
    $user_blocks_key = 'blocksss::' . $row['id'];
    if (($user_row = $cache->get($user_blocks_key)) === false) {
		$user_opt_int = array(
		  'id',
		  'userid'
		  );
		$user_opt_str = array(
		  'index_ie_alert_on',
		  'index_news_on',
		  'index_shoutbox_on',
		  'index_active_users_on',
		  'index_last_24_active_users_on',
		  'irc_active_users_on',
		  'index_birthday_active_users_on',
		  'index_stats_on',
		  'index_forumposts_on',
		  'index_latest_torrents_on',
		  'index_latest_torrents_scroll_on',
		  'index_disclaimer_on',
		  'index_announcement_on',
		  'index_donation_progress_on',
		  'index_advertisements_on',
		  'index_radio_on',
		  'index_torrentfreak_on',
		  'index_xmas_gift_on',
		  'index_active_poll_on',
		  'index_staff_shoutbox_on',
		  'index_movie_ofthe_week_on',
		  'index_requests_and_offers_on',
		  'stdhead_freelech_on',
		  'stdhead_demotion_on',
		  'stdhead_newpm_on',
		  'stdhead_staff_message_on',
		  'stdhead_reports_on',
		  'stdhead_uploadapp_on',
		  'stdhead_happyhour_on',
		  'stdhead_crazyhour_on',
		  'stdhead_bugmessage_on',
		  'stdhead_freeleech_contribution_on',
		  'stdhead_stafftools_on',
		  'userdetails_login_link_on',
		  'userdetails_flush_on',
		  'userdetails_joined_on',
		  'userdetails_online_time_on',
		  'userdetails_browser_on',
		  'userdetails_reputation_on',
		  'userdetails_user_hits_on',
		  'userdetails_birthday_on',
		  'userdetails_contact_info_on',
		  'userdetails_iphistory_on',
		  'userdetails_traffic_on',
		  'userdetails_share_ratio_on',
		  'userdetails_seedtime_ratio_on',
		  'userdetails_seedbonus_on',
		  'userdetails_irc_stats_on',
		  'userdetails_connectable_port_on',
		  'userdetails_avatar_on',
		  'userdetails_forumposts_on',
		  'userdetails_gender_on',
		  'userdetails_freestuffs_on',
		  'userdetails_comments_on',
		  'userdetails_invitedby_on',
		  'userdetails_torrents_block_on',
		  'userdetails_completed_on',
		  'userdetails_snatched_staff_on',
		  'userdetails_userinfo_on',
		  'userdetails_showpm_on',
		  'userdetails_report_user_on',
		  'userdetails_user_status_on',
		  'userdetails_user_comments_on',
		  'userdetails_showfriends_on'
		  );
		$user_opt_fields = implode(', ', array_merge($user_opt_str));
        $c1_sql = sql_query("SELECT {$user_opt_fields} FROM user_options WHERE userid = " . sqlesc($row['id'])) or sqlerr(__FILE__, __LINE__);
        if (mysqli_num_rows($c1_sql) == 0) {
            sql_query('INSERT INTO user_options(userid) VALUES(' . sqlesc($row['id']) . ')');
            header('Location: index.php');
            die();
        }		
		$user_row = mysqli_fetch_assoc($c1_sql);
        foreach ($user_opt_int as $ii) $user_row[$ii] = (int)$user_row[$ii];
        foreach ($user_opt_str as $ii) $user_row[$ii] = $user_row[$ii];
		$cache->set($user_blocks_key, $user_row, $INSTALLER09['expires']['curuser']);
        unset($c1_sql);
		?>