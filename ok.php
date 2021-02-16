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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
dbconn();
$lang = array_merge(load_language('global') , load_language('ok'));
$type = isset($_GET['type']) ? $_GET['type'] : '';
$HTMLOUT = '';
$HTMLOUT.= '<div class="grid-container">
	        <div class="grid-x grid-padding-x align-center-middle text-center margin-top-3">
			<div class="callout success margin-3">';
if ($type == "signup" && isset($_GET['email'])) {
    stderr("{$lang['ok_success']}", sprintf((EMAIL_CONFIRM ? $lang['ok_email_confirm'] : $lang['ok_email']) , htmlsafechars($_GET['email'], ENT_QUOTES)));
} elseif ($type == "invite" && isset($_GET['email'])) {
    stderr("{$lang['ok_invsuccess']}", sprintf($lang['ok_email2'], htmlsafechars($_GET['email'], ENT_QUOTES)));
} elseif ($type == "sysop") {
    $HTMLOUT = stdhead("{$lang['ok_sysop_account']}");
    $HTMLOUT.= "{$lang['ok_sysop_activated']}";
    if (isset($CURUSER)) {
        $HTMLOUT.= "{$lang['ok_account_activated']}";
    } else {
        $HTMLOUT.= "{$lang['ok_account_login']}";
    }
    $HTMLOUT.= stdfoot();
    echo $HTMLOUT;
} elseif ($type == "confirmed") {
    $HTMLOUT.= stdhead("{$lang['ok_confirmed']}");
    $HTMLOUT.= "<h1>{$lang['ok_confirmed']}</h1>";
    $HTMLOUT.= "{$lang['ok_user_confirmed']}";
    $HTMLOUT.= stdfoot();
    echo $HTMLOUT;
} elseif ($type == "confirm") {
    if (isset($CURUSER)) {
        $HTMLOUT.= stdhead("{$lang['ok_signup_confirm']}");
        $HTMLOUT.= "<h1>{$lang['ok_success_confirmed']}</h1>";
        $HTMLOUT.= "<p>" . sprintf($lang['ok_account_active_login'], "<a href='{$TRINITY20['baseurl']}/index.php'><b>{$lang['ok_account_active_login_link']}</b></a>") . "</p>";
        $HTMLOUT.= sprintf($lang['ok_read_rules'], $TRINITY20['site_name']);
        $HTMLOUT.= stdfoot();
        echo $HTMLOUT;
    } else {
        $HTMLOUT.= stdhead("{$lang['ok_signup_confirm']}");
        $HTMLOUT.= "<h1>{$lang['ok_success_confirmed']}</h1>";
        $HTMLOUT.= "{$lang['ok_account_cookies']}";
        $HTMLOUT.= stdfoot();
        echo $HTMLOUT;
    }
} else {
    stderr("{$lang['ok_user_error']}", "{$lang['ok_no_action']}");
}
$HTMLOUT.= '</div></div></div>';
?>
