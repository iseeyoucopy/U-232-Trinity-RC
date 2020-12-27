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
if ($INSTALLER09['uploadapp_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($newapp = $cache->get('new_uploadapp_')) === false) {
        $res_newapps = sql_query("SELECT count(id) FROM uploadapp WHERE status = 'pending'");
        list($newapp) = mysqli_fetch_row($res_newapps);
        $cache->set('new_uploadapp_', $newapp, $INSTALLER09['expires']['alerts']);
    }
    if ($newapp > 0) {
        $htmlout.= "
		<a class='button small alert' data-toggle='bug-dropdown-1'>{$lang['gl_uploadapp_new']}</a>
	<div class='dropdown-pane' id='bug-dropdown-1' data-dropdown data-hover='true' data-hover-pane='true'>
		<div class='card card-body'>
   <a class='button' href='staffpanel.php?tool=uploadapps&amp;action=app'><b class='btn btn-warning btn-sm'></b>
   <span class='custom info alert alert-warning'><em>{$lang['gl_uploadapp_new']}</em>
   {$lang['gl_hey']} {$CURUSER['username']}!<br /> $newapp {$lang['gl_uploadapp_ua']}" . ($newapp > 1 ? "s" : "") . " {$lang['gl_uploadapp_dealt']} 
   {$lang['gl_uploadapp_click']}</span></a></div></div>";
    }
}
//==End
// End Class
// End File
