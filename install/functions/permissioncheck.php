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
function checkdir(&$dirs)
{
    foreach ($dirs as $dir => $x) {
        if (is_dir($dir)) {
            $fn = $dir.uniqid(time(), true).'.tmp';
            if (@file_put_contents($fn, '1')) {
                unlink($fn);
                $dirs[$dir] = 1;
            } else {
                $dirs[$dir] = 0;
            }
        } else {
            $dirs[$dir] = 0;
        }
    }
}

function permissioncheck()
{
    global $root;
    if (file_exists('step0.lock')) {
        header('Location: index.php?step=1');
    }
    $dirs = [
        $root.'dir_list/' => 0,
        $root.'imdb/' => 0,
        $root.'cache/' => 0,
        $root.'torrents/' => 0,
        $root.'uploads/' => 0,
        $root.'include/backup/' => 0,
        $root.'sqlerr_logs/' => 0,
        $root.'install/' => 0,
        $root.'install/extra/' => 0,
        $root.'include/' => 0,
    ];
    checkdir($dirs);
    $continue = true;
    $out = '<fieldset><legend>Directory check</legend>';
    $cmd = 'chmod 0777';
    foreach ($dirs as $dir => $state) {
        if (!$state) {
            $continue = false;
            $cmd .= ' '.$dir;
        }
        $out .= '<div class="'.($state ? 'readable' : 'notreadable').'">'.$dir.'</div>';
    }

    if (!$continue) {
        $out .= '<div class="info">It looks like you need to chmod some directories!<br/>all directories marked in red should be chmoded 0777<br/>'.
            '<label for="show-chmod" class="btn">Show me the CHMOD command</label><input type="checkbox" id="show-chmod">'.
            '<pre class="chmod-cmd">'.$cmd.'</pre>'.
            '<input type="button" value="Reload" onclick="window.location.reload()"/>'.
            '</div>';
    }
    $out .= '</fieldset>';


    $out .= '<fieldset><legend>Php Module check</legend>
    <div class="info">The following PHP modules are required for u232 if you want to use one of them for caching <br/>
    By default caching is set to memory do you will not need any of them to be installed<br>'.
            '<input type="button" value="Reload" onclick="window.location.reload()"/>'.
            '</div>';
    if (!extension_loaded('memcached')) {
        $out .= '<div class="notreadable">memcached</div>';
    } else {
        $out .= '<div class="readable">memcached</div>';
    }
    if (!extension_loaded('redis')) {
        $out .= '<div class="notreadable">redis</div>';
    } else {
        $out .= '<div class="readable">redis</div>';
    }
    if (!extension_loaded('apcu')) {
        $out .= '<div class="notreadable">APCu</div>';
    } else {
        $out .= '<div class="readable">APCu</div>';
    }
    $out .= '<legend>PHP required module check</legend>
    <div class="info">The following PHP modules are required for u232<br>'.
            '</div>';
    if (!extension_loaded('curl')) {
        $out .= '<div class="notreadable">cURL</div>';
    } else {
        $out .= '<div class="readable">cURL</div>';
    }
    if (!extension_loaded('igbinary')) {
        $out .= '<div class="notreadable">igbinary</div>';
    } else {
        $out .= '<div class="readable">igbinary</div>';
    }
    if (!extension_loaded('json')) {
        $out .= '<div class="notreadable">json</div>';
    } else {
        $out .= '<div class="readable">json</div>';
    }
    if (!extension_loaded('msgpack')) {
        $out .= '<div class="notreadable">msgpack</div>';
    } else {
        $out .= '<div class="readable">msgpack</div>';
    }
    if (!extension_loaded('mysqli')) {
        $out .= '<div class="notreadable">MySQLi</div>';
    } else {
        $out .= '<div class="readable">MySQLi</div>';
    }
    if (!extension_loaded('mbstring')) {
        $out .= '<div class="notreadable">mbstring</div>';
    } else {
        $out .= '<div class="readable">mbstring</div>';
    }
    if (!extension_loaded('gd')) {
        $out .= '<div class="notreadable">Image Processing and GD</div>';
    } else {
        $out .= '<div class="readable">Image Processing and GD</div>';
    }
    if (!extension_loaded('geoip')) {
        $out .= '<div class="notreadable">GEOIP</div>';
    } else {
        $out .= '<div class="readable">GEOIP</div>';
    }
    if (!extension_loaded('opcache')) {
        $out .= '<div class="notreadable">OPcache</div>';
    } else {
        $out .= '<div class="readable">OPcache</div>';
    }
    if (!extension_loaded('xml')) {
        $out .= '<div class="notreadable">XML</div>';
    } else {
        $out .= '<div class="readable">XML</div>';
    }
    if (!extension_loaded('zip')) {
        $out .= '<div class="notreadable">ZIP</div>';
    } else {
        $out .= '<div class="readable">ZIP</div>';
    }
    $out .= '<input type="button" value="Reload" onclick="window.location.reload()"/>'.'</fieldset>';
    if ($continue) {
        $out .= '<fieldset><div><input type="button" onclick="window.location.href=\'index.php?step=1\'" value="Next step" /></div></fieldset>';
        file_put_contents('step0.lock', '1');
    }
    return $out;
}

