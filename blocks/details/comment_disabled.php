<?php
if ($torrents["allow_comments"] == "yes" || $CURUSER['class'] >= UC_STAFF && $CURUSER['class'] <= UC_MAX) {
    $HTMLOUT.= "\n";
} else {
    $HTMLOUT.= "
    <p><table align='center' class='table table-bordered'>
    <tr>
    <td><a name='startcomments'>&nbsp;</a><b>{$lang['details_com_disabled']}</b></td>
    </tr>
        </table></p>
     </div>
     </div><div class='row'><div class='col-md-1'></div><div class='col-md-10'>\n";
    echo stdhead("{$lang['details_details']}\"" . htmlsafechars($torrents["name"], ENT_QUOTES) . "\"", true, $stdhead) . $HTMLOUT . stdfoot(true, $stdfoot);
    die();
}
?>