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
    global $CURUSER, $INSTALLER09, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $cache, $BLOCKS, $CURBLOCK, $mood, $blocks;
    if (!$INSTALLER09['site_online']) die("Site is down for maintenance, please check back again later... thanks<br />");
    if ($title == "") $title = $INSTALLER09['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '');
    else $title = $INSTALLER09['site_name'] . (isset($_GET['tbv']) ? " (" . TBVERSION . ")" : '') . " :: " . htmlsafechars($title);
    if ($CURUSER) {
        $INSTALLER09['stylesheet'] = isset($CURUSER['stylesheet']) ? "{$CURUSER['stylesheet']}.css" : $INSTALLER09['stylesheet'];
        $INSTALLER09['categorie_icon'] = isset($CURUSER['categorie_icon']) ? "{$CURUSER['categorie_icon']}" : $INSTALLER09['categorie_icon'];
        $INSTALLER09['language'] = isset($CURUSER['language']) ? "{$CURUSER['language']}" : $INSTALLER09['language'];
    }
	$torrent_pass = isset($CURUSER['torrent_pass']) ? "{$CURUSER['torrent_pass']}" : '';
    /** ZZZZZZZZZZZZZZZZZZZZZZZZZZip it! */

if (!isset($_NO_COMPRESS)) if (!ob_start('ob_gzhandler')) ob_start();
    $htmlout = '';
    //== Include js files needed only for the page being used by pdq
    $js_incl = '';
    $js_incl.= '<!-- javascript goes here or in footer -->';
    if (!empty($stdhead['js'])) {
        foreach ($stdhead['js'] as $JS) $js_incl.= "<script type='text/javascript' src='{$INSTALLER09['baseurl']}/scripts/" . $JS . ".js'></script>";
    }

    //== Include css files needed only for the page being used by pdq
    $stylez = ($CURUSER ? "{$CURUSER['stylesheet']}" : "{$INSTALLER09['stylesheet']}");
    $css_incl = '';
    $css_incl.= '<!-- css goes in header -->';
    if (!empty($stdhead['css'])) {
        foreach ($stdhead['css'] as $CSS) $css_incl.= "<link type='text/css' rel='stylesheet' href='{$INSTALLER09['baseurl']}/templates/{$stylez}/css/" . $CSS . ".css' />";
    }
$htmlout .='<!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <!-- ####################################################### -->
        <!-- #   This website is powered by U-232    	           # -->
        <!-- #   Download and support at:                          # -->
        <!-- #     https://u-232-forum.duckdns.org/                # -->
        <!-- #   Template Modded by U-232 Dev Team                 # -->
        <!-- ####################################################### -->
  <head>
    <!--<meta charset="'.charset().'" />-->
    <meta charset="utf-8" />
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>'.$title.'</title>
		<!-- favicon  -->
    	<link rel="shortcut icon" href="/favicon.ico" />
<!-- Template CSS-->
        <link rel="stylesheet" href="templates/1/1.css" />
        <link rel="stylesheet" href="templates/1/fontawesome/css/all.min.css" />
		<link rel="stylesheet" href="templates/1/foundation-icons/foundation-icons.css" />
		<link rel="stylesheet" href="foundation/dist/assets/css/app.css">';
if ($CURUSER){
    $htmlout .='
		<script src="scripts/jquery.js"></script>
    <script type="application/rss+xml" title="Latest Torrents" src="/rss.php?torrent_pass='.$torrent_pass.'"></script>';
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
$htmlout.='<div class="grid-x off-canvas-content" data-off-canvas-content>';
		$htmlout.= TopBarResponsive();
		$htmlout.= NavBar();
		$htmlout.= '<div class="cell medium-9 large-10 padding-1">';
		    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFFTOOLS && $BLOCKS['global_staff_tools_on'] && $CURUSER['class'] >= UC_STAFF) {
    require_once (BLOCK_DIR.'global/staff_tools.php');
    }
		$htmlout.= '<div class="banners">';
		$htmlout.= "<div class='margin-3 float-left'><a href='{$INSTALLER09['baseurl']}/index.php'><img src='{$INSTALLER09['pic_base_url']}logo.png'></a></div>";
		$htmlout.= "<div class='margin-3 float-right'><a href='{$INSTALLER09['baseurl']}/donate.php'><img src='{$INSTALLER09['pic_base_url']}makedonation.gif'></a></div>";
		$htmlout.= '</div>';
		$htmlout.= StatusBar();
		$htmlout.= '<div class="card">';
					$link = sql_query("SELECT VERSION()");
					while ($row = mysqli_fetch_assoc($link)) {
						foreach($row as $value){
						$mysql_v = $value;
						}
					}
			$memcached_version = phpversion("memcached");	
			$redis_version = phpversion("redis");			
$htmlout.= "<b class='text-center'> PHP : ". phpversion() ." | Mysql : ". $mysql_v	." | Memcached : ". $memcached_version." | Redis : ".$redis_version."</b>
</div>";
$htmlout.= AlertBar();

    }
    return $htmlout;
   }

function stdfoot($stdfoot = false)
{
    global $CURUSER, $INSTALLER09, $start, $query_stat, $cache, $querytime, $lang, $rc;
	$user_id = isset($CURUSER['id']) ? $CURUSER['id'] : '';
    $debug = (SQL_DEBUG && in_array($user_id, $INSTALLER09['allowed_staff']['id']) ? 1 : 0);
    $seconds = microtime(true) - $start;
    $r_seconds = round($seconds, 5);
    $queries = (!empty($query_stat)); // sql query count by pdq
    define('REQUIRED_PHP_VER', 7.0);
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
    $htmlfoot.= '';
    if (!empty($stdfoot['js'])) {
        $htmlfoot.= '<!-- javascript goes here in footer -->';
        foreach ($stdfoot['js'] as $JS) $htmlfoot.= '
		<script src="' . $INSTALLER09['baseurl'] . '/scripts/' . $JS . '.js"></script>';
    }
    $querytime = 0;
    $max_class = isset($CURUSER['class']) ? $CURUSER['class'] : '';
    if ($CURUSER){
	    if ($query_stat && $debug) {
        $htmlfoot.= "
<div class='card'>
	<div class='card-divider'>
		<label for='checkbox_4' class='text-left'>{$lang['gl_stdfoot_querys']}</label>
	</div>
	<div class='card-body'>
					<table class='table table-hover table-bordered'>
						<thead>
							<tr>
								<th class='text-center'>{$lang['gl_stdfoot_id']}</th>
								<th class='text-center'>{$lang['gl_stdfoot_qt']}</th>
								<th class='text-center'>{$lang['gl_stdfoot_qs']}</th>
							</tr>
						</thead>";
        foreach ($query_stat as $key => $value) {
            $querytime+= $value['seconds']; // query execution time
             $htmlfoot.= "
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
        $htmlfoot.= '</table></div></div>';
    }
        $htmlfoot.= "
				<div class='callout primary float-left'>
				" . $INSTALLER09['site_name'] . " {$lang['gl_stdfoot_querys_page']}" . $r_seconds . " {$lang['gl_stdfoot_querys_seconds']}<br />" . "
				{$lang['gl_stdfoot_querys_server']}" . $queries . " {$lang['gl_stdfoot_querys_time']} " . ($queries != 1 ? "{$lang['gl_stdfoot_querys_times']}" : "") . "</br>
				" . ($debug ? "{$lang['gl_stdfoot_uptime']} " . $uptime . "" : " ") . "
				</div>
				<div class='callout primary float-right text-right'>
				{$lang['gl_stdfoot_powered']}" . TBVERSION . "<br />
				{$lang['gl_stdfoot_using']}{$lang['gl_stdfoot_using1']}<br />
				{$lang['gl_stdfoot_support']}<a href='https://u-232-forum.duckdns.org'>{$lang['gl_stdfoot_here']}</a>";
		$htmlfoot.= '</div></div></div><!--  End main outer container -->
                     <!-- Ends Footer -->
		             <!-- localStorage for collapse -->
                     <script src="foundation/dist/assets/js/app.js"></script>
					 <script src="templates/1/fontawesome/js/all.min.js"></script>';
                }
        $htmlfoot.='</body></html>';
    return $htmlfoot;
}
function stdmsg($heading, $text)
{
$htmlout = "<div class='callout alert-callout-border alert'>";
if ($heading) 
	$htmlout.= "<strong><p>{$heading}</p></strong>";
$htmlout.= "<p>{$text}</p>";
$htmlout.= "</div>";
return $htmlout;
}
function StatusBar()
{
    global $CURUSER, $INSTALLER09, $lang, $rep_is_on, $cache, $mysqli, $msgalert;
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
 }
 else {
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
}
else
$max = 999;
/*
//==Memcache peers
if (XBT_TRACKER == true) {
    if ($MyPeersXbtCache = $cache->get('MyPeers_XBT_' . $CURUSER['id']) === false) {
        $seed['yes'] = $seed['no'] = 0;
        $seed['conn'] = 3;
        $result = sql_query("SELECT COUNT(uid) AS `count`, `left`, `active`, `connectable` FROM `xbt_peers` WHERE uid= " . sqlesc($CURUSER['id']) . " AND `left` = 0 AND `active` = 1") or sqlerr(__LINE__, __FILE__);
        while ($a = mysqli_fetch_assoc($result)) {
            $key = $a['left'] == 0 ? 'yes' : 'no';
            $seed[$key] = number_format(0 + $a['count']);
            $seed['conn'] = $a['connectable'] == 0 ? 1 : 2;
        }
        $cache->set('MyPeers_XBT_'.$CURUSER['id'], $seed, $INSTALLER09['expires']['MyPeers_xbt_']);
        //unset($result, $a);
    }
	$seed = $MyPeersXbtCache;
} else {
    if (($MyPeersCache = $cache->get('MyPeers_' . $CURUSER['id'])) === false) {
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
        $cache->set('MyPeers_' . $CURUSER['id'], $seed, 0);
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
	*/
	/*
    if (($Achievement_Points = $cache->get('user_achievement_points_' . $CURUSER['id'])) === false) {
        $Sql = "SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($CURUSER['id']) or sqlerr(__FILE__, __LINE__);
		$result = $mysqli->query($Sql);
        $Achievement_Points = $result->fetch_assoc();;
        $Achievement_Points['id'] = (int)$Achievement_Points['id'];
        $Achievement_Points['achpoints'] = (int)$Achievement_Points['achpoints'];
        $Achievement_Points['spentpoints'] = (int)$Achievement_Points['spentpoints'];
        $cache->set('user_achievement_points_' . $CURUSER['id'], $Achievement_Points);
    }
	*/
	$salty_username = isset($CURUSER['username']) ? "{$CURUSER['username']}" : '';
	$salty = md5("Th15T3xtis5add3dto66uddy6he@water...". $salty_username . "");
    $hitnruns = ($CURUSER['hit_and_run_total'] > 0) ? $CURUSER['hit_and_run_total'] : '0';
    $member_reputation = get_reputation($CURUSER);
    $usrclass = $StatusBar = "";
    if ($CURUSER['override_class'] != 255) $usrclass = "&nbsp;<b>[" . get_user_class_name($CURUSER['class']) . "]</b>&nbsp;";
    else if ($CURUSER['class'] >= UC_STAFF) $usrclass = "&nbsp;<a href='".$INSTALLER09['baseurl']."/setclass.php'><b>[" . get_user_class_name($CURUSER['class']) . "]</b></a>&nbsp;";
	$StatusBar.= '<div class="dropdown-pane padding-0" id="profile-dropdown" data-dropdown data-hover="true" data-hover-pane="true" data-close-on-click="true">
    <div class="grid-container">
      <div class="grid-x grid-margin-x">';
    $StatusBar.= "<div class='card padding-1'><dl>
		<dd class='text-center'>".(isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? get_user_class_name($CURUSER['class']) : $usrclass)."</dd>
		<dd>{$lang['gl_act_torrents']}&nbsp;:&nbsp;

		<dd>".($INSTALLER09['seedbonus_on'] ? "{$lang['gl_karma']}: <a href='".$INSTALLER09['baseurl']."/mybonus.php'>{$CURUSER['seedbonus']}</a>&nbsp;" : "")."</dd>
		<dd>{$lang['gl_invites']}: <a href='".$INSTALLER09['baseurl']."/invite.php'>{$CURUSER['invites']}</a> | Free Slots: ".$CURUSER['freeslots']."</dd>
		<dd>".($INSTALLER09['rep_sys_on'] ? "{$lang['gl_rep']}:{$member_reputation}&nbsp;" : "")."</dd>
		<dd>{$lang['gl_shareratio']}". member_ratio($CURUSER['uploaded'], $INSTALLER09['ratio_free'] ? '0' : $CURUSER['downloaded'])."</dd>";
		
		if ($INSTALLER09['ratio_free']) {
    $StatusBar .= "<dd>{$lang['gl_uploaded']}:".$upped."</dd>";
    } else {
        $StatusBar .= "<dd>{$lang['gl_uploaded']}:{$upped}</dd>
		<dd>{$lang['gl_downloaded']}:{$downed}</dd>
		<dd>{$lang['gl_connectable']}:{$connectable}</dd>";
}
	$StatusBar .="<dd>{$lang['gl_hnr']}: <a href='".$INSTALLER09['baseurl']."/hnr.php?id=".$CURUSER['id']."'>{$hitnruns}</a>&nbsp;</dd>
	<dd><a href='#' onclick='themes();'><i class='fas fa-palette blueiconcolor' title='{$lang['gl_theme']}'></i></a> | <a href='#' onclick='language_select();'><i class='fas fa-language' title='{$lang['gl_language_select']}'></i></a> | <a href='" . $INSTALLER09['baseurl'] . "/pm_system.php'><i class='far fa-envelope' title='{$lang['gl_pms']}'></i></a> | <a href='" . $INSTALLER09['baseurl'] . "/usercp.php'><i class='fas fa-user-edit' title='{$lang['gl_usercp']}'></i></a> | <a href='" . $INSTALLER09['baseurl'] . "/friends.php'><i class='fas fa-user-friends' title='{$lang['gl_friends']}'></i></a> | <a href='" . $INSTALLER09['baseurl'] . "/logout.php?hash_please={$salty}'><i class='fas fa-power-off rediconcolor' title='{$lang['gl_logout']}'></i></a></dd></dl>";
	$StatusBar .= "</div></div></div></div>";
    return $StatusBar;
}
function GlobalAlert()
{
	global $CURUSER, $INSTALLER09, $lang, $free, $_NO_COMPRESS, $query_stat, $querytime, $cache, $BLOCKS, $CURBLOCK, $mood, $blocks;
	$htmlout = '';
	if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_REPORTS && $BLOCKS['global_staff_report_on']) {
    require_once (BLOCK_DIR.'global/report.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_UPLOADAPP && $BLOCKS['global_staff_uploadapp_on']) {
    require_once (BLOCK_DIR.'global/uploadapp.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_HAPPYHOUR && $BLOCKS['global_happyhour_on']) {
    require_once (BLOCK_DIR.'global/happyhour.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_STAFF_MESSAGE && $BLOCKS['global_staff_warn_on']) {
    require_once (BLOCK_DIR.'global/staffmessages.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_NEWPM && $BLOCKS['global_message_on']) {
    require_once (BLOCK_DIR.'global/message.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_DEMOTION && $BLOCKS['global_demotion_on']) {
    require_once (BLOCK_DIR.'global/demotion.php');
    }
	
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH && $BLOCKS['global_freeleech_on']) {
    require_once (BLOCK_DIR.'global/freeleech.php');
    }
	
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_CRAZYHOUR && $BLOCKS['global_crazyhour_on']) {
    require_once (BLOCK_DIR.'global/crazyhour.php');
    }
	
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_BUG_MESSAGE && $BLOCKS['global_bug_message_on']) {
    require_once (BLOCK_DIR.'global/bugmessages.php');
    }
    if (curuser::$blocks['global_stdhead'] & block_stdhead::STDHEAD_FREELEECH_CONTRIBUTION && $BLOCKS['global_freeleech_contribution_on']) {
    require_once (BLOCK_DIR.'global/freeleech_contribution.php');
    }
	return $htmlout;	
}
function NavBar()
{
	global $CURUSER, $INSTALLER09, $lang;
	$NavBar ="";
$NavBar .="<div class='cell medium-3 large-2 sticky-container' id='menu-bar' data-sticky-container>
    <div class='sticky' data-anchor='navbar' data-sticky data-margin-top='0'>
        <div id='myMenu' class='padding-0' style='overflow: auto; height: calc(100vh);'>
        <input type='text' id='mySearch' onkeyup='myFunction()' placeholder='Search Menu' title='Type in a menu'>
			<div class='card'>
				<div class='card-divider padding-0'>{$lang['gl_general']}</div>
				<div class='card-section'><dl>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/index.php'>{$lang['gl_home']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/topten.php'>{$lang['gl_stats']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/chat.php'>{$lang['gl_chat']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/faq.php'>{$lang['gl_faq']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/rules.php'>{$lang['gl_rules']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/staff.php'>{$lang['gl_staff']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/wiki.php'>{$lang['gl_wiki']}</a></dd>
					<dd><a href='#' onclick='radio();'>{$lang['gl_radio']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/rsstfreak.php'>{$lang['gl_tfreak']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/sitepot.php'>{$lang['gl_sitepot']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/forums.php'>{$lang['gl_forums']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/tv_guide.php'>Tv Guide</a></dd>
				</div>
			</div>
			<div class='card'>
				" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "<div class='card-divider padding-0'>{$lang['gl_games']}</div><div class='card-section'>" : "")."" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "<dl><dd><a href='" . $INSTALLER09['baseurl'] . "/casino.php'>{$lang['gl_casino']}</a></dd>" : "") . "" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "<dd><a href='" . $INSTALLER09['baseurl'] . "/blackjack.php'>{$lang['gl_bjack']}</a></dd></dl>" : "") . "" . (isset($CURUSER) && $CURUSER['class'] >= UC_POWER_USER ? "</div>" : "") . "
			</div>
			<div class='card'>
				<div class='card-divider padding-0'>{$lang['gl_torrent']}</div>
				<div class='card-section'><dl>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/browse.php'>{$lang['gl_torrents']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/requests.php'>{$lang['gl_requests']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/offers.php'>{$lang['gl_offers']}</a></dd>
					<dd><a href='" . $INSTALLER09['baseurl'] . "/needseed.php?needed=seeders'>{$lang['gl_nseeds']}</a></dd>" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "
					<dd><a href='" . $INSTALLER09['baseurl'] . "/uploadapp.php'>{$lang['gl_uapp']}</a></dd> " : "
					<dd><a href='" . $INSTALLER09['baseurl'] . "/upload.php'>{$lang['gl_upload']}</a></dd>") . "" . (isset($CURUSER) && $CURUSER['class'] <= UC_VIP ? "" : "
					<dd><a href='" . $INSTALLER09['baseurl'] . "/multiupload.php'>{$lang['gl_mupload']}</a></dd>") . "
					<dd><a href='" . $INSTALLER09['baseurl'] . "/bookmarks.php'>{$lang['gl_bookmarks']}</a></dd></dl>
				</div>
			</div>
			<div class='card'>
				<div class='card-divider padding-0'>Staff Tools</div>
				<div class='card-section'><dl>
					<dd>" . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/bugs.php?action=add'>{$lang['gl_breport']}</a>" : "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/bugs.php?action=bugs'>{$lang['gl_brespond']}</a>") . "</dd>
					<dd> " . (isset($CURUSER) && $CURUSER['class'] < UC_STAFF ? "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/contactstaff.php'>{$lang['gl_cstaff']}</a>" : "<a class='brand' href='" . $INSTALLER09['baseurl'] . "/staffbox.php'>{$lang['gl_smessages']}</a>") . "</dd>
					" . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<dd><a href='" . $INSTALLER09['baseurl'] . "/staffpanel.php'>{$lang['gl_admin']}</a></dd>" : "") . "
					" . (isset($CURUSER) && $CURUSER['class'] >= UC_STAFF ? "<dd><a data-toggle='StaffPanel'>Quick Links</a></dd>" : "") . "	
				</dl></div>
			</div>
			<div class='card'>				
				<div class='card-divider padding-0'>{$lang['gl_userblocks']}</div>
				<div class='card-section'><dl>
					<dd>" . (isset($CURUSER) && $CURUSER['got_blocks'] == 'yes' ? "<a href='./user_blocks.php'>My Blocks</a>" : "") . "</dd>
					<dd>" . (isset($CURUSER) && $CURUSER['got_moods'] == 'yes' ? "<a href='./user_unlocks.php'>My Unlocks</a>" : "") . "</dd>
                </dl>
               </div>
			</div>
		</div>
	</div>
</div>";
return $NavBar;
}
function AlertBar()
{	
	global $CURUSER, $INSTALLER09, $lang, $cache;
	$htmlout = '';
	$htmlout = '<div class="callout clearfix">
		<a class="button small float-right" data-toggle="profile-dropdown">'.$CURUSER['username'] .'</a>';
		$htmlout .= GlobalAlert();
	$htmlout.= "</div>";
	return $htmlout;
}
function TopBarResponsive()
{
	$htmlout = '';
	$htmlout.= '<div class="title-bar-left hide-for-large" data-responsive-toggle="menu-bar">
		<button class="menu-icon" type="button" data-toggle="menu-bar"></button>
		<div class="title-bar-title">Menu</div>
	</div>';
	return $htmlout;
}

?>