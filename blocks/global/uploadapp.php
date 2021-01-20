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
if ($TRINITY20['uploadapp_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($newapp = $cache->get('new_uploadapp_')) === false) {
        $res_newapps = sql_query("SELECT count(id) FROM uploadapp WHERE status = 'pending'");
        list($newapp) = mysqli_fetch_row($res_newapps);
        $cache->set('new_uploadapp_', $newapp, $TRINITY20['expires']['alerts']);
    }
    if ($newapp > 0) {
        $htmlout.= "
        <a class='hollow small button notification' href='staffpanel.php?tool=uploadapps&amp;action=app'>{$lang['gl_uploadapp_new']}<span class='badge_corner'>" . $newapp[0] . "</span></a>";
    }
}
//==End
// End Class
// End File
