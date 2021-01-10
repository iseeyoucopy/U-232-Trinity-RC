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
$browser = $_SERVER['HTTP_USER_AGENT'];
if (preg_match("/MSIE/i", $browser)) //browser is IE
{
    $HTMLOUT.= "<div class='card'>
	<div class='card-divider'>
		<label for='checkbox_4' class='text-left'>{$lang['index_ie_warn']}</label>
	</div>
	<div class='card-section'>
    {$lang['index_ie_not']}{$TRINITY20['site_name']}{$lang['index_ie_suggest']}<a href='http://browsehappy.com'><b>{$lang['index_ie_bhappy']}</b></a>{$lang['index_ie_consider']}<br /><br /><a href='http://www.mozilla.com/firefox'><img alt='{$lang['index_ie_firefox']}' title='{$lang['index_ie_firefox']}' src='{$TRINITY20['pic_base_url']}getfirefox.gif' /></a><br /><strong>{$lang['index_ie_get']}</strong>
     </div></div>";
}
//==End
// End Class
// End File

