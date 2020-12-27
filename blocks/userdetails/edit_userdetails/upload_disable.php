<?php


$uploadpos = $user['uploadpos'] != 1;
$HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$uploadpos ? ' rowspan="2"' : '') . ">{$lang['userdetails_upos']}</div>
<div class='col-sm-2'> " . ($uploadpos ? "<input name='uploadpos' value='42' type='radio'>{$lang['userdetails_remove_upload_d']}" : $lang['userdetails_no_disablement']) . "</div>";
if ($uploadpos) {
if ($user['uploadpos'] == 0) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['uploadpos'], 'DATE') . " (" . mkprettytime($user['uploadpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input class='form-control' placeholder='Comments' type='text' name='updisable_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_disable_for'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name="uploadpos">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input class="form-control" placeholder="Comment" type="text" name="updisable_pm"></div></div>';
        }