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
//users parked an reset passkey
$HTMLOUT.= "
<div class='cell medium-6'>
    <fieldset class='fieldset'>
        <legend>{$lang['userdetails_park']}</legend>
        <input name='parked' value='yes' type='radio'".($user["parked"] == "yes" ? " checked='checked'" : "").">
        {$lang['userdetails_yes']} 
        <input name='parked' value='no' type='radio'".($user["parked"] == "no" ? " checked='checked'" : "").">
        {$lang['userdetails_no']}
    </fieldset>
</div>
<div class='cell medium-6'>
    <fieldset class='fieldset'>
        <legend>{$lang['userdetails_reset']}</legend>
        <input type='checkbox' name='reset_torrent_pass' value='1' aria-describedby='edit_passkey'>
        <p class='help-text' id='edit_passkey'>{$lang['userdetails_pass_msg']}</p>
    </fieldset>
</div>";
