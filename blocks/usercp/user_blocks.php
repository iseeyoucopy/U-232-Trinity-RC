<?php
//==Index
$checkbox_index_ie_alert = ((curuser::$blocks['index_page'] & block_index::IE_ALERT) ? ' checked="checked"' : '');
$checkbox_index_news = ((curuser::$blocks['index_page'] & block_index::NEWS) ? ' checked="checked"' : '');
$checkbox_index_shoutbox = ((curuser::$blocks['index_page'] & block_index::SHOUTBOX) ? ' checked="checked"' : '');
$checkbox_index_active_users = ((curuser::$blocks['index_page'] & block_index::ACTIVE_USERS) ? ' checked="checked"' : '');
$checkbox_index_active_24h_users = ((curuser::$blocks['index_page'] & block_index::LAST_24_ACTIVE_USERS) ? ' checked="checked"' : '');
$checkbox_index_active_birthday_users = ((curuser::$blocks['index_page'] & block_index::BIRTHDAY_ACTIVE_USERS) ? ' checked="checked"' : '');
$checkbox_index_stats = ((curuser::$blocks['index_page'] & block_index::STATS) ? ' checked="checked"' : '');
$checkbox_index_disclaimer = ((curuser::$blocks['index_page'] & block_index::DISCLAIMER) ? ' checked="checked"' : '');
$checkbox_index_latest_user = ((curuser::$blocks['index_page'] & block_index::LATEST_USER) ? ' checked="checked"' : '');
$checkbox_index_latest_forumposts = ((curuser::$blocks['index_page'] & block_index::FORUMPOSTS) ? ' checked="checked"' : '');
$checkbox_index_latest_torrents = ((curuser::$blocks['index_page'] & block_index::LATEST_TORRENTS) ? ' checked="checked"' : '');
$checkbox_index_latest_torrents_scroll = ((curuser::$blocks['index_page'] & block_index::LATEST_TORRENTS_SCROLL) ? ' checked="checked"' : '');
$checkbox_index_announcement = ((curuser::$blocks['index_page'] & block_index::ANNOUNCEMENT) ? ' checked="checked"' : '');
$checkbox_index_donation_progress = ((curuser::$blocks['index_page'] & block_index::DONATION_PROGRESS) ? ' checked="checked"' : '');
$checkbox_index_ads = ((curuser::$blocks['index_page'] & block_index::ADVERTISEMENTS) ? ' checked="checked"' : '');
$checkbox_index_radio = ((curuser::$blocks['index_page'] & block_index::RADIO) ? ' checked="checked"' : '');
$checkbox_index_torrentfreak = ((curuser::$blocks['index_page'] & block_index::TORRENTFREAK) ? ' checked="checked"' : '');
$checkbox_index_xmasgift = ((curuser::$blocks['index_page'] & block_index::XMAS_GIFT) ? ' checked="checked"' : '');
$checkbox_index_active_poll = ((curuser::$blocks['index_page'] & block_index::ACTIVE_POLL) ? ' checked="checked"' : '');
$checkbox_index_staffshoutbox = ((curuser::$blocks['index_page'] & block_index::STAFF_SHOUT) ? ' checked="checked"' : '');
$checkbox_index_mow = ((curuser::$blocks['index_page'] & block_index::MOVIEOFWEEK) ? ' checked="checked"' : '');
$checkbox_index_reqnoff = ((curuser::$blocks['index_page'] & block_index::REQNOFF) ? ' checked="checked"' : '');
//==Stdhead
$checkbox_global_freeleech = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH) ? ' checked="checked"' : '');
$checkbox_global_demotion = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_DEMOTION) ? ' checked="checked"' : '');
$checkbox_global_message_alert = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_NEWPM) ? ' checked="checked"' : '');
$checkbox_global_staff_message_alert = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFF_MESSAGE) ? ' checked="checked"' : '');
$checkbox_global_staff_report = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_REPORTS) ? ' checked="checked"' : '');
$checkbox_global_staff_uploadapp = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_UPLOADAPP) ? ' checked="checked"' : '');
$checkbox_global_happyhour = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_HAPPYHOUR) ? ' checked="checked"' : '');
$checkbox_global_crazyhour = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_CRAZYHOUR) ? ' checked="checked"' : '');
$checkbox_global_bugmessage = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_BUG_MESSAGE) ? ' checked="checked"' : '');
$checkbox_global_freeleech_contribution = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION) ? ' checked="checked"' : '');
$checkbox_global_stafftools = ((curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFFTOOLS) ? ' checked="checked"' : '');
//==Userdetails
$checkbox_userdetails_login_link = ((curuser::$blocks['userdetails_page'] & block_userdetails::LOGIN_LINK) ? ' checked="checked"' : '');
$checkbox_userdetails_flush = ((curuser::$blocks['userdetails_page'] & block_userdetails::FLUSH) ? ' checked="checked"' : '');
$checkbox_userdetails_joined = ((curuser::$blocks['userdetails_page'] & block_userdetails::JOINED) ? ' checked="checked"' : '');
$checkbox_userdetails_onlinetime = ((curuser::$blocks['userdetails_page'] & block_userdetails::ONLINETIME) ? ' checked="checked"' : '');
$checkbox_userdetails_browser = ((curuser::$blocks['userdetails_page'] & block_userdetails::BROWSER) ? ' checked="checked"' : '');
$checkbox_userdetails_reputation = ((curuser::$blocks['userdetails_page'] & block_userdetails::REPUTATION) ? ' checked="checked"' : '');
$checkbox_userdetails_userhits = ((curuser::$blocks['userdetails_page'] & block_userdetails::PROFILE_HITS) ? ' checked="checked"' : '');
$checkbox_userdetails_birthday = ((curuser::$blocks['userdetails_page'] & block_userdetails::BIRTHDAY) ? ' checked="checked"' : '');
$checkbox_userdetails_contact_info = ((curuser::$blocks['userdetails_page'] & block_userdetails::CONTACT_INFO) ? ' checked="checked"' : '');
$checkbox_userdetails_iphistory = ((curuser::$blocks['userdetails_page'] & block_userdetails::IPHISTORY) ? ' checked="checked"' : '');
$checkbox_userdetails_traffic = ((curuser::$blocks['userdetails_page'] & block_userdetails::TRAFFIC) ? ' checked="checked"' : '');
$checkbox_userdetails_shareratio = ((curuser::$blocks['userdetails_page'] & block_userdetails::SHARE_RATIO) ? ' checked="checked"' : '');
$checkbox_userdetails_seedtime_ratio = ((curuser::$blocks['userdetails_page'] & block_userdetails::SEEDTIME_RATIO) ? ' checked="checked"' : '');
$checkbox_userdetails_seedbonus = ((curuser::$blocks['userdetails_page'] & block_userdetails::SEEDBONUS) ? ' checked="checked"' : '');
$checkbox_userdetails_connectable = ((curuser::$blocks['userdetails_page'] & block_userdetails::CONNECTABLE_PORT) ? ' checked="checked"' : '');
$checkbox_userdetails_avatar = ((curuser::$blocks['userdetails_page'] & block_userdetails::AVATAR) ? ' checked="checked"' : '');
$checkbox_userdetails_userclass = ((curuser::$blocks['userdetails_page'] & block_userdetails::USERCLASS) ? ' checked="checked"' : '');
$checkbox_userdetails_gender = ((curuser::$blocks['userdetails_page'] & block_userdetails::GENDER) ? ' checked="checked"' : '');
$checkbox_userdetails_freestuffs = ((curuser::$blocks['userdetails_page'] & block_userdetails::FREESTUFFS) ? ' checked="checked"' : '');
$checkbox_userdetails_torrent_comments = ((curuser::$blocks['userdetails_page'] & block_userdetails::COMMENTS) ? ' checked="checked"' : '');
$checkbox_userdetails_forumposts = ((curuser::$blocks['userdetails_page'] & block_userdetails::FORUMPOSTS) ? ' checked="checked"' : '');
$checkbox_userdetails_invitedby = ((curuser::$blocks['userdetails_page'] & block_userdetails::INVITEDBY) ? ' checked="checked"' : '');
$checkbox_userdetails_torrents_block = ((curuser::$blocks['userdetails_page'] & block_userdetails::TORRENTS_BLOCK) ? ' checked="checked"' : '');
$checkbox_userdetails_completed = ((curuser::$blocks['userdetails_page'] & block_userdetails::COMPLETED) ? ' checked="checked"' : '');
$checkbox_userdetails_snatched_staff = ((curuser::$blocks['userdetails_page'] & block_userdetails::SNATCHED_STAFF) ? ' checked="checked"' : '');
$checkbox_userdetails_userinfo = ((curuser::$blocks['userdetails_page'] & block_userdetails::USERINFO) ? ' checked="checked"' : '');
$checkbox_userdetails_showpm = ((curuser::$blocks['userdetails_page'] & block_userdetails::SHOWPM) ? ' checked="checked"' : '');
$checkbox_userdetails_report = ((curuser::$blocks['userdetails_page'] & block_userdetails::REPORT_USER) ? ' checked="checked"' : '');
$checkbox_userdetails_userstatus = ((curuser::$blocks['userdetails_page'] & block_userdetails::USERSTATUS) ? ' checked="checked"' : '');
$checkbox_userdetails_usercomments = ((curuser::$blocks['userdetails_page'] & block_userdetails::USERCOMMENTS) ? ' checked="checked"' : '');
$checkbox_userdetails_showfriends = ((curuser::$blocks['userdetails_page'] & block_userdetails::SHOWFRIENDS) ? ' checked="checked"' : '');
$HTMLOUT.= '<div class="tabs-panel" id="user-block">
<ul class="tabs" data-deep-link="true" data-update-history="true" data-deep-link-smudge="true" data-deep-link-smudge-delay="500" data-tabs id="userblocks-tabs">
  <li class="tabs-title is-active"><a href="#panel1d" aria-selected="true">' . $lang['user_b_title1'] . '</a></li>
  <li class="tabs-title"><a href="#panel2d">' . $lang['user_b_title2'] . '</a></li>
  <li class="tabs-title"><a href="#panel3d">' . $lang['user_b_title3'] . '</a></li>
  <li class="tabs-title"><a href="#panel4d">Browse Page</a></li>
</ul>
<form action="./user_blocks.php" method="post">
<div class="tabs-content" data-tabs-content="userblocks-tabs">
  <div class="tabs-panel is-active" id="panel1d">
        <table class="table bordered">
        <tr><td><b>' . $lang['user_b_ie1'] . '</b></td><td>
        <div class="switch tiny">
        <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="ie_alert" name="ie_alert" value="yes" ' . $checkbox_index_ie_alert . '>
        <label class="switch-paddle" for="ie_alert">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
    </td>
    </tr>
        <tr><td><b>' . $lang['user_b_nb1'] . '</b></td><td>
        <div class="switch tiny">
        <input onchange="this.form.submit()" class="switch-input" id="index_news" type="checkbox" name="news" ' . $checkbox_index_news . '>
        <label class="switch-paddle" for="index_news">
        <span class="show-for-sr">Do you like me?</span>
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
    </td>
    </tr>
    <tr><td><b>' . $lang['user_b_sh1'] . '</b></td><td>
    <div class="switch tiny">
    <input onchange="this.form.submit()" class="switch-input" id="index_shoutbox" type="checkbox" name="shoutbox" ' . $checkbox_index_shoutbox . '>
    <label class="switch-paddle" for="index_shoutbox">
    <span class="show-for-sr">Do you like me?</span>
    <span class="switch-active" aria-hidden="true">Yes</span>
    <span class="switch-inactive" aria-hidden="true">No</span>
    </label>
    </div>
</td>
        <tr><td><b>' . $lang['user_b_actu1'] . '</b></td><td>
        <div class="switch tiny">
        <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="active_users" name="active_users" value="yes"' . $checkbox_index_active_users . '>
        <label class="switch-paddle" for="active_users">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_act24'] . '</b></td><td>
        <div class="switch tiny">
        <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="active_users2" name="last_24_active_users" value="yes"' . $checkbox_index_active_24h_users . '>
        <label class="switch-paddle" for="active_users2">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_bir1'] . '</b></td><td>
        <div class="switch tiny">
        <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="birthday_active_users" name="birthday_active_users" value="yes"' . $checkbox_index_active_birthday_users . '>
        <label class="switch-paddle" for="birthday_active_users">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_sit1'] . '</b></td><td>
        <div class="switch tiny">
        <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stats" name="stats" value="yes"' . $checkbox_index_stats . '>
        <label class="switch-paddle" for="stats">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>     
        <tr><td><b>' . $lang['user_b_disc1'] . '</b></td><td>
         <div class="switch tiny">
         <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="disclaimer" name="disclaimer" value="yes"' . $checkbox_index_disclaimer . '>
         <label class="switch-paddle" for="disclaimer">
         <span class="switch-active" aria-hidden="true">Yes</span>
         <span class="switch-inactive" aria-hidden="true">No</span>
         </label>
         </div>
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_b_last1'] . '</b></td><td>
    <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="latest_user" name="latest_user" value="yes"' . $checkbox_index_latest_user . '>
    <label class="switch-paddle" for="latest_user">
    <span class="switch-active" aria-hidden="true">Yes</span>
    <span class="switch-inactive" aria-hidden="true">No</span>
    </label>
    </div>
        </tr>   
        <tr><td><b>' . $lang['user_b_fol1'] . '</b></td><td>
    <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="forumposts" name="forumposts" value="yes"' . $checkbox_index_latest_forumposts . '>
    <label class="switch-paddle" for="forumposts">
    <span class="switch-active" aria-hidden="true">Yes</span>
    <span class="switch-inactive" aria-hidden="true">No</span>
    </label>
    </div>
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_b_torl1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="latest_torrents" name="latest_torrents" value="yes"' . $checkbox_index_latest_torrents . '>
        <label class="switch-paddle" for="latest_torrents">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        
        <tr><td><b>' . $lang['user_b_tors1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="latest_torrents_scroll" name="latest_torrents_scroll" value="yes"' . $checkbox_index_latest_torrents_scroll . '>
        <label class="switch-paddle" for="latest_torrents_scroll">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_b_ann1'] . '</b></td><td>
    <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="announcement" name="announcement" value="yes"' . $checkbox_index_announcement . '>
    <label class="switch-paddle" for="announcement">
    <span class="switch-active" aria-hidden="true">Yes</span>
    <span class="switch-inactive" aria-hidden="true">No</span>
    </label>
    </div>
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_b_don1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="donation_progress" name="donation_progress" value="yes"' . $checkbox_index_donation_progress . '>
        <label class="switch-paddle" for="donation_progress">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_adv1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="advertisements" name="advertisements" value="yes"' . $checkbox_index_ads . '>
        <label class="switch-paddle" for="advertisements">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_rad1'] . '</b></td><td>
       <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="radio" name="radio" value="yes"' . $checkbox_index_radio . '>
       <label class="switch-paddle" for="radio">
       <span class="switch-active" aria-hidden="true">Yes</span>
       <span class="switch-inactive" aria-hidden="true">No</span>
       </label>
       </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_tf1'] . '</b></td><td>
    <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="torrentfreak" name="torrentfreak" value="yes"' . $checkbox_index_torrentfreak . '>
    <label class="switch-paddle" for="torrentfreak">
    <span class="switch-active" aria-hidden="true">Yes</span>
    <span class="switch-inactive" aria-hidden="true">No</span>
    </label>
    </div>
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_b_xmas1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="xmas_gift" name="xmas_gift" value="yes"' . $checkbox_index_xmasgift . ' />
        <label class="switch-paddle" for="xmas_gift">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_b_poll1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="active_poll" name="active_poll" value="yes"' . $checkbox_index_active_poll . '>
        <label class="switch-paddle" for="active_poll">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_b_mow1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="index_movie_ofthe_week" name="movie_ofthe_week" value="yes"' . $checkbox_index_mow . '>
        <label class="switch-paddle" for="index_movie_ofthe_week">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
        <tr><td><b>' . $lang['user_b_rqno1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="index_requests_and_offers" name="requests_and_offers" value="yes"' . $checkbox_index_reqnoff . '>
        <label class="switch-paddle" for="index_requests_and_offers">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>
        </td>
        </tr>
    </table>
     </div>
  <div class="tabs-panel" id="panel2d">
        <table class="table table-bordered">        
    <tr><td><b>' . $lang['user_s_free1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_freeleech" name="stdhead_freeleech" value="yes"' . $checkbox_global_freeleech . '>
        <label class="switch-paddle" for="stdhead_freeleech">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>      
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_s_rep1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_reports" name="stdhead_reports" value="yes"' . $checkbox_global_staff_report . '>
        <label class="switch-paddle" for="stdhead_reports">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>    
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_s_app1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_uploadapp" name="stdhead_uploadapp" value="yes"' . $checkbox_global_staff_uploadapp . '>
        <label class="switch-paddle" for="stdhead_uploadapp">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>     
        </td>
        </tr>';
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '        
    <tr><td><b>' . $lang['user_s_dem1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_demotion" name="stdhead_demotion" value="yes"' . $checkbox_global_demotion . '>
        <label class="switch-paddle" for="stdhead_demotion">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>';
}
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
    <tr><td><b>' . $lang['user_s_warn1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_staff_message" name="stdhead_staff_message" value="yes"' . $checkbox_global_staff_message_alert . '>
        <label class="switch-paddle" for="stdhead_staff_message">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>';
}
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
    <tr><td><b>' . $lang['user_s_bug1'] . '</b></td><td>
         <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_bugmessage" name="stdhead_bugmessage" value="yes"' . $checkbox_global_bugmessage . '>
         <label class="switch-paddle" for="stdhead_bugmessage">
         <span class="switch-active" aria-hidden="true">Yes</span>
         <span class="switch-inactive" aria-hidden="true">No</span>
         </label>
         </div>         
        </td>
        </tr>';
}
$HTMLOUT.= '
    <tr><td><b>' . $lang['user_s_mess1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_newpm" name="stdhead_newpm" value="yes"' . $checkbox_global_message_alert . '>
        <label class="switch-paddle" for="stdhead_newpm">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
       </td>
        </tr>       
        <tr><td><b>' . $lang['user_s_hh1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_happyhour" name="stdhead_happyhour" value="yes"' . $checkbox_global_happyhour . '>
        <label class="switch-paddle" for="stdhead_happyhour">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_s_ch1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_crazyhour" name="stdhead_crazyhour" value="yes"' . $checkbox_global_crazyhour . '>
        <label class="switch-paddle" for="stdhead_crazyhour">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_s_kc1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_freeleech_contribution" name="stdhead_freeleech_contribution" value="yes"' . $checkbox_global_freeleech_contribution . '>
        <label class="switch-paddle" for="stdhead_freeleech_contribution">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>    
        </td>
       </tr>';
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
        <tr><td><b>' . $lang['user_s_stq1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="stdhead_stafftools" name="stdhead_stafftools" value="yes"' . $checkbox_global_stafftools . '>
        <label class="switch-paddle" for="stdhead_stafftools">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>      
        </td>
        </tr>';
}
       $HTMLOUT.= '</table>
       </div>
       <div class="tabs-panel" id="panel3d">
        <table class="table table-bordered">
        <tr><td><b>' . $lang['user_u_ll1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_login_link" name="userdetails_login_link" value="yes"' . $checkbox_userdetails_login_link . '>
        <label class="switch-paddle" for="userdetails_login_link">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div> 
        </td>
        </tr>
        <tr><td><b>' . $lang['user_u_ftt1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_flush" name="userdetails_flush" value="yes"' . $checkbox_userdetails_flush . '>
        <label class="switch-paddle" for="userdetails_flush">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>     
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_join1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_joined" name="userdetails_joined" value="yes"' . $checkbox_userdetails_joined . '>
        <label class="switch-paddle" for="userdetails_joined">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_onl1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_online_time" name="userdetails_online_time" value="yes"' . $checkbox_userdetails_onlinetime . '>
        <label class="switch-paddle" for="userdetails_online-time">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>     
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_brw1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_browser" name="userdetails_browser" value="yes"' . $checkbox_userdetails_browser . '>
        <label class="switch-paddle" for="userdetails_browser">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>         
        </td>
        </tr>   
        ' . ($TRINITY20['rep_sys_on'] ? '<tr><td><b>' . $lang['user_u_rep1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_reputation" name="userdetails_reputation" value="yes"' . $checkbox_userdetails_reputation . '>
        <label class="switch-paddle" for="userdetails_reputation">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>' : '') . '       
        <tr><td><b>' . $lang['user_u_phit1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_user_hits" name="userdetails_user_hits" value="yes"' . $checkbox_userdetails_userhits . '>
        <label class="switch-paddle" for="userdetails_user_hits">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_bir1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_birthday" name="userdetails_birthday" value="yes"' . $checkbox_userdetails_birthday . '>
        <label class="switch-paddle" for="userdetails_birthday">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>     
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_con1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_contact_info" name="userdetails_contact_info" value="yes"' . $checkbox_userdetails_contact_info . '>
        <label class="switch-paddle" for="userdetails_contact_info">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
       </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_ip1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_iphistory" name="userdetails_iphistory" value="yes"' . $checkbox_userdetails_iphistory . '>
        <label class="switch-paddle" for="userdetails_iphistory">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>         
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_ut1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_traffic" name="userdetails_traffic" value="yes"' . $checkbox_userdetails_traffic . '>
        <label class="switch-paddle" for="userdetails_traffic">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>     
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_shr1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_share_ratio" name="userdetails_share_ratio" value="yes"' . $checkbox_userdetails_shareratio . '>
        <label class="switch-paddle" for="userdetails_share_ratio">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_st1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_seedtime_ratio" name="userdetails_seedtime_ratio" value="yes"' . $checkbox_userdetails_seedtime_ratio . '>
        <label class="switch-paddle" for="userdetails_seedtime_ratio">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_seed1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_seedbonus" name="userdetails_seedbonus" value="yes"' . $checkbox_userdetails_seedbonus . '>
        <label class="switch-paddle" for="userdetails_seedbonus">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
         </td>
        </tr>
        </tr>   
        <tr><td><b>' . $lang['user_u_cnn1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_connectable_port" name="userdetails_connectable_port" value="yes"' . $checkbox_userdetails_connectable . '>
        <label class="switch-paddle" for="userdetails_connectable_port">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>         
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_av1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_avatar" name="userdetails_avatar" value="yes"' . $checkbox_userdetails_avatar . '>
        <label class="switch-paddle" for="userdetails_avatar">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_ucc1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_userclass" name="userdetails_userclass" value="yes"' . $checkbox_userdetails_userclass . '>
        <label class="switch-paddle" for="userdetails_userclass">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>    
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_gen1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_gender" name="userdetails_gender" value="yes"' . $checkbox_userdetails_gender . '>
        <label class="switch-paddle" for="userdetails_gender">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>      
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_free1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_freestuffs" name="userdetails_freestuffs" value="yes"' . $checkbox_userdetails_freestuffs . '>
        <label class="switch-paddle" for="userdetails_freestuffs">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_cmm1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_comments" name="userdetails_comments" value="yes"' . $checkbox_userdetails_torrent_comments . '>
        <label class="switch-paddle" for="userdetails_comments">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_fpt1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_forumposts" name="userdetails_forumposts" value="yes"' . $checkbox_userdetails_forumposts . '>
        <label class="switch-paddle" for="userdetails_forumposts">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div> 
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_inv1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_invitedby" name="userdetails_invitedby" value="yes"' . $checkbox_userdetails_invitedby . '>
        <label class="switch-paddle" for="userdetails_invitedby">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>  
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_tb1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_torrents_block" name="userdetails_torrents_block" value="yes"' . $checkbox_userdetails_torrents_block . '>
        <label class="switch-paddle" for="userdetails_torrents_block">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>      
        </td>
        </tr>   
        <tr><td><b>' . $lang['user_u_sts1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_snatched_staff" name="userdetails_snatched_staff" value="yes"' . $checkbox_userdetails_snatched_staff . '>
        <label class="switch-paddle" for="userdetails_snatched_staff">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_usinf1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_userinfo" name="userdetails_userinfo" value="yes"' . $checkbox_userdetails_userinfo . '>
        <label class="switch-paddle" for="userdetails_userinfo">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>       
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_pm1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_showpm" name="userdetails_showpm" value="yes"' . $checkbox_userdetails_showpm . '>
        <label class="switch-paddle" for="userdetails_showpm">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>      
        </td>
        </tr>';
if ($BLOCKS['userdetails_report_user_on']) {
    $HTMLOUT.= '<tr><td><b>' . $lang['user_u_rpu1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_report_user" name="userdetails_report_user" value="yes"' . $checkbox_userdetails_report . '>
        <label class="switch-paddle" for="userdetails_report_user">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>      
        </td>
        </tr>';
}
$HTMLOUT.= '<tr><td><b>' . $lang['user_u_ust1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_user_status" name="userdetails_user_status" value="yes"' . $checkbox_userdetails_userstatus . '>
        <label class="switch-paddle" for="userdetails_user_status">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>       
        <tr><td><b>' . $lang['user_u_ucmm1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_user_comments" name="userdetails_user_comments" value="yes"' . $checkbox_userdetails_usercomments . '>
        <label class="switch-paddle" for="userdetails_user_comments">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>    
        </td>
        </tr>';
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= '
    <tr><td><b>' . $lang['user_u_comp1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_completed" name="userdetails_completed" value="yes"' . $checkbox_userdetails_completed . '>
        <label class="switch-paddle" for="userdetails_completed">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>        
        </td>
        </tr>';
}
$HTMLOUT.= '
    <tr><td><b>' . $lang['user_u_sfd1'] . '</b></td><td>
        <div class="switch tiny"><input onchange="this.form.submit()" class="switch-input" type="checkbox" id="userdetails_showfriends" name="userdetails_showfriends" value="yes"' . $checkbox_userdetails_showfriends . '>
        <label class="switch-paddle" for="userdetails_showfriends">
        <span class="switch-active" aria-hidden="true">Yes</span>
        <span class="switch-inactive" aria-hidden="true">No</span>
        </label>
        </div>         
        </td>
        </tr>
    </table>
    </div>
    <div class="tabs-panel" id="panel4d">
    </div></div></form></div>';