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
if (!defined('IN_TRINITY20_ADMIN')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_testip'));
$HTMLOUT = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ip = $_POST["ip"] ?? false;
} else {
    $ip = $_GET["ip"] ?? false;
}
if ($ip) {
    $nip = ip2long($ip);
    if ($nip == -1) {
        stderr($lang['testip_error'], $lang['testip_error1']);
    }
    ($res = sql_query("SELECT * FROM bans WHERE $nip >= first AND $nip <= last")) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows == 0) {
        stderr($lang['testip_result'], sprintf($lang['testip_notice'], htmlentities($ip, ENT_QUOTES)));
    } else {
        $HTMLOUT .= "<table class='main' border='0' cellspacing='0' cellpadding='5'>
        <tr>
          <td class='colhead'>{$lang['testip_first']}</td>
          <td class='colhead'>{$lang['testip_last']}</td>
          <td class='colhead'>{$lang['testip_comment']}</td>
        </tr>\n";
        while ($arr = $res->fetch_assoc()) {
            $first = long2ip($arr["first"]);
            $last = long2ip($arr["last"]);
            $comment = htmlsafechars($arr["comment"]);
            $HTMLOUT .= "<tr><td>$first</td><td>$last</td><td>$comment</td></tr>\n";
        }
        $HTMLOUT .= "</table>\n";
        stderr($lang['testip_result'],
            "<table border='0' cellspacing='0' cellpadding='0'><tr><td class='embedded' style='padding-right: 5px'><img src='{$TRINITY20['pic_base_url']}smilies/excl.gif' alt=''></td><td class='embedded'>".sprintf($lang['testip_notice2'],
                $ip)."</td></tr></table><p>$HTMLOUT</p>");
    }
}
$HTMLOUT .= "
    <h1>{$lang['testip_title']}</h1>
    <form method='post' action='staffpanel.php?tool=testip&amp;action=testip'>
    <table border='1' cellspacing='0' cellpadding='5'>
    <tr><td class='rowhead'>{$lang['testip_address']}</td><td><input type='text' class='form-control' name='ip'></td></tr>
    <tr><td colspan='2' align='center'><input type='submit' class='btn btn-default' value='{$lang['testip_ok']}'></td></tr>
    </table>
    </form>";
echo stdhead($lang['testip_windows_title']).$HTMLOUT.stdfoot();
?>
