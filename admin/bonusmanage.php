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
require_once(INCL_DIR.'html_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('bonusmanager'));
$HTMLOUT = $count = '';
($res = sql_query("SELECT * FROM bonus")) || sqlerr(__FILE__, __LINE__);
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST["id"]) || isset($_POST["points"]) || isset($_POST["pointspool"]) || isset($_POST["minpoints"]) || isset($_POST["description"]) || isset($_POST["enabled"]))) {
    $id = 0 + $_POST["id"];
    $points = 0 + $_POST["bonuspoints"];
    $pointspool = 0 + $_POST["pointspool"];
    $minpoints = 0 + $_POST["minpoints"];
    $descr = htmlsafechars($_POST["description"]);
    $enabled = "yes";
    if (isset($_POST["enabled"]) == '') {
        $enabled = "no";
    }
    $sql = sql_query("UPDATE bonus SET points = ".sqlesc($points).", pointspool=".sqlesc($pointspool).", minpoints=".sqlesc($minpoints).", enabled = ".sqlesc($enabled).", description = ".sqlesc($descr)." WHERE id = ".sqlesc($id));
    if ($sql) {
        header("Location: {$TRINITY20['baseurl']}/staffpanel.php?tool=bonusmanage");
    } else {
        stderr($lang['bonusmanager_oops'], "{$lang['bonusmanager_sql']}");
    }
}
$HTMLOUT .= "<div class='card'>
	<div class='card-divider'>{$lang['bonusmanager_bm']}</div>
	<div class='card-section'>";
while ($arr = $res->fetch_assoc()) {
		$HTMLOUT .= "<div class='card'>
   			<div class='card-divider'><strong>".htmlsafechars($arr["bonusname"])."</strong></div>
			<form name='bonusmanage' method='post' action='staffpanel.php?tool=bonusmanage&amp;action=bonusmanage'>
				<div class='card-section'>
					<input name='id' type='hidden' value='".(int)$arr["id"]."'>
					<div class='input-group'>
						<span class='input-group-label'>{$lang['bonusmanager_type']}</span>
					   	<input class='input-group-field' type='text' disabled value='".htmlsafechars($arr["art"])."'>
				 	</div>
					<div class='input-group'>
						<span class='input-group-label'>{$lang['bonusmanager_quantity']}</span>
						<input class='input-group-field' type='text' disabled value='".(($arr["art"] == "traffic" || $arr["art"] == "traffic2" || $arr["art"] == "gift_1" || $arr["art"] == "gift_2") ? (htmlsafechars($arr["menge"]) / 1024 / 1024 / 1024)." GB" : htmlsafechars($arr["menge"]))."'>
					</div>
					<div class='input-group'>
						<span class='input-group-label'>{$lang['bonusmanager_points']}</span>
				   		<input class='input-group-field' type='text' name='bonuspoints' value='".(int)$arr["points"]."'>
				 	</div>
					<div class='input-group'>
						<span class='input-group-label'>{$lang['bonusmanager_pointspool']}</span>
						<input class='input-group-field' type='text' name='pointspool' value='".(int)$arr["pointspool"]."'>
					</div>
					<div class='input-group'>
						<span class='input-group-label'>{$lang['bonusmanager_minpoints']}</span>
						<input class='input-group-field' type='text' name='minpoints' value='".(int)$arr["minpoints"]."'>
					</div>
					<div class='input-group'>
						<span class='input-group-label'>{$lang['bonusmanager_description']}</span>
							<textarea class='input-group-field' name='description' rows='4'>".htmlsafechars($arr["description"])."</textarea>
					</div>
					<div class='clearfix'></div>
					<p>{$lang['bonusmanager_enabled']}</p>
					<div class='switch large'>
						<input class='input-group-field switch-input' type='checkbox' id='enabled'  name='enabled'".($arr["enabled"] == "yes" ? " checked='checked'" : "")." value='yes'>
						<label class='switch-paddle float-center' for='enabled'>
							<span class='show-for-sr'>{$lang['bonusmanager_enabled']}</span>
							<span class='switch-active' aria-hidden='true'>Yes</span>
							<span class='switch-inactive' aria-hidden='true'>No</span>
						</label>
					</div>
					<input class='button' type='submit' value='{$lang['bonusmanager_submit']}'>
				</div>
			</form>
		</div>";
}
$HTMLOUT .="</div></div>";
echo stdhead($lang['bonusmanager_stdhead']).$HTMLOUT.stdfoot();
?>
