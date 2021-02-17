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
//Adjust up/down
$HTMLOUT .= "<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_addupload']}</span>
    <input class='input-group-field' type='text' name='amountup'>
    <select class='input-group-field'  name='formatup'>
        <option value='mb'>{$lang['userdetails_MB']}</option>
        <option value='gb'>{$lang['userdetails_GB']}</option></select>
    <input class='input-group-field' type='hidden' id='upchange' name='upchange' value='plus'>
</div>   
<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_adddownload']}</span>
    <input class='input-group-field' type='text' name='amountdown'>
    <select class='input-group-field' name='formatdown'>
        <option value='mb'>{$lang['userdetails_MB']}</option>
        <option value='gb'>{$lang['userdetails_GB']}</option>
    </select>
    <input class='input-group-field' type='hidden' id='downchange' name='downchange' value='plus'>
</div>";