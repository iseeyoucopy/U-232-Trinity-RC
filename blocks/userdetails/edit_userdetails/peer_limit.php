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
// == Wait time, peers limit and torrents limit
// == Wait time
$HTMLOUT.= "<div class='input-group'>
    <span class='input-group-label'>{$lang['userdetails_waittime']}</span>
    <input class='input-group-field' type='text' name='wait_time' value='" . (int)$user['wait_time'] . "'>";
    // ==end
    // == Peers limit
    if ($CURUSER['class'] >= UC_STAFF) {
        $HTMLOUT .= "<span class='input-group-label'>{$lang['userdetails_peerslimit']}</span>
    <input class='input-group-field' type='text'' name='peers_limit' value='".(int)$user['peers_limit']."'>";
    }
    // ==end
    // == Torrents limit
    if ($CURUSER['class'] >= UC_STAFF) {
        $HTMLOUT .= "<span class='input-group-label'>{$lang['userdetails_torrentslimit']}</span>
    <input class='input-group-field' type='text' name='torrents_limit' value='".(int)$user['torrents_limit']."'>
</div>";
    }
// ==end
