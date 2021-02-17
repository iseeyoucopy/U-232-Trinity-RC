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
if (!defined('IN_LOTTERY')) {
    die('You can\'t access this file directly!');
}
ini_set('display_errors', 1);
//get config from database
($lconf = sql_query('SELECT * FROM lottery_config')) || sqlerr(__FILE__, __LINE__);
while ($ac = $lconf->fetch_assoc()) {
    $lottery_config[$ac['name']] = $ac['value'];
}
if (!$lottery_config['enable']) {
    stderr('Sorry', 'Lottery is closed');
}
$html = '';
$html = "<div class='row'><div class='col-md-12'><h2>Lottery Stats</h2>";
$html .= "<h2>Lottery started on <b>".get_date($lottery_config['start_date'], 'LONG')."</b> and ends on <b>".get_date($lottery_config['end_date'],
        'LONG')."</b> remaining <span style='color:#ff0000;'>".mkprettytime($lottery_config['end_date'] - TIME_NOW)."</span></h2>";
($qs = sql_query('SELECT count(t.id) as tickets , u.username, u.id, u.seedbonus FROM tickets as t LEFT JOIN users as u ON u.id = t.user GROUP BY u.id ORDER BY tickets DESC, username ASC')) || sqlerr(__FILE__,
    __LINE__);
if (!$qs->num_rows) {
    $html .= "<h2>No tickets were bought</h2>";
} else {
    $html .= "<div class='row'><div class='col-md-12'>";
    $html .= "<table class='table table-bordered'>
    <tr>
      <td width='100%'>Username</td>
      <td style='white-space:nowrap;'>tickets</td>
      <td style='white-space:nowrap;'>seedbonus</td>
    </tr>";
    while ($ar = $qs->fetch_assoc()) {
        $html .= "<tr>
                  <td align='left'><a href='userdetails.php?id=".(int)$ar['id']."'>".htmlsafechars($ar['username'])."</a></td>
                  <td align='center'>".(int)$ar['tickets']."</td>
                  <td align='center'>".(float)$ar['seedbonus']."</td>
        </tr>";
    }
    $html .= "</table>";
    $html .= "</div></div>";
}
$html .= "</div></div>";
echo(stdhead('Lottery tickets').$html.stdfoot());
?>
