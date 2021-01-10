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
$xmasday = mktime(0, 0, 0, 12, 25, date("Y"));
$today = mktime(date("G") , date("i") , date("s") , date("m") , date("d") , date("Y"));
//if (($CURUSER["opt1"] & user_options::GOTGIFT) && $today <> $xmasday) {
if ($CURUSER["gotgift"] == 'no' && $today <> $xmasday) {
    $HTMLOUT .= "<div class='card'>
        <div class='card-divider'>{$lang['index_xmas_gift']}</div>
        <div class='card-section'>
            <a href='{$TRINITY20['baseurl']}/gift.php?open=1'>
                <img src='{$TRINITY20['pic_base_url']}gift.png' alt='{$lang['index_xmas_gift']}' title='{$lang['index_xmas_gift']}' class='float-center'>
            </a>
        </div>
    </div>";
}
//==End
// End Class
// End File
