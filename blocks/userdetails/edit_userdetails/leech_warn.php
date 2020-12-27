<?php
$leechwarn = $user['leechwarn'] != 0;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$leechwarn ? ' rowspan="2"' : '') . ">{$lang['userdetails_leechwarn']}</div>
               <div class='col-sm-2'>" . ($leechwarn ? "<input name='leechwarn' value='42' type='radio'>{$lang['userdetails_remove_leechwarn']}" : $lang['userdetails_no_leechwarn']) . "</div>";
        if ($leechwarn) {
            if ($user['leechwarn'] == 1) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['leechwarn'], 'DATE') . " (" . mkprettytime($user['leechwarn'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text' size='60' name='leechwarn_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_leechwarn_for'].'&nbsp; <select name="leechwarn">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text" name="leechwarn_pm"></div></div>';
        }
?>