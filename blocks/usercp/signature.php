<?php
    $HTMLOUT.= "<div class='col-md-8'>
	<table class='table table-bordered'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='signature' />{$lang['usercp_sign_opt']}</td></tr>";
    //=== signature stuff
    $HTMLOUT.= tr(''.$lang['usercp_sign_view'].'', '<input type="radio" name="signatures" '.($CURUSER['signatures'] == 'yes' ? 'checked="checked"' : '').' value="yes" />'.$lang['usercp_av_yes1'].'
     <input type="radio" name="signatures" '.($CURUSER['signatures'] == 'no' ? 'checked="checked"' : '').' value="no" />'.$lang['usercp_av_no1'].'', 1);
    //$HTMLOUT.= tr('View Signatures', '<input class="styled" type="checkbox" name="signatures"' . (($CURUSER['opt1'] & user_options::SIGNATURES) ? ' checked="checked"' : '') . ' value="yes" /> (Check to view signatures)', 1);
    $HTMLOUT.= tr(''.$lang['usercp_sign_tit'].'', '<textarea name="signature" cols="50" rows="4">' . htmlsafechars($CURUSER['signature'], ENT_QUOTES) . '</textarea><br />'.$lang['usercp_sign_BB'].'', 1);
    $HTMLOUT.= tr($lang['usercp_info'], "<textarea name='info' cols='50' rows='4'>" . htmlsafechars($CURUSER["info"], ENT_QUOTES) . "</textarea><br />{$lang['usercp_tags']}", 1);
    $HTMLOUT.= "<tr ><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";

$HTMLOUT.="</table></div>";