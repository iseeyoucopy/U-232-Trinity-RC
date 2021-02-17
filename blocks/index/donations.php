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
$progress = '';
if (($totalfunds_cache = $cache->get($keys['ttl_funds'])) === false) {
    ($totalfunds_cache_query = sql_query("SELECT sum(cash) as total_funds FROM funds")) || sqlerr(__FILE__, __LINE__);
    $totalfunds_cache = $totalfunds_cache_query->fetch_assoc();
    $totalfunds_cache["total_funds"] = (int)$totalfunds_cache["total_funds"];
    $cache->set($keys['ttl_funds'], $totalfunds_cache, $TRINITY20['expires']['total_funds']);
}
$funds_so_far = (int)$totalfunds_cache["total_funds"];
$funds_difference = $TRINITY20['totalneeded'] - $funds_so_far;
$Progress_so_far = number_format($funds_so_far / $TRINITY20['totalneeded'] * 100, 1);
if ($Progress_so_far >= 100) {
    $Progress_so_far = 100;
}
$HTMLOUT .= "<div class='card'>
  <div class='card-divider'>{$lang['index_donations']}</div>
    <div class='card-section'>
      <div class='progress' role='progressbar' tabindex='0' aria-valuenow='$Progress_so_far%' aria-valuemin='0' aria-valuemax='100'>
        <span class='progress-meter' style='width: $Progress_so_far%'>
          <span class='progress-meter-text'>$Progress_so_far%</span>
        </span>
      </div>
    </div>
</div>";
//==End
// End Class
// End File
