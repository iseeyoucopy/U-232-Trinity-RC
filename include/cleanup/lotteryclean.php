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
function docleanup($data)
{
    global $TRINITY20, $queries, $keys, $mysqli;
    set_time_limit(0);
    ignore_user_abort(1);
    ($lconf = sql_query('SELECT * FROM lottery_config')) || sqlerr(__FILE__, __LINE__);
    while ($aconf = $lconf->fetch_assoc()) $lottery_config[$aconf['name']] = $aconf['value'];
    if ($lottery_config['enable'] && TIME_NOW > $lottery_config['end_date']) {
        ($q = sql_query('SELECT t.user as uid, u.seedbonus, u.modcomment FROM tickets as t LEFT JOIN users as u ON u.id = t.user ORDER BY RAND() ')) || sqlerr(__FILE__, __LINE__);
        while ($a = $q->fetch_assoc()) $tickets[] = $a;
        shuffle($tickets);
        $lottery['winners'] = array();
        $lottery['total_tickets'] = count($tickets);
        for ($i = 0; $i < $lottery['total_tickets']; $i++) {
            if (!isset($lottery['winners'][$tickets[$i]['uid']])) $lottery['winners'][$tickets[$i]['uid']] = $tickets[$i];
            if ($lottery_config['total_winners'] == count($lottery['winners'])) break;
        }
        if ($lottery_config['use_prize_fund']) $lottery['total_pot'] = $lottery_config['prize_fund'];
        else $lottery['total_pot'] = $lottery['total_tickets'] * $lottery_config['ticket_amount'];
        $lottery['user_pot'] = round($lottery['total_pot'] / $lottery_config['total_winners'], 2);
        $msg['subject'] = sqlesc('You have won the lottery');
        $msg['body'] = sqlesc('Congratulations, You have won : ' . ($lottery['user_pot']) . '. This has been added to your seedbonus total amount. Thanks for playing Lottery.');
        foreach ($lottery['winners'] as $winner) {
            $_userq[] = '(' . $winner['uid'] . ',' . ($winner['seedbonus'] + $lottery['user_pot']) . ',' . sqlesc("User won the lottery: " . ($lottery['user_pot']) . " at " . get_date(TIME_NOW, 'LONG') . "\n" . $winner['modcomment']) . ')';
            $_pms[] = '(0,' . $winner['uid'] . ',' . $msg['subject'] . ',' . $msg['body'] . ',' . TIME_NOW . ')';
        }
        $lconfig_update = array(
            '(\'enable\',0)',
            '(\'lottery_winners_time\',' . TIME_NOW . ')',
            '(\'lottery_winners_amount\',' . $lottery['user_pot'] . ')',
            '(\'lottery_winners\',\'' . implode('|', array_keys($lottery['winners'])) . '\')'
        );
        if (count($_userq) > 0) sql_query('INSERT INTO users(id,seedbonus,modcomment) VALUES ' . implode(',', $_userq) . ' ON DUPLICATE KEY UPDATE seedbonus = values(seedbonus), modcomment = values(modcomment)') || die($mysqli->error);
        if (count($_pms) > 0) sql_query('INSERT INTO messages(sender, receiver, subject, msg, added) VALUES ' . implode(',', $_pms)) || die($mysqli->error);
        sql_query('INSERT INTO lottery_config(name,value) VALUES ' . implode(',', $lconfig_update) . ' ON DUPLICATE KEY UPDATE value=values(value)') || die($mysqli->error);
        sql_query('DELETE FROM tickets') || die($mysqli->error);
    }
    //==End 09 seedbonus lottery by putyn
    if ($queries > 0) write_log("Lottery clean-------------------- lottery Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
