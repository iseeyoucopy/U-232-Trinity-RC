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
//== we do not want mods to be able to change user classes or amount donated...
// === Donor mod time based by snuggles
if ($user["donor"] == "no") {
    $HTMLOUT.= "<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_dfor']}</span>
    <select class='input-group-field' name='donorlength'>
        <option value='0'>------</option>
        <option value='4'>1 {$lang['userdetails_month']}</option>" . "
        <option value='6'>6 {$lang['userdetails_weeks']}</option>
        <option value='8'>2 {$lang['userdetails_months']}</option>
        <option value='10'>10 {$lang['userdetails_weeks']}</option>" . "
        <option value='12'>3 {$lang['userdetails_months']}</option>
        <option value='255'>{$lang['userdetails_unlimited']}</option>
    </select></div>";
}
$HTMLOUT.= "<div class='input-group'>
<span class='input-group-label'>{$lang['userdetails_cdonation']}</span>
    <input class='input-group-field' placeholder='{$lang['userdetails_cdonation']}' type='text' name='donated' value=" . htmlsafechars($user["donated"]) . ">
    <span class='input-group-label'>{$lang['userdetails_tdonations']}" . htmlsafechars($user["total_donated"]) ."</span>
    </div>";
if ($user["donor"] == "yes") {
    $HTMLOUT.= "<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_adonor']}</span>
        <select class='input-group-field' name='donorlengthadd'>
            <option value='0'>------</option>
            <option value='4'>1 {$lang['userdetails_month']}</option>" . "
            <option value='6'>6 {$lang['userdetails_weeks']}</option>
            <option value='8'>2 {$lang['userdetails_months']}</option>
            <option value='10'>10 {$lang['userdetails_weeks']}</option>" . "
            <option value='12'>3 {$lang['userdetails_months']}</option>
            <option value='255'>{$lang['userdetails_unlimited']}</option>
        </select>
    </div>
    <div class='input-group'>
        <span class='input-group-label'>{$lang['userdetails_rdonor']}</span>
        <input class='input-group-label' name='donor' value='no' type='checkbox'>
    </div>";
}
