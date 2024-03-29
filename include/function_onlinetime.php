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
//==online by superman ?
function time_return($stamp)
{
    $ysecs = 365 * 24 * 60 * 60;
    $mosecs = 31 * 24 * 60 * 60;
    $wsecs = 7 * 24 * 60 * 60;
    $dsecs = 24 * 60 * 60;
    $hsecs = 60 * 60;
    $msecs = 60;
    $years = floor($stamp / $ysecs);
    $stamp %= $ysecs;
    $months = floor($stamp / $mosecs);
    $stamp %= $mosecs;
    $weeks = floor($stamp / $wsecs);
    $stamp %= $wsecs;
    $days = floor($stamp / $dsecs);
    $stamp %= $dsecs;
    $hours = floor($stamp / $hsecs);
    $stamp %= $hsecs;
    $minutes = floor($stamp / $msecs);
    $stamp %= $msecs;
    $seconds = $stamp;
    if ($years == 1) {
        $nicetime['years'] = "1 Year";
    } elseif ($years > 1) {
        $nicetime['years'] = $years . " Years";
    }
    if ($months == 1) {
        $nicetime['months'] = "1 Month";
    } elseif ($months > 1) {
        $nicetime['months'] = $months . " Months";
    }
    if ($weeks == 1) {
        $nicetime['weeks'] = "1 Week";
    } elseif ($weeks > 1) {
        $nicetime['weeks'] = $weeks . " Weeks";
    }
    if ($days == 1) {
        $nicetime['days'] = "1 Day";
    } elseif ($days > 1) {
        $nicetime['days'] = $days . " Day";
    }
    if ($hours == 1) {
        $nicetime['hours'] = "1 Hour";
    } elseif ($hours > 1) {
        $nicetime['hours'] = $hours . " Hours";
    }
    if ($minutes == 1) {
        $nicetime['minutes'] = "1 minute";
    } elseif ($minutes > 1) {
        $nicetime['minutes'] = $minutes . " Minutes";
    }
    if ($seconds == 1) {
        $nicetime['seconds'] = "1 second";
    } elseif ($seconds > 1) {
        $nicetime['seconds'] = $seconds . " Seconds";
    }
    if (is_array($nicetime)) {
        return implode(", ", $nicetime);
    }
}

?>