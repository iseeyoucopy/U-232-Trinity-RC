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
//== pdq Class Checker and Verify Staff
if (!defined('IN_TRINITY20_ADMIN')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once CLASS_DIR.'class_check.php';
class_check(UC_MAX);
$lang = array_merge($lang, load_language('ad_staff_config'));
function write_staffs2()
{
    global $lang;
    //==ids
    $t = '$TRINITY20';
    $iconfigfile = "<"."?php\n/**\n{$lang['staffcfg_file_created']}".date('M d Y H:i:s').".\n{$lang['staffcfg_mod_by']}\n**/\n";
    ($ri = sql_query("SELECT id, username, class FROM users WHERE class BETWEEN ".UC_STAFF." AND ".UC_MAX." ORDER BY id ASC")) || sqlerr(__file__,
        __line__);
    $iconfigfile .= "".$t."['allowed_staff']['id'] = array(";
    while ($ai = $ri->fetch_assoc()) {
        $ids[] = $ai['id'];
        $usernames[] = "'".$ai["username"]."' => 1";
    }
    $iconfigfile .= "".implode(",", $ids);
    $iconfigfile .= ");";
    $iconfigfile .= '
?>';
    $filenum = fopen('./cache/staff_settings.php', 'w');
    ftruncate($filenum, 0);
    fwrite($filenum, $iconfigfile);
    fclose($filenum);
    //==names
    $t = '$TRINITY20';
    $nconfigfile = "<"."?php\n/**\n{$lang['staffcfg_file_created']}".date('M d Y H:i:s').".\n{$lang['staffcfg_mod_by']}\n**/\n";
    $nconfigfile .= "".$t."['staff']['allowed'] = array(";
    $nconfigfile .= "".implode(",", $usernames);
    $nconfigfile .= ");";
    $nconfigfile .= '
?>';
    $filenum1 = fopen('./cache/staff_settings2.php', 'w');
    ftruncate($filenum1, 0);
    fwrite($filenum1, $nconfigfile);
    fclose($filenum1);
    stderr($lang['staffcfg_success'], $lang['staffcfg_updated']);
}

write_staffs2();
echo stdhead($lang['staffcfg_stdhead']).stdfoot();
?>
