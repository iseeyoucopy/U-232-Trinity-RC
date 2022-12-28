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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('setclass'));
$HTMLOUT = "";
if ($CURUSER['class'] < UC_STAFF || $CURUSER['override_class'] != 255) {
    stderr("Error", "wots the story ?");
}
if (isset($_GET["action"]) && htmlsafechars($_GET["action"]) == "editclass") { //Process the querystring - No security checks are done as a temporary class higher
    //then the actual class mean absoluetly nothing.
    $newclass = (int)$_GET['class'];
    $returnto = htmlsafechars($_GET['returnto']);
    sql_query("UPDATE users SET override_class = ".sqlesc($newclass)." WHERE id = ".sqlesc($CURUSER['id'])); // Set temporary class
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'override_class' => $newclass,
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($keys['user'].$CURUSER['id'], [
        'override_class' => $newclass,
    ], $TRINITY20['expires']['user_cache']);
    header("Location: {$TRINITY20['baseurl']}/".$returnto);
    die();
}
// HTML Code to allow changes to current class
$HTMLOUT .= "<div class='row'><div class='col-md-12'>
<h2 class'text-center'><b>{$lang['set_class_allow']}</b></h2>
<br>
<form method='get' action='{$TRINITY20['baseurl']}/setclass.php'>
	<input type='hidden' name='action' value='editclass' />
	<input type='hidden' name='returnto' value='userdetails.php?id=".(int)$CURUSER['id']."' />
	<table class='table table-bordered'>
	<tr>
	<td class='text-right'>Class</td>
	<td class='text-center'>
	<select name='class'>";
$maxclass = $CURUSER['class'] - 1;
for ($i = 0; $i <= $maxclass; ++$i) {
    if (trim(get_user_class_name($i)) != "") {
        $HTMLOUT .= "<option value='$i"."'>".get_user_class_name($i)."</option>\n";
    }
}
$HTMLOUT .= "</select></td></tr>
		<tr><td class='text-center'colspan='3'><input type='submit' class='btn' value='{$lang['set_class_ok']}' /></td></tr>
	</table>
</form>
<br>";
$HTMLOUT .= "</div></div><br>";
echo stdhead("{$lang['set_class_temp']}").$HTMLOUT.stdfoot();
