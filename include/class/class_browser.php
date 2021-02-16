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
//== Get browser by ruudrp
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";
    //First get the platform?
    if (false !== stripos($u_agent, "linux")) {
        $platform = 'Linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'Mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'Windows';
    } else {
        $platform = 'Unknown Platform';
    }
    // Next get the name of the useragent yes seperately and for good reason
    if (false !== stripos($u_agent, "MSIE") && false === stripos($u_agent, "Opera")) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (false !== stripos($u_agent, "Firefox")) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (false !== stripos($u_agent, "Chrome")) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (false !== stripos($u_agent, "Safari")) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (false !== stripos($u_agent, "Opera")) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (false !== stripos($u_agent, "Netscape")) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } else {
        $bname = "Unknown";
        $ub = "Unknown";
    }
    // finally get the correct version number
    $known = array(
        'Version',
        $ub,
        'other'
    );
    $pattern = '#(?<browser>' . implode('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if($u_agent != '') {
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
           
        }
        // see how many we have
        $i = is_countable($matches['browser']) ? count($matches['browser']) : 0;
        if ($i != 1) {
            $version = strripos($u_agent, "Version") < strripos($u_agent, (string) $ub) ? $matches['version'][0] : $matches['version'][1];
        } else {
            $version = $matches['version'][0];
        }
    }
    // check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
    return array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'pattern' => $pattern
    );
}
?>
