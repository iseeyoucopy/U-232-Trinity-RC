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
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_namechanger'));
$HTMLOUT = '';
$mode = (isset($_GET['mode']) && htmlsafechars($_GET['mode']));
if (isset($mode) && $mode == 'change') {
    $uid = (int)$_POST["uid"];
    $uname = htmlsafechars($_POST["uname"]);
    if ($_POST["uname"] == "" || $_POST["uid"] == "") {
        stderr($lang['namechanger_err'], $lang['namechanger_missing']);
    }
    ($nc_sql = sql_query("SELECT class FROM users WHERE id = ".sqlesc($uid))) || sqlerr(__FILE__, __LINE__);
    if ($nc_sql->num_rows) {
        $classuser = $nc_sql->fetch_assoc();
        if ($classuser['class'] >= UC_STAFF) {
            stderr($lang['namechanger_err'], $lang['namechanger_cannot']);
        }
        ($change = sql_query("UPDATE users SET username=".sqlesc($uname)." WHERE id=".sqlesc($uid))) || sqlerr(__FILE__, __LINE__);
        $cache->update_row($keys['my_userid'].$uid, [
            'username' => $uname,
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row('user'.$uid, [
            'username' => $uname,
        ], $TRINITY20['expires']['user_cache']);
        $added = TIME_NOW;
        $changed = sqlesc("{$lang['namechanger_changed_to']} $uname");
        $subject = sqlesc($lang['namechanger_changed']);
        if (!$change && $mysqli->errno) {
            stderr($lang['namechanger_borked'], $lang['namechanger_already_exist']);
        }
        sql_query("INSERT INTO messages (sender, receiver, msg, subject, added) VALUES(0, $uid, $changed, $subject, $added)") || sqlerr(__FILE__,
            __LINE__);
        header("Refresh: 2; url=staffpanel.php?tool=namechanger");
        stderr($lang['namechanger_success'], $lang['namechanger_u_changed'].htmlsafechars($uname).$lang['namechanger_please']);
    }
}
$HTMLOUT .= "<div class='row'><div class='col-md-12'>
    <h1>{$lang['namechanger_change_u']}</h1>
    <form method='post' action='staffpanel.php?tool=namechanger&amp;mode=change'>
    <table class=table table-bordered'>
    <tr><td class='rowhead'>{$lang['namechanger_id']}</td><td><input type='text' name='uid' size='10' /></td></tr>
    <tr><td class='rowhead'>{$lang['namechanger_new_user']}</td><td><input type='text' name='uname' size='20' /></td></tr>
    <tr><td colspan='2' align='center'>{$lang['namechanger_if']}<input type='submit' value='{$lang['namechanger_change_name']}' class='btn' /></td></tr>
    </table>
    </form></div></div>";
echo stdhead($lang['namechanger_stdhead']).$HTMLOUT.stdfoot();
?>
