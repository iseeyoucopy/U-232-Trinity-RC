<?php
        $HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_canleech']}</div><div class='col-sm-3'><input type='radio' name='can_leech' value='1' " . ($user["can_leech"] == 1 ? " checked='checked'" : "") . ">{$lang['userdetails_yes']} <input type='radio' name='can_leech' value='0' " . ($user["can_leech"] == 0 ? " checked='checked'" : "") . ">{$lang['userdetails_no']}</div></div>";
?>