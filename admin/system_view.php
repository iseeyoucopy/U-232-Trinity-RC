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
if (!defined('IN_TRINITY20_ADMIN')) {
    $htmlout = '';
    $htmlout .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $htmlout;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(CLASS_DIR.'class_check.php');
class_check(UC_MAX, true, true);
$lang = array_merge($lang, load_language('ad_systemview'));
$htmlout = '';
if (isset($_GET['phpinfo']) && $_GET['phpinfo']) {
    @ob_start();
    phpinfo();
    $parsed = @ob_get_contents();
    @ob_end_clean();
    preg_match("#<body>(.*)</body>#is", $parsed, $match1);
    $php_body = $match1[1];
    // PREVENT WRAP: Most cookies
    // PREVENT WRAP: Very long string cookies
    $php_body = str_replace(["; ", "%3B"], [";<br>", "<br>"], $php_body);
    // PREVENT WRAP: Serialized array string cookies
    // PREVENT WRAP: LS_COLORS env
    $php_body = str_replace([";i:", ":*."], [";<br>i:", "<br>:*."], $php_body);
    // PREVENT WRAP: PATH env
    // PREVENT WRAP: Cookie %2C split
    $php_body = str_replace(["bin:/", "%2C"], ["bin<br>:/", "%2C<br>"], $php_body);
    // PREVENT WRAP: Cookie , split
    $php_body = preg_replace("#,(\d+),#", ",<br>\\1,", $php_body);
    $php_style = "<style type='text/css'>
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: left; }
.center th { text-align: center; }
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold;}
.h {background-color: #9999cc; font-weight: bold;}
.v {background-color: #cccccc; white-space: normal;}
</style>\n";
    $html = $php_style.$php_body;
    echo $html;
    stdfoot();
    exit();
}
$html = [];
function sql_get_version()
{
    $query = sql_query("SELECT VERSION() AS version");
    if (!$row = $query->fetch_assoc()) {
        unset($row);
        $query = sql_query("SHOW VARIABLES LIKE 'version'");
        $row = $query->fetch_row();
        $row['version'] = $row[1];
    }
    $true_version = $row['version'];
    $tmp = explode('.', preg_replace("#[^\d\.]#", "\\1", $row['version']));
    $mysql_version = sprintf('%d%02d%02d', $tmp[0], $tmp[1], $tmp[2]);
    return $mysql_version." (".$true_version.")";
}

$php_version = phpversion()." (".@php_sapi_name().") ( <a href='{$TRINITY20['baseurl']}/staffpanel.php?tool=system_view&amp;action=system_view&amp;phpinfo=1'>{$lang['system_phpinfo']}</a> )";
$server_software = php_uname();
// print $php_version ." ".$server_software;
$load_limit = "--";
$server_load_found = 0;
$using_cache = 0;
$avp = @sql_query("SELECT value_s FROM avps WHERE arg = 'loadlimit'");
if (false !== $row = $avp->fetch_assoc()) {
    $loadinfo = explode("-", $row['value_s']);
    if ((int)$loadinfo[1] > (time() - 20)) {
        $server_load_found = 1;
        $using_cache = 1;
        $load_limit = $loadinfo[0];
    }
}
if (!$server_load_found) {
    if (@file_exists('/proc/loadavg')) {
        if ($fh = @fopen('/proc/loadavg', 'r')) {
            $data = @fread($fh, 6);
            @fclose($fh);
            $load_avg = explode(" ", $data);
            $load_limit = trim($load_avg[0]);
        }
    } elseif (strpos(strtolower(PHP_OS), 'win') !== false) {
        $serverstats = @shell_exec("typeperf \"Processor(_Total)\% Processor Time\" -sc 1");
        if ($serverstats) {
            $server_reply = explode("\n", str_replace("\r", "", $serverstats));
            $serverstats = array_slice($server_reply, 2, 1);
            $statline = explode(",", str_replace('"', '', $serverstats[0]));
            $load_limit = round($statline[1], 4);
        }
    } elseif ($serverstats = @exec("uptime")) {
        preg_match("/(?:averages)?\: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $serverstats, $load);
        $load_limit = $load[1];
    }
    if ($load_limit) {
        @sql_query("UPDATE avps SET value_s = '".$load_limit."-".time()."' WHERE arg = 'loadlimit'");
    }
}
$total_memory = $avail_memory = "--";
if (strpos(strtolower(PHP_OS), 'win') !== false) {
    $mem = @shell_exec('systeminfo');
    if ($mem) {
        $server_reply = explode("\n", str_replace("\r", "", $mem));
        if (count($server_reply) > 0) {
            foreach ($server_reply as $info) {
                if (strpos($info, "Total Physical Memory") !== false) {
                    $total_memory = trim(str_replace(":", "", strrchr($info, ":")));
                }
                if (strpos($info, "Available Physical Memory") !== false) {
                    $avail_memory = trim(str_replace(":", "", strrchr($info, ":")));
                }
            }
        }
    }
} else {
    $mem = @shell_exec("free -m");
    $server_reply = explode("\n", str_replace("\r", "", $mem));
    $mem = array_slice($server_reply, 1, 1);
    $mem = preg_split("#\s+#", $mem[0]);
    $total_memory = $mem[1].' MB';
    $avail_memory = $mem[3].' MB';
}
$disabled_functions = @ini_get('disable_functions') ? str_replace(",", ", ", @ini_get('disable_functions')) : "<i>{$lang['system_noinf']}</i>";
if (strpos(strtolower(PHP_OS), 'win') !== false) {
    $tasks = @shell_exec("tasklist");
    $tasks = str_replace(" ", " ", $tasks);
} else {
    $tasks = @shell_exec("top -b -n 1");
    $tasks = str_replace(" ", " ", $tasks);
}
$tasks = $tasks === '' ? "<i>{$lang['system_unable']}</i>" : "<pre>".$tasks."</pre>";
$load_limit = $load_limit." ({$lang['system_fromcache']}".($using_cache == 1 ? "<span style='color:green;font-weight:bold;'>{$lang['system_true']})</span>" : "<span style='color:red;font-weight:bold;'>{$lang['system_false']})</span>");
$html[] = [
    $lang['system_mysql'],
    sql_get_version(),
];
$html[] = [
    $lang['system_php'],
    $php_version,
];
$html[] = [
    $lang['system_safe'],
    @ini_get('safe_mode') == 1 ? "<span style='color:red;font-weight:bold;'>{$lang['system_on']}</span>" : "<span style='color:green;font-weight:bold;'>{$lang['system_off']}</span>",
];
$html[] = [
    $lang['system_disabled'],
    $disabled_functions,
];
$html[] = [
    $lang['system_server_soft'],
    $server_software,
];
$html[] = [
    $lang['system_server_load'],
    $load_limit,
];
$html[] = [
    $lang['system_server_memory'],
    $total_memory,
];
$html[] = [
    $lang['system_server_avail'],
    $avail_memory,
];
$html[] = [
    $lang['system_sys_proc'],
    $tasks,
];
$htmlout .= '<table>';
foreach ($html as $key => $value) {
    $htmlout .= '<tr><td>'.$value[0].'</td><td>'.$value[1].'</td></tr>';
}
$htmlout .= '</table>';
echo stdhead($lang['system_stdhead']).$htmlout.stdfoot();
?>
