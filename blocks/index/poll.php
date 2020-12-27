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
//==Poll
$HTMLOUT.= "<div class='callout'>
<h4 class='subheader'>{$lang['index_poll_name']}".(($CURUSER['class'] >= UC_STAFF) ? "&nbsp;&nbsp;<a class='button small' href='staffpanel.php?tool=polls_manager'>{$lang['index_poll_title']}</a>" : "")."</h4>";
    $HTMLOUT.= parse_poll()."</div>";
// End Class
// End File
