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
function mksecret($len = 24)
{
    $salt = '';
    for ($i = 0; $i < $len; $i++) {
        $num = rand(33, 126);
        if ($num == '92') {
            $num = 93;
        }
        $salt.= chr($num);
    }
    return $salt;
}
function make_passhash_login_key($len = 64)
{
    $pass = mksecret($len);
    return password_hash($pass, PASSWORD_DEFAULT);
}
function make_passhash($password) {
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
}

function make_pass_hash($salt, $md5_once_password)
{
    return md5(md5($salt) . $md5_once_password);
}
?>
