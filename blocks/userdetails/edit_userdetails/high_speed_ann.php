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
//==High speed php announce
$HTMLOUT .= "<div class='cell medium-12'>
<fieldset class='fieldset'>
<legend>{$lang['userdetails_highspeed']}</legend>
    <input type='radio' id='high_speed_yes' name='highspeed' value='yes' ".($user["highspeed"] == "yes" ? " checked='checked'" : "").">
    <label for='high_speed_yes'>{$lang['userdetails_yes']}</label>
    <input type='radio' id='high_speed_no' name='highspeed' value='no' ".($user["highspeed"] == "no" ? " checked='checked'" : "").">
    <label for='high_speed_no'>{$lang['userdetails_no']}</label>
</div>";