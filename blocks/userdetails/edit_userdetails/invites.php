<?php
$HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_invright']}<input type='radio' name='invite_on' value='yes'".($user["invite_on"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}<input type='radio' name='invite_on' value='no'".($user["invite_on"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</div>";
$HTMLOUT.= "<div class='col-sm-1'><b>{$lang['userdetails_invites']}</b><input class='form-control' type='text'name='invites' value='" . htmlsafechars($user['invites']) . "'></div><!--</div>-->";
?>