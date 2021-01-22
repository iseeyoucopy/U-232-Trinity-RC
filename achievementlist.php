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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
dbconn();
$newpage = new page_verify();
$newpage->check('takecounts');
$lang = array_merge(load_language('global'), load_language('achievementlist'));
//$doUpdate = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && $CURUSER['class'] >= UC_MAX) {
    $clienticon = htmlsafechars(trim($_POST["clienticon"]));
    $achievname = htmlsafechars(trim($_POST["achievname"]));
    $notes = htmlsafechars($_POST["notes"]);
    $clienticon = htmlsafechars($clienticon);
    $achievname = htmlsafechars($achievname);
    sql_query("INSERT INTO achievementist (achievname, notes, clienticon) VALUES(" . sqlesc($achievname) . ", " . sqlesc($notes) . ", " . sqlesc($clienticon) . ")") or sqlerr(__FILE__, __LINE__);
    $message = "{$lang['achlst_new_ach_been_added']}. {$lang['achlst_achievement']}: [{$achievname}]";
    //autoshout($message);
    //$doUpdate = true;
    
}
// == Query update by Putyn
$res = sql_query("SELECT a1.*, (SELECT COUNT(a2.id) FROM achievements AS a2 WHERE a2.achievement = a1.achievname) as count FROM achievementist AS a1 ORDER BY a1.id ") or sqlerr(__FILE__, __LINE__);
$HTMLOUT = '';
if ($res->num_rows == 0) {
    require_once (BLOCK_DIR . 'achievements/ach_list_noachiev.php');
} else {
    require_once (BLOCK_DIR . 'achievements/ach_list.php');
}
if ($CURUSER['class'] == UC_MAX) {
    require_once (BLOCK_DIR . 'achievements/ach_list_add.php');
}
echo stdhead($lang['achlst_std_head']) . $HTMLOUT . stdfoot();
?>
