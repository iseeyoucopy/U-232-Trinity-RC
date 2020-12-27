<?php
       $immunity = $user['immunity'] != 0;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$immunity ? ' rowspan="2"' : '') . ">{$lang['userdetails_immunity']}</div>
               <div class='col-sm-2'>" . ($immunity ? "<input name='immunity' value='42' type='radio'>{$lang['userdetails_remove_immunity']}" : $lang['userdetails_no_immunity']) . "</div>";
        if ($immunity) {
            if ($user['immunity'] == 1) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['immunity'], 'DATE') . " (" . mkprettytime($user['immunity'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text'  name='immunity_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_immunity_for'].'&nbsp;&nbsp;&nbsp;&nbsp; <select name="immunity">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text"  name="immunity_pm"></div></div>';
        }
?>