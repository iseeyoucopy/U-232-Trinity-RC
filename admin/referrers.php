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
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br />You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'pager_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('referrers'));
$HTMLOUT = '';
if ((isset($_GET['page']) ? (int)$_GET['page'] : ($_GET['page'] = 0)) !== 0) {
}
($res = sql_query("SELECT * FROM referrers")) || sqlerr(__FILE__, __LINE__);
$count = $res->num_rows;
if ($count > 0) {
    $HTMLOUT .= "<h1>{$lang['ref_last']}</h1>
	<table class='table table-bordered'>
		<tr>
			<td class='colhead'>{$lang['ref_nr']}</td>
			<td class='colhead'>{$lang['ref_date']}</td>
			<td class='colhead'>{$lang['ref_browser']}</td>
			<td class='colhead'>{$lang['ref_ip']}</td>
			<td class='colhead'>{$lang['ref_user']}</td>
			<td class='colhead'>{$lang['ref_url']}</td>
			<!--<td class='colhead'>{$lang['ref_result']}</td>-->
		</tr>";
    $perpage = 10;
    $i = (int)$_GET['page'] * $perpage;
    $pager = pager($perpage, $count, "staffpanel.php?tool=referrers&amp;");
    ($res = sql_query("SELECT r.*, u.id as uid, u.username FROM referrers AS r LEFT JOIN users AS u ON u.ip = r.ip ORDER BY date DESC {$pager['limit']}")) || sqlerr(__FILE__,
        __LINE__);
    if ($res->num_rows > 0) {
        while ($data = $res->fetch_assoc()) {
            $i++;
            $http_agent = htmlsafechars($data['browser']);
            if (strpos($http_agent, "Opera") !== false) {
                $browser = "<i class='fab fa-opera' alt='Opera' title='Opera'></i>&nbsp;&nbsp;Opera";
            } elseif (strpos($http_agent, "Konqueror") !== false) {
                $browser = "<i class='fab fa-opera' alt='Opera' title='Opera'></i>&nbsp;&nbsp;Opera";
            } elseif (strpos($http_agent, "MSIE") !== false) {
                $browser = "<i class='fab fa-internet-explorer'  alt='IE' title='IE'></i>&nbsp;&nbsp;IE";
            } elseif (strpos($http_agent, "Chrome") !== false) {
                $browser = "<i class='fab fa-chrome' alt='Chrome' title='Chrome'></i>&nbsp;&nbsp;Chrome";
            } elseif ((strpos($http_agent, "Nav") !== false) || (strpos($http_agent, "Gold") !== false) || (strpos($http_agent,
                        "X11") !== false) || (strpos($http_agent, "Mozilla") !== false) || (strpos($http_agent, "Netscape") !== false)) {
                $browser = "<i class='fab fa-firefox' alt='FireFox' title='FireFox'></i>&nbsp;&nbsp;Mozilla";
            } elseif (strpos($http_agent, "Safari") !== false) {
                $browser = "<i class='fab fa-safari' alt='Safari' title='Safari'></i>&nbsp;&nbsp;Chrome";
            } else {
                $browser = $lang['ref_unknow'];
            }
            $HTMLOUT .= "
		<tr>
			<td>".$i."</td>
			<td>".get_date($data['date'], '')."</td>
			<td>".$browser."</td>
			<td>".htmlsafechars($data['ip'])."</td>
			<td>".htmlsafechars($data['ip'])." ".((int)$data['uid'] ? "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$data['uid']."'>" : "")."".(htmlsafechars($data['username']) ? "&nbsp;[".htmlsafechars($data['username'])."]</a>" : "{$lang['ref_guest']}")."
</td><td><a href='".htmlsafechars($data['referer'])."'>".CutName($data['referer'],
                    '50')."</a></td><!--<td><a href='".htmlsafechars($data['page'])."'>{$lang['ref_view']}</a></td>--></tr>";
            $browser = '';
        }
    }
    $HTMLOUT .= "</table>";
    $HTMLOUT .= $pager['pagerbottom']."<br>";
} else {
    $HTMLOUT .= $lang['ref_nothing'];
}
////////////////////////// HTML OUTPUT //////////////////////////
echo stdhead($lang['ref_stdhead']).$HTMLOUT.stdfoot();
?>
