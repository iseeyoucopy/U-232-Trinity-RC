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
//-------- Begins a main frame
function begin_main_frame()
{
    return "<table class='table' width='750' border='0' cellspacing='0' cellpadding='0'>" . "<tr><td class='embedded'>\n";
}
//-------- Ends a main frame
function end_main_frame()
{
    return "</td></tr></table>\n";
}
function begin_frame($caption = "", $center = false, $padding = 10)
{
    $tdextra = "";
    $htmlout = '';
    if ($caption) {
        $htmlout .= "<h2>$caption</h2>\n";
    }
    if ($center) {
        $tdextra .= " align='center'";
    }
    return $htmlout . "<table class='table'><tr><td$tdextra>\n";
}
function attach_frame($padding = 10)
{
    $htmlout = '';
    return $htmlout . "</td></tr><tr><td style='border-top: 0px'>\n";
}
function end_frame()
{
    return "</td></tr></table>\n";
}
function begin_table($fullwidth = false, $padding = 5)
{
    $width   = "";
    $htmlout = '';
    if ($fullwidth) {
        $width .= " width='100%'";
    }
    return $htmlout . "<table class='table table-bordered'>";
}
function end_table()
{
    return "</table>";
}
function tr($x, $y, $noesc = 0)
{
    if ($noesc) {
        $a = $y;
    }
    else {
        $a = htmlsafechars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    return "<tr>
				<td>$x</td>
				<td>$a</td>
			</tr>";
}
//-------- Inserts a smilies frame
function insert_smilies_frame()
{
    global $smilies, $TRINITY20;
    $htmlout = '';
    $htmlout .= begin_frame("Smilies", true);
    $htmlout .= begin_table(false, 5);
    $htmlout .= "<tr><td>Type...</td><td>To make a...</td></tr>";
    foreach ($smilies as $code => $url) {
        $htmlout .= "<tr><td>$code</td><td><img src=\"{$TRINITY20['pic_base_url']}smilies/{$url}\" alt='' /></td></tr>";
    }
    $htmlout .= end_table();
    $htmlout .= end_frame();
    return $htmlout;
}
?>
