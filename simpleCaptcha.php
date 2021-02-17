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
// This is the handler for captcha image requests
// The captcha ID is placed in the session, so session vars are required for this plug-in
session_start();
$numImages = '';
// -------------------- EDIT THESE ----------------- //
$images = [
    'house' => 'captchaImages/01.png',
    'key' => 'captchaImages/04.png',
    'flag' => 'captchaImages/06.png',
    'clock' => 'captchaImages/15.png',
    'bug' => 'captchaImages/16.png',
    'pen' => 'captchaImages/19.png',
    'light bulb' => 'captchaImages/21.png',
    'musical note' => 'captchaImages/40.png',
    'heart' => 'captchaImages/43.png',
    'world' => 'captchaImages/99.png',
];
// ------------------- STOP EDITING ---------------- //
$_SESSION['simpleCaptchaAnswer'] = null;
$_SESSION['simpleCaptchaTimestamp'] = time();
$SALT = "o^Gj".$_SESSION['simpleCaptchaTimestamp']."7%8W";
$resp = [];
header("Content-Type: application/json");
if (!isset($images) || !is_array($images) || count($images) < 3) {
    $resp['error'] = "There aren\'t enough images!";
    echo json_encode($resp, JSON_THROW_ON_ERROR);
    exit;
}
if (isset($_POST['numImages']) && strlen($_POST['numImages']) > 0) {
    $numImages = (int)$_POST['numImages'];
} elseif (isset($_GET['numImages']) && strlen($_GET['numImages']) > 0) {
    $numImages = (int)$_GET['numImages'];
}
$numImages = ($numImages > 0) ? $numImages : 5;
$size = count($images);
$num = min([
    $size,
    $numImages,
]);
$keys = array_keys($images);
$used = [];
mt_srand(((float)microtime() * 587) / 33);
for ($i = 0; $i < $num; ++$i) {
    $r = random_int(0, $size - 1);
    while (in_array($keys[$r], $used)) {
        $r = random_int(0, $size - 1);
    }
    $used[] = $keys[$r];
}
$selectText = $used[random_int(0, $num - 1)];
$_SESSION['simpleCaptchaAnswer'] = sha1($selectText.$SALT);
$resp['text'] = ''.$selectText;
$resp['images'] = [];
shuffle($used);
for ($i = 0, $iMax = count($used); $i < $iMax; ++$i) {
    $resp['images'][] = [
        'hash' => sha1($used[$i].$SALT),
        'file' => $images[$used[$i]],
    ];
}
echo json_encode($resp, JSON_THROW_ON_ERROR);
exit;
?>
