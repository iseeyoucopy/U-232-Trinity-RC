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
 
// Achievements mod by MelvinMeow
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once(INCL_DIR . 'user_functions.php');
require_once(INCL_DIR . 'bbcode_functions.php');
require_once(CLASS_DIR . 'page_verify.php');
dbconn(false);
loggedinorreturn();
$newpage = new page_verify();
$newpage->check('takecounts');
if ($TRINITY20['achieve_sys_on'] == false) {
    stderr($lang['achbon_err'], $lang['achbon_off']);
    exit();
}
$lang = array_merge(load_language('global'), load_language('achievementbonus'));
$id = (int) $CURUSER['id'];
$min = 1;
$max = 32;
$rand = (int) mt_rand((int) $min, (int) $max);
($res = sql_query("SELECT achpoints FROM usersachiev WHERE id =" . sqlesc($id) . " AND achpoints >= '1'")) || sqlerr(__FILE__, __LINE__);
$row = $res->fetch_row();
$count = $row['0'];
if (!$count) {
    header("Refresh: 3; url=achievementhistory.php?id=$id");
    stderr($lang['achbon_no_ach_bon_pnts'], $lang['achbon_no_ach_bon_pnts_msg']);
    exit();
}
$HTMLOUT = '';
($get_bonus = sql_query("SELECT * FROM ach_bonus WHERE bonus_id =" . sqlesc($rand))) || sqlerr(__FILE__, __LINE__);
$bonus = $get_bonus->fetch_assoc();
$bonus_desc = htmlsafechars($bonus['bonus_desc']);
$bonus_type = htmlsafechars($bonus['bonus_type']);
$bonus_do = htmlsafechars($bonus['bonus_do']);
($get_d = sql_query("SELECT * FROM users WHERE id =" . sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$dn = $get_d->fetch_assoc();
$down = (float) $dn['downloaded'];
$up = (float) $dn['uploaded'];
$invite = (int) $dn['invites'];
$karma = (float) $dn['seedbonus'];
if ($bonus_type == 1) {
    if ($down >= $bonus_do) {
        $msg = "{$lang['achbon_congratulations']}, {$lang['achbon_you_hv_just_won']} $bonus_desc";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
        $sql = "UPDATE users SET downloaded = downloaded - " . sqlesc($bonus_do) . " WHERE id = " . sqlesc($id);
        sql_query($sql) || sqlerr(__FILE__, __LINE__);
        $cache->update_row($keys['user_stats'] . $id, [
            'downloaded' => $down - $bonus_do
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row('user_stats_' . $id, [
            'downloaded' => $down - $bonus_do
        ], $TRINITY20['expires']['user_stats']);
    }
    if ($down < $bonus_do) {
        $msg = "{$lang['achbon_congratulations']}, {$lang['achbon_your_dl_been_reset_0']}";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
        $sql = "UPDATE users SET downloaded = '0' WHERE id =" . sqlesc($id);
        sql_query($sql) || sqlerr(__FILE__, __LINE__);
        $cache->update_row($keys['user_stats'] . $id, [
            'downloaded' => 0
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row('user_stats_' . $id, [
            'downloaded' => 0
        ], $TRINITY20['expires']['user_stats']);
    }
}
if ($bonus_type == 2) {
    $msg = "{$lang['achbon_congratulations']}, {$lang['achbon_you_hv_just_won']} $bonus_desc";
    sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id = " . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete('user_achievement_points_' . $id);
    $sql = "UPDATE users SET uploaded = uploaded + " . sqlesc($bonus_do) . " WHERE id =" . sqlesc($id);
    sql_query($sql) || sqlerr(__FILE__, __LINE__);
    $cache->update_row($keys['user_stats'] . $id, [
        'uploaded' => $up + $bonus_do
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row('user_stats_' . $id, [
        'uploaded' => $up + $bonus_do
    ], $TRINITY20['expires']['user_stats']);
}
if ($bonus_type == 3) {
    $msg = "{$lang['achbon_congratulations']}, {$lang['achbon_you_hv_just_won']} $bonus_desc";
    sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id = " . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete('user_achievement_points_' . $id);
    $sql = "UPDATE users SET invites = invites + " . sqlesc($bonus_do) . " WHERE id =" . sqlesc($id);
    sql_query($sql) || sqlerr(__FILE__, __LINE__);
    $cache->update_row('user' . $id, [
        'invites' => $invite + $bonus_do
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($keys['my_userid'] . $id, [
        'invites' => $invite + $bonus_do
    ], $TRINITY20['expires']['curuser']);
}
if ($bonus_type == 4) {
    $msg = "{$lang['achbon_congratulations']}, {$lang['achbon_you_hv_just_won']} $bonus_desc";
    sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    $cache->delete('user_achievement_points_' . $id);
    $sql = "UPDATE users SET seedbonus = seedbonus + " . sqlesc($bonus_do) . " WHERE id =" . sqlesc($id);
    sql_query($sql) || sqlerr(__FILE__, __LINE__);
    $cache->update_row($keys['user_stats'] . $id, [
        'seedbonus' => $karma + $bonus_do
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row('user_stats_' . $id, [
        'seedbonus' => $karma + $bonus_do
    ], $TRINITY20['expires']['user_stats']);
}
if ($bonus_type == 5) {
    $rand_fail = rand(1, 5);
    if ($rand_fail == 1) {
        $msg = "{$lang['gl_sorry']}, {$lang['achbon_failed_msg1']}";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
    }
    if ($rand_fail == 2) {
        $msg = "{$lang['gl_sorry']}, {$lang['achbon_failed_msg2']}";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
    }
    if ($rand_fail == 3) {
        $msg = "{$lang['gl_sorry']}, {$lang['achbon_failed_msg3']}";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
    }
    if ($rand_fail == 4) {
        $msg = "{$lang['gl_sorry']}, {$lang['achbon_failed_msg4']}";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
    }
    if ($rand_fail == 5) {
        $msg = "{$lang['gl_sorry']}, {$lang['achbon_failed_msg5']}";
        sql_query("UPDATE usersachiev SET achpoints = achpoints-1, spentpoints = spentpoints+1 WHERE id =" . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        $cache->delete('user_achievement_points_' . $id);
    }
}
header("Refresh: 5; url=usercp.php?action=awards");
stderr($lang['achbon_random_achievement_bonus'], "$msg");
echo stdhead($lang['achbon_std_head']) . $HTMLOUT . stdfoot();
?>
