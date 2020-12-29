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
if ($TRINITY20['staffmsg_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($answeredby = $cache->get('staff_mess_')) === false) {
        $res1 = sql_query("SELECT count(id) FROM staffmessages WHERE answeredby = 0");
        list($answeredby) = mysqli_fetch_row($res1);
        $cache->set('staff_mess_', $answeredby, $TRINITY20['expires']['alerts']);
    }
    if ($answeredby > 0) {
        $htmlout.= "
		<a class='button small warning' data-toggle='staffmess-dropdown-1'>" . ($answeredby > 1 ? $lang['gl_staff_messages'] . $lang['gl_staff_message_news'] : $lang['gl_staff_message'] . $lang['gl_newmess']) . "</a>
	<div class='dropdown-pane' id='staffmess-dropdown-1' data-dropdown data-hover='true' data-hover-pane='true'>
		<div class='card card-body'>
    <a class='button' href='staffbox.php'>
	<em>" . ($answeredby > 1 ? $lang['gl_staff_messages'] . $lang['gl_staff_message_news'] : $lang['gl_staff_message'] . $lang['gl_staff_message_news']) . "</em>
   <b>{$lang['gl_hey']} {$CURUSER['username']}!<br /> " . sprintf($lang['gl_staff_message_alert'], $answeredby) . ($answeredby > 1 ? $lang['gl_staff_message_alerts'] : "") . "{$lang['gl_staff_message_for']}</b>
   {$lang['gl_staff_message_click']}
   </a></div></div>";
    }
}
//==End
// End Class
// End File
