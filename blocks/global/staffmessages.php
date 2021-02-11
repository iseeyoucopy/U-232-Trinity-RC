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
if ($TRINITY20['staffmsg_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($answeredby = $cache->get('staff_mess_')) === false) {
        $res1 = sql_query("SELECT count(id) FROM staffmessages WHERE answeredby = 0") or sqlerr(__FILE__, __LINE__);
        list($answeredby) = $res->fetch_row(1);
        $cache->set('staff_mess_', $answeredby, $TRINITY20['expires']['alerts']);
    }
    if ($answeredby > 0) {
        $htmlout.= "
        <a class='hollow small button notification' href='staffbox.php'>{$lang['gl_staff_message']}<span class='badge_corner'>" . $answeredby[0] . "</span></a>";
    }
}
//==End
// End Class
// End File
