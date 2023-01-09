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
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>Error!</title>
</head>
<body>
<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br />You cannot access this file directly.</div>
</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_class_config'));
if (!in_array($CURUSER['id'], $TRINITY20['allowed_staff']['id'])) {
    stderr($lang['classcfg_error'], $lang['classcfg_denied']);
}
//get the config from db - stoner/pdq
($pconf = sql_query('SELECT * FROM class_config ORDER BY value ASC ')) || sqlerr(__FILE__, __LINE__);
while ($ac = $pconf->fetch_assoc()) {
    $class_config[$ac['name']]['value'] = $ac['value'];
    $class_config[$ac['name']]['classname'] = $ac['classname'];
    $class_config[$ac['name']]['classcolor'] = $ac['classcolor'];
    $class_config[$ac['name']]['classpic'] = $ac['classpic'];
}
$possible_modes = [
    'add',
    'edit',
    'remove',
    '',
];
$mode = (isset($_GET['mode']) ? htmlspecialchars($_GET['mode']) : '');
if (!in_array($mode, $possible_modes)) {
    stderr($lang['classcfg_error'], $lang['classcfg_error1']);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($mode == 'edit') {
        foreach ($class_config as $c_name => $value) {
            // handing from database
            $c_value = $value['value']; // $key is like UC_USER etc....
            $c_classname = htmlspecialchars($value['classname']);
            $c_classcolor = $value['classcolor'];
            $c_classcolor = str_replace("#", "", "$c_classcolor");
            $c_classpic = $value['classpic'];
            // handling from posting of contents
            $post_data = $_POST[$c_name]; //    0=> value,1=>classname,2=>classcolor,3=>classpic
            $value = $post_data[0];
            $classname = htmlspecialchars($post_data[1]);
            $classcolor = $post_data[2];
            $classcolor = str_replace("#", "", "$classcolor");
            $classpic = $post_data[3];
            if (isset($_POST[$c_name][0]) && (($value != $c_value) || ($classname != $c_classname) || ($classcolor !== $c_classcolor) || ($classpic != $c_classpic))) {
                $update[$c_name] = '('.sqlesc($c_name).','.sqlesc(is_array($value) ? implode('|',
                        $value) : $value).','.sqlesc(is_array($classname) ? implode('|',
                        $classname) : $classname).','.sqlesc(is_array($classcolor) ? implode('|',
                        $classcolor) : $classcolor).','.sqlesc(is_array($classpic) ? implode('|', $classpic) : $classpic).')';
            }
        }
        $cache->delete('is_staffs_');
        if (sql_query('INSERT INTO class_config(name,value,classname,classcolor,classpic) VALUES '.implode(',',
                $update).' ON DUPLICATE KEY update value=values(value),classname=values(classname),classcolor=values(classcolor),classpic=values(classpic)')) { // need to change strut
            $t = 'define(';
            $configfile = "<".$lang['classcfg_file_created'].date('M d Y H:i:s').$lang['classcfg_user_cfg'];
            ($res = sql_query("SELECT * from class_config ORDER BY value  ASC")) || sqlerr(__FILE__, __LINE__);
            $the_names = $the_colors = $the_images = '';
            while ($arr = $res->fetch_assoc()) {
                $configfile .= "".$t."'$arr[name]', $arr[value]);\n";
            }
            unset($arr);
            ($res = sql_query("SELECT * from class_config WHERE name NOT IN ('UC_MIN','UC_MAX','UC_STAFF') ORDER BY value  ASC")) || sqlerr(__FILE__,
                __LINE__);
            $the_names = $the_colors = $the_images = '';
            while ($arr = $res->fetch_assoc()) {
                $the_names .= "$arr[name] => '$arr[classname]',";
                $the_colors .= "$arr[name] => '$arr[classcolor]',";
                $the_images .= "$arr[name] => ".'$TRINITY20['."'pic_base_url'".']'.".'class/$arr[classpic]',";
            }
            $configfile .= get_cache_config_data($the_names, $the_colors, $the_images);
            $configfile .= '


?>';
            $filenum = fopen('./cache/class_config.php', 'w');
            ftruncate($filenum, 0);
            fwrite($filenum, $configfile);
            fclose($filenum);
            stderr($lang['classcfg_success'], $lang['classcfg_success_save']);
        } else {
            stderr($lang['classcfg_error'], $lang['classcfg_error_query1']);
        }
        exit;
    }
    //ADD CLASS
    if ($mode == 'add' && (!empty($_POST['name']) && !empty($_POST['value']) && !empty($_POST['cname']) && !empty($_POST['color']))) {
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : stderr($lang['classcfg_error'], $lang['classcfg_error_class_name']);
        $value = isset($_POST['value']) ? (int)$_POST['value'] : stderr($lang['classcfg_error'], $lang['classcfg_error_class_value']);
        $r_name = isset($_POST['cname']) ? htmlspecialchars($_POST['cname']) : stderr($lang['classcfg_error'], $lang['classcfg_error_class_value']);
        $color = isset($_POST['color']) ? htmlspecialchars($_POST['color']) : '';
        $color = str_replace("#", "", "$color");
        $pic = isset($_POST['pic']) ? htmlspecialchars($_POST['pic']) : '';
        //FIND UC_MAX;
        //FROM HERE
        // CAN REMOVE THE QUERY I THINK.   $old_max = UC_MAX;  OR EVEN  $new_max = UC_MAX +1;  << BOTH WORK
        ($res = sql_query("SELECT * from class_config WHERE name IN ('UC_MAX') ")) || sqlerr(__FILE__, __LINE__);
        while ($arr = $res->fetch_array(MYSQLI_BOTH)) {
            $old_max = $arr['value'];
            $new_max = $arr['value'] + 1;
            sql_query("UPDATE class_config SET value = '$new_max' WHERE name = 'UC_MAX'") || sqlerr(__FILE__, __LINE__);
        }
        // TO HERE
        //FIND AND UPDATE UC_STAFF
        //FROM HERE
        //SAME AS ABOVE $new_staff = UC_STAFF +1; THEN UPDATE DB WITH THAT
        ($res = sql_query("SELECT * from class_config WHERE name = 'UC_STAFF'")) || sqlerr(__FILE__, __LINE__);
        while ($arr = $res->fetch_array(MYSQLI_BOTH)) {
            if ($value <= $arr['value']) {
                $new_staff = $arr['value'] + 1;
                sql_query("UPDATE class_config SET value = '$new_staff' WHERE name = 'UC_STAFF'") || sqlerr(__FILE__, __LINE__);
            }
        }
        //TO HERE
        //UPDATE ALL CLASSES TO ONE HIGHER, BUT NOT SECURITY SHITZ
        $i = $old_max;
        while ($i >= $value) {
            sql_query("UPDATE class_config SET value = value +1 where value = $i AND name NOT IN ('UC_MIN', 'UC_STAFF', 'UC_MAX')") || sqlerr(__FILE__,
                __LINE__);

            $i--;
        }
        //IF ADDING NEW CLASS ABOVE UC_MAXX CLASS WE NEED TO PUT CURRENT MAX CLASS USERS INTO IT
        if ($value > UC_MAX) {
            sql_query("UPDATE users SET class = class +1 where class = $old_max") || sqlerr(__FILE__, __LINE__);
            ($result = sql_query("SELECT id, class FROM users")) || sqlerr(__FILE__, __LINE__);


            ($result = sql_query("SELECT id, class FROM users")) || sqlerr(__FILE__, __LINE__);
            while ($row = $result->fetch_assoc()) {
                $row1 = [];
                $row1[] = $row;
                foreach ($row1 as $row2) {
                    $cache->update_row($keys['my_userid'].$row2['id'], ['class' => $row2['class']], $TRINITY20['expires']['curuser']);
                    $cache->update_row($keys['user'].$row2['id'], ['class' => $row2['class']], $TRINITY20['expires']['user_cache']);
                }
            }
        }
        //END IFF SETTING ABOVE UC_MAX
        //UPDATE ALL USERS TO ONE HIGHER IN REVERSE ORDER
        else {
            $i = $old_max;
            while ($i >= $value) {
                sql_query("UPDATE users SET class = class +1 where class = $i") || sqlerr(__FILE__, __LINE__);
                sql_query("UPDATE staffpanel SET av_class = av_class +1 where av_class = $i") || sqlerr(__FILE__, __LINE__);
                $i--;
            }


            ($result = sql_query("SELECT id, class FROM users")) || sqlerr(__FILE__, __LINE__);
            while ($row = $result->fetch_assoc()) {
                $row1 = [];
                $row1[] = $row;
                foreach ($row1 as $row2) {
                    $cache->update_row($keys['my_userid'].$row2['id'], ['class' => $row2['class']], $TRINITY20['expires']['curuser']);
                    $cache->update_row($keys['user'].$row2['id'], ['class' => $row2['class']], $TRINITY20['expires']['user_cache']);
                }
            }
        }
        if (sql_query("INSERT INTO class_config (name, value,classname,classcolor,classpic) VALUES(".sqlesc($name).",".sqlesc($value).",".sqlesc($r_name).",".sqlesc($color).",".sqlesc($pic).")")) {
            $t = 'define(';
            $configfile = "<".$lang['classcfg_file_created'].date('M d Y H:i:s').$lang['classcfg_user_cfg'];
            ($res = sql_query("SELECT * from class_config ORDER BY value  ASC")) || sqlerr(__FILE__, __LINE__);
            $the_names = $the_colors = $the_images = '';
            while ($arr = $res->fetch_assoc()) {
                $configfile .= "".$t."'$arr[name]', $arr[value]);\n";
            }
            unset($arr);
            ($res = sql_query("SELECT * from class_config WHERE name NOT IN ('UC_MIN','UC_MAX','UC_STAFF') ORDER BY value  ASC")) || sqlerr(__FILE__,
                __LINE__);
            $the_names = $the_colors = $the_images = '';
            while ($arr = $res->fetch_assoc()) {
                $the_names .= "$arr[name] => '$arr[classname]',";
                $the_colors .= "$arr[name] => '$arr[classcolor]',";
                $the_images .= "$arr[name] => ".'$TRINITY20['."'pic_base_url'".']'.".'class/$arr[classpic]',";
            }
            $configfile .= get_cache_config_data($the_names, $the_colors, $the_images);
            $configfile .= '


?>';
            $filenum = fopen('./cache/class_config.php', 'w');
            ftruncate($filenum, 0);
            fwrite($filenum, $configfile);
            fclose($filenum);
            $cache->delete('is_staffs_');
            stderr($lang['classcfg_success'], $lang['classcfg_success_save']);
        } else {
            stderr($lang['classcfg_error'], $lang['classcfg_error_query2']);
        }
        exit;
    }
    // remove
    if ($mode == 'remove') {
        $name = isset($_POST['remove']) ? htmlspecialchars($_POST['remove']) : stderr($lang['classcfg_error'], $lang['classcfg_error_required']);
        ($res = sql_query("SELECT value from class_config WHERE name = '$name' ")) || sqlerr(__FILE__, __LINE__);
        while ($arr = $res->fetch_array(MYSQLI_BOTH)) {
            $value = $arr['value'];
        }
        //FIND UC_MAX;
        ($res = sql_query("SELECT * from class_config WHERE name IN ('UC_MAX') ")) || sqlerr(__FILE__, __LINE__);
        while ($arr = $res->fetch_array(MYSQLI_BOTH)) {
            $old_max = $arr['value'];
            $new_max = $arr['value'] - 1;
            sql_query("UPDATE class_config SET value = '$new_max' WHERE name = 'UC_MAX'") || sqlerr(__FILE__, __LINE__);
        }
        //FIND AND UPDATE UC_STAFF
        ($res = sql_query("SELECT * from class_config WHERE name = 'UC_STAFF'")) || sqlerr(__FILE__, __LINE__);
        while ($arr = $res->fetch_array(MYSQLI_BOTH)) {
            if ($value <= $arr['value']) {
                $new_staff = $arr['value'] - 1;
                sql_query("UPDATE class_config SET value = '$new_staff' WHERE name = 'UC_STAFF'") || sqlerr(__FILE__, __LINE__);
            }
        }
        //UPDATE ALL CLASSES TO ONE LOWER, BUT NOT SECURITY SHITZ
        $i = $value;
        while ($i <= $old_max) {
            sql_query("UPDATE class_config SET value = value -1 where value = $i AND name NOT IN ('UC_MIN', 'UC_STAFF', 'UC_MAX')") || sqlerr(__FILE__,
                __LINE__);
            $i++;
        }
        //UPDATE ALL USERS TO ONE LOWER IN REVERSE ORDER
        $i = $value;
        while ($i <= $old_max) {
            sql_query("UPDATE users SET class = class -1 where class = $i") || sqlerr(__FILE__, __LINE__);
            sql_query("UPDATE staffpanel SET av_class = av_class -1 where av_class = $i") || sqlerr(__FILE__, __LINE__);
            $cache->delete('is_staffs_');
            $i++;
        }


        ($result = sql_query("SELECT id, class FROM users")) || sqlerr(__FILE__, __LINE__);
        while ($row = $result->fetch_assoc()) {
            $row1 = [];
            $row1[] = $row;
            foreach ($row1 as $row2) {
                $cache->update_row($keys['my_userid'].$row2['id'], ['class' => $row2['class']], $TRINITY20['expires']['curuser']);
                $cache->update_row($keys['user'].$row2['id'], ['class' => $row2['class']], $TRINITY20['expires']['user_cache']);
            }
        }
        if (sql_query("DELETE FROM class_config WHERE name = ".sqlesc($name)."")) {
            $t = 'define(';
            $configfile = "<".$lang['classcfg_file_created'].date('M d Y H:i:s').$lang['classcfg_user_cfg'];
            ($res = sql_query("SELECT * from class_config ORDER BY value  ASC")) || sqlerr(__FILE__, __LINE__);
            $the_names = $the_colors = $the_images = '';
            while ($arr = $res->fetch_assoc()) {
                $configfile .= "".$t."'$arr[name]', $arr[value]);\n";
            }
            unset($arr);
            ($res = sql_query("SELECT * from class_config WHERE name NOT IN ('UC_MIN','UC_MAX','UC_STAFF') ORDER BY value  ASC")) || sqlerr(__FILE__,
                __LINE__);
            $the_names = $the_colors = $the_images = '';
            while ($arr = $res->fetch_assoc()) {
                $the_names .= "$arr[name] => '$arr[classname]',";
                $the_colors .= "$arr[name] => '$arr[classcolor]',";
                $the_images .= "$arr[name] => ".'$TRINITY20['."'pic_base_url'".']'.".'class/$arr[classpic]',";
            }
            $configfile .= get_cache_config_data($the_names, $the_colors, $the_images);
            $configfile .= '


?>';
            $filenum = fopen('./cache/class_config.php', 'w');
            ftruncate($filenum, 0);
            fwrite($filenum, $configfile);
            fclose($filenum);
            stderr($lang['classcfg_success'], $lang['classcfg_success_reset']);
        } else {
            stderr($lang['classcfg_error'], $lang['classcfg_error_query2']);
        }
        exit;
    }
}

$HTMLOUT .= "<div class='row'><div class='col-md-12'>
<h3>{$lang['classcfg_class_settings']}</h3>
<form name='edit' action='staffpanel.php?tool=class_config&amp;mode=edit' method='post'>
<table class='table table-bordered'><tr>
<td>{$lang['classcfg_class_name']}</td>
<td>{$lang['classcfg_class_value']}</td>
<td>{$lang['classcfg_class_refname']}</td>
<td>{$lang['classcfg_class_color']}</td>
<td>{$lang['classcfg_class_pic']}</td>
<td>{$lang['classcfg_class_del']}</td>
</tr>";
($res = sql_query("SELECT * from class_config WHERE name NOT IN ('UC_MIN','UC_MAX','UC_STAFF') ORDER BY value  ASC")) || sqlerr(__FILE__, __LINE__);
while ($arr = $res->fetch_assoc()) {
    $HTMLOUT .= "
<tr>
<td>".htmlspecialchars($arr['name'])."</td>
<td><input type='text' class='form-control' name='".htmlspecialchars($arr['name'])."[]' size='2' value='".(int)$arr['value']." 'readonly='readonly' /></td>
<td><input type='text' class='form-control' name='".htmlspecialchars($arr['name'])."[]' size='8' value='".htmlspecialchars($arr['classname'])."' /></td>
<td><input type='text' class='form-control' name='".htmlspecialchars($arr['name'])."[]' size='8' value='#".htmlspecialchars($arr['classcolor'])."' /></td>
<td><input type='text' class='form-control' name='".htmlspecialchars($arr['name'])."[]' size='8' value='".htmlspecialchars($arr['classpic'])."' /></td>
<td><form name='remove' action='staffpanel.php?tool=class_config&amp;mode=remove' method='post'><input type='hidden' name='remove' value='".htmlspecialchars($arr['name'])."' /><input class='btn btn-default' type='submit' value='{$lang['classcfg_class_remove']}' /></form></td>
</tr>";
}
$HTMLOUT .= "</table><br /><br /> ";


$HTMLOUT .= "<h3>{$lang['classcfg_class_security']}</h3>

<table class='table table-bordered'><tr>
<td>{$lang['classcfg_class_name']}</td>
<td>{$lang['classcfg_class_value']}</td></tr>";

($res1 = sql_query("SELECT * from class_config WHERE name IN ('UC_MIN','UC_MAX','UC_STAFF') ORDER BY value  ASC")) || sqlerr(__FILE__, __LINE__);
while ($arr1 = $res1->fetch_assoc()) {
    $HTMLOUT .= "
<tr>
<td>".htmlspecialchars($arr1['name'])."</td>
<td><input type='text' class='form-control' name='".htmlspecialchars($arr1['name'])."[]' size='2' value='".(int)$arr1['value']."' /></td>
<td><form name='remove' action='staffpanel.php?tool=class_config&amp;mode=remove' method='post'></form></td>
</tr>";
}
$HTMLOUT .= "<tr><td colspan='7' class='table' align='center'><input class='btn btn-default' type='submit' value='{$lang['classcfg_class_apply']}' /></td></tr></table></form>";


$HTMLOUT .= "<h3>{$lang['classcfg_class_add']}</h3>
<form name='add' action='staffpanel.php?tool=class_config&amp;mode=add' method='post'>
<table class='table table-bordered'><tr>
<th>{$lang['classcfg_class_name']}</th>
<th>{$lang['classcfg_class_level']}</th>
<th>{$lang['classcfg_class_refname']}</th>
<th>{$lang['classcfg_class_color']}</th>
<th>{$lang['classcfg_class_pic']}</th>
</tr>
<tr>
				<td><input type='text' class='form-control' name='name' size='20' value='' /></td>
				<td><input type='text' class='form-control' name='value' size='20' value='' /></td>
				<td><input type='text' class='form-control' name='cname' size='20' value='' /></td>
				<td><input type='text' class='form-control' name='color' size='20' value='#ff0000' /></td>
				<td><input type='text' class='form-control' name='pic' size='20' value='' /></td>
				</tr>
<tr><td colspan='5' class='table' align='center'><input class='btn btn-default' type='submit' value='{$lang['classcfg_add_new']}' /></td></tr>
</table></form></div></div>";
echo stdhead($lang['classcfg_stdhead']).$HTMLOUT.stdfoot();
?>
