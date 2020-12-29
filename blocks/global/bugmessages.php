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
if ($TRINITY20['bug_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($bugs = $cache->get('bug_mess_')) === false) {
        $res1 = sql_query("SELECT COUNT(id) FROM bugs WHERE status = 'na'");
        list($bugs) = mysqli_fetch_row($res1);
        $cache->set('bug_mess_', $bugs, $TRINITY20['expires']['alerts']);
    }
    if ($bugs > 0) {
        $htmlout.= "
<a class='button small alert' data-toggle='bug-dropdown-1'>{$lang['gl_bug_alert']}</a>
	<div class='dropdown-pane' id='bug-dropdown-1' data-dropdown data-hover='true' data-hover-pane='true'>
		<div class='card card-body'>		
    <a class='sa-tooltip' href='bugs.php?action=bugs'><b class='btn btn-danger btn-sm'>{$lang['gl_bug_alert']}</b>
	<span class='custom info custom info alert alert-danger'><em>{$lang['gl_bug_alert1']}</em>
   <b>{$lang['gl_bug_alert2']} {$CURUSER['username']}!<br /> " . sprintf($lang['gl_bugs'], $bugs[0]) . ($bugs[0] > 1 ? "{$lang['gl_bugss']}" : "") . "!</b>
   {$lang['gl_bug_alert3']}
   </span></a></div></div>";
    }
}
//==End
// End Class
// End File

