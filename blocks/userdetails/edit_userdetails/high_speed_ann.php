<?php
$HTMLOUT.= "<br><br><div class='col-sm-2'>{$lang['userdetails_highspeed']}<br><input type='radio' name='highspeed' value='yes' ".($user["highspeed"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']} <input type='radio' name='highspeed' value='no' ".($user["highspeed"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</div>";
?>