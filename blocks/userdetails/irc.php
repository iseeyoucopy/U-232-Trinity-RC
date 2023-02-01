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
function calctime($val)
{
    global $lang;
    $days = (int)($val / 86400);
    $val -= $days * 86400;
    $hours = (int)($val / 3600);
    $val -= $hours * 3600;
    $mins = (int)($val / 60);
    $secs = $val - ($mins * 60);
    return "&nbsp;$days {$lang['userdetails_irc_days']}, $hours {$lang['userdetails_irc_hrs']}, $mins {$lang['userdetails_irc_min']}";
}

//==Irc
if ($user['onirc'] == 'yes') {
    $ircbonus = (empty($user['irctotal']) ? '0.0' : number_format($user["irctotal"] / $TRINITY20['autoclean_interval'], 1));
    $HTMLOUT .= "<tr><td class='rowhead' valign='top' align='right'>{$lang['userdetails_irc_bonus']}</td><td align='left'>{$ircbonus}</td></tr>";
    $irctotal = (empty($user['irctotal']) ? htmlsafechars($user['username']).$lang['userdetails_irc_never'] : calctime($user['irctotal']));
    $HTMLOUT .= "<tr><td class='rowhead' valign='top' align='right'>{$lang['userdetails_irc_idle']}</td><td align='left'>{$irctotal}</td></tr>";
}
//==end
// End Class
// End File
