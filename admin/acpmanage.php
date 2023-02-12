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
require_once(INCL_DIR.'pager_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_acp'));
$stdfoot = [
    /** include js **/
    'js' => [
        'acp',
    ],
];
$HTMLOUT = "";
if (isset($_POST['ids'])) {
    $ids = $_POST["ids"];
    foreach ($ids as $id) {
        if (!is_valid_id($id)) {
            stderr($lang['std_error'], $lang['text_invalid']);
        }
    }
    $do = isset($_POST["do"]) ? htmlsafechars(trim($_POST["do"])) : '';
    if ($do == 'enabled') {
        sql_query("UPDATE users SET enabled = 'yes' WHERE ID IN(".implode(', ', array_map('sqlesc', $ids)).") AND enabled = 'no'") || sqlerr(__FILE__,
            __LINE__);
    }
    $cache->update_row($cache_keys['my_userid'].$id, [
        'enabled' => 'yes',
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($cache_keys['user'].$id, [
        'enabled' => 'yes',
    ], $TRINITY20['expires']['user_cache']);
    //else
    if ($do == 'confirm') {
        sql_query("UPDATE users SET status = 'confirmed' WHERE ID IN(".implode(', ',
                array_map('sqlesc', $ids)).") AND status = 'pending'") || sqlerr(__FILE__, __LINE__);
    }
    $cache->update_row($cache_keys['my_userid'].$id, [
        'status' => 'confirmed',
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($cache_keys['user'].$id, [
        'status' => 'confirmed',
    ], $TRINITY20['expires']['user_cache']);
    //else
    if ($do == 'delete') {
        sql_query("DELETE FROM users WHERE ID IN(".implode(', ', array_map('sqlesc', $ids)).") AND class < 3") || sqlerr(__FILE__, __LINE__);
    } else {
        header('Location: staffpanel.php?tool=acpmanage&amp;action=acpmanage');
        exit;
    }
}
$disabled = number_format(get_row_count("users", "WHERE enabled='no'"));
$pending = number_format(get_row_count("users", "WHERE status='pending'"));
$count = number_format(get_row_count("users", "WHERE enabled='no' OR status='pending' ORDER BY username DESC"));
$perpage = 25;
$pager = pager($perpage, $count, "staffpanel.php?tool=acpmanage&amp;action=acpmanage&amp;");
$res = sql_query("SELECT id, username, added, downloaded, uploaded, last_access, class, donor, warned, enabled, status FROM users WHERE enabled='no' OR status='pending' ORDER BY username DESC {$pager['limit']}");
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagertop'];
}
if ($res->num_rows == 0) {
    $HTMLOUT .= stdmsg($lang['std_sorry'], $lang['std_nf']);
} else {
$HTMLOUT .= "";
$HTMLOUT .= "<div class='card'>
    <div class='card-divider'>
        {$lang['text_du']} $disabled
        {$lang['text_pu']} $pending
    </div>
    <div class='card-section'>
        <div class='table-scroll'>
            <form action='staffpanel.php?tool=acpmanage&amp;action=acpmanage' method='post'>
                <table>
                    <thead>
                        <tr>
                            <td>
                                <input type='checkbox' title='".$lang['text_markall']."' value='".$lang['text_markall']."' onclick='this.value=check(form);'></td>
                            <td>{$lang['text_username']}</td>
                            <td>{$lang['text_reg']}</td>
                            <td>{$lang['text_la']}</td>
                            <td>{$lang['text_class']}</td>
                            <td>{$lang['text_dload']}</td>
                            <td>{$lang['text_upload']}</td>
                            <td>{$lang['text_ratio']}</td>
                            <td>{$lang['text_status']}</td>
                            <td>{$lang['text_enabled']}</td>
                        </tr>
                    </thead>";
                    while ($arr = $res->fetch_assoc()) {
                        $uploaded = mksize($arr["uploaded"]);
                        $downloaded = mksize($arr["downloaded"]);
                        $ratio = $arr['downloaded'] > 0 ? $arr['uploaded'] / $arr['downloaded'] : 0;
                        $ratio = number_format($ratio, 2);
                        $color = get_ratio_color($ratio);
                        if ($color) {
                            $ratio = "<font color='$color'>$ratio</font>";
                        }
                        $added = get_date($arr['added'], 'LONG', 0, 1);
                        $last_access = get_date($arr['last_access'], 'LONG', 0, 1);
                        $class = get_user_class_name($arr["class"]);
                        $status = htmlsafechars($arr['status']);
                        $enabled = htmlsafechars($arr['enabled']);
                    $HTMLOUT .= "<tbody>
                    <tr>
                        <td>
                            <input type='checkbox' name='ids[]' value='".(int)$arr['id']."'>
                        </td>
                        <td>
                            <a href='/userdetails.php?id=".(int)$arr['id']."'>
                                <b>".htmlsafechars($arr['username'])."</b>
                            </a>".($arr["donor"] == "yes" ? "<span data-tooltip class='top' tabindex='2' title='".$lang['text_donor']."'><i class='fas fa-star' style='color:yellow'></i></span>" : "").($arr["warned"] >= 1 ? "<span data-tooltip class='top' tabindex='2' title='".$lang['text_warned']."'><i class='fas fa-exclamation-triangle'></i></span>" : "")."
                        </td>
                        <td>{$added}</td>
                        <td>{$last_access}</td>
                        <td>{$class}</td>
                        <td>{$downloaded}</td>
                        <td>{$uploaded}</td>
                        <td>{$ratio}</td>
                        <td>{$status}</td>
                        <td>{$enabled}</td>
                    </tr>
                    </tbody>";
                    }
                    $HTMLOUT .= "<div class='input-group'>
                        <span class='input-group-label primary'>{$lang['text_wtd']}</span>
                        <select class='input-group-field' name='do'>
                            <option value='enabled'>{$lang['text_es']}</option>
                            <option value='confirm'>{$lang['text_cs']}</option>
                            <option value='delete'>{$lang['text_ds']}</option>
                        </select>
                        <div class='input-group-button primary'>
                        <input type='submit' value='".$lang['text_submit']."'>
                        </div>
                    </div>
                </table>
            </form>
        </div>
    </div>
</div>";
}
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagerbottom'];
}
echo stdhead($lang['text_stdhead']).$HTMLOUT.stdfoot($stdfoot);
?>
