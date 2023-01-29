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
if (XBT_TRACKER == true) {
    $htmlout .= "
	<a class='button small' data-toggle='collapse' href='#colapseXBT' role='button' aria-expanded='false' aria-controls='colapseXBT'>
      XBT TRACKER
  </a>
  <div class='collapse' id='colapseXBT'>
	<div class='alert alert-success'>When XBT TRACKER is running -<br /> There is no alerts for crazyhour, <br /> happyhour, or active freeslots &#128560</div>
  </div>";
    $htmlout .= "";
} else {
    /** karma contribution alert hack **/
    $fpoints = $dpoints = $hpoints = $freeleech_enabled = $double_upload_enabled = $half_down_enabled = '';
    if (($scheduled_events = $cache->get($keys['freecontribution_datas_alerts'])) === false) {
        $scheduled_events = mysql_fetch_all("SELECT * from `events` ORDER BY `startTime` DESC LIMIT 3;", []);
        $cache->set($keys['freecontribution_datas_alerts'], $scheduled_events, 3 * 86400);
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
    if (($freeleech_counter = $cache->get($keys['freeleech_counter_alerts'])) === false) {
        $total_fl = sql_query('SELECT SUM(pointspool) AS pointspool, points FROM bonus WHERE id =11');
        $fl_total_row = $total_fl->fetch_assoc();
        $percent_fl = number_format($fl_total_row['pointspool'] / $fl_total_row['points'] * 100, 2);
        $cache->set($keys['freeleech_counter_alerts'], $percent_fl, 0);
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
    if (($doubleupload_counter = $cache->get($keys['doubleupload_counter_alerts'])) === false) {
        $total_du = sql_query('SELECT SUM(pointspool) AS pointspool, points FROM bonus WHERE id =12');
        $du_total_row = $total_du->fetch_assoc();
        $percent_du = number_format($du_total_row['pointspool'] / $du_total_row['points'] * 100, 2);
        $cache->set($keys['doubleupload_counter_alerts'], $percent_du, 0);
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
    if (($halfdownload_counter = $cache->get($keys['halfdownload_counter_alerts'])) === false) {
        ($total_hd = sql_query('SELECT SUM(pointspool) AS pointspool, points FROM bonus WHERE id =13')) || sqlerr(__FILE__, __LINE__);
        $hd_total_row = $total_hd->fetch_assoc();
        $percent_hd = number_format($hd_total_row['pointspool'] / $hd_total_row['points'] * 100, 2);
        $cache->set($keys['halfdownload_counter_alerts'], $percent_hd, 0);
    } else {
        $percent_hd = $halfdownload_counter;
    }
    switch ($percent_hd) {
        case $percent_hd >= 90:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd >= 80:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd >= 70:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd >= 50:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd >= 40:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd >= 30:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd >= 20:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
        case $percent_hd < 20:
            $font_color_hd = '<strong>'.number_format($percent_hd).'&nbsp;%</strong>';
            break;
    }

    $fstatus = $freeleech_enabled ? "<strong>&nbsp;ON&nbsp;</strong>" : $font_color_fl."";
    $dstatus = $double_upload_enabled ? "<strong>&nbsp;ON&nbsp;</strong>" : $font_color_du."";
    $hstatus = $half_down_enabled ? "<strong>&nbsp;ON&nbsp;</strong>" : $font_color_hd."";
    $htmlout .= "<a class='button small success' data-toggle='karma-dropdown-1'>Karma Contribution's</a>
	<div class='dropdown-pane' id='karma-dropdown-1' data-dropdown data-hover='true' data-hover-pane='true'>
		<div class='card card-body'><div class='alert alert-success'>Freeleech&nbsp;[&nbsp;";
    if ($freeleech_enabled) {
        $htmlout .= "<strong>&nbsp;ON</strong>&nbsp;".get_date($freeleech_start_time, 'DATE')."&nbsp;-&nbsp;".get_date($freeleech_end_time, 'DATE');
    } else {
        $htmlout .= $fstatus;
    }
    $htmlout .= "&nbsp;]<br />";

    $htmlout .= "DoubleUpload&nbsp;[&nbsp;";
    if ($double_upload_enabled) {
        $htmlout .= "<strong>&nbsp;ON</strong>&nbsp;".get_date($double_upload_start_time, 'DATE')."&nbsp;-&nbsp;".get_date($double_upload_end_time,
                'DATE');
    } else {
        $htmlout .= $dstatus;
    }
    $htmlout .= "&nbsp;]<br />";

    $htmlout .= "Half Download&nbsp;[&nbsp;";
    if ($half_down_enabled) {
        $htmlout .= "<strong>&nbsp;ON</strong>&nbsp;".get_date($half_down_start_time, 'DATE')."&nbsp;-&nbsp;".get_date($half_down_end_time, 'DATE');
    } else {
        $htmlout .= $hstatus;
    }
    $htmlout .= "&nbsp;]</div></div></div>";
}
//=== karma contribution alert end
// End Class
// End File
