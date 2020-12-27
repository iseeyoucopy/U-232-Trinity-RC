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
function ircbot($messages)
{
    $bot = array(
        'ip' => '127.0.0.1',
        'port' => 5631,
        'pass' => 'bWFtYWFyZW1lcmU',
        'pidfile' => '/home/...../eggdrop/pid.U232_Bot', //path to the pid. file
        'sleep' => 5,
    );
    if (empty($messages)) return; //die ('Empty message');
    if (!file_exists($bot['pidfile'])) return; //die ('Bot not online');
    if ($bot['hand'] = fsockopen($bot['ip'], $bot['port'], $errno, $errstr, 45)) {
        sleep($bot['sleep']);
        if (is_array($messages)) {
            foreach ($messages as $message) {
                fputs($bot['hand'], $bot['pass'] . ' ' . $message . "\n");
                sleep($bot['sleep']);
            }
        } else {
            fputs($bot['hand'], $bot['pass'] . ' ' . $messages . "\n");
            sleep($bot['sleep']);
        }
        fclose($bot['hand']);
    }
}
?>
