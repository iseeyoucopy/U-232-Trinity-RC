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
//== Anonymous function ==//
function get_anonymous()
{
    global $CURUSER;
    return $CURUSER['anonymous_until'];
}

//== Get Parked function ==//
function get_parked()
{
    global $CURUSER;
    return $CURUSER['parked_until'];
}

//== Auto Shout Function ==//
function autoshout($msg)
{
    global $TRINITY20, $cache, $cache_keys;
    require_once(INCL_DIR . 'bbcode_functions.php');
    sql_query('INSERT INTO shoutbox(userid,date,text,text_parsed,autoshout)VALUES (' . $TRINITY20['bot_id'] . ',' . TIME_NOW . ',' . sqlesc($msg) . ',' . sqlesc(format_comment($msg)) . ', "yes")');
    $cache->delete('auto_shoutbox_');
}

//== Parked function ==//
function parked()
{
    require_once(CLASS_DIR . 'class_user_options.php');
    global $CURUSER;
    if ((isset($CURUSER['opt1']) & user_options::PARKED) !== 0) {
        stderr("Error", "<b>Your account is currently parked.</b>");
    }
}

//== Reputation function==//
function get_reputation($user, $mode = '', $rep_is_on = true, $post_id = 0)
{
    global $TRINITY20, $CURUSER;
    $member_reputation = "";
    if ($rep_is_on) {
        include CACHE_DIR . '/rep_cache.php';
        require_once(CLASS_DIR . 'class_user_options.php');
        // ok long winded file checking, but it's much better than file_exists
        if (!isset($reputations) || !is_array($reputations) || count($reputations) < 1) {
            return '<span title="Cache doesn\'t exist or zero length">Reputation: Offline</span>';
        }
        $user['g_rep_hide'] ??= 0;
        $user['username'] = isset($user['opt1']) & user_options::ANONYMOUS ? $user['username'] : 'Anonymous';
        // Hmmm...bit of jiggery-pokery here, couldn't think of a better way.
        $max_rep = max(array_keys($reputations));
        if ($user['reputation'] >= $max_rep) {
            $user_reputation = $reputations[$max_rep];
        } else {
            foreach ($reputations as $y => $x) {
                if ($y > $user['reputation']) {
                    $user_reputation = $old;
                    break;
                }
                $old = $x;
            }
        }
        //$rep_is_on = TRUE;
        //$CURUSER['g_rep_hide'] = FALSE;
        $rep_power = $user['reputation'];
        $posneg = '';
        if ($user['reputation'] == 0) {
            $rep_img = '<i class="fas fa-star" style="color:blue"></i>';
            $rep_power = $user['reputation'] * -1;
        } elseif ($user['reputation'] < 0) {
            $rep_img = '<i class="fas fa-star" style="color:#ff0000"></i>';
            $rep_img_2 = '<i class="fas fa-star" style="color:#ff0066"></i>';
            $rep_power = $user['reputation'] * -1;
        } else {
            $rep_img = '<i class="fas fa-star" style="color:#009933"></i>';
            $rep_img_2 = '<i class="fas fa-star" style="color:#66ff33"></i>';
        }
        if ($rep_power > 500) {
            // work out the bright green shiny bars, cos they cost 100 points, not the normal 100
            $rep_power = ($rep_power - ($rep_power - 500)) + (($rep_power - 500) / 2);
        }
        // shiny, shiny, shiny boots...
        // ok, now we can work out the number of bars/pippy things
        $pips = 5;
        switch ($mode) {
            case 'comments':
                $pips = 12;
                break;
            case 'torrents':
                $pips = 1003;
                break;
            case 'users':
                $pips = 970;
                break;
            case 'posts':
                $pips = 12;
                break;
            default:
                $pips = 5; // statusbar
        }
        $rep_bar = (int)($rep_power / 100);
        if ($rep_bar > 10) {
            $rep_bar = 10;
        }
        if ($user['g_rep_hide']) // can set this to a group option if required, via admin?
        {
            $posneg = 'off<i class="fas fa-star" style="color:#ebebe0"></i>';
        } else { // it ain't off then, so get on with it! I wanna see shiny stuff!!
            for ($i = 0; $i <= $rep_bar; $i++) {
                if ($i >= 5) {
                    $posneg .= $rep_img_2;
                } else {
                    $posneg .= $rep_img;
                }
            }
        }
        // now decide the locale
        if ($mode != '') {
            return "Rep: " . $posneg . "<br><br><a href='javascript:;' onclick=\"PopUp('{$TRINITY20['baseurl']}/reputation.php?pid=" . ($post_id != 0 ? (int)$post_id : (int)$user['id']) . "&amp;locale=" . $mode . "','Reputation',400,241,1,1);\"><button type='button' class='tiny button' style='margin-top:-9px;' alt='Add reputation:: " . htmlsafechars($user['username']) . "' title='Add reputation:: " . htmlsafechars($user['username']) . "'><i class='fa fa-check'></i> Add Rep</button></a>";
        }

        return " " . $posneg;
    } // END IF ONLINE
    // default
    return '<span title="Set offline by admin setting">Rep System Offline</span>';
}

//== Write staff function... This function will write usernames and their id's into staff_settings.php and staff_settings.php ==//
function write_staffs()
{
    global $TRINITY20;
    //==ids
    $t = '$TRINITY20';
    $iconfigfile = "<" . "?php\n/**\nThis file created on " . date('M d Y H:i:s') . ".\nSite Config staff mod.\n**/\n";
    ($ri = sql_query("SELECT id, username, class FROM users WHERE class BETWEEN " . UC_STAFF . " AND " . UC_MAX . " ORDER BY id ASC")) || sqlerr(__file__,
        __line__);
    $iconfigfile .= "" . $t . "['allowed_staff']['id'] = array(";
    while ($ai = $ri->fetch_assoc()) {
        $ids[] = $ai['id'];
        $usernames[] = "'" . $ai["username"] . "' => 1";
    }
    $iconfigfile .= "" . implode(",", $ids);
    $iconfigfile .= ");";
    $iconfigfile .= '
?>';
    $filenum = fopen('./cache/staff_settings.php', 'w');
    ftruncate($filenum, 0);
    fwrite($filenum, $iconfigfile);
    fclose($filenum);
    //==names
    $t = '$TRINITY20';
    $nconfigfile = "<" . "?php\n/**\nThis file created on " . date('M d Y H:i:s') . ".\nSite Config staff mod.\n**/\n";
    $nconfigfile .= "" . $t . "['staff']['allowed'] = array(";
    $nconfigfile .= "" . implode(",", $usernames);
    $nconfigfile .= ");";
    $nconfigfile .= '
?>';
    $filenum1 = fopen('./cache/staff_settings2.php', 'w');
    ftruncate($filenum1, 0);
    fwrite($filenum1, $nconfigfile);
    fclose($filenum1);
}

//== Function Ratio Color ==//
function get_ratio_color($ratio)
{
    if ($ratio < 0.1) {
        return "#ff0000";
    }
    if ($ratio < 0.2) {
        return "#ee0000";
    }
    if ($ratio < 0.3) {
        return "#dd0000";
    }
    if ($ratio < 0.4) {
        return "#cc0000";
    }
    if ($ratio < 0.5) {
        return "#bb0000";
    }
    if ($ratio < 0.6) {
        return "#aa0000";
    }
    if ($ratio < 0.7) {
        return "#990000";
    }
    if ($ratio < 0.8) {
        return "#880000";
    }
    if ($ratio < 0.9) {
        return "#770000";
    }
    if ($ratio < 1) {
        return "#660000";
    }
    if (($ratio >= 1.0) && ($ratio < 2.0)) {
        return "#006600";
    }
    if (($ratio >= 2.0) && ($ratio < 3.0)) {
        return "#007700";
    }
    if (($ratio >= 3.0) && ($ratio < 4.0)) {
        return "#008800";
    }
    if (($ratio >= 4.0) && ($ratio < 5.0)) {
        return "#009900";
    }
    if (($ratio >= 5.0) && ($ratio < 6.0)) {
        return "#00aa00";
    }
    if (($ratio >= 6.0) && ($ratio < 7.0)) {
        return "#00bb00";
    }
    if (($ratio >= 7.0) && ($ratio < 8.0)) {
        return "#00cc00";
    }
    if (($ratio >= 8.0) && ($ratio < 9.0)) {
        return "#00dd00";
    }
    if (($ratio >= 9.0) && ($ratio < 10.0)) {
        return "#00ee00";
    }
    if ($ratio >= 10) {
        return "#00ff00";
    }
    return "#777777";
}

function get_slr_color($ratio)
{
    if ($ratio < 0.025) {
        return "#ff0000";
    }
    if ($ratio < 0.05) {
        return "#ee0000";
    }
    if ($ratio < 0.075) {
        return "#dd0000";
    }
    if ($ratio < 0.1) {
        return "#cc0000";
    }
    if ($ratio < 0.125) {
        return "#bb0000";
    }
    if ($ratio < 0.15) {
        return "#aa0000";
    }
    if ($ratio < 0.175) {
        return "#990000";
    }
    if ($ratio < 0.2) {
        return "#880000";
    }
    if ($ratio < 0.225) {
        return "#770000";
    }
    if ($ratio < 0.25) {
        return "#660000";
    }
    if ($ratio < 0.275) {
        return "#550000";
    }
    if ($ratio < 0.3) {
        return "#440000";
    }
    if ($ratio < 0.325) {
        return "#330000";
    }
    if ($ratio < 0.35) {
        return "#220000";
    }
    if ($ratio < 0.375) {
        return "#110000";
    }
    if (($ratio >= 1.0) && ($ratio < 2.0)) {
        return "#006600";
    }
    if (($ratio >= 2.0) && ($ratio < 3.0)) {
        return "#007700";
    }
    if (($ratio >= 3.0) && ($ratio < 4.0)) {
        return "#008800";
    }
    if (($ratio >= 4.0) && ($ratio < 5.0)) {
        return "#009900";
    }
    if (($ratio >= 5.0) && ($ratio < 6.0)) {
        return "#00aa00";
    }
    if (($ratio >= 6.0) && ($ratio < 7.0)) {
        return "#00bb00";
    }
    if (($ratio >= 7.0) && ($ratio < 8.0)) {
        return "#00cc00";
    }
    if (($ratio >= 8.0) && ($ratio < 9.0)) {
        return "#00dd00";
    }
    if (($ratio >= 9.0) && ($ratio < 10.0)) {
        return "#00ee00";
    }
    if ($ratio >= 10) {
        return "#00ff00";
    }
    return "#777777";
}

function ratio_image_machine($ratio_to_check)
{
    global $TRINITY20;
    switch ($ratio_to_check) {
        case $ratio_to_check >= 5:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/yay.gif" alt="Yay" title="Yay">';
            break;
        case $ratio_to_check >= 4:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/pimp.gif" alt="Pimp" title="Pimp">';
            break;
        case $ratio_to_check >= 3:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/w00t.gif" alt="W00t" title="W00t">';
            break;
        case $ratio_to_check >= 2:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/grin.gif" alt="Grin" title="Grin">';
            break;
        case $ratio_to_check >= 1.5:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/evo.gif" alt="Evo" title="Evo">';
            break;
        case $ratio_to_check >= 1:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/smile1.gif" alt="Smile" title="Smile">';
            break;
        case $ratio_to_check >= 0.5:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/noexpression.gif" alt="Blank" title="Blank">';
            break;
        case $ratio_to_check >= 0.25:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/cry.gif" alt="Cry" title="Cry">';
            break;
        case $ratio_to_check < 0.25:
            return '<img src="' . $TRINITY20['pic_base_url'] . 'smilies/shit.gif" alt="Shit" title="Shit">';
            break;
    }
}

//== User class functions - pdq 2010 ==//
function get_user_class_name($class)
{
    global $class_names;
    $class = (int)$class;
    if (!valid_class($class)) {
        return '';
    }
    if (isset($class_names[$class])) {
        return $class_names[$class];
    }

    return '';
}

function get_user_class_color($class)
{
    global $class_colors;
    $class = (int)$class;
    if (!valid_class($class)) {
        return '';
    }
    if (isset($class_colors[$class])) {
        return $class_colors[$class];
    }

    return '';
}

function get_user_class_image($class)
{
    global $class_images;
    $class = (int)$class;
    if (!valid_class($class)) {
        return '';
    }
    if (isset($class_images[$class])) {
        return $class_images[$class];
    }

    return '';
}

function valid_class($class)
{
    $class = (int)$class;
    return (bool)($class >= UC_MIN && $class <= UC_MAX);
}

function min_class($min = UC_MIN, $max = UC_MAX)
{
    global $CURUSER;
    $minclass = (int)$min;
    $maxclass = (int)$max;
    if (!isset($CURUSER)) {
        return false;
    }
    if (!valid_class($minclass) || !valid_class($maxclass)) {
        return false;
    }
    if ($maxclass < $minclass) {
        return false;
    }
    return (bool)($CURUSER['class'] >= $minclass && $CURUSER['class'] <= $maxclass);
}

function format_username($user, $icons = true)
{
    global $TRINITY20;
    $userf_id = (isset($user['id']) ? (int)$user['id'] : 0);
    $userf_class = (isset($user['class']) ? (int)$user['class'] : 0);
    if ($userf_id == 0) {
        return 'System';
    }

    if ((isset($user['username']) ? htmlsafechars($user['username']) : '') == '') {
        return 'unknown[' . $userf_id . ']';
    }
    $username = '<span style="color:#' . get_user_class_color($userf_class) . ';"><strong>' . htmlsafechars($user['username']) . '</strong></span>';
    $str = '<span style="white-space: nowrap;"><a class="user_' . $userf_id . '" href="' . $TRINITY20['baseurl'] . '/userdetails.php?id=' . $userf_id . '" target="_blank">' . $username . '</a>';
    if ($icons != false) {
        $str .= ($user['donor'] == 'yes' ? '<img src="' . $TRINITY20['pic_base_url'] . 'star.png" alt="Donor" title="Donor">' : '');
        $str .= ($user['warned'] >= 1 ? '<img src="' . $TRINITY20['pic_base_url'] . 'alertred.png" alt="Warned" title="Warned">' : '');
        $str .= ($user['leechwarn'] >= 1 ? '<img src="' . $TRINITY20['pic_base_url'] . 'alertblue.png" alt="Leech Warned" title="Leech Warned">' : '');
        $str .= ($user['enabled'] != 'yes' ? '<img src="' . $TRINITY20['pic_base_url'] . 'disabled.gif" alt="Disabled" title="Disabled">' : '');
        $str .= ($user['chatpost'] == 0 ? '<img src="' . $TRINITY20['pic_base_url'] . 'warned.png" alt="No Chat" title="Shout disabled">' : '');
        $str .= ($user['pirate'] != 0 ? '<img src="' . $TRINITY20['pic_base_url'] . 'pirate.png" alt="Pirate" title="Pirate">' : '');
        $str .= ($user['king'] != 0 ? '<img src="' . $TRINITY20['pic_base_url'] . 'king.png" alt="King" title="King">' : '');
    }
    return $str . "</span>\n";
}

function is_valid_id($id)
{
    return is_numeric($id) && ($id > 0) && (floor($id) == $id);
}

function member_ratio($up, $down)
{
    switch (true) {
        case ($down > 0 && $up > 0):
            $ratio = '<span style="color:' . get_ratio_color($up / $down) . ';">' . number_format($up / $down, 3) . '</span>';
            break;
        case ($down > 0 && $up == 0):
            $ratio = '<span style="color:' . get_ratio_color(1 / $down) . ';">' . number_format(1 / $down, 3) . '</span>';
            break;
        case ($down == 0 && $up > 0):
            $ratio = '<span style="color: ' . get_ratio_color($up / 1) . ';">Inf</span>';
            break;
        default:
            $ratio = '---';
    }
    return $ratio;
}

//=== get smilie based on ratio
function get_user_ratio_image($ratio)
{
    global $TRINITY20;
    switch ($ratio) {
        case ($ratio == 0):
            return;
            break;
        case ($ratio < 0.6):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/shit.gif" alt=" Bad ratio :("  title=" Bad ratio :("/>';
            break;
        case ($ratio <= 0.7):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/weep.gif" alt=" Could be better"  title=" Could be better">';
            break;
        case ($ratio <= 0.8):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/cry.gif" alt=" Getting there!" title=" Getting there!">';
            break;
        case ($ratio <= 1.5):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/smile1.gif" alt=" Good Ratio :)" title=" Good Ratio :)">';
            break;
        case ($ratio <= 2.0):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/grin.gif" alt=" Great Ratio :)" title=" Great Ratio :)">';
            break;
        case ($ratio <= 3.0):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/w00t.gif" alt=" Wow! :D" title=" Wow! :D">';
            break;
        case ($ratio <= 4.0):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/pimp.gif" alt=" Fa-boo Ratio!" title=" Fa-boo Ratio!">';
            break;
        case ($ratio > 4.0):
            return ' <img src="' . $TRINITY20['pic_base_url'] . 'smilies/yahoo.gif" alt=" Great ratio :-D" title=" Great ratio :-D">';
            break;
    }
    return '';
}

//=== avatar stuff... hell it's called all over the place :-o
/*
function avatar_stuff($avatar, $width = 80)
{
    global $CURUSER, $TRINITY20;
    require_once (CLASS_DIR . 'class_user_options.php');
    $avatar_show = (!($CURUSER['opt1'] & user_options::AVATARS) ? '' : (!$avatar['avatar'] ? '<img style="max-width:' . $width . 'px;" src="' . $TRINITY20['pic_base_url'] . 'default_avatar.gif" alt="avatar">' : (($CURUSER['opt1'] & user_options::OFFENSIVE_AVATAR && $CURUSER['opt2'] & user_options::VIEW_OFFENSIVE_AVATAR) ? '<img style="max-width:' . $width . 'px;" src="' . $TRINITY20['pic_base_url'] . 'fuzzybunny.gif" alt="avatar">' : '<img style="max-width:' . $width . 'px;" src="' . htmlsafechars($avatar['avatar']) . '" alt="avatar">')));
    return $avatar_show;
}
*/
//=== avatar stuff... hell it's called all over the place :-o
function avatar_stuff($avatar, $width = 80)
{
    global $CURUSER, $TRINITY20;
    return $CURUSER['avatars'] == 'no' ? '' : ($avatar['avatar'] ? ($avatar['offensive_avatar'] === 'yes' && $CURUSER['view_offensive_avatar'] === 'no') ? '<img style="max-width:' . $width . 'px;" src="' . $TRINITY20['pic_base_url'] . 'fuzzybunny.gif" alt="avatar">' : '<img style="max-width:' . $width . 'px;" src="' . htmlsafechars($avatar['avatar']) . '" alt="avatar">' : ('<img style="max-width:' . $width . 'px;" src="' . $TRINITY20['pic_base_url'] . 'default_avatar.gif" alt="avatar">'));
}

//=== added a function to get all user info and print them up with link to userdetails page, class color, user icons... pdq's idea \o/
function print_user_stuff($arr)
{
    global $CURUSER, $TRINITY20;
    return '<a href="userdetails.php?id=' . (isset($arr['id']) ? (int)$arr['id'] : '') . '" title="' . get_user_class_name(isset($arr['class']) ? (int)$arr['class'] : '') . '">
  <span style="font-weight: bold;"></span></a>' . format_username($arr) . '';
}

//made by putyn@tbdev
function blacklist($fo)
{
    global $TRINITY20;
    $blacklist = file_exists($TRINITY20['nameblacklist']) && is_array(unserialize(file_get_contents($TRINITY20['nameblacklist']))) ? unserialize(file_get_contents($TRINITY20['nameblacklist'])) : [];
    return !(isset($blacklist[$fo]) && $blacklist[$fo] == 1);
}

function get_server_load($windows = 0)
{
    if (class_exists("COM")) {
        $wmi = new COM("WinMgmts:\\\\.");
        $cpus = $wmi->InstancesOf("Win32_Processor");
        $i = 1;
        // Use the while loop on PHP 4 and foreach on PHP 5
        //while ($cpu = $cpus->Next()) {
        foreach ($cpus as $cpu) {
            $cpu_stats = 0;
            $cpu_stats += $cpu->LoadPercentage;
            $i++;
        }
        return round($cpu_stats / 2); // remove /2 for single processor systems
    }
}

function get_cache_config_data($the_names, $the_colors, $the_images)
{
    $configfile = '';
    $the_names = str_replace(',', ",\n", trim($the_names, ','));
    $the_colors = str_replace(',', ",\n", trim($the_colors, ','));
    $the_images = str_replace(',', ",\n", trim($the_images, ','));
    $configfile .= "\n\n\n" . '$class_names = array(
  ' . $the_names . '								
  );';
    // adding class colors like in user_functions
    $configfile .= "\n\n\n" . '$class_colors = array( 
  ' . $the_colors . '								
  );';
    // adding class pics like in user_functions
    $configfile .= "\n\n\n" . '$class_images = array(
  ' . $the_images . '										
  );';
    return $configfile;
}

function topicmods($id, $utopics, $read = false)
{
    global $TRINITY20;
    $file = $TRINITY20['cache'] . "/topicsmods.txt";
    $topics = file_exists($file) ? unserialize(file_get_contents($file)) : [];
    if (!$read) {
        $topics[$id] = $utopics;
        return (bool)file_put_contents($file, serialize($topics));
    }

    if (array_key_exists($id, $topics)) {
        return $topics[(int)$id];
    } else {
        return 0;
    }
}

function forummods($forced = false)
{
    global $TRINITY20;
    $file = $TRINITY20['cache'] . "/forummods.txt";
    if (!file_exists($file) || $forced == true) {
        ($q = sql_query("SELECT id,username,forums_mod FROM users WHERE forum_mod = 'yes'")) || sqlerr(__FILE__, __LINE__);
        while ($a = $q->fetch_assoc()) {
            $users[] = $a;
        }
        $forums = [];
        foreach ($users as $user) {
            $reg = "([0-9]+)";
            preg_match_all($reg, $user["forums_mod"], $fids);
            foreach ($fids[0] as $fid) {
                if (!array_key_exists($fid, $forums)) {
                    $forums[$fid] = [];
                }
                $forums[$fid][] = [$user["id"], $user["username"]];
            }
        }
        file_put_contents($file, serialize($forums));
    }
    if ($forced == false) {
        return unserialize(file_get_contents($file));
    }
}

/** end functions **/
?>
