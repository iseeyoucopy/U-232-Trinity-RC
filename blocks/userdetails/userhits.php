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
//==profile hits
if ($CURUSER["id"] == $user["id"]) $HTMLOUT.= "<tr><td class='rowhead'>{$lang['userdetails_pviews']}</td><td align='left'><a href='staffpanel.php?tool=user_hits&amp;id=$id'>" . number_format((int)$user["hits"]) . "</a></td></tr>\n";
//==end
// End Class
// End File
