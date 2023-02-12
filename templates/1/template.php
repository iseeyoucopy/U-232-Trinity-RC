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
//==Template system by Terranova
function stdhead($title = "", $msgalert = true, $stdhead = false)
{
    global $CURUSER, $TRINITY20, $lang, $mysqli, $BLOCKS;
    if (!$TRINITY20['site_online']) {
        die("Site is down for maintenance, please check back again later... thanks<br >");
    }
    if ($title == "") {
        $title = $TRINITY20['site_name'].(isset($_GET['tbv']) ? " (".TBVERSION.")" : '');
    }
    else {
        $title = $TRINITY20['site_name'].(isset($_GET['tbv']) ? " (".TBVERSION.")" : '')." :: ".htmlsafechars($title);
    }
    if ($CURUSER) {
        $TRINITY20['stylesheet'] = isset($CURUSER['stylesheet']) ? "{$CURUSER['stylesheet']}.css" : $TRINITY20['stylesheet'];
        $TRINITY20['categorie_icon'] = isset($CURUSER['categorie_icon']) ? "{$CURUSER['categorie_icon']}" : $TRINITY20['categorie_icon'];
        $TRINITY20['language'] = isset($CURUSER['language']) ? "{$CURUSER['language']}" : $TRINITY20['language'];
    }
    $torrent_pass = isset($CURUSER['torrent_pass']) ? "{$CURUSER['torrent_pass']}" : '';
    if (!isset($_NO_COMPRESS)) if (!ob_start('ob_gzhandler')) ob_start();
    $htmlout = '';
    //== Include js files needed only for the page being used by pdq
    $js_incl = '';
    $js_incl .= '<!-- javascript goes here or in footer -->';
    if (!empty($stdhead['js'])) {
        foreach ($stdhead['js'] as $JS) {
            $js_incl .= "<script type='text/javascript' src='{$TRINITY20['baseurl']}/scripts/".$JS.".js'></script>";
        }
    }
    //== Include css files needed only for the page being used by pdq
    $stylez = ($CURUSER ? "{$CURUSER['stylesheet']}" : "{$TRINITY20['stylesheet']}");
    $css_incl = '';
    $css_incl .= '<!-- css goes in header -->';
    if (!empty($stdhead['css'])) {
        foreach ($stdhead['css'] as $CSS) {
            $css_incl .= "<link type='text/css' rel='stylesheet' href='{$TRINITY20['baseurl']}/templates/{$stylez}/css/".$CSS.".css'>";
        }
    }
    $htmlout .= '<!doctype html>
    <html class="no-js" lang="en">
        <!-- ####################################################### -->
        <!-- #   This website is powered by U-232    	           # -->
        <!-- #   Download and support at:                          # -->
        <!-- #     https://u-232-forum.duckdns.org/                # -->
        <!-- #   Template Modded by U-232 Dev Team                 # -->
        <!-- ####################################################### -->
  <head>
    <meta charset="' . charset() . '">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
	<!-- favicon  -->
    <link rel="shortcut icon" href="/favicon.ico">
    <!-- Template CSS-->
    <link rel="stylesheet" href="templates/1/1.css">
    <link rel="stylesheet" href="dist/css/app.css">
    <script src="scripts/jquery.min.js"></script>';
    if ($CURUSER) {
        $htmlout .= '<script type="application/rss+xml" title="Latest Torrents" src="/rss.php?torrent_pass=' . $torrent_pass . '"></script>';
        $htmlout .= "<style type='text/css'>#mlike{cursor:pointer;}</style>
        {$js_incl}{$css_incl}
        </head><body>";
        $htmlout .= TitleBar();
        $htmlout .= quickStaffTools();
        $htmlout .= "<!--Start main grid-container-->
        <div class='grid-container'>";
        $htmlout .= "<div data-sticky-container>
        <div class='sticky' data-sticky data-margin-top='0'><span class='label secondary'>" . statusBar(). "</span></div>
        </div>";
        $htmlout .= hero_header();
        $htmlout .= global_alerts();
    }
    return $htmlout;
}

function stdfoot($stdfoot = false)
{
    global $CURUSER, $TRINITY20, $start, $query_stat, $cache, $cache_keys, $querytime, $lang;
    $user_id = $CURUSER['id'] ?? '';
    $debug = (SQL_DEBUG && in_array($user_id, $TRINITY20['allowed_staff']['id']) ? 1 : 0);
    $seconds = microtime(true) - $start;
    $r_seconds = round($seconds, 5);
    $queries = (!empty($query_stat)); // sql query count by pdq
    // load averages - pdq
    if ($debug && ($uptime = $cache->get($cache_keys['uptime'])) === false) {
        $uptime = `uptime`;
        $cache->set($cache_keys['uptime'], $uptime, 25);
    }
    //== end class
    $htmlfoot = '';
    //== query stats
    if (!empty($stdfoot['js'])) {
        $htmlfoot .= '<!-- javascript goes here in footer -->';
        foreach ($stdfoot['js'] as $JS) {
            $htmlfoot .= '
		<script src="'.$TRINITY20['baseurl'].'/scripts/'.$JS.'.js"></script>';
        }
    }
    $querytime = 0;
    $max_class = $CURUSER['class'] ?? '';
    if ($CURUSER) {
        if ($query_stat && $debug) {
            $htmlfoot .= "
                <div class='card'>
                    <div class='card-divider'>
		                <label>{$lang['gl_stdfoot_querys']}</label>
	                </div>
	                <div class='card-section'>
					    <table class='table'>
						    <thead>
							    <tr>
                                    <th>{$lang['gl_stdfoot_id']}</th>
                                    <th>{$lang['gl_stdfoot_qt']}</th>
                                    <th>{$lang['gl_stdfoot_qs']}</th>
                                </tr>
                            </thead>";
                            foreach ($query_stat as $key => $value) {
                                $querytime += $value['seconds']; // query execution time
                                $htmlfoot .= "
                            <tbody>
                                <tr>
                                    <td>" . ($key + 1) . "</td>
                                    <td>" . ($value['seconds'] > 0.01 ? "
                                        <span class='text-danger' title='{$lang['gl_stdfoot_ysoq']}'>" . $value['seconds'] . "</span>" : "
                                        <span class='text-success' title='{$lang['gl_stdfoot_qg']}'>" . $value['seconds'] . "</span>") . "
                                    </td>
                                    <td>" . htmlsafechars($value['query']) . "</td>
                                </tr>
                            </tbody>";
        }
                        $htmlfoot .= '
                        </table>
                    </div>
                </div>';
    }
                $htmlfoot .= "
				<div class='callout primary float-left'>
				    " . $TRINITY20['site_name'] . " {$lang['gl_stdfoot_querys_page']}" . $r_seconds . " {$lang['gl_stdfoot_querys_seconds']}<br >" . "
				    {$lang['gl_stdfoot_querys_server']}" . $queries . " {$lang['gl_stdfoot_querys_time']} " . ($queries != 1 ? "{$lang['gl_stdfoot_querys_times']}" : "") . "<br>
				    " . ($debug ? "{$lang['gl_stdfoot_uptime']} " . $uptime . "" : " ") . "
				</div>
				<div class='callout primary float-right text-right'>
				    {$lang['gl_stdfoot_powered']}" . TBVERSION . "<br >
				    {$lang['gl_stdfoot_using']}{$lang['gl_stdfoot_using1']}<br >
                    {$lang['gl_stdfoot_support']}<a href='https://u-232-forum.duckdns.org'>{$lang['gl_stdfoot_here']}</a>
                </div><!-- Ends Footer -->
            </div><!--  End main outer container -->
		<!-- localStorage for collapse -->
        <script src='/dist/js/app.js'></script>";
    }
    $htmlfoot .= '</body></html>';
    return $htmlfoot;
}

function stdmsg($heading, $text)
{
    $htmlout = "<div class='callout alert-callout-border alert'>";
    if ($heading) {
        $htmlout .= "<strong><p>{$heading}</p></strong>";
    }
    $htmlout .= "<p>{$text}</p>";
    $htmlout .= "</div>";
    return $htmlout;
}

function StatusBar()
{
    global $CURUSER, $TRINITY20, $lang, $rep_is_on, $cache, $mysqli, $msgalert, $cache_keys;
    if (!$CURUSER) {
        return "";
    }
    $upped = mksize($CURUSER['uploaded']);
    $downed = mksize($CURUSER['downloaded']);
    $connectable = "";
    if ($CURUSER['class'] < UC_VIP) {
        $ratioq = (($CURUSER['downloaded'] > 0) ? ($CURUSER['uploaded'] / $CURUSER['downloaded']) : 1);
        if ($ratioq < 0.95) {
            switch (true) {
                case ($ratioq < 0.5):
                    $max = 2;
                    break;
                case ($ratioq < 0.65):
                    $max = 3;
                    break;
                case ($ratioq < 0.8):
                    $max = 5;
                    break;
                case ($ratioq < 0.95):
                    $max = 10;
                    break;
                default:
                    $max = 10;
            }
        } else {
            switch ($CURUSER['class']) {
                case UC_USER:
                    $max = 20;
                    break;
                case UC_POWER_USER:
                    $max = 30;
                    break;
                default:
                    $max = 99;
            }
        }
    } else {
        $max = 999;
    }
    if (XBT_TRACKER) {
        if ($MyPeersXbtCache = $cache->get($cache_keys['my_xbt_peers'] . $CURUSER['id']) === false) {
            $seed['yes'] = $seed['no'] = 0;
            $seed['conn'] = 3;
            $result = sql_query("SELECT COUNT(uid) AS `count`, `left`, `active`, `connectable` FROM `xbt_peers` WHERE uid= " . sqlesc($CURUSER['id']) . " AND `left` = 0 AND `active` = 1") or sqlerr(__LINE__, __FILE__);
            while ($a = $result->fetch_assoc()) {
                $key = $a['left'] == 0 ? 'yes' : 'no';
                $seed[$key] = number_format(0 + $a['count']);
                $seed['conn'] = $a['connectable'] == 0 ? 1 : 2;
            }
            $cache->set($cache_keys['my_xbt_peers'] . $CURUSER['id'], $seed, $TRINITY20['expires']['MyPeers_xbt_']);
            //unset($result, $a);
        }
        $seed = $MyPeersXbtCache;
    } else {
        if (($MyPeersCache = $cache->get($cache_keys['my_peers'] . $CURUSER['id'])) === false) {
            $seed['yes'] = $seed['no'] = 0;
            $seed['conn'] = 3;
            $resultp = "SELECT COUNT(id) AS count, seeder, connectable FROM peers WHERE userid=" . sqlesc($CURUSER['id']) . " GROUP BY seeder" or sqlerr(__FILE__, __LINE__);
            $result = $mysqli->query($resultp);
            $rows = array();
            while ($rows = $result->fetch_assoc()) {
                $key = $rows['seeder'] == 'yes' ? 'yes' : 'no';
                $seed[$key] = number_format(0 + $rows['count']);
                $seed['conn'] = $rows['connectable'] == 'no' ? 1 : 2;
            }
            $cache->set($cache_keys['my_peers'] . $CURUSER['id'], $seed, 0);
            unset($resultp, $rows);
        }
        $seed = $MyPeersCache;
    }
    // for display connectable  1 / 2 / 3
    if (!empty($seed['conn'])) {
        switch ($seed['conn']) {
            case 1:
                $connectable = "<span style='color: #82c115;' title='{$lang['gl_connectable']}'><i class='fas fa-times'></i></span>";
                break;
            case 2:
                $connectable = "<span style='color: #f10917;' title='{$lang['gl_connectable']}'><i class='fas fa-check-circle'></i></span>";
                break;
            default:
                $connectable = "<span title='Connectable - Unknown'><i class='fas fa-question'></i></span>";
        }
    } else {
        $connectable = 'N/A';
    }
    if (($Achievement_Points = $cache->get($cache_keys['user_achiev_points'] . $CURUSER['id'])) === false) {
        $Sql = "SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($CURUSER['id']) or sqlerr(__FILE__, __LINE__);
        $result = $mysqli->query($Sql);
        $Achievement_Points = $result->fetch_assoc();;
        $Achievement_Points['id'] = (int)$Achievement_Points['id'];
        $Achievement_Points['achpoints'] = (int)$Achievement_Points['achpoints'];
        $Achievement_Points['spentpoints'] = (int)$Achievement_Points['spentpoints'];
        $cache->set($cache_keys['user_achiev_points'] . $CURUSER['id'], $Achievement_Points);
    }
    $hitnruns = ($CURUSER['hit_and_run_total'] > 0) ? $CURUSER['hit_and_run_total'] : '0';
    $member_reputation = get_reputation($CURUSER);
    $usrclass = $htmlout = "";
    if ($CURUSER['override_class'] != 255) {
        $usrclass = "&nbsp;<b>[".get_user_class_name($CURUSER['class'])."]</b>&nbsp;";
    }
    else if ($CURUSER['class'] >= UC_STAFF) {
        $usrclass = "&nbsp;<a href='".$TRINITY20['baseurl']."/setclass.php'><b>[".get_user_class_name($CURUSER['class'])."]</b></a>&nbsp;";
    }
    $htmlout .= "Welcome " . format_username($CURUSER) . "" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "[" . get_user_class_name($CURUSER['class']) . "]" : $usrclass) . " | 
    {$lang['gl_act_torrents']} : | 
    " . ($TRINITY20['achieve_sys_on'] ? "<span style='color: #82c115;' title='{$lang['gl_achpoints']}'><i class='fas fa-award'></i></span> <a href='./achievementhistory.php?id={$CURUSER['id']}'>" . (int) $Achievement_Points['achpoints'] . "</a>&nbsp;" : "") . " | 
    " . ($TRINITY20['seedbonus_on'] ? "<span style='color: #82c115;' title='{$lang['gl_karma']}'><i class='fas fa-coins'></i></span> : <a href='" . $TRINITY20['baseurl'] . "/mybonus.php'>{$CURUSER['seedbonus']}</a>" : "") . " | <span style='color: Tomato;' title='{$lang['gl_invites']}'><i class='fas fa-user-plus'></i></span> : <a href='" . $TRINITY20['baseurl'] . "/invite.php'>{$CURUSER['invites']}</a> | 
    Free Slots: " . $CURUSER['freeslots'] . " | 
    " . ($TRINITY20['rep_sys_on'] ? "{$lang['gl_rep']}:{$member_reputation}" : "") . " | 
    <span style='color: Tomato;' title='{$lang['gl_shareratio']}'><i class='fas fa-chart-pie'></i></span> " . member_ratio($CURUSER['uploaded'], $TRINITY20['ratio_free'] ? '0' : $CURUSER['downloaded']) . " | ";

    if ($TRINITY20['ratio_free']) {
        $htmlout .= "<span style='color: #82c115;' title='{$lang['gl_uploaded']}'><i class='fas fa-upload'></i></span> " . $upped . " | ";
    } else {
        $htmlout .= "<span style='color: #82c115;' title='{$lang['gl_uploaded']}'><i class='fas fa-upload'></i></span> {$upped} | 
		<span style='color: #f10917;' title='{$lang['gl_downloaded']}'><i class='fas fa-download'></i></span> {$downed} | 
		{$connectable} | ";
    }
    //$htmlout .= "{$lang['gl_hnr']}: <a href='" . $TRINITY20['baseurl'] . "/hnr.php?id=" . $CURUSER['id'] . "'>{$hitnruns}</a>";
    return $htmlout;
}
function hero_header() {
    global $TRINITY20, $CURUSER, $lang, $salty, $htmlout;
    $salty_username = isset($CURUSER['username']) ? "{$CURUSER['username']}" : '';
    $salty = HashIt($TRINITY20['site']['salt'], $salty_username);
    $htmlout = '';
    $htmlout .= "<header class='subnav-hero-section'>
    <a href='" . $TRINITY20['baseurl'] . "/index.php'>
        <img class='subnav-hero-img' src='" . $TRINITY20['pic_base_url']. "logo.png'></a>
        <ul class='subnav-hero-subnav'>
            <li><a href='" . $TRINITY20['baseurl'] . "/index.php'>{$lang['gl_home']}</a></li>
            <li><a href='" . $TRINITY20['baseurl'] . "/tv_guide.php'>Tv Guide</a></li>
            <li><a href='" . $TRINITY20['baseurl'] . "/forums.php'>{$lang['gl_forums']}</a></li>
            <li><a href='#' data-open='alert-sitess'><i class='fa fa-warning'></i> Alert</a></li>
            " . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a data-toggle='StaffPanel'>Staff Links</a></li>" : "");
                $htmlout.="<li><a href='" . $TRINITY20['baseurl'] . "/pm_system.php'><i class='fas fa-envelope fa-lg'></i>{$lang['gl_pms']}<span id='unread_m'></span></a></li>
                <li><a href='" . $TRINITY20['baseurl'] . "/logout.php?hash_please={$salty}'><i class='fas fa-power-off'></i>{$lang['gl_logout']}</a></li>
        </ul>
    </header>";
    return $htmlout;
}

function global_alerts() {
    global $CURUSER, $blocks, $BLOCKS, $htmlout, $cache, $lang, $cache_keys, $TRINITY20;
    
    $htmlout = '';

    $htmlout .= '<div class="reveal" id="alert-sitess" data-reveal>
        <div class="mobile-ios-modal-inner">
        <div class="button-group align-center">';
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_REPORTS && $BLOCKS['global_staff_report_on']) {
        require_once(BLOCK_DIR . 'global/report.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_UPLOADAPP && $BLOCKS['global_staff_uploadapp_on']) {
        require_once(BLOCK_DIR . 'global/uploadapp.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_HAPPYHOUR && $BLOCKS['global_happyhour_on']) {
        require_once(BLOCK_DIR . 'global/happyhour.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFF_MESSAGE && $BLOCKS['global_staff_warn_on']) {
        require_once(BLOCK_DIR . 'global/staffmessages.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_DEMOTION && $BLOCKS['global_demotion_on']) {
        require_once(BLOCK_DIR . 'global/demotion.php');
    }

    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH && $BLOCKS['global_freeleech_on']) {
        require_once(BLOCK_DIR . 'global/freeleech.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_BUG_MESSAGE && $BLOCKS['global_bug_message_on']) {
        require_once(BLOCK_DIR . 'global/bugmessages.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION && $BLOCKS['global_freeleech_contribution_on']) {
        require_once(BLOCK_DIR . 'global/freeleech_contribution.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_CRAZYHOUR && $BLOCKS['global_crazyhour_on']) {
        require_once(BLOCK_DIR . 'global/crazyhour.php');
    }
    $htmlout .= '</div>
  </div>
</div>';
    return $htmlout;
}

function TitleBar() {

    global $TRINITY20, $lang, $CURUSER, $htmlout; 
    $htmlout .= "
    <div class='title-bar' data-sticky data-options='marginTop:0;' data-responsive-toggle='topbar-nav' data-hide-for='medium'>
        <button class='menu-icon' type='button' data-toggle></button>
        <div class='title-bar-title'>Menu</div>
    </div>
    <div class='top-bar stacked-for-medium' style='width:100%; z-index: 100;' data-margin-top='0' id='topbar-nav'>
        <div class='top-bar-left'>

        </div>
        <div class='top-bar-right'>
            <ul class='dropdown menu' data-dropdown-menu>
                <li>
                <a href='#'>{$lang['gl_general']}</a>
                <ul class='menu vertical'>
                    <li><a href='" . $TRINITY20['baseurl'] . "/topten.php'>{$lang['gl_stats']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/chat.php'>{$lang['gl_chat']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/staff.php'>{$lang['gl_staff']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/wiki.php'>{$lang['gl_wiki']}</a></li>
                    <li><a href='#' onclick='radio();'>{$lang['gl_radio']}</a></li>
                    <li><a class='hide-for-medium' href='" . $TRINITY20['baseurl'] . "/tv_guide.php'>Tv Guide</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/rsstfreak.php'>{$lang['gl_tfreak']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/sitepot.php'>{$lang['gl_sitepot']}</a></li>
                </ul>
                </li>
                <li>
                <a href='#'>{$lang['gl_torrent']}</a>
                <ul class='menu vertical'>
                    <li><a href='" . $TRINITY20['baseurl'] . "/browse.php'>{$lang['gl_torrents']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/requests.php'>{$lang['gl_requests']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/offers.php'>{$lang['gl_offers']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/needseed.php?needed=seeders'>{$lang['gl_nseeds']}</a></li>" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "
                    <li><a href='" . $TRINITY20['baseurl'] . "/uploadapp.php'>{$lang['gl_uapp']}</a></li> " : "
                    <li><a href='" . $TRINITY20['baseurl'] . "/upload.php'>{$lang['gl_upload']}</a></li>") . "" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "" : "
                    <li><a href='" . $TRINITY20['baseurl'] . "/multiupload.php'>{$lang['gl_mupload']}</a></li>") . "
                    <li><a href='" . $TRINITY20['baseurl'] . "/bookmarks.php'>{$lang['gl_bookmarks']}</a></li>
                    <li><a href='" . $TRINITY20['baseurl'] . "/subtitle.php'>Subtitles</a></li>
                </ul>
                </li>
                    <li>
                        <a href='#'>{$lang['gl_games']}</a>
                        <ul class='menu vertical'>
                            <li><a href='" . $TRINITY20['baseurl'] . "/casino.php'>{$lang['gl_casino']}</a></li>
                            <li><a href='" . $TRINITY20['baseurl'] . "/blackjack.php'>{$lang['gl_bjack']}</a></li>
                            <li><a href='lottery.php'>{$lang['gl_lnk_lott']}</a></li>
                        </ul>
                    </li>
                <li>
                <a href='#'>STAFF</a>
                <ul class='menu vertical'>
                    <li>" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $TRINITY20['baseurl'] . "/bugs.php?action=add'>{$lang['gl_breport']}</a>" : "<a class='brand' href='" . $TRINITY20['baseurl'] . "/bugs.php?action=bugs'>{$lang['gl_brespond']}</a>") . "</li>
                    <li> " . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $TRINITY20['baseurl'] . "/contactstaff.php'>{$lang['gl_cstaff']}</a>" : "<a class='brand' href='" . $TRINITY20['baseurl'] . "/staffbox.php'>{$lang['gl_smessages']}</a>") . "</li>
                    " . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a href='" . $TRINITY20['baseurl'] . "/staffpanel.php'>{$lang['gl_admin']}</a></li>" : "") . "
                    " . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a data-toggle='StaffPanel'>Quick Links</a></li>" : "") . "
                </ul>
                </li>
                <li><a href='" . $TRINITY20['baseurl']. "/help.php'>Help</a></li>
                <li>
                    <a href='https://github.com/iseeyoucopy/U-232-Trinity-RC'><i class='fab fa-github fa-lg'></i></a></li>
                <li>
                <a href='#'><i class='fas fa-id-badge fa-lg'></i></a>
                <ul class='menu vertical align-center'>
                    <li><a href='" . $TRINITY20['baseurl'] . "/usercp.php?action=default'>{$lang['gl_usercp']}</a></li>
                    <li><a href='#' onclick='themes();'>{$lang['gl_theme']}</a></li>
                    <li><a href='#' onclick='language_select();'>{$lang['gl_language_select']}</a></li>
                    <li><a href='mytorrents.php'>{$lang['gl_mytorrents']}</a></li>
                    <li><a href='friends.php'>{$lang['gl_myfriends']}</a></li>
                    <li><a href='users.php'>{$lang['gl_search_members']}</a></li>
                    <li><a href='invite.php'>{$lang['gl_lnk_inv']}</a>
                    <li><a href='tenpercent.php'>{$lang['gl_lnk_life']}</a></li>
                    <li><a href='topmoods.php'>{$lang['gl_lnk_top']}</a></li>
                </ul>
            </li>
            </ul>
        </div>
    </div>";
    return $htmlout;
}

function quickStaffTools() {
    
    global $CURUSER, $blocks, $BLOCKS, $htmlout, $lang, $cache, $cache_keys, $TRINITY20;
    
    $htmlout = '';
    //** Start Quick stafftools canvas menu */
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFFTOOLS && $BLOCKS['global_staff_tools_on'] && $CURUSER['class'] >= UC_STAFF) {
        require_once(BLOCK_DIR . 'global/staff_tools.php');
    }

    return $htmlout;
}
