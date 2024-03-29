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
//made by putyn @tbdev 06.11.2008
//==09 Edits
function happyHour($action)
{
    global $TRINITY20;
    //generate happy hour
    if ($action == "generate") {
        $nextDay = date("Y-m-d", TIME_NOW + 86400);
        $nextHoura = random_int(0, 2);
        $nextHourb = $nextHoura == 2 ? random_int(0, 3) : random_int(0, 9);
        $nextHour = $nextHoura . $nextHourb;
        $nextMina = random_int(0, 5);
        $nextMinb = random_int(0, 9);
        $nextMin = $nextMina . $nextMinb;
        return $nextDay . " " . $nextHour . ":" . $nextMin . "";
    }
    $file = $TRINITY20['happyhour'];
    $happy = unserialize(file_get_contents($file));
    $happyHour = strtotime($happy["time"]);
    $happyDate = $happyHour;
    $curDate = TIME_NOW;
    $nextDate = $happyHour + 3600;
    //action check
    if ($action == "check" && ($happyDate < $curDate && $nextDate >= $curDate)) {
        return true;
    }
    //action time left
    if ($action == "time") {
        $timeLeft = mkprettytime(($happyHour + 3600) - TIME_NOW);
        $timeLeft = explode(":", $timeLeft);
        return $timeLeft[0] . " min : " . $timeLeft[1] . " sec";
    }
    //this will set all torrent free or just one category
    if ($action == "todo") {
        $act = random_int(1, 2);
        if ($act == 1) {
            $todo = 255;
        } // this will mean that all the torrent are free
        elseif ($act == 2) {
            $todo = random_int(1, 14);
        } // only one cat will be free || remember to change the number of categories i have 14 but you may have more
        return $todo;
    }
    //this will generate the multiplier so every torrent downloaded in the happy hour will have upload multiplied but this
    if ($action == "multiplier") {
        //max value of the multiplier will be 5,5 || you could change it to a higher or a lower value
        return random_int(11, 55) / 10;
    }
}

function happyCheck($action, $id = null)
{
    global $TRINITY20;
    $file = $TRINITY20['happyhour'];
    $happy = unserialize(file_get_contents($file));
    $happycheck = $happy["catid"];
    if ($action == "check") {
        return $happycheck;
    }
    if ($action == "checkid" && (($happycheck == "255") || $happycheck == $id)) {
        return true;
    }
}

function happyFile($act)
{
    global $TRINITY20;
    $file = $TRINITY20['happyhour'];
    $happy = unserialize(file_get_contents($file));
    if ($act == "set") {
        $array_happy = [
            'time' => happyHour("generate"),
            'status' => '1',
            'catid' => happyHour("todo"),
        ];
    } elseif ($act == "reset") {
        $array_happy = [
            'time' => $happy["time"],
            'status' => '0',
            'catid' => $happy["catid"],
        ];
    }
    $array_happy = serialize($array_happy);
    $file = $TRINITY20['happyhour'];
    $file = fopen($file, 'w');
    ftruncate($file, 0);
    fwrite($file, $array_happy);
    fclose($file);
}

function happyLog($userid, $torrentid, $multi)
{
    $time = sqlesc(TIME_NOW);
    sql_query("INSERT INTO happylog (userid, torrentid,multi, date) VALUES(" . sqlesc($userid) . ", " . sqlesc($torrentid) . ", " . sqlesc($multi) . ", $time)") || sqlerr(__FILE__,
        __LINE__);
}

?>
