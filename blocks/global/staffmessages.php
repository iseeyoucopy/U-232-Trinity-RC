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
    if (($answeredby = $cache->get($cache_keys['staff_mess'])) === false) {
        ($res1 = sql_query("SELECT count(id) FROM staffmessages WHERE answeredby = 0")) || sqlerr(__FILE__, __LINE__);
        [$answeredby] = $res1->fetch_row();
        $cache->set($cache_keys['staff_mess'], $answeredby, $TRINITY20['expires']['alerts']);
    }
    if ($answeredby > 0) {
        $htmlout .= "
        <a class='hollow small button notification' href='staffbox.php'>{$lang['gl_staff_message']}<span class='badge_corner'>".$answeredby[0]."</span></a>";
    }
}
//==End
// End Class
// End File
