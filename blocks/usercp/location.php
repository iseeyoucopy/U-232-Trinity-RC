<?php
$HTMLOUT.= "<script type='text/javascript'>
    /*<![CDATA[*/
    function daylight_show()
    {
    if ( document.getElementById( 'tz-checkdst' ).checked )
    {
    document.getElementById( 'tz-checkmanual' ).style.display = 'none';
    }
    else
    {
    document.getElementById( 'tz-checkmanual' ).style.display = 'block';
    }
    }
    /*]]>*/
    </script>";
$countries = "<option value='0'>---- {$lang['usercp_none']} ----</option>\n";
$ct_r = sql_query("SELECT id,name FROM countries ORDER BY name") or sqlerr(__FILE__, __LINE__);
while ($ct_a = mysqli_fetch_assoc($ct_r)) {
    $countries.= "<option value='" . (int)$ct_a['id'] . "'" . ($CURUSER["country"] == $ct_a['id'] ? " selected='selected'" : "") . ">" . htmlsafechars($ct_a['name']) . "</option>\n";
}
$offset = ($CURUSER['time_offset'] != "") ? (string)$CURUSER['time_offset'] : (string)$TRINITY20['time_offset'];
$time_select = "<select name='user_timezone'>";
foreach ($TZ as $off => $words) {
    if (preg_match("/^time_(-?[\d\.]+)$/", $off, $match)) {
        $time_select.= $match[1] == $offset ? "<option value='{$match[1]}' selected='selected'>$words</option>\n" : "<option value='{$match[1]}'>$words</option>\n";
    }
}
$time_select.= "</select>";
if ($CURUSER['dst_in_use']) {
    $dst_check = 'checked="checked"';
} else {
    $dst_check = '';
}
if ($CURUSER['auto_correct_dst']) {
    $dst_correction = 'checked="checked"';
} else {
    $dst_correction = '';
}
 $HTMLOUT.= "
<div class='col-md-7'>
	<table class='table table-bordered'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='location' />{$lang['usercp_loc_opt']}</td></tr>";
    //==Time Zone
    $HTMLOUT.= tr($lang['usercp_tz'], $time_select, 1);
    $HTMLOUT.= tr($lang['usercp_checkdst'], "<input type='checkbox' name='checkdst' id='tz-checkdst' onclick='daylight_show()' value='1' $dst_correction />&nbsp;{$lang['usercp_auto_dst']}<br />
    <div id='tz-checkmanual' style='display: none;'><input type='checkbox' name='manualdst' value='1' $dst_check />&nbsp;{$lang['usercp_is_dst']}</div>", 1);
    //==Country
    $HTMLOUT.= tr($lang['usercp_country'], "<select name='country'>\n$countries\n</select>", 1);
    //==Language
    $HTMLOUT.= tr($lang['usercp_language'], "<select name='language'>
    <option value='1'" . ($CURUSER['language'] == '1' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc1']}</option>
    <option value='2'" . ($CURUSER['language'] == '2' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc4']}</option>
    </select>", $CURUSER['language']);
//    <option value='2'" . ($CURUSER['language'] == '2' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc2']}</option>
//    <option value='3'" . ($CURUSER['language'] == '3' ? " selected='selected'" : "") . ">{$lang['usercp_loc_loc3']}</option>
    $HTMLOUT.= "<tr ><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";
$HTMLOUT.="</table></div>";