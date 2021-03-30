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
//== iphistory
if ($user['ip'] && ($CURUSER['class'] >= UC_STAFF || $user['id'] == $CURUSER['id'])) {
    $dom = @gethostbyaddr($user['ip']);
    $addr = ($dom == $user['ip'] || @gethostbyname($dom) != $user['ip']) ? $user['ip'] : $user['ip'].' ('.$dom.')';
}
if (($iphistory = $cache->get($keys['ip_history'].$id)) === false) {
    ($ipto = sql_query("SELECT COUNT(id),enabled FROM `users` AS iplist WHERE `ip` = ".sqlesc($user["ip"])." group by enabled")) || sqlerr(__FILE__,
        __LINE__);
    $row = $ipto->fetch_row();
    $ipuse[$row[1]] = isset($row[0]) ? (int)$row[0] : '';
    $ipuse_no = empty($ipuse['no']) ? '' : $ipuse['no'];
    $ipuse_yes = empty($ipuse['yes']) ? '' : $ipuse['yes'];
    if (($ipuse_yes == 1 && $ipuse_no == 0) || ($ipuse_no == 1 && $ipuse_yes == 0)) {
        $use = "";
    } else {
        $ipcheck = $user["ip"];
        $enbl = isset($ipuse['yes']) ? $ipuse['yes'].' enabled ' : '';
        $dbl = isset($ipuse['no']) ? $ipuse['no'].' disabled ' : '';
        $mid = $enbl && $dbl ? 'and' : '';
        $iphistory['use'] = "<b>(<font color='red'>{$lang['userdetails_ip_warn']}</font> <a href='staffpanel.php?tool=usersearch&amp;action=usersearch&amp;ip=$ipcheck'>{$lang['userdetails_ip_used']}$enbl $mid $dbl{$lang['userdetails_ip_users']}</a>)</b>";
    }
    ($resip = sql_query("SELECT ip FROM ips WHERE userid = ".sqlesc($id)." GROUP BY ip")) || sqlerr(__FILE__, __LINE__);
    $iphistory['ips'] = $resip->num_rows;
    $cache->set($keys['ip_history'].$id, $iphistory, $TRINITY20['expires']['iphistory']);
}
if (isset($addr) && ($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF)) {
    $HTMLOUT .= "<tr>
            <td>{$lang['userdetails_address']}</td>
            <td>{$addr}</td>";
}
if ($CURUSER["class"] >= UC_STAFF && $iphistory['ips'] > 0) {
    $HTMLOUT .= "<tr>
            <td>{$lang['userdetails_ip_history']}</td>
            <td>{$lang['userdetails_ip_earlier']}
                <b><a href='{$TRINITY20['baseurl']}/staffpanel.php?tool=iphistory&amp;action=iphistory&amp;id=".(int)$user['id']."'>{$iphistory['ips']} {$lang['userdetails_ip_different']}</a></b>
                {$iphistory['use']}&nbsp;(<a class='altlink' href='staffpanel.php?tool=iphistory&amp;action=iphistory&amp;id=".(int)$user['id']."'><b>{$lang['userdetails_ip_hist']}</b></a>)&nbsp;(<a class='altlink' href='staffpanel.php?tool=iphistory&amp;action=iplist&amp;id=".(int)$user['id']."'><b>{$lang['userdetails_ip_list']}</b></a>)
            </td>       
        </tr>";
}
//==end
// End Class
// End File
