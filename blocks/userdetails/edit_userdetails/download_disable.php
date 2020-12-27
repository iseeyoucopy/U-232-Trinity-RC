<?php
        $downloadpos = $user['downloadpos'] != 1;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$downloadpos ? ' rowspan="2"' : '') . ">{$lang['userdetails_dpos']}</div>
               <div class='col-sm-2'>" . ($downloadpos ? "<input name='downloadpos' value='42' type='radio'>{$lang['userdetails_remove_download_d']}" : $lang['userdetails_no_disablement']) . "</div>";
        if ($downloadpos) {
            if ($user['downloadpos'] == 0) $HTMLOUT.= '<div class="col-sm-1">('.$lang['userdetails_unlimited_d'].')</div>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['downloadpos'], 'DATE') . " (" . mkprettytime($user['downloadpos'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input class='form-control' placeholder='Comments' type='text' name='disable_pm'></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_disable_for'].' <select name="downloadpos">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input class="form-control" placeholder="Comments" type="text" size="60" name="disable_pm"></div></div>';
        }
?>