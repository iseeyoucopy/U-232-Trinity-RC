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
$HTMLOUT .= "<div class='cell medium-12'>
    <fieldset class='fieldset'>
        <legend>{$lang['userdetails_invright']}</legend>
        <input type='radio' name='invite_on' id='invite_pos_yes' value='yes'".($user["invite_on"] == "yes" ? " checked='checked'" : "").">
        <label for='invite_pos_yes'>{$lang['userdetails_yes']}</label>
        <input type='radio' name='invite_on' id='invite_pos_no' value='no'".($user["invite_on"] == "no" ? " checked='checked'" : "").">
        <label for='invite_pos_no'>{$lang['userdetails_no']}</label>";
$HTMLOUT .= "<div class='input-group'>
        <span class='input-group-label'>{$lang['userdetails_invites']}</span>
        <input class='input-group-field' type='text'name='invites' value='".htmlsafechars($user['invites'])."'>
    </div>
    </fieldset>
</div>";
