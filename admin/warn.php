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
require_once(INCL_DIR.'html_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_warn'));
global $TRINITY20, $CURUSER;
$HTMLOUT = '';
function mkint($x)
{
    return (int)$x;
}

$stdfoot = [
    /** include js **/
    'js' => [
        'wz_tooltip',
    ],
];
$this_url = $_SERVER["SCRIPT_NAME"];
$do = isset($_GET["do"]) && $_GET["do"] == "disabled" ? "disabled" : "warned";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $r = $_POST["ref"] ?? $this_url;
    $_uids = isset($_POST["users"]) ? array_map('mkint', $_POST["users"]) : 0;
    if ($_uids == 0 || (is_countable($_uids) ? count($_uids) : 0) == 0) {
        stderr($lang['warn_stderr'], $lang['warn_stderr_msg']);
    }
    $valid = [
        "unwarn",
        "disable",
        "delete",
    ];
    $act = isset($_POST["action"]) && in_array($_POST["action"], $valid) ? $_POST["action"] : false;
    if (!$act) {
        stderr("Err", $lang['warn_stderr_msg1']);
    }
    if ($act == "delete") {
        if (sql_query("DELETE FROM users WHERE id IN (".implode(",", $_uids).")")) {
            $c = $mysqli->affected_rows;
            header("Refresh: 2; url=".$r);
            stderr($lang['warn_stdmsg_success'], $c.$lang['warn_stdmsg_user'].($c > 1 ? "s" : "").$lang['warn_stdmsg_deleted']);
        } else {
            stderr($lang['warn_stderr'], $lang['warn_stderr_msg2']);
        }
    }
    if ($act == "disable") {
        if (sql_query("UPDATE users set enabled='no', modcomment=CONCAT(".sqlesc(get_date(TIME_NOW, 'DATE',
                    1).$lang['warn_disabled_by'].$CURUSER['username']."\n").",modcomment) WHERE id IN (".implode(",", $_uids).")")) {
            foreach ($_uids as $uid) {
                $cache->update_row($cache_keys['my_userid'].$_uid, [
                    'enabled' => 'no',
                ], $TRINITY20['expires']['curuser']);
                $cache->update_row($cache_keys['user'].$_uid, [
                    'enabled' => 'no',
                ], $TRINITY20['expires']['user_cache']);
            }
            $d = $mysqli->affected_rows;
            header("Refresh: 2; url=".$r);
            stderr($lang['warn_stdmsg_success'], $d.$lang['warn_stdmsg_user'].($d > 1 ? "s" : "").$lang['warn_stdmsg_disabled']);
        } else {
            stderr($lang['warn_stderr'], $lang['warn_stderr_msg3']);
        }
    } elseif ($act == "unwarn") {
        $sub = $lang['warn_removed'];
        $body = $lang['warn_removed_msg'].$CURUSER["username"].$lang['warn_removed_msg1'];
        foreach ($_uids as $uid) {
            $cache->update_row($cache_keys['my_userid'].$_uid, [
                'warned' => 0,
            ], $TRINITY20['expires']['curuser']);
            $cache->update_row($cache_keys['user'].$_uid, [
                'warned' => 0,
            ], $TRINITY20['expires']['user_cache']);
        }
        $pms = [];
        foreach ($_uids as $id) {
            $pms[] = "(0,".$id.",".sqlesc($sub).",".sqlesc($body).",".sqlesc(TIME_NOW).")";
        }
        if (count($pms) > 0) {
            ($g = sql_query("INSERT INTO messages(sender,receiver,subject,msg,added) VALUE ".implode(",", $pms))) || ($q_err = $mysqli->error);
            ($q1 = sql_query("UPDATE users set warned='0', modcomment=CONCAT(".sqlesc(get_date(TIME_NOW, 'DATE',
                        1).$lang['warn_removed_msg'].$CURUSER['username']."\n").",modcomment) WHERE id IN (".implode(",",
                    $_uids).")")) || ($q2_err = $mysqli->error);
            if ($g && $q1) {
                header("Refresh: 2; url=".$r);
                stderr($lang['warn_stdmsg_success'],
                    count($pms).$lang['warn_stdmsg_user'].(count($pms) > 1 ? "s" : "").$lang['warn_stdmsg_unwarned']);
            } else {
                stderr($lang['warn_stderr'], $lang['warn_stderr_msgq1'].$q_err.$lang['warn_stderr_msgq2'].$q2_err);
            }
        }
    }
    exit;
}
switch ($do) {
    case "disabled":
        $query = "SELECT id,username, class, downloaded, uploaded, IF(downloaded>0, round((uploaded/downloaded),2), '---') as ratio, disable_reason, added, last_access FROM users WHERE enabled='no' ORDER BY last_access DESC ";
        $title = $lang['warn_disable_title'];
        $link = "<a href=\"staffpanel.php?tool=warn&amp;action=warn&amp;?do=warned\">{$lang['warn_warned_users']}</a>";
        break;

    case "warned":
        $query = "SELECT id, username, class, downloaded, uploaded, IF(downloaded>0, round((uploaded/downloaded),2), '---') as ratio, warn_reason, warned, added, last_access FROM users WHERE warned>='1' ORDER BY last_access DESC, warned DESC ";
        $title = $lang['warn_warned_title'];
        $link = "<a href=\"staffpanel.php?tool=warn&amp;action=warn&amp;do=disabled\">{$lang['warn_disabled_users']}</a>";
        break;
}
($g = sql_query($query)) || (print($mysqli->error));
$count = $g->num_rows;
$HTMLOUT .= "<div class='row'><div class='col-md-12'><h2>$title&nbsp;<font class=\"small\">[{$lang['warn_total']}".$count.$lang['warn_total_user'].($count > 1 ? $lang['warn_total_user_plural'] : "")."]</font>&nbsp;&nbsp;$link</h2> ";
if ($count == 0) {
    $HTMLOUT .= stdmsg($lang['warn_hey'], $lang['warn_hey_msg'].strtolower($title));
} else {
    $HTMLOUT .= "<div class='row'><div class='col-md-12'>
		<form action='staffpanel.php?tool=warn&amp;action=warn' method='post'>
                <table width='600' cellpadding='3' cellspacing='2' style='border-collapse:separate;' align='center'>
                <tr>           
                        <td class='colhead' align='left' width='100%' >{$lang["warn_user"]}</td>
                        <td class='colhead' align='center' nowrap='nowrap'>{$lang["warn_ratio"]}</td>
                        <td class='colhead' align='center' nowrap='nowrap'>{$lang["warn_class"]}</td>
                        <td class='colhead' align='center' nowrap='nowrap'>{$lang["warn_ltacces"]}</td>
                        <td class='colhead' align='center' nowrap='nowrap'>{$lang["warn_joined"]}</td>
                        <td class='colhead' align='center' nowrap='nowrap'><input type='checkbox' name='checkall'></td>
                </tr>";
    while ($a = $g->fetch_assoc()) {
        $tip = ($do == "warned" ? $lang['warn_for'].$a["warn_reason"]."<br>".$lang['warn_till'].get_date($a["warned"], 'DATE',
                1)." - ".mkprettytime($a['warned'] - TIME_NOW) : $lang['warn_disabled_for'].$a["disable_reason"]);
        $HTMLOUT .= "<tr>
                                  <td align='left' width='100%'><a href='userdetails.php?id=".(int)$a["id"]."' onmouseover=\"Tip('($tip)')\" onmouseout=\"UnTip()\">".htmlsafechars($a["username"])."</a></td>
                                  <td align='left' nowrap='nowrap'>".(float)$a["ratio"]."<br><font class='small'><b>{$lang["warn_down"]}</b>".mksize($a["downloaded"])."&nbsp;<b>{$lang["warn_upl"]}</b> ".mksize($a["uploaded"])."</font></td>
                                  <td align='center' nowrap='nowrap'>".get_user_class_name($a["class"])."</td>
                                  <td align='center' nowrap='nowrap'>".get_date($a["last_access"], 'LONG', 0, 1)."</td>
                                  <td align='center' nowrap='nowrap'>".get_date($a["added"], 'DATE', 1)."</td>
                                  <td align='center' nowrap='nowrap'><input type='checkbox' name='users[]' value='".(int)$a["id"]."'></td>
                                </tr>";
    }
    $HTMLOUT .= "<tr>
                        <td colspan='6' class='colhead' align='center'>
                                <select name='action'>
                                        <option value='unwarn'>{$lang["warn_unwarn"]}</option>
                                        <option value='disable'>{$lang["warn_disable"]}</option>
                                        <option value='delete'>{$lang["warn_delete"]}</option>
                                </select>
                                &raquo;
                                <input type='submit' value='Apply'>
                                <input type='hidden' value='".htmlsafechars($_SERVER["REQUEST_URI"])."' name='ref'>
                        </td>
                        </tr>
                        </table>
                        </form></div></div><br>";
}
$HTMLOUT .= "</div></div><br>";
echo stdhead($title).$HTMLOUT.stdfoot($stdfoot);
?>
