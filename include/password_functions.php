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
        $num = random_int(33, 126);
        if ($num == '92') {
            $num = 93;
        }
        $salt .= chr($num);
    }
    return $salt;
}

function make_passhash_login_key($x, $y)
{
    return password_hash($x . $y, PASSWORD_BCRYPT);
}

function make_passhash($x, $y, $z)
{

    return password_hash($x . $y . $z, PASSWORD_ARGON2ID, ['memory_cost' => 1 << 12, 'time_cost' => 1]);
}

function make_pass_hash($salt, $md5_once_password)
{
    return md5(md5($salt) . $md5_once_password);
}

function HashIt($x, $y)
{
    return hash("sha3-512", $y . $x . $y);
}

function make_hash_log($x, $y)
{
    return hash("sha3-512", $x . $y);
}

function t_Hash($x, $y, $z)
{
    return hash("haval256,5", $x . $y . $z);
}

function h_store($x)
{
    return hash("haval256,5", "" . $x . "");

}

function h_cook($x, $y, $z)
{
    return hash("sha3-512", $x . $y . $z);
}

?>
