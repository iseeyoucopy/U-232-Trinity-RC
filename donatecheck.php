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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(CACHE_DIR.'paypal_settings.php');
dbconn();
//$payment_status = "Completed";//Only uncomment if using sandbox mode
$donate_goods = [
    $TRINITY20['paypal_config']['gb_donated_1'] => [
        'to_add' => [
            'uploaded' => $TRINITY20['paypal_config']['up_amt_1'] * 1_073_741_824,
            'invites' => $TRINITY20['paypal_config']['inv_amt_1'],
            'seedbonus' => $TRINITY20['paypal_config']['kp_amt_1'],
            'total_donated' => $TRINITY20['paypal_config']['gb_donated_1'],
        ],
        'to_update' => [
            'donated' => $TRINITY20['paypal_config']['gb_donated_1'],
            'warned' => 0,
            'downloadpos' => 1,
            'uploadpos' => 1,
            'leechwarn' => 0,
            'hit_and_run_total' => 0,
            'hnrwarn' => '\'no\'',
            'enabled' => '\'yes\'',
            'class' => 'IF(class < '.UC_VIP.', '.UC_VIP.', class), vipclass_before = IF(class < '.UC_VIP.', class, 0)',
            'donoruntil' => sprintf('IF(donoruntil > 0,donoruntil + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['duntil_dur_1'], $TRINITY20['paypal_config']['duntil_dur_1']),
            'vip_until' => sprintf('IF(vip_until > 0,vip_until + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['vip_dur_1'], $TRINITY20['paypal_config']['vip_dur_1']),
            'free_switch' => sprintf('IF(free_switch > 0,free_switch + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['free_dur_1'], $TRINITY20['paypal_config']['free_dur_1']),
            'immunity' => sprintf('IF(immunity > 0,immunity + (%d * 604800), '.TIME_NOW.' + (%d * 604800))', $TRINITY20['paypal_config']['imm_dur_1'],
                $TRINITY20['paypal_config']['imm_dur_1']),
            'donor' => '\'yes\'',
            'modcomment' => "CONCAT('".get_date(TIME_NOW, 'DATE',
                    1)." - User donated ".$TRINITY20['paypal_config']['gb_donated_1']." ".$TRINITY20['paypal_config']['currency']."\n',modcomment)",
        ],
    ],
    $TRINITY20['paypal_config']['gb_donated_2'] => [
        'to_add' => [
            'uploaded' => $TRINITY20['paypal_config']['up_amt_2'] * 1_073_741_824,
            'invites' => $TRINITY20['paypal_config']['inv_amt_2'],
            'seedbonus' => $TRINITY20['paypal_config']['kp_amt_2'],
            'total_donated' => $TRINITY20['paypal_config']['gb_donated_2'],
        ],
        'to_update' => [
            'donated' => $TRINITY20['paypal_config']['gb_donated_2'],
            'warned' => 0,
            'downloadpos' => 1,
            'uploadpos' => 1,
            'leechwarn' => 0,
            'hit_and_run_total' => 0,
            'hnrwarn' => '\'no\'',
            'enabled' => '\'yes\'',
            'class' => 'IF(class < '.UC_VIP.', '.UC_VIP.', class), vipclass_before = IF(class < '.UC_VIP.', class, 0)',
            'donoruntil' => sprintf('IF(donoruntil > 0,donoruntil + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['duntil_dur_2'], $TRINITY20['paypal_config']['duntil_dur_2']),
            'vip_until' => sprintf('IF(vip_until > 0,vip_until + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['vip_dur_2'], $TRINITY20['paypal_config']['vip_dur_2']),
            'free_switch' => sprintf('IF(free_switch > 0,free_switch + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['free_dur_2'], $TRINITY20['paypal_config']['free_dur_2']),
            'immunity' => sprintf('IF(immunity > 0,immunity + (%d * 604800), '.TIME_NOW.' + (%d * 604800))', $TRINITY20['paypal_config']['imm_dur_2'],
                $TRINITY20['paypal_config']['imm_dur_2']),
            'donor' => '\'yes\'',
            'modcomment' => "CONCAT('".get_date(TIME_NOW, 'DATE',
                    1)." - User donated ".$TRINITY20['paypal_config']['gb_donated_2']." ".$TRINITY20['paypal_config']['currency']."\n',modcomment)",
        ],
    ],
    $TRINITY20['paypal_config']['gb_donated_3'] => [
        'to_add' => [
            'uploaded' => $TRINITY20['paypal_config']['up_amt_3'] * 1_073_741_824,
            'invites' => $TRINITY20['paypal_config']['inv_amt_3'],
            'seedbonus' => $TRINITY20['paypal_config']['kp_amt_3'],
            'total_donated' => $TRINITY20['paypal_config']['gb_donated_3'],
        ],
        'to_update' => [
            'donated' => $TRINITY20['paypal_config']['gb_donated_3'],
            'warned' => 0,
            'downloadpos' => 1,
            'uploadpos' => 1,
            'leechwarn' => 0,
            'hit_and_run_total' => 0,
            'hnrwarn' => '\'no\'',
            'enabled' => '\'yes\'',
            'class' => 'IF(class < '.UC_VIP.', '.UC_VIP.', class), vipclass_before = IF(class < '.UC_VIP.', class, 0)',
            'donoruntil' => sprintf('IF(donoruntil > 0,donoruntil + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['duntil_dur_3'], $TRINITY20['paypal_config']['duntil_dur_3']),
            'vip_until' => sprintf('IF(vip_until > 0,vip_until + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['vip_dur_3'], $TRINITY20['paypal_config']['vip_dur_3']),
            'free_switch' => sprintf('IF(free_switch > 0,free_switch + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['free_dur_3'], $TRINITY20['paypal_config']['free_dur_3']),
            'immunity' => sprintf('IF(immunity > 0,immunity + (%d * 604800), '.TIME_NOW.' + (%d * 604800))', $TRINITY20['paypal_config']['imm_dur_3'],
                $TRINITY20['paypal_config']['imm_dur_3']),
            'donor' => '\'yes\'',
            'modcomment' => "CONCAT('".get_date(TIME_NOW, 'DATE',
                    1)." - User donated ".$TRINITY20['paypal_config']['gb_donated_3']." ".$TRINITY20['paypal_config']['currency']."\n',modcomment)",
        ],
    ],
    $TRINITY20['paypal_config']['gb_donated_4'] => [
        'to_add' => [
            'uploaded' => $TRINITY20['paypal_config']['up_amt_4'] * 1_073_741_824,
            'invites' => $TRINITY20['paypal_config']['inv_amt_4'],
            'seedbonus' => $TRINITY20['paypal_config']['kp_amt_4'],
            'total_donated' => $TRINITY20['paypal_config']['gb_donated_4'],
        ],
        'to_update' => [
            'donated' => $TRINITY20['paypal_config']['gb_donated_4'],
            'warned' => 0,
            'downloadpos' => 1,
            'uploadpos' => 1,
            'leechwarn' => 0,
            'hit_and_run_total' => 0,
            'hnrwarn' => '\'no\'',
            'enabled' => '\'yes\'',
            'class' => 'IF(class < '.UC_VIP.', '.UC_VIP.', class), vipclass_before = IF(class < '.UC_VIP.', class, 0)',
            'donoruntil' => sprintf('IF(donoruntil > 0,donoruntil + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['duntil_dur_4'], $TRINITY20['paypal_config']['duntil_dur_4']),
            'vip_until' => sprintf('IF(vip_until > 0,vip_until + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['vip_dur_4'], $TRINITY20['paypal_config']['vip_dur_4']),
            'free_switch' => sprintf('IF(free_switch > 0,free_switch + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['free_dur_4'], $TRINITY20['paypal_config']['free_dur_4']),
            'immunity' => sprintf('IF(immunity > 0,immunity + (%d * 604800), '.TIME_NOW.' + (%d * 604800))', $TRINITY20['paypal_config']['imm_dur_4'],
                $TRINITY20['paypal_config']['imm_dur_4']),
            'donor' => '\'yes\'',
            'modcomment' => "CONCAT('".get_date(TIME_NOW, 'DATE',
                    1)." - User donated ".$TRINITY20['paypal_config']['gb_donated_4']." ".$TRINITY20['paypal_config']['currency']."\n',modcomment)",
        ],
    ],
    $TRINITY20['paypal_config']['gb_donated_5'] => [
        'to_add' => [
            'uploaded' => $TRINITY20['paypal_config']['up_amt_5'] * 1_073_741_824,
            'invites' => $TRINITY20['paypal_config']['inv_amt_5'],
            'seedbonus' => $TRINITY20['paypal_config']['kp_amt_5'],
            'total_donated' => $TRINITY20['paypal_config']['gb_donated_5'],
        ],
        'to_update' => [
            'donated' => $TRINITY20['paypal_config']['gb_donated_5'],
            'warned' => 0,
            'downloadpos' => 1,
            'uploadpos' => 1,
            'leechwarn' => 0,
            'hit_and_run_total' => 0,
            'hnrwarn' => '\'no\'',
            'enabled' => '\'yes\'',
            'class' => 'IF(class < '.UC_VIP.', '.UC_VIP.', class), vipclass_before = IF(class < '.UC_VIP.', class, 0)',
            'donoruntil' => sprintf('IF(donoruntil > 0,donoruntil + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['duntil_dur_5'], $TRINITY20['paypal_config']['duntil_dur_5']),
            'vip_until' => sprintf('IF(vip_until > 0,vip_until + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['vip_dur_5'], $TRINITY20['paypal_config']['vip_dur_5']),
            'free_switch' => sprintf('IF(free_switch > 0,free_switch + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['free_dur_5'], $TRINITY20['paypal_config']['free_dur_5']),
            'immunity' => sprintf('IF(immunity > 0,immunity + (%d * 604800), '.TIME_NOW.' + (%d * 604800))', $TRINITY20['paypal_config']['imm_dur_5'],
                $TRINITY20['paypal_config']['imm_dur_5']),
            'donor' => '\'yes\'',
            'modcomment' => "CONCAT('".get_date(TIME_NOW, 'DATE',
                    1)." - User donated ".$TRINITY20['paypal_config']['gb_donated_5']." ".$TRINITY20['paypal_config']['currency']."\n',modcomment)",
        ],
    ],
    $TRINITY20['paypal_config']['gb_donated_6'] => [
        'to_add' => [
            'uploaded' => $TRINITY20['paypal_config']['up_amt_6'] * 1_073_741_824,
            'invites' => $TRINITY20['paypal_config']['inv_amt_6'],
            'seedbonus' => $TRINITY20['paypal_config']['kp_amt_6'],
            'total_donated' => $TRINITY20['paypal_config']['gb_donated_6'],
        ],
        'to_update' => [
            'donated' => $TRINITY20['paypal_config']['gb_donated_6'],
            'warned' => 0,
            'downloadpos' => 1,
            'uploadpos' => 1,
            'leechwarn' => 0,
            'hit_and_run_total' => 0,
            'hnrwarn' => '\'no\'',
            'enabled' => '\'yes\'',
            'class' => 'IF(class < '.UC_VIP.', '.UC_VIP.', class), vipclass_before = IF(class < '.UC_VIP.', class, 0)',
            'donoruntil' => sprintf('IF(donoruntil > 0,donoruntil + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['duntil_dur_6'], $TRINITY20['paypal_config']['duntil_dur_6']),
            'vip_until' => sprintf('IF(vip_until > 0,vip_until + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['vip_dur_6'], $TRINITY20['paypal_config']['vip_dur_6']),
            'free_switch' => sprintf('IF(free_switch > 0,free_switch + (%d * 604800), '.TIME_NOW.' + (%d * 604800))',
                $TRINITY20['paypal_config']['free_dur_6'], $TRINITY20['paypal_config']['free_dur_6']),
            'immunity' => sprintf('IF(immunity > 0,immunity + (%d * 604800), '.TIME_NOW.' + (%d * 604800))', $TRINITY20['paypal_config']['imm_dur_6'],
                $TRINITY20['paypal_config']['imm_dur_6']),
            'donor' => '\'yes\'',
            'modcomment' => "CONCAT('".get_date(TIME_NOW, 'DATE',
                    1)." - User donated ".$TRINITY20['paypal_config']['gb_donated_6']." ".$TRINITY20['paypal_config']['currency']."\n',modcomment)",
        ],
    ],
];
function mk_update_query($amount, $user_id)
{
    global $donate_goods;
    $query = [];
    foreach ($donate_goods[$amount]['to_add'] as $field => $value) {
        $query[] = sprintf('%s = %s + %d', $field, $field, $value);
    }
    foreach ($donate_goods[$amount]['to_update'] as $field => $value) {
        $query[] = sprintf('%s = %s', $field, $value);
    }
    return sprintf('update users set %s where id = %d', implode(', ', $query), $user_id);
}

function paypallog($txt)
{
    file_put_contents(ROOT_DIR.'/logs/paypal.txt', "\n[".date('h:m D-M-Y')."]\n".$txt, FILE_APPEND);
}

if (count($_POST) == 0) {
    paypallog('There is no _POST from paypal');
}
$request = 'cmd=_notify-validate';
foreach ($_POST as $p_key => $p_value) {
    $request .= '&'.$p_key.'='.urlencode(stripslashes($p_value));
}
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Host: www.paypal.com\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: ".strlen($request)."\r\n\r\n";
if ($hand = fsockopen('ssl://www.paypal.com', 443, $errno, $errstr, 30)) {
    fwrite($hand, $header.$request);
    $paypal_data = '';
    while (!feof($hand)) {
        $paypal_data .= fgets($hand, 128);
    }
    $vars['uid'] = isset($_POST['custom']) ? (int)$_POST['custom'] : 0;
    $vars['amount'] = isset($_POST['mc_gross']) ? (int)$_POST['mc_gross'] : 0;
    $vars['memo'] = isset($_POST['memo']) ? htmlsafechars($_POST['memo']) : '';
    if (stripos($paypal_data, 'VERIFIED') !== false) {
        ($user_query = sql_query(sprintf('SELECT COUNT(id) FROM users WHERE id = %d', $vars['uid']))) || paypallog($mysqli->error);
        if ($user_query->num_rows == 1) {
            //update the user and add the goodies
            sql_query(mk_update_query($vars['amount'], $vars['uid'])) || paypallog($mysqli->error);
            //instead of updating the cache delete it :P
            $cache->delete($cache_keys['my_userid'].$vars['uid']);
            $cache->delete($cache_keys['user'].$vars['uid']);
            $cache->delete($cache_keys['user_stats'].$vars['uid']);
            $cache->delete($cache_keys['user_statss'].$vars['uid']);
            //update total funds
            sql_query(sprintf('INSERT INTO funds(cash,user,added) VALUES (%d,%d,%d)', $vars['amount'], $vars['uid'],
                TIME_NOW)) || paypallog($mysqli->error);
            //clear the cache for the funds
            $cache->delete($cache_keys['ttl_funds']);
            $msg[] = '('.$vars['uid'].',0,'.sqlesc('Donation - processed').','.sqlesc("Your donation was processed by paypal and our system\nWe remind you that you donated ".$vars['amount'].$TRINITY20['paypal_config']['currency']."\nIf you forgot what you'll get check the donation page again\nStaff from ".$TRINITY20['site_name']." is grateful for your donation\nIf you have any question's feel free to contact someone from staff").','.TIME_NOW.')';
            $msg[] = '('.$TRINITY20['paypal_config']['staff'].',0,'.sqlesc('Donation - made').','.sqlesc("This [url=".$TRINITY20['baseurl']."/userdetails.php?id=".(int)$vars['uid']."]user[/url] - donated ".$vars['amount'].$TRINITY20['paypal_config']['currency'].(empty($vars['memo']) ? '' : "\nUser sent a message with his donation:\n[b]".$vars['memo']."[/b]")).','.TIME_NOW.')';
        } else {
            paypallog('Could not find user with id = '.$vars['uid']);
        }
    } elseif (stripos($paypal_data, 'INVALID') !== false) {
        //something went wrong log data
        paypallog('Paypal didn\'t like the transaction and it rejected it. _POST = '.print_r($_POST, 1));
        //make some nice messages to let everyone know about the problem
        $msg[] = '('.$vars['uid'].',0,'.sqlesc('Donation - problem').','.sqlesc("We are sorry to announce you that paypal rejected the donation please contact the staff\n".$TRINITY20['site_name']."'s staff").','.TIME_NOW.')';
        $msg[] = '('.$TRINITY20['paypal_config']['staff'].',0,'.sqlesc('Donation - problem').','.sqlesc("This [url=".$TRINITY20['baseurl']."/userdetails.php?id=".(int)$vars['uid']."]user[/url] - donated but there was a problem with paypal. Check paypal log!").','.TIME_NOW.')';
    }
    sql_query('INSERT INTO messages(receiver,sender,subject,msg,added) VALUES '.implode(',', $msg)) || paypallog($mysqli->error);
    //clear memcache for staff
    $cache->delete($cache_keys['inbox_new'].$TRINITY20['paypal_config']['staff']);
    $cache->delete($cache_keys['inbox_new_sb'].$TRINITY20['paypal_config']['staff']);
    //and for the user that donated
    $cache->delete($cache_keys['inbox_new'].$vars['uid']);
    $cache->delete($cache_keys['inbox_new_sb'].$vars['uid']);
    $cache->delete('shoutbox_');
    fclose($hand);
} else {
    paypallog('Can\'t open hand');
}
?>
