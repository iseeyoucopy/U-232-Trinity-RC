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
$_settings = $_SERVER["DOCUMENT_ROOT"]."/avatar/settings/";
$op[1] = 'Posts';
$op[2] = 'Stats';
$op[3] = 'Irc';
$op[4] = 'Reputation';
$op[5] = 'Country';
$op[6] = 'Comments';
$op[7] = 'Browser';
$op[8] = 'Profile hits';
$op[9] = 'Online time';
$var['line1']['value'] = $var['line2']['value'] = $var['line3']['value'] = 0;
if (isset($_POST["firstrun"]) && htmlspecialchars($_POST["firstrun"]) == 1) {
    $user = isset($_POST["user"]) ? strtolower(htmlspecialchars($_POST["user"])) : "";
    if (file_exists($_settings.$user.".set")) {
        $var = unserialize(file_get_contents($_settings.$user.".set"));
    }
}
$drp['op1'][] = $drp['op2'][] = $drp['op3'][] = "<option value=\"0\">----</option>";
$opCount = count($op);
for ($i = 1; $i <= $opCount; $i++) {
    if ($_POST['drp2'] == $i || $_POST['drp3'] == $i || $var['line2']['value'] == $i || $var['line3']['value'] == $i) {
        continue;
    }
    $drp['op1'][] = "<option value=\"".$i."\" ".($_POST['drp1'] == $i || $var['line1']['value'] == $i ? "selected=\"selected\"" : "").">".$op[$i]."</option>";
}
$opCount = count($op);
for ($i = 1; $i <= $opCount; $i++) {
    if ($_POST['drp1'] == $i || $_POST['drp3'] == $i || $var['line1']['value'] == $i || $var['line3']['value'] == $i) {
        continue;
    }
    $drp['op2'][] = "<option value=\"".$i."\" ".($_POST['drp2'] == $i || $var['line2']['value'] == $i ? "selected=\"selected\"" : "").">".$op[$i]."</option>";
}
$opCount = count($op);
for ($i = 1; $i <= $opCount; $i++) {
    if ($_POST['drp1'] == $i || $_POST['drp2'] == $i || $var['line2']['value'] == $i || $var['line1']['value'] == $i) {
        continue;
    }
    $drp['op3'][] = "<option value=\"".$i."\" ".($_POST['drp3'] == $i || $var['line3']['value'] == $i ? "selected=\"selected\"" : "").">".$op[$i]."</option>";
}
foreach ($drp as $key => $ops) {
    $temp[$key] = implode('', $ops);
}
for ($i = 1; $i <= 3; $i++) {
    $temp['line'.$i] = $var['line'.$i]['title'] ?? '';
}
$temp['showuser'] = isset($var['showuser']) && $var['showuser'] == 1 ? 1 : 0;
print (json_encode($temp));
?>
