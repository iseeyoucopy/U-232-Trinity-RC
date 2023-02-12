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
//==Rep
$member_reputation = get_reputation($user, 'users');
$HTMLOUT .= "<tr><td class='rowhead' valign='top' align='right' width='1%'>{$lang['userdetails_rep']}</td><td align='left' width='99%'>{$member_reputation}<br></td></tr>";
//==end
// End Class
// End File
