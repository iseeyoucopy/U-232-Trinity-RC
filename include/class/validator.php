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
function validator($context)
{
    global $CURUSER;
    $timestamp = time();
    $hash = hash_hmac("sha1", $CURUSER['secret'], $context . $timestamp);
    return substr($hash, 0, 20) . dechex($timestamp);
}
function validatorForm($context)
{
    return "<input type=\"hidden\" name=\"validator\" value=\"" . validator($context) . "\"/>";
}
function validate($validator, $context, $seconds = 0)
{
    global $CURUSER;
    $timestamp = hexdec(substr($validator, 20));
    if ($seconds && time() > $timestamp + $seconds) {
        return false;
    }
    $hash = substr(hash_hmac("sha1", $CURUSER['secret'], $context . $timestamp) , 0, 20);
    return !(substr($validator, 0, 20) !== $hash);
}
?>
