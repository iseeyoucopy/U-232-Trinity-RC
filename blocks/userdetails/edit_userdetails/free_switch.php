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
if ($user['free_switch'] != 0) {
    $HTMLOUT .= "<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_freeleech_status']}</span>
    <input class='input-group-field' name='free_switch' id='freeswitch' value='42' type='radio'>{$lang['userdetails_remove_freeleech']}
    </div>";
    if ($user['free_switch'] == 1) {
        $HTMLOUT .= '<p class="help-text" id="freeswitch">'.$lang['userdetails_freeleech_status'].' ('.$lang['userdetails_unlimited_d'].')</p>';
    } else {
        $HTMLOUT .= "<p class='help-text' id='freeswitch'>{$lang['userdetails_freeleech_status']} {$lang['userdetails_until']} ".get_date($user['free_switch'],
                'DATE')." (".mkprettytime($user['free_switch'] - TIME_NOW)." {$lang['userdetails_togo']})</p>";
    }
}
if ($user['free_switch'] == 0) {
    $HTMLOUT .= '<div class="input-group">
        <span class="input-group-label">'.$lang['userdetails_freeleech_for'].'</span>
        <select class="input-group-field" name="free_switch">
            <option value="0">------</option>
            <option value="1">1 '.$lang['userdetails_week'].'</option>
            <option value="2">2 '.$lang['userdetails_weeks'].'</option>
            <option value="4">4 '.$lang['userdetails_weeks'].'</option>
            <option value="8">8 '.$lang['userdetails_weeks'].'</option>
            <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select>
        <span class="input-group-label">'.$lang['userdetails_pm_comment'].'</span>
        <input class="input-group-field" type="text" name="free_pm">
    </div>';
}
