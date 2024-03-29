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
function get_pretime($st)
{
    $secs = $st;
    $mins = floor($st / 60);
    $hours = floor($mins / 60);
    $days = floor($hours / 24);
    $week = floor($days / 7);
    $month = floor($week / 4);
    $week_elapsed = floor(($st - ($month * 4 * 7 * 24 * 60 * 60)) / (7 * 24 * 60 * 60));
    $days_elapsed = floor(($st - ($week * 7 * 24 * 60 * 60)) / (24 * 60 * 60));
    $hours_elapsed = floor(($st - ($days * 24 * 60 * 60)) / (60 * 60));
    $mins_elapsed = floor(($st - ($hours * 60 * 60)) / 60);
    $secs_elapsed = floor($st - $mins * 60);
    $pretime = "";
    if ($secs_elapsed > 0) {
        $pretime = "$secs_elapsed Secs. " . $pretime;
    }
    if ($mins_elapsed > 0) {
        $pretime = "$mins_elapsed Mins, " . $pretime;
    }
    if ($hours_elapsed > 0) {
        $pretime = "$hours_elapsed Hrs., " . $pretime;
    }
    if ($days_elapsed > 0) {
        $pretime = "$days_elapsed Days, " . $pretime;
    }
    if ($week_elapsed > 0) {
        $pretime = "$week_elapsed Weeks, " . $pretime;
    }
    if ($month > 0) {
        $pretime = "$month Months, " . $pretime;
    }
    return "$pretime";
}

?>
