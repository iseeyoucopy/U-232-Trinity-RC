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
if (XBT_TRACKER) {
    $htmlout .= "
    <a class='button' data-toggle='xbt-tracker-1'>XBT TRACKER</a>
    <div class='reveal' id='xbt-tracker-1' data-reveal>
        <div class='mobile-ios-modal-inner'>
    <span class='label success'>  
    When XBT TRACKER is running there is no alerts for crazyhour, happyhour, or active freeslots &#128560
    </span>
  </div>
  </div>";
    $htmlout .= "";
} else {
    /** karma contribution alert hack **/
    $fpoints = $dpoints = $hpoints = $freeleech_enabled = $double_upload_enabled = $half_down_enabled = '';
    if (($scheduled_events = $cache->get($cache_keys['freecontribution_datas_alerts'])) === false) {
        $scheduled_events = mysql_fetch_all("SELECT * from `events` ORDER BY `startTime` DESC LIMIT 3;", []);
        $cache->set($cache_keys['freecontribution_datas_alerts'], $scheduled_events, 3 * 86400);
    }

    if (is_array($scheduled_events)) {
        foreach ($scheduled_events as $scheduled_event) {
            if (is_array($scheduled_event) && array_key_exists('startTime', $scheduled_event) &&
                array_key_exists('endTime', $scheduled_event)) {
                $startTime = 0;
                $endTime = 0;
                $startTime = $scheduled_event['startTime'];
                $endTime = $scheduled_event['endTime'];
                if (TIME_NOW < $endTime && TIME_NOW > $startTime) {
                    if (array_key_exists('freeleechEnabled', $scheduled_event)) {
                        $freeleechEnabled = $scheduled_event['freeleechEnabled'];
                        if ($scheduled_event['freeleechEnabled'] !== []) {
                            $freeleech_start_time = $scheduled_event['startTime'];
                            $freeleech_end_time = $scheduled_event['endTime'];
                            $freeleech_enabled = true;
                        }
                    }
                    if (array_key_exists('duploadEnabled', $scheduled_event)) {
                        $duploadEnabled = $scheduled_event['duploadEnabled'];
                        if ($scheduled_event['duploadEnabled'] !== []) {
                            $double_upload_start_time = $scheduled_event['startTime'];
                            $double_upload_end_time = $scheduled_event['endTime'];
                            $double_upload_enabled = true;
                        }
                    }
                    if (array_key_exists('hdownEnabled', $scheduled_event)) {
                        $hdownEnabled = $scheduled_event['hdownEnabled'];
                        if ($scheduled_event['hdownEnabled'] !== []) {
                            $half_down_start_time = $scheduled_event['startTime'];
                            $half_down_end_time = $scheduled_event['endTime'];
                            $half_down_enabled = true;
                        }
                    }
                }
            }
        }
    }
//=== get total points
//$target_fl = 30000;
    if (($freeleech_counter = $cache->get($cache_keys['freeleech_counter_alerts'])) === false) {
        $total_fl = sql_query('SELECT SUM(pointspool) AS pointspool, points FROM bonus WHERE id =11');
        $fl_total_row = $total_fl->fetch_assoc();
        $percent_fl = number_format($fl_total_row['pointspool'] / $fl_total_row['points'] * 100, 2);
        $cache->set($cache_keys['freeleech_counter_alerts'], $percent_fl, 0);
    } else {
        $percent_fl = $freeleech_counter;
    }
    switch ($percent_fl) {
        case $percent_fl >= 90:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl >= 80:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl >= 70:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl >= 50:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl >= 40:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl >= 30:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl >= 20:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
        case $percent_fl < 20:
            $font_color_fl = '<strong>'.number_format($percent_fl).' %</strong>';
            break;
    }
//=== get total points
//$target_du = 30000;
    if (($doubleupload_counter = $cache->get($cache_keys['doubleupload_counter_alerts'])) === false) {
        $total_du = sql_query('SELECT SUM(pointspool) AS pointspool, points FROM bonus WHERE id =12');
        $du_total_row = $total_du->fetch_assoc();
        $percent_du = number_format($du_total_row['pointspool'] / $du_total_row['points'] * 100, 2);
        $cache->set($cache_keys['doubleupload_counter_alerts'], $percent_du, 0);
    } else {
        $percent_du = $doubleupload_counter;
    }
    switch ($percent_du) {
        case $percent_du >= 90:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du >= 80:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du >= 70:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du >= 50:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du >= 40:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du >= 30:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du >= 20:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
        case $percent_du < 20:
            $font_color_du = '<strong>'.number_format($percent_du).' %</strong>';
            break;
    }
//=== get total points
//$target_hd = 30000;
    if (($halfdownload_counter = $cache->get($cache_keys['halfdownload_counter_alerts'])) === false) {
        ($total_hd = sql_query('SELECT SUM(pointspool) AS pointspool, points FROM bonus WHERE id =13')) || sqlerr(__FILE__, __LINE__);
        $hd_total_row = $total_hd->fetch_assoc();
        $percent_hd = number_format($hd_total_row['pointspool'] / $hd_total_row['points'] * 100, 2);
        $cache->set($cache_keys['halfdownload_counter_alerts'], $percent_hd, 0);
    } else {
        $percent_hd = $halfdownload_counter;
    }
    switch ($percent_hd) {
        case $percent_hd >= 90:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd >= 80:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd >= 70:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd >= 50:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd >= 40:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd >= 30:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd >= 20:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
        case $percent_hd < 20:
            $font_color_hd = '<strong>'.number_format($percent_hd).' %</strong>';
            break;
    }

    $fstatus = $freeleech_enabled ? "<strong> ON </strong>" : $font_color_fl."";
    $dstatus = $double_upload_enabled ? "<strong> ON </strong>" : $font_color_du."";
    $hstatus = $half_down_enabled ? "<strong> ON </strong>" : $font_color_hd."";

    $htmlout .= "<a class='button' data-toggle='karma-dropdown-1'>Karma Contribution's</a>
    <div class='reveal' id='karma-dropdown-1' data-reveal>
        <div class='mobile-ios-modal-inner'><p>Freeleech ";
    if ($freeleech_enabled) {
        $htmlout .= "<strong> ON </strong> ".get_date($freeleech_start_time, 'DATE')." - ".get_date($freeleech_end_time, 'DATE');
    } else {
        $htmlout .= $fstatus;
    }
    $htmlout .= " </p>";

    $htmlout .= "<p>DoubleUpload ";
    if ($double_upload_enabled) {
        $htmlout .= "<strong> ON </strong> ".get_date($double_upload_start_time, 'DATE')." - ".get_date($double_upload_end_time,
                'DATE');
    } else {
        $htmlout .= $dstatus;
    }
    $htmlout .= " </p>";

    $htmlout .= "<p>Half Download ";
    if ($half_down_enabled) {
        $htmlout .= "<strong> ON</strong> ".get_date($half_down_start_time, 'DATE')." - ".get_date($half_down_end_time, 'DATE');
    } else {
        $htmlout .= $hstatus;
    }
    $htmlout .= " </p></div></div>";
}
//=== karma contribution alert end
// End Class
// End File
