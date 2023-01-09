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
/*
  function deny_access($def) {
        global $TRINITY20;
    if (!defined($def)) {
        //== browsers and user agents that support xhtml
                if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml')) {
                        header('Content-type: application/xhtml+xml; charset='.$TRINITY20['char_set']);
                        $doctype = '<?xml version="1.0" encoding="'.$TRINITY20['char_set'].'" ?>'.
                                   '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" '.
                               '"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'.
                       '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$TRINITY20['language'].'">';
                }
                //== browsers and user agents that DO NOT support xhtml
                else {
                header('Content-type: text/html; charset='.$TRINITY20['char_set']);
                        $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" '.
                                   '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'.
                                       '<html xmlns="http://www.w3.org/1999/xhtml">';
                }
                echo $doctype.
                         '<head>'.
                         '<style type="text/css">div#error{font-size:33px;color:white;background-color:red;text-align:center;}</style>'.
                         '<title>ERROR</title>'.
                 '</head><body>'.
             '<div id="error">Incorrect access<br />You cannot access this file directly.</div>'.
             '</body></html>';
        
        exit();
    }
}


  deny_access('IN_TRINITY20_ADMIN');
*/
require_once(INCL_DIR.'user_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
//error_reporting(E_ALL);
$lang = array_merge($lang, load_language('ad_allagents'));
$HTMLOUT = '';
($res = sql_query("SELECT agent, peer_id FROM peers GROUP BY agent")) || sqlerr(__FILE__, __LINE__);
$HTMLOUT .= "<div class='card'>
    <div class='card-divider'>All agents</div>
    <div class='card-section'>
        <table>
            <thead>
	            <tr>
                    <td>{$lang['allagents_client']}</td>
                    <td>{$lang['allagents_peerid']}</td>
                </tr>
            </thead>";
while ($arr = $res->fetch_assoc()) {
    $HTMLOUT .= "<tbody>
        <tr>
            <td>".htmlspecialchars($arr["agent"])."</td>
            <td>".htmlspecialchars($arr["peer_id"])."</td>
        </tr>
    </tbody>";
}
    $HTMLOUT .= "</table>
    </div>
</div>";
echo stdhead($lang['allagents_allclients']).$HTMLOUT.stdfoot();
?>
