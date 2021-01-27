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
$HTMLOUT.= "<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_bonus_points']}</span>
    <input class='input-group-field' type='text' name='seedbonus' value='" . (int)$user_stats['seedbonus'] . "'>
</div>";