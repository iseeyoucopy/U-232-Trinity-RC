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
if ($user["info"]) $HTMLOUT.= "<tr valign='top'><td align='left' colspan='2' class='text' bgcolor='#F4F4F0'>" . format_comment($user["info"]) . "</td></tr>\n";
if ($user["signature"]) $HTMLOUT.= "<tr valign='top'><td align='left' colspan='2' class='text' bgcolor='#F4F4F0'>" . format_comment($user["signature"]) . "</td></tr>\n";
//==end
// End Class
// End File
