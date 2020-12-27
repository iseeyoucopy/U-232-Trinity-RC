<?php
        $game_access = $user['game_access'] != 1;
        $HTMLOUT.= "<br><div class='row'><div class='col-sm-2'" . (!$game_access ? ' rowspan="2"' : '') . ">{$lang['userdetails_games']}</div>
           <div class='col-sm-2'>" . ($game_access ? "<input name='game_access' value='42' type='radio'>{$lang['userdetails_remove_game_d']}" : $lang['userdetails_no_disablement']) . "</div>";
        if ($game_access) {
            if ($user['game_access'] == 0) $HTMLOUT.= '<td align="center">('.$lang['userdetails_unlimited_d'].')</td></tr>';
            else $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_until']} " . get_date($user['game_access'], 'DATE') . " (" . mkprettytime($user['game_access'] - TIME_NOW) . " {$lang['userdetails_togo']})</div><div class='col-sm-6'><!--<input placeholder='Comments' class='form-control' type='text' name='game_disable_pm'>--></div></div>";
        } else {
            $HTMLOUT.= '<div class="col-sm-2">'.$lang['userdetails_disable_for'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="game_access">
        <option value="0">------</option>
        <option value="1">1 '.$lang['userdetails_week'].'</option>
        <option value="2">2 '.$lang['userdetails_weeks'].'</option>
        <option value="4">4 '.$lang['userdetails_weeks'].'</option>
        <option value="8">8 '.$lang['userdetails_weeks'].'</option>
        <option value="90">'.$lang['userdetails_unlimited'].'</option>
        </select></div>
        <div class="col-sm-6"><input placeholder="Comments" class="form-control" type="text" name="game_disable_pm"></div></div>';
        }
?>