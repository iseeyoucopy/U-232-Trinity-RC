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
if ($TRINITY20['bug_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($bugs = $cache->get('bug_mess_')) === false) {
        ($res1 = sql_query("SELECT COUNT(id) FROM bugs WHERE status = 'na'")) || sqlerr(__FILE__, __LINE__);
        [$bugs] = $res1->fetch_row();
        $cache->set('bug_mess_', $bugs, $TRINITY20['expires']['alerts']);
    }
    if ($bugs > 0) {
        $htmlout.= "
        <a class='hollow small button notification' href='bugs.php?action=bugs'>{$lang['gl_bug_alert']}<span class='badge_corner'>" . $bugs[0] . "</span></a>";
    }
}
//==End
// End Class
// End File

