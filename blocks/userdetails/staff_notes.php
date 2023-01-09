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
$HTMLOUT .= '<div class="reveal" id="staff-notes" data-reveal>
			<form method="post" action="member_input.php" name="notes_for_staff">
				<input name="id" type="hidden" value="'.(int)$user['id'].'">
				<input type="hidden" value="staff_notes" name="action" id="action">
				<textarea id="new_staff_note" cols="50" rows="6" name="new_staff_note">'.htmlspecialchars($user['staff_notes']).'</textarea>
				<input id="staff_notes_button" type="submit" value="'.$lang['userdetails_submit'].'" class="small button" name="staff_notes_button"/>
			</form>
			<button class="close-button" data-close aria-label="Close modal" type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>';