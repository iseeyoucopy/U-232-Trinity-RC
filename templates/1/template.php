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
    global $CURUSER, $TRINITY20, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $cache, $BLOCKS, $CURBLOCK, $mood, $blocks;
    if (!$TRINITY20['site_online']) die("Site is down for maintenance, please check back again later... thanks<br />");
    if ($title == "") $title = $TRINITY20['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '');
    else $title = $TRINITY20['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '') . " :: " . htmlsafechars($title);
    if ($CURUSER) {
        $TRINITY20['stylesheet'] = isset($CURUSER['stylesheet']) ? "{$CURUSER['stylesheet']}.css" : $TRINITY20['stylesheet'];
        $TRINITY20['categorie_icon'] = isset($CURUSER['categorie_icon']) ? "{$CURUSER['categorie_icon']}" : $TRINITY20['categorie_icon'];
        $TRINITY20['language'] = isset($CURUSER['language']) ? "{$CURUSER['language']}" : $TRINITY20['language'];
    }
    $torrent_pass = isset($CURUSER['torrent_pass']) ? "{$CURUSER['torrent_pass']}" : '';
    /** ZZZZZZZZZZZZZZZZZZZZZZZZZZip it! */

    if (!isset($_NO_COMPRESS)) if (!ob_start('ob_gzhandler')) ob_start();
    $htmlout = '';
    //== Include js files needed only for the page being used by pdq
    $js_incl = '';
    $js_incl .= '<!-- javascript goes here or in footer -->';
    if (!empty($stdhead['js'])) {
        foreach ($stdhead['js'] as $JS) $js_incl .= "<script type='text/javascript' src='{$TRINITY20['baseurl']}/scripts/" . $JS . ".js'></script>";
    }

    //== Include css files needed only for the page being used by pdq
    $stylez = ($CURUSER ? "{$CURUSER['stylesheet']}" : "{$TRINITY20['stylesheet']}");
    $css_incl = '';
    $css_incl .= '<!-- css goes in header -->';
    if (!empty($stdhead['css'])) {
        foreach ($stdhead['css'] as $CSS) $css_incl .= "<link type='text/css' rel='stylesheet' href='{$TRINITY20['baseurl']}/templates/{$stylez}/css/" . $CSS . ".css' />";
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
    <meta charset="' . charset() . '" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
		<!-- favicon  -->
    	<link rel="shortcut icon" href="/favicon.ico" />
<!-- Template CSS-->
        <link rel="stylesheet" href="templates/1/1.css" />
        <link rel="stylesheet" href="templates/1/fontawesome/css/all.min.css" />
		<link rel="stylesheet" href="templates/1/foundation-icons/foundation-icons.css" />
        <link rel="stylesheet" href="foundation/dist/assets/css/app.css">
        <script src="scripts/jquery.js"></script>';
    if ($CURUSER) {
        $htmlout .= '
    <script type="application/rss+xml" title="Latest Torrents" src="/rss.php?torrent_pass=' . $torrent_pass . '"></script>';
        $htmlout .= "
    <style type='text/css'>#mlike{cursor:pointer;}</style>
    <script type='text/javascript'>
        /*<![CDATA[*/
	// template changer function
	//================================================== -->
        function themes() {
          window.open('take_theme.php','My themes','height=150,width=200,resizable=no,scrollbars=no,toolbar=no,menubar=no');
        }
	// language changer function
	//================================================== -->
        function language_select() {
          window.open('take_lang.php','My language','height=150,width=200,resizable=no,scrollbars=no,toolbar=no,menubar=no');
        }
	// radio function
	//================================================== -->
        function radio() {
          window.open('radio_popup.php','My Radio','height=700,width=800,resizable=no,scrollbars=no,toolbar=no,menubar=no');
        }
         /*]]>*/
        </script>
        {$js_incl}{$css_incl}
        </head><body>";
        $htmlout .= TopBar();
        if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFFTOOLS && $BLOCKS['global_staff_tools_on'] && $CURUSER['class'] >= UC_STAFF) {
            require_once(BLOCK_DIR . 'global/staff_tools.php');
        }
        $htmlout .= '<div class="grid-container">';
        $htmlout .= subnav_header();
        $htmlout .= StatusBar() . GlobalAlert();
    }
    return $htmlout;
}

function stdfoot($stdfoot = false)
{
    global $CURUSER, $TRINITY20, $start, $query_stat, $cache, $querytime, $lang, $rc;
    $user_id = isset($CURUSER['id']) ? $CURUSER['id'] : '';
    $debug = (SQL_DEBUG && in_array($user_id, $TRINITY20['allowed_staff']['id']) ? 1 : 0);
    $seconds = microtime(true) - $start;
    $r_seconds = round($seconds, 5);
    $queries = (!empty($query_stat)); // sql query count by pdq
    // load averages - pdq
    if ($debug) {
        if (($uptime = $cache->get('uptime')) === false) {
            $uptime = `uptime`;
            $cache->set('uptime', $uptime, 25);
        }
    }
    //== end class
    $htmlfoot = '';
    //== query stats
    $htmlfoot .= '';
    if (!empty($stdfoot['js'])) {
        $htmlfoot .= '<!-- javascript goes here in footer -->';
        foreach ($stdfoot['js'] as $JS) $htmlfoot .= '
		<script src="' . $TRINITY20['baseurl'] . '/scripts/' . $JS . '.js"></script>';
    }
    $querytime = 0;
    $max_class = isset($CURUSER['class']) ? $CURUSER['class'] : '';
    if ($CURUSER) {
        if ($query_stat && $debug) {
            $htmlfoot .= "
<div class='card'>
	<div class='card-divider'>
		<label for='checkbox_4' class='text-left'>{$lang['gl_stdfoot_querys']}</label>
	</div>
	<div class='card-section'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr>
								<th class='text-center'>{$lang['gl_stdfoot_id']}</th>
								<th class='text-center'>{$lang['gl_stdfoot_qt']}</th>
								<th class='text-center'>{$lang['gl_stdfoot_qs']}</th>
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
								<td>" . htmlsafechars($value['query']) . "<br /></td>
							</tr>
						</tbody>";
            }
            $htmlfoot .= '</table></div></div>';
        }
        $htmlfoot .= "
				<div class='callout primary float-left'>
				" . $TRINITY20['site_name'] . " {$lang['gl_stdfoot_querys_page']}" . $r_seconds . " {$lang['gl_stdfoot_querys_seconds']}<br />" . "
				{$lang['gl_stdfoot_querys_server']}" . $queries . " {$lang['gl_stdfoot_querys_time']} " . ($queries != 1 ? "{$lang['gl_stdfoot_querys_times']}" : "") . "</br>
				" . ($debug ? "{$lang['gl_stdfoot_uptime']} " . $uptime . "" : " ") . "
				</div>
				<div class='callout primary float-right text-right'>
				{$lang['gl_stdfoot_powered']}" . TBVERSION . "<br />
				{$lang['gl_stdfoot_using']}{$lang['gl_stdfoot_using1']}<br />
				{$lang['gl_stdfoot_support']}<a href='https://u-232-forum.duckdns.org'>{$lang['gl_stdfoot_here']}</a>";
        $htmlfoot .= '</div></div></div><!--  End main outer container -->
                     <!-- Ends Footer -->
		             <!-- localStorage for collapse -->
                     <script src="foundation/dist/assets/js/app.js"></script>
					 <script src="templates/1/fontawesome/js/all.min.js"></script>';
    }
    $htmlfoot .= '</body></html>';
    return $htmlfoot;
}
function stdmsg($heading, $text)
{
    $htmlout = "<div class='callout alert-callout-border alert'>";
    if ($heading)
        $htmlout .= "<strong><p>{$heading}</p></strong>";
    $htmlout .= "<p>{$text}</p>";
    $htmlout .= "</div>";
    return $htmlout;
}
function StatusBar()
{
    global $CURUSER, $TRINITY20, $lang, $rep_is_on, $cache, $mysqli, $msgalert, $keys;
    if (!$CURUSER) return "";
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
    } else
        $max = 999;
    //==Memcache peers
    if (XBT_TRACKER == true) {
        if ($MyPeersXbtCache = $cache->get($keys['my_xbt_peers'] . $CURUSER['id']) === false) {
            $seed['yes'] = $seed['no'] = 0;
            $seed['conn'] = 3;
            $result = sql_query("SELECT COUNT(uid) AS `count`, `left`, `active`, `connectable` FROM `xbt_peers` WHERE uid= " . sqlesc($CURUSER['id']) . " AND `left` = 0 AND `active` = 1") or sqlerr(__LINE__, __FILE__);
            while ($a = mysqli_fetch_assoc($result)) {
                $key = $a['left'] == 0 ? 'yes' : 'no';
                $seed[$key] = number_format(0 + $a['count']);
                $seed['conn'] = $a['connectable'] == 0 ? 1 : 2;
            }
            $cache->set($keys['my_xbt_peers'] . $CURUSER['id'], $seed, $TRINITY20['expires']['MyPeers_xbt_']);
            //unset($result, $a);
        }
        $seed = $MyPeersXbtCache;
    } else {
        if (($MyPeersCache = $cache->get($keys['my_peers'] . $CURUSER['id'])) === false) {
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
            $cache->set($keys['my_peers'] . $CURUSER['id'], $seed, 0);
            unset($resultp, $rows);
        }
        $seed = $MyPeersCache;
    }
    // for display connectable  1 / 2 / 3
    if (!empty($seed['conn'])) {
        switch ($seed['conn']) {
            case 1:
                $connectable = "<i class='fas fa-times greeniconcolor'></i>";
                break;
            case 2:
                $connectable = "<i class='fas fa-check-circle greeniconcolor'></i>";
                break;
            default:
                $connectable = "N/A";
        }
    } else $connectable = 'N/A';
    if (($Achievement_Points = $cache->get('user_achievement_points_' . $CURUSER['id'])) === false) {
        $Sql = "SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($CURUSER['id']) or sqlerr(__FILE__, __LINE__);
        $result = $mysqli->query($Sql);
        $Achievement_Points = $result->fetch_assoc();;
        $Achievement_Points['id'] = (int)$Achievement_Points['id'];
        $Achievement_Points['achpoints'] = (int)$Achievement_Points['achpoints'];
        $Achievement_Points['spentpoints'] = (int)$Achievement_Points['spentpoints'];
        $cache->set('user_achievement_points_' . $CURUSER['id'], $Achievement_Points);
    }
    $salty_username = isset($CURUSER['username']) ? "{$CURUSER['username']}" : '';
    $salty = md5("Th15T3xtis5add3dto66uddy6he@water..." . $salty_username . "");
    $hitnruns = ($CURUSER['hit_and_run_total'] > 0) ? $CURUSER['hit_and_run_total'] : '0';
    $member_reputation = get_reputation($CURUSER);
    $usrclass = $StatusBar = "";
    if ($CURUSER['override_class'] != 255) $usrclass = "&nbsp;<b>[" . get_user_class_name($CURUSER['class']) . "]</b>&nbsp;";
    else if ($CURUSER['class'] >= UC_STAFF) $usrclass = "&nbsp;<a href='" . $TRINITY20['baseurl'] . "/setclass.php'><b>[" . get_user_class_name($CURUSER['class']) . "]</b></a>&nbsp;";
    $StatusBar .= '<div class="dropdown-pane padding-0" id="profile-dropdown" data-dropdown data-close-on-click="true" data-auto-focus="true">
    <div class="grid-container">
      <div class="grid-x grid-margin-x">';
    $StatusBar .= "<div class='card padding-1'><dl>
		<dd class='text-center'>" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? get_user_class_name($CURUSER['class']) : $usrclass) . "</dd>
		<dd>{$lang['gl_act_torrents']}&nbsp;:&nbsp;

		<dd>" . ($TRINITY20['seedbonus_on'] ? "{$lang['gl_karma']}: <a href='" . $TRINITY20['baseurl'] . "/mybonus.php'>{$CURUSER['seedbonus']}</a>&nbsp;" : "") . "</dd>
		<dd>{$lang['gl_invites']}: <a href='" . $TRINITY20['baseurl'] . "/invite.php'>{$CURUSER['invites']}</a> | Free Slots: " . $CURUSER['freeslots'] . "</dd>
		<dd>" . ($TRINITY20['rep_sys_on'] ? "{$lang['gl_rep']}:{$member_reputation}&nbsp;" : "") . "</dd>
		<dd>{$lang['gl_shareratio']}" . member_ratio($CURUSER['uploaded'], $TRINITY20['ratio_free'] ? '0' : $CURUSER['downloaded']) . "</dd>";

    if ($TRINITY20['ratio_free']) {
        $StatusBar .= "<dd>{$lang['gl_uploaded']}:" . $upped . "</dd>";
    } else {
        $StatusBar .= "<dd>{$lang['gl_uploaded']}:{$upped}</dd>
		<dd>{$lang['gl_downloaded']}:{$downed}</dd>
		<dd>{$lang['gl_connectable']}:{$connectable}</dd>";
    }
    $StatusBar .= "<dd>{$lang['gl_hnr']}: <a href='" . $TRINITY20['baseurl'] . "/hnr.php?id=" . $CURUSER['id'] . "'>{$hitnruns}</a>&nbsp;</dd>
	<dd><a href='#' onclick='themes();'><i class='fas fa-palette blueiconcolor' title='{$lang['gl_theme']}'></i></a> | <a href='#' onclick='language_select();'><i class='fas fa-language' title='{$lang['gl_language_select']}'></i></a> | <a href='" . $TRINITY20['baseurl'] . "/friends.php'><i class='fas fa-user-friends' title='{$lang['gl_friends']}'></i></a> | <a href='" . $TRINITY20['baseurl'] . "/logout.php?hash_please={$salty}'><i class='fas fa-power-off rediconcolor' title='{$lang['gl_logout']}'></i></a></dd></dl>";
    $StatusBar .= "</div></div></div></div>";
    return $StatusBar;
}
function GlobalAlert()
{
    global $CURUSER, $TRINITY20, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $cache, $BLOCKS, $CURBLOCK, $mood, $blocks;
    $htmlout = '';
    $htmlout .= '<div class="dropdown-pane padding-0" id="alerts-dropdown" data-dropdown data-close-on-click="true" data-auto-focus="true">';
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

    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_CRAZYHOUR && $BLOCKS['global_crazyhour_on']) {
        require_once(BLOCK_DIR . 'global/crazyhour.php');
    }

    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_BUG_MESSAGE && $BLOCKS['global_bug_message_on']) {
        require_once(BLOCK_DIR . 'global/bugmessages.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION && $BLOCKS['global_freeleech_contribution_on']) {
        require_once(BLOCK_DIR . 'global/freeleech_contribution.php');
    }
    $htmlout .= '</div>';
    return $htmlout;
}
function TopBar()
{
    global $CURUSER, $TRINITY20, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $cache, $BLOCKS, $CURBLOCK, $mood, $blocks;
    $htmlout = '';
    $htmlout.= "
    <div class='multilevel-offcanvas off-canvas position-right' id='offCanvasRight' data-off-canvas>
    <ul class='vertical menu accordion-menu' data-accordion-menu>
      <li><a href='#'>{$lang['gl_general']}</a>
        <ul class='menu vertical nested'>
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
      <li><a href='#'>{$lang['gl_torrent']}</a>
        <ul class='menu vertical nested'>
            <li><a class='hide-for-medium' href='" . $TRINITY20['baseurl'] . "/browse.php'>{$lang['gl_torrents']}</a></li>
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
       <li><a href='#'>{$lang['gl_games']}</a>
       <ul class='menu vertical nested'>
        <li><a href='" . $TRINITY20['baseurl'] . "/casino.php'>{$lang['gl_casino']}</a></li>
        <li><a href='" . $TRINITY20['baseurl'] . "/blackjack.php'>{$lang['gl_bjack']}</a></li>
         </ul>
      </li>
      <li><a href='#'>{$lang['gl_lnk_men']}</a>
      <ul class='menu vertical nested'>
      <li><a href='mytorrents.php'>{$lang['gl_mytorrents']}</a></li>
      <li><a href='friends.php'>{$lang['gl_myfriends']}</a></li>
      <li><a href='users.php'>{$lang['gl_search_members']}</a></li>
      <li><a href='invite.php'>{$lang['gl_lnk_inv']}</a>
      {$lang['gl_lnk_enter']}
      <li><a href='tenpercent.php'>{$lang['gl_lnk_life']}</a></li>
      <li><a href='topmoods.php'>{$lang['gl_lnk_top']}</a></li>
      <li><a href='lottery.php'>{$lang['gl_lnk_lott']}</a></li>
      </ul>
      </li>
      <li><a href='#'>Staff</a>
        <ul class='menu vertical nested'>
        <li>" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $TRINITY20['baseurl'] . "/bugs.php?action=add'>{$lang['gl_breport']}</a>" : "<a class='brand' href='" . $TRINITY20['baseurl'] . "/bugs.php?action=bugs'>{$lang['gl_brespond']}</a>") . "</li>
        <li> " . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $TRINITY20['baseurl'] . "/contactstaff.php'>{$lang['gl_cstaff']}</a>" : "<a class='brand' href='" . $TRINITY20['baseurl'] . "/staffbox.php'>{$lang['gl_smessages']}</a>") . "</li>
        " . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a href='" . $TRINITY20['baseurl'] . "/staffpanel.php'>{$lang['gl_admin']}</a></li>" : "") . "
        " . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a data-toggle='StaffPanel'>Quick Links</a></li>" : "") . "
         </ul>
       </li>
     </ul>
    <ul class='vertical menu'>
      <li class='off-canvas-menu-item'><a href='" . $TRINITY20['baseurl'] . "/forums.php'>{$lang['gl_forums']}</a></li>
      <li><a href='{$TRINITY20['baseurl']}/donate.php'>Donate</a></li>
      <li><a href='#'><a href='{$TRINITY20['baseurl']}/help.php'>Help</a></a></li>
      <li><a href='#'>New link</a></li>
      <li><a href='#'>New link</a></li>
    </ul>
    <ul class='vertical menu'>
       <li><a class='hide-for-medium' href='" . $TRINITY20['baseurl'] . "/usercp.php?action=default'>{$lang['gl_usercp']}</a></li>
       <li><a class='hide-for-medium' href='" . $TRINITY20['baseurl'] . "/pm_system.php'>{$lang['gl_pms']}</a></li>
       <li><a href='#'>New link5</a></li>
     </ul>
    <ul class='menu simple social-links'>
      <li><a href='https://github.com/iseeyoucopy/U-232-Trinity-RC'><i class='fab fa-github fa-lg'></i></a></li>
      <li><a href='#' target='_blank'><i class='fab fa-facebook-square' aria-hidden='true'></i></a></li>
      <li><a href='#' target='_blank'><i class='fab fa-github-square' aria-hidden='true'></i></a></li>
      <li><a href='#' target='_blank'><i class='fab fa-google-plus-square' aria-hidden='true'></i></a></li>
    </ul>
  </div>
  <div class='off-canvas-content' data-off-canvas-content>
    <div class='nav-bar'>
        <div class='nav-bar-left'>
        <a class='nav-bar-logo'><img class='logo' src='{$TRINITY20['pic_base_url']}logo.png'></a>
        <a class='dropdown hollow small button' data-toggle='profile-dropdown'>" . $CURUSER['username'] . "</a>
        <a class='dropdown hollow small button' data-toggle='alerts-dropdown'><i class='fas fa-bell'></i></a>";
        if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_NEWPM && $BLOCKS['global_message_on']) {
            $htmlout .= "<script type='text/javascript'>
            $(document).ready(function(){  
                $.ajax({
                    type: 'GET',
                    url: 'ajax.php',
                    success: function(data){
                       $('#unread_m').append(data);
                    }
                });
            setInterval(function(){//setInterval() method execute on every interval until called clearInterval()
                    $('#unread_m').load('ajax.php').fadeIn('slow');
                    //load() method fetch data from fetch.php page
                   }, 1000);    
            });
            </script>";
            //require_once(BLOCK_DIR . 'global/message.php');
            $htmlout.="<a class='small hollow button' href='" . $TRINITY20['baseurl'] . "/pm_system.php'><i class='fas fa-envelope'></i><span class='badge warning' id='unread_m'></span></a>";
        }
        
        $htmlout.="</div><div class='nav-bar-right'>
          <ul class='menu'>
          <li class='hide-for-small-only'></li>
            <li>
              <button class='offcanvas-trigger' type='button' data-open='offCanvasRight'>
                <span class='offcanvas-trigger-text hide-for-small-only'>Menu</span>
                <div class='hamburger'>
                  <span class='line'></span>
                  <span class='line'></span>
                  <span class='line'></span>
                </div>
              </button>
            </li>
          </ul>
      </div>
    </div>";
    return $htmlout;
}
function subnav_header() {
    global $CURUSER, $TRINITY20, $lang;
    $htmlout = "";
    $htmlout.= "
    <header class='subnav-hero-section hide-for-small-only'>
    <h1 class='subnav-hero-headline'>Trinity <small>by U-232 Team</small></h1>
    <ul class='subnav-hero-subnav'>
        <li><a href='" . $TRINITY20['baseurl'] . "/index.php'>{$lang['gl_home']}</a></li>
        <li><a href='" . $TRINITY20['baseurl'] . "/browse.php'>{$lang['gl_torrents']}</a></li>
        <li><a href='" . $TRINITY20['baseurl'] . "/usercp.php?action=default'>{$lang['gl_usercp']}</a></li>
        <li><a href='" . $TRINITY20['baseurl'] . "/tv_guide.php'>Tv Guide</a></li>
        " . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<li><a data-toggle='StaffPanel'>Staff Links</a></li>" : "") . "
        </ul>
  </header>";
    return $htmlout;
}