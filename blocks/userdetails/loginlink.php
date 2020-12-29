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
//==Qlogin by stonebreath and laffin
if ($CURUSER['class'] >= UC_STAFF && $id == $CURUSER['id']) {
    $hash1 = $cache->get('hash1_' . $id);
    if ($hash1 === false) {
        $res = sql_query("SELECT hash1 FROM users WHERE id = " . sqlesc($CURUSER['id']) . " AND class >= " . UC_STAFF) or sqlerr(__FILE__, __LINE__);
        $hash1 = mysqli_fetch_assoc($res);
        $cache->set('hash1_' . $id, $hash1, $TRINITY20['expires']['user_hash']);
    }
    $arr = $hash1;
    if ($arr['hash1'] != '') {
        $HTMLOUT.= "<tr><td class='rowhead'>{$lang['userdetails_login_link']}<br /><a href='createlink.php?action=reset&amp;id=" . (int)$CURUSER['id'] . "' target='_blank'>{$lang['userdetails_login_reset']}</a></td><td align='left'>{$TRINITY20['baseurl']}/pagelogin.php?qlogin=" . htmlsafechars($arr['hash1']) . "</td></tr>";
    } else {
        $HTMLOUT.= "<tr><td class='rowhead'>{$lang['userdetails_login_link']}</td><td align='left'><a href='createlink.php?id=" . (int)$CURUSER['id'] . "' target='_blank'>{$lang['userdetails_login_create']}</a></td></tr>";
    }
}
//==End
// End Class
// End File
