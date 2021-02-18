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
//==new row
$HTMLOUT .= '<div class="input-group">
    <span class="input-group-label">'.$lang['userdetails_hnr'].'</span>
    <input class="input-group-field" type="text" name="hit_and_run_total" value="'.(int)$user['hit_and_run_total'].'">
</div>';