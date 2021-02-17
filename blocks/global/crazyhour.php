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
if(XBT_TRACKER == false && $TRINITY20['crazy_hour'] == true) {
function crazyhour()
{
    global $CURUSER, $TRINITY20, $cache, $lang;
    $htmlout = $cz = '';
    $crazy_hour = (TIME_NOW + 3600);
    if (($crazyhour['crazyhour'] = $cache->get('crazyhour')) === false) {
        ($crazyhour['crazyhour_sql'] = sql_query('SELECT var, amount FROM freeleech WHERE type = "crazyhour"')) || sqlerr(__FILE__, __LINE__);
        $crazyhour['crazyhour'] = array();
        if ($crazyhour['crazyhour_sql']->num_rows !== 0) $crazyhour['crazyhour'] = $crazyhour['crazyhour_sql']->fetch_assoc();
        else {
            $crazyhour['crazyhour']['var'] = random_int(TIME_NOW, (TIME_NOW + 86400));
            $crazyhour['crazyhour']['amount'] = 0;
            sql_query('UPDATE freeleech SET var = ' . $crazyhour['crazyhour']['var'] . ', amount = ' . $crazyhour['crazyhour']['amount'] . ' WHERE type = "crazyhour"') || sqlerr(__FILE__, __LINE__);
        }
        $cache->set('crazyhour', $crazyhour['crazyhour'], 0);
    }
    $cimg = '<img src="' . $TRINITY20['pic_base_url'] . 'cat_free.gif" alt="FREE!" />';
    if ($crazyhour['crazyhour']['var'] < TIME_NOW) { // if crazyhour over
        $cz_lock = $cache->set('crazyhour_lock', 1, 10);
        if ($cz_lock !== false) {
            $crazyhour['crazyhour_new'] = mktime(23, 59, 59, date('m') , date('d') , date('y'));
            $crazyhour['crazyhour']['var'] = random_int($crazyhour['crazyhour_new'], ($crazyhour['crazyhour_new'] + 86400));
            $crazyhour['crazyhour']['amount'] = 0;
            $crazyhour['remaining'] = ($crazyhour['crazyhour']['var'] - TIME_NOW);
            sql_query('UPDATE freeleech SET var = ' . $crazyhour['crazyhour']['var'] . ', amount = ' . $crazyhour['crazyhour']['amount'] . ' WHERE type = "crazyhour"') || sqlerr(__FILE__, __LINE__);
            $cache->set('crazyhour', $crazyhour['crazyhour'], 0);
            write_log('Next [color=#FFCC00][b]Crazyhour[/b][/color] is at ' . get_date($crazyhour['crazyhour']['var'] + ($CURUSER['time_offset'] - 3600) , 'LONG') . '');
            $text = 'Next [color=orange][b]Crazyhour[/b][/color] is at ' . get_date($crazyhour['crazyhour']['var'] + ($CURUSER['time_offset'] - 3600) , 'LONG');
            $text_parsed = '<b class="button small success">Next <span style="font-weight:bold;color:orange;">Crazyhour</span> is at ' . get_date($crazyhour['crazyhour']['var'] + ($CURUSER['time_offset'] - 3600) , 'LONG');
            sql_query('INSERT INTO shoutbox (userid, date, text, text_parsed) ' . ' VALUES (2, ' . TIME_NOW . ', ' . sqlesc($text) . ', ' . sqlesc($text_parsed) . ')') || sqlerr(__FILE__, __LINE__);
            
        }
    } elseif (($crazyhour['crazyhour']['var'] < $crazy_hour) && ($crazyhour['crazyhour']['var'] >= TIME_NOW)) { // if crazyhour
        if ($crazyhour['crazyhour']['amount'] !== 1) {
            $crazyhour['crazyhour']['amount'] = 1;
            $cz_lock = $cache->set('crazyhour_lock', 1, 10);
            if ($cz_lock !== false) {
                sql_query('UPDATE freeleech SET amount = ' . $crazyhour['crazyhour']['amount'] . ' WHERE type = "crazyhour"') || sqlerr(__FILE__, __LINE__);
                $cache->set('crazyhour', $crazyhour['crazyhour'], 0);
                write_log('w00t! It\'s [color=#FFCC00][b]Crazyhour[/b][/color]!');
                $text = 'w00t! It\'s [color=orange][b]Crazyhour[/b][/color] :w00t:';
                $message = 'w00t! It\'s <span style="font-weight:bold;color:orange;">Crazyhour</span> <img src="pic/smilies/w00t.gif" alt=":w00t:">';
                //sql_query('INSERT INTO shoutbox (userid, date, text, text_parsed) ' . 'VALUES (2, ' . TIME_NOW . ', ' . sqlesc($text) . ', ' . sqlesc($text_parsed) . ')') or sqlerr(__FILE__, __LINE__);
                if ($TRINITY20['autoshout_on'] == 1) {
                    autoshout($message);
                    
                }
            }
        }
        $crazyhour['remaining'] = ($crazyhour['crazyhour']['var'] - TIME_NOW);
        $crazytitle = $lang['gl_crazy_title'];
        $crazymessage = $lang['gl_crazy_message'] . ' <b> ' . $lang['gl_crazy_message1'] . '</b> ' . $lang['gl_crazy_message2'] . ' <strong> ' . $lang['gl_crazy_message3'] . '</strong>!';
        return $htmlout . ('
        <div class="input-group margin-0">
            <span class="input-group-label label success">' . $lang['gl_crazy_on'] . '</span>
            <span class="input-group-label label primary">' . $crazytitle . $crazymessage . $lang['gl_crazy_ends'] . ' ' . mkprettytime($crazyhour['remaining']) . '&nbsp;' . $lang['gl_crazy_at'] . ' ' . get_date($crazyhour['crazyhour']['var'], 'LONG') . '</span>
        </div>');
    }
    return $htmlout . ('
    <div class="input-group margin-0">
        <span class="input-group-label label success">' . $lang['gl_crazy_'] . '</span>
        <span class="input-group-label label primary">' . $lang['gl_crazy_message4'] . '&nbsp;' . $lang['gl_crazy_message5'] .'&nbsp;'. $lang['gl_crazy_message6'] . '&nbsp;' . mkprettytime($crazyhour['crazyhour']['var'] - 3600 - TIME_NOW) . '&nbsp;'. $lang['gl_crazy_at'] .'&nbsp;'. get_date($crazyhour['crazyhour']['var'] + ($CURUSER['time_offset'] - 3600) , 'LONG') . '</span>
    </div>');
}
$htmlout.= crazyhour();
}
// End Class
// End File
