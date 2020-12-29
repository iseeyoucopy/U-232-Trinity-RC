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
// happy hour
if(XBT_TRACKER == false OR $TRINITY20['happy_hour'] == true) {
if ($CURUSER) {
    require_once (INCL_DIR . 'function_happyhour.php');
    if (happyHour("check")) {
        $htmlout.= "
        <li>
        <a class='sa-tooltip' href='browse.php?cat=" . happyCheck("check") . "'><b class='btn btn-success btn-sm'>{$lang['gl_happyhour']}</b>
		<span class='custom info alert alert-success'>
        {$lang['gl_happyhour1']}<br /> " . ((happyCheck("check") == 255) ? "{$lang['gl_happyhour2']}" : "{$lang['gl_happyhour3']}") . "<br /><font color='red'><b> " . happyHour("time") . " </b></font> {$lang['gl_happyhour4']}</span></a></li>";
    }
}
}
//==
// End Class
// End File
