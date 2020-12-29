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
//==Memcached message query
if ($TRINITY20['msg_alert'] && $CURUSER) {
    if (($unread = $cache->get('inbox_new_' . $CURUSER['id'])) === false) {
        $res = sql_query('SELECT count(id) FROM messages WHERE receiver=' . sqlesc($CURUSER['id']) . ' && unread="yes" AND location = "1"') or sqlerr(__FILE__, __LINE__);
        $arr = mysqli_fetch_row($res);
        $unread = (int)$arr[0];
        $cache->set('inbox_new_' . $CURUSER['id'], $unread, $TRINITY20['expires']['unread']);
    }
}
if (($CURUSER['pm_forced'] == 'yes') AND (!defined("INBOX_SCRIPT")) AND ($unread)) {
   header("Location: {$TRINITY20['baseurl']}/pm_system.php");
   die;
}
//==End
if ($TRINITY20['msg_alert'] && isset($unread) && !empty($unread)) {
    $htmlout.= "
	<a href='pm_system.php' class='button small alert'>
  " . ($unread > 1 ? $lang['gl_newprivs'] . $lang['gl_newmesss'] : $lang['gl_newpriv'] . $lang['gl_newmess']) . " <span class='badge'>". $unread . "</span>
</a>";
}
//==
// End Class
// End File
