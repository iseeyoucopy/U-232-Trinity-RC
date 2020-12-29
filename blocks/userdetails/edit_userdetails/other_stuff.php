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
$HTMLOUT.= '<div class="row"><div class="col-sm-1">'.$lang['userdetails_hnr'].'<br><input class="form-control" type="text" name="hit_and_run_total" value="' . (int)$user['hit_and_run_total'] . '"></div>
                 
	<div class="col-sm-1">'.$lang['userdetails_suspended'].'<br><input name="suspended" value="yes" type="radio"'.($user['suspended'] == 'yes' ? ' checked="checked"' : '').'>'.$lang['userdetails_yes'].'
                     <input name="suspended" value="no" type="radio"'.($user['suspended'] == 'no' ? ' checked="checked"' : '').'></div><div class="col-sm-4">'.$lang['userdetails_no'].'
		 '.$lang['userdetails_suspended_reason'].'<input class="form-control" type="text" name="suspended_reason"></div>';

$HTMLOUT.="<div style='display:inline-block;height:50px;'></div>";

//==new row

$HTMLOUT.= "<br><div class='row'>
	<div class='col-sm-2'>{$lang['userdetails_avatar_rights']}<br><input name='view_offensive_avatar' value='yes' type='radio'".($user['view_offensive_avatar'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                  <input name='view_offensive_avatar' value='no' type='radio'".($user['view_offensive_avatar'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']} </div>
                 
                <div class='col-sm-2'>{$lang['userdetails_offensive']}<br><input name='offensive_avatar' value='yes' type='radio'".($user['offensive_avatar'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                  <input name='offensive_avatar' value='no' type='radio'".($user['offensive_avatar'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']} </div>
               
                <div class='col-sm-2'>{$lang['userdetails_view_offensive']}<br>
                 <input name='avatar_rights' value='yes' type='radio'".($user['avatar_rights'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                  <input name='avatar_rights' value='no' type='radio'".($user['avatar_rights'] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']} </div>";
 
//users parked
     $HTMLOUT.= "<div class='col-sm-1'>{$lang['userdetails_park']}<br><input name='parked' value='yes' type='radio'".($user["parked"] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']} <input name='parked' value='no' type='radio'".($user["parked"] == "no" ? " checked='checked'" : "").">{$lang['userdetails_no']}</div>";
//end users parked     

//reset passkey
    $HTMLOUT.= "<div class='col-sm-2'>{$lang['userdetails_reset']}<br><input type='checkbox' name='reset_torrent_pass' value='1'><br><font class='small'>{$lang['userdetails_pass_msg']}</font></div></div>";
//end reset    

$HTMLOUT.="<div style='display:inline-block;height:50px;'></div>";

//==ANOTHER ROW

$HTMLOUT.= "
<div class='row'>

<div class='col-sm-2'>{$lang['userdetails_forum_rights']}<br><input name='forum_post' value='yes' type='radio'".($user['forum_post'] == "yes" ? " checked='checked'" : "").">{$lang['userdetails_yes']}
                     <input name='forum_post' value='no' type='radio'".($user['forum_post'] == "no" ? " checked='checked'" : "")."><br>{$lang['userdetails_forums_no']}</div>";
  

$HTMLOUT .="<div class=\"col-sm-2\">Forum Moderator<br><input name=\"forum_mod\" value=\"yes\" type=radio " . ($user["forum_mod"]=="yes" ? "checked=\"checked\"" : "") . ">Yes <input name=\"forum_mod\" value=\"no\" type=\"radio\" " . ($user["forum_mod"]=="no" ? "checked=\"checked\"" : "") . ">No</div>";
  

$q = sql_query("SELECT o.id as oid, o.name as oname, f.id as fid, f.name as fname FROM `over_forums` as o LEFT JOIN forums as f ON f.forum_id = o.id ") or sqlerr(__FILE__, __LINE__);
	while($a = mysqli_fetch_assoc($q))
		$boo[$a['oname']][] = array($a['fid'],$a['fname']);
	$forum_list = "<ul id=\"browser\" class=\"filetree treeview-gray\" style=\"width:50%;text-align:left;\">";
	foreach($boo as $fo=>$foo) {
		$forum_list .="<li class=\"closed\"><span class=\"folder\">".$fo."</span>";
		$forum_list .="<ul>";
			foreach($foo as $fooo)
				$forum_list .= "<li><label for=\"forum_".$fooo[0]."\"><span class=\"file\" style=\"position:relative;width:200px;\"><b>".$fooo[1]."</b><div style=\"display:inline-block;width:15px;\"></div><input type=\"checkbox\" ".(stristr($user["forums_mod"],"[".$fooo[0]."]") ? "checked=\"checked\"" : "" )."style=\"right:0;top:0;position:absolute;\" name=\"forums[]\" id=\"forum_".$fooo[0]."\" value=\"".$fooo[0]."\"></span></label></li>";
		$forum_list .= "</ul></li>";	
	}
	$forum_list .= "</ul>";
  

$HTMLOUT .="<div class=\"col-sm-8\">Forums List<br>".$forum_list."</div></div>";
   
    $HTMLOUT.= "<br><br><div class='row'><div class='col-sm-offset-5'><input type='submit' class='btn btn-default' value='{$lang['userdetails_okay']}'></div></div>";
    $HTMLOUT.= "</table>";
    $HTMLOUT.= "</form>";
?>