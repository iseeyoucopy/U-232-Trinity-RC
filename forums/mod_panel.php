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
if (!defined('IN_TRINITY20_FORUM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="'.charset().'">
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
$lang = array_merge($lang, load_language('ad_modpanel'));

if ($CURUSER['class'] >= UC_STAFF || isMod($forumid, "forum")) {
	$HTMLOUT .= '<form method="post" action="forums.php">
		<input type="hidden" name="action" value="updatetopic">
		<input type="hidden" name="topicid" value="'.$topicid.'">
		<div class="card">
			<div class="card-divider">'.$lang['mod_mp_opt'].'</div>
			<div class="card-section"> 
				<div class="input-group">
					<span class="input-group-label">'.$lang['mod_mp_stk'].'</span>
					<select class="input-group-field" name="sticky">
						<option value="yes" '.($sticky ? ' selected="selected"' : '').'>'.$lang['mod_mp_yes'].'</option>
						<option value="no" '.($sticky ? '' : ' selected="selected"').'>'.$lang['mod_mp_no'].'</option>
					</select>
				</div>
				<div class="input-group">
					<span class="input-group-label">'.$lang['mod_mp_lck'].'</span>
					<select class="input-group-field" name="locked">
						<option value="yes" '.($locked ? ' selected="selected"' : '').'>'.$lang['mod_mp_yes'].'</option>
						<option value="no" '.($locked ? '' : ' selected="selected"').'>'.$lang['mod_mp_no'].'</option>
					</select>
				</div>
				<div class="input-group">
					<span class="input-group-label">'.$lang['mod_mp_name'].'</span>
					<input class="input-group-field" type="text" name="topic_name" size="60" maxlength="'.$Multi_forum['configs']['maxsubjectlength'].' value="'.htmlsafechars($subject).'">
				</div>
				<div class="input-group">
					<span class="input-group-label">'.$lang['mod_mp_move'].'</span>
					<select class="input-group-field" name="new_forumid">';
					($res = sql_query("SELECT id, name, min_class_write FROM forums ORDER BY name")) || sqlerr(__FILE__, __LINE__);
					while ($arr = $res->fetch_assoc()) {
						if ($CURUSER['class'] >= $arr["min_class_write"]) {
							$HTMLOUT .= '<option value="'.(int)$arr["id"].'"'.($arr["id"] == $forumid ? ' selected="selected"' : '').'>'.htmlsafechars($arr["name"]).'</option>';
						}
					}
					$HTMLOUT .= '</select>
				</div>
				<div class="input-group">
					<span class="input-group-label">'.$lang['mod_mp_del'].'</span>
					<select class="input-group-field" name="delete">
						<option value="no" selected="selected">'.$lang['mod_mp_no'].'</option>
						<option value="yes">'.$lang['mod_mp_yes'].'</option>
					</select>
				</div>
				<br>
				<div class="callout alert">
	 				<p>'.$lang['mod_mp_note'].'</p>
				</div>
	 			<input type="submit" class="button float-center" value="'.$lang['mod_mp_uptop'].'">
			</div>
		</div>
	</form>';
}
