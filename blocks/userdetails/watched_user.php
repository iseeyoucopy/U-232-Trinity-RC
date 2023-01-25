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
$HTMLOUT .= '<div class="reveal" id="watched-user" data-reveal>
		<div class="callout alert-callout-border warning">
			<p>'.($user['watched_user'] > 0 ? $lang['userdetails_watched_since'].get_date($user['watched_user'],
            '') : $lang['userdetails_not_watched']).'</p>
			<p style="color:red;font-size: small;">Note* '.$lang['userdetails_watch_change1'].'</p>
			<p style="color:red;font-size: small;">'.$lang['userdetails_watch_change2'].'</p>
		</div>
		<form method="post" action="member_input.php" name="notes_for_staff">
			<fieldset class="fieldset">
				<input name="id" type="hidden" value="'.$id.'">
				<input type="hidden" value="watched_user" name="action">
				<legend>'.$lang['userdetails_add_watch'].'</legend>
				<input type="radio" value="yes" name="add_to_watched_users"'.($user['watched_user'] > 0 ? ' checked="checked"' : '').' id="watchedYes1"> 
				<label for="watchedYes1">'.$lang['userdetails_yes1'].'</label>
				<input type="radio" value="no" name="add_to_watched_users"'.($user['watched_user'] == 0 ? ' checked="checked"' : '').' id="watchedNo1">
				<label for="watchedYes1">'.$lang['userdetails_no1'].'</label>
			</fieldset>
			<textarea id="watched_reason" cols="50" rows="6" name="watched_reason">'.htmlspecialchars($user['watched_user_reason'] ?? '').'</textarea>
			<input id="watched_user_button" type="submit" value="'.$lang['userdetails_submit'].'" class="tiny button" name="watched_user_button">
		</form>
		<button class="close-button" data-close aria-label="Close modal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>';