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
//==Connectable and port shit
if ($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
        $What_Cache = (XBT_TRACKER == true ? 'port_data_xbt_' : 'port_data_' );
    if (($port_data = $cache->get($What_Cache . $id)) === false) {
        if(XBT_TRACKER == true) {
        ($q1 = sql_query('SELECT `active` FROM `xbt_peers` WHERE uid = ' . sqlesc($id) . ' LIMIT 1')) || sqlerr(__FILE__, __LINE__);
        } else {
        ($q1 = sql_query('SELECT connectable, port ,agent FROM peers WHERE userid = ' . sqlesc($id) . ' LIMIT 1')) || sqlerr(__FILE__, __LINE__);
        }
        $port_data = $q1->fetch_row();
        $cache->set('port_data_' . $id, $port_data, $TRINITY20['expires']['port_data']);
    }
    if ($port_data > 0) {
        $connect = $port_data[0];
		$portdat1 = $port_data['1'] ?? '';
        $port = (XBT_TRACKER == true ? '' : $port_data[1]);
        $Ident_Client = (XBT_TRACKER == true ? $portdat1 : $port_data[2]);
        $XBT_or_PHP = (XBT_TRACKER == true ? '1' : 'yes');
        if ($connect == $XBT_or_PHP) {
            $connectable = "<img src='{$TRINITY20['pic_base_url']}tick.png' alt='{$lang['userdetails_yes']}' title='{$lang['userdetails_conn_sort']}' style='border:none;padding:2px;' /><font color='green'><b>{$lang['userdetails_yes']}</b></font>";
        } else {
            $connectable = "<img src='{$TRINITY20['pic_base_url']}cross.png' alt='{$lang['userdetails_no']}' title='{$lang['userdetails_conn_staff']}' style='border:none;padding:2px;' /><font color='red'><b>{$lang['userdetails_no']}</b></font>";
        }
    } else {
        $connectable = "<font color='orange'><b>{$lang['userdetails_unknown']}</b></font>";
    }
    $HTMLOUT.= "<tr><td class='rowhead'>{$lang['userdetails_connectable']}</td><td align='left'>" . $connectable . "</td></tr>";
    if (!empty($port)) {
        $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_port']}</td><td class='tablea' align='left'>".htmlsafechars($port)."</td></tr>
    <tr><td class='rowhead'>{$lang['userdetails_client']}</td><td class='tablea' align='left'>".htmlsafechars($Ident_Client)."</td></tr>";
    }
}
//==End
// End Class
// End File
