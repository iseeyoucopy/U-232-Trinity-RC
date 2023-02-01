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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once INCL_DIR.'bbcode_functions.php';
require_once INCL_DIR.'user_functions.php';
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'));
if ($CURUSER['smile_until'] == '0') {
    stderr("Error", "you do not have access!");
}
$htmlout = '';
$htmlout = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
    <meta name='generator' content='U-232' />
	  <meta name='MSSmartTagsPreventParsing' content='TRUE' />
		<title>Custom Smilies</title>
    <link rel='stylesheet' href='./templates/{$CURUSER['stylesheet']}/{$CURUSER['stylesheet']}.css' type='text/css' />
    </head>
    <body>
    <script type='text/javascript'>
    function SmileIT(smile,form,text){
    window.opener.document.forms[form].elements[text].value = window.opener.document.forms[form].elements[text].value+' '+smile+' ';
    window.opener.document.forms[form].elements[text].focus();
    window.close();
    }
    </script>
    <table class='table'>";
$count = is_countable($customsmilies) ? count($customsmilies) : 0;
global $customsmilies;
foreach ($customsmilies as $code => $url) {
    if ($count % 3 == 0) {
        $htmlout .= "<tr>";
    }
    $htmlout .= "<td><a href=\"javascript: SmileIT('".str_replace("'", "\'",
            $code)."','".htmlsafechars($_GET['form'])."','".htmlsafechars($_GET['text'])."')\"><img border='0' src='./pic/smilies/".$url."' alt='' /></a></td>";
    $count++;
    if ($count % 3 == 0) {
        $htmlout .= "</tr>";
    }
}
$htmlout .= "</table><br /><div align='center'><a class='altlink' href='javascript: window.close()'><b>[ Close window ]</b></a></div></body></html>";
echo $htmlout;
?>
