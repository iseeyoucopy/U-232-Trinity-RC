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
//==XBT - Can Leech
$HTMLOUT.= "
<div class='cell medium-12'>
<fieldset class='fieldset'>
    <legend>{$lang['userdetails_canleech']}</legend>
    <input type='radio' name='can_leech' id='canleech_yes' value='1' " . ($user["can_leech"] == 1 ? " checked='checked'" : "") . ">
    <label for='canleech_yes'>{$lang['userdetails_yes']}</label> 
    <input type='radio' name='can_leech' id='canleech_no' value='0' " . ($user["can_leech"] == 0 ? " checked='checked'" : "") . ">
    <label for='canleech_no'>{$lang['userdetails_no']}</label>
</fieldset>
</div>";