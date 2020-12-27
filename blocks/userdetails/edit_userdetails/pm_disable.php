<?php
        $sendpmpos = $user['sendpmpos'] != 1;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$sendpmpos ? ' rowspan="2"' : '') . ">{$lang['userdetails_pmpos']}</div>
               <div class='col-sm-2'>" . ($sendpmpos ? "<input name='sendpmpos' value='42' type='radio'>{$lang['userdetails_remove_pm_d']}" : $lang['userdetails_no_disablement']) . "</div>";
        if ($sendpmpos) {
            if ($user['sendpmpos'] == 0) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['sendpmpos'], 'DATE') . " (" . mkprettytime($user['sendpmpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text' name='pmdisable_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_disable_for'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <select name="sendpmpos">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text" name="pmdisable_pm"></div></div>';
        }
?>