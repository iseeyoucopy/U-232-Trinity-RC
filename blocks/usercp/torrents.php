<?php
   $HTMLOUT.= "
	<table class='table table-bordered'>";
    $HTMLOUT.= "<tr><td><input type='hidden' name='action' value='torrents' />{$lang['usercp_tt_opt']}</td></tr>";
    //==cats
    $categories = '';
    $r = sql_query("SELECT id, image, name FROM categories ORDER BY name") or sqlerr(__FILE__, __LINE__);
    if (mysqli_num_rows($r) > 0) {
        $categories.= "<table><tr>\n";
        $i = 0;
        while ($a = mysqli_fetch_assoc($r)) {
            $categories.= ($i && $i % 2 == 0) ? "</tr><tr>" : "";
            $categories.= "<td class='bottom' style='padding-right: 5px'><input name='cat{$a['id']}' type='checkbox' " . (strpos($CURUSER['notifs'], "[cat{$a['id']}]") !== false ? " checked='checked'" : "") . " value='yes' />&nbsp;<a class='catlink' href='browse.php?cat={$a['id']}'><img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/" . htmlspecialchars($a['image']) . "' alt='" . htmlspecialchars($a['name']) . "' title='" . htmlspecialchars($a['name']) . "' /></a>&nbsp;" . htmlspecialchars($a["name"]) . "</td>\n";
            ++$i;
        }
        $categories.= "</tr></table>\n";
    }
    $HTMLOUT.= tr($lang['usercp_browse'], $categories, 1);
    /*$HTMLOUT.= tr($lang['usercp_clearnewtagmanually'], "<input type='checkbox' name='clear_new_tag_manually'".($CURUSER["clear_new_tag_manually"] == "yes" ? " checked='checked'" : "")." /> {$lang['usercp_default_clearnewtagmanually']}", 1);*/
    $HTMLOUT.= tr($lang['usercp_clearnewtagmanually'], "<input type='checkbox' name='clear_new_tag_manually' value='yes'" . (($CURUSER['opt1'] & user_options::CLEAR_NEW_TAG_MANUALLY) ? " checked='checked'" : "") . " /> {$lang['usercp_default_clearnewtagmanually']}", 1);
    /*$HTMLOUT.= tr($lang['usercp_scloud'], "<input type='checkbox' name='viewscloud'".($CURUSER["viewscloud"] == "yes" ? " checked='checked'" : "")." /> {$lang['usercp_scloud1']}", 1);*/
    $HTMLOUT.= tr($lang['usercp_scloud'], "<input type='checkbox' name='viewscloud' value='yes'" . (($CURUSER['opt1'] & user_options::VIEWSCLOUD) ? " checked='checked'" : "") . " /> {$lang['usercp_scloud1']}", 1);
    /*$HTMLOUT.= tr($lang['usercp_split'], "<input type='radio' name='split'".($CURUSER["split"] == "yes" ? " checked='checked'" : "")." value='yes' />Yes<input type='radio' name='split'".($CURUSER["split"] == "no" ? " checked='checked'" : "")." value='no' />No", 1);*/
    $HTMLOUT.= tr($lang['usercp_split'], "<input type='checkbox' name='split'" . (($CURUSER['opt2'] & user_options_2::SPLIT) ? " checked='checked'" : "") . " value='yes' />{$lang['usercp_tt_split']}", 1);
    /*$HTMLOUT.= tr($lang['usercp_icons'], "<input type='radio' name='browse_icons'".($CURUSER["browse_icons"] == "yes" ? " checked='checked'" : "")." value='yes' />Yes<input type='radio' name='browse_icons'".($CURUSER["browse_icons"] == "no" ? " checked='checked'" : "")." value='no' />No", 1);*/
    $HTMLOUT.= tr($lang['usercp_icons'], "<input type='checkbox' name='browse_icons'" . (($CURUSER['opt2'] & user_options_2::BROWSE_ICONS) ? " checked='checked'" : "") . " value='yes' />{$lang['usercp_tt_vcat']}", 1);
    $HTMLOUT.= tr($lang['usercp_cats_sets'], "<select name='categorie_icon'>
     <option value='1'" . ($CURUSER['categorie_icon'] == 1 ? " selected='selected'" : "") . ">{$lang['usercp_tt_typ1']}</option>
     <option value='2'" . ($CURUSER['categorie_icon'] == 2 ? " selected='selected'" : "") . ">{$lang['usercp_tt_typ2']}</option>
     <option value='3'" . ($CURUSER['categorie_icon'] == 3 ? " selected='selected'" : "") . ">{$lang['usercp_tt_typ3']}</option>
     <option value='4'" . ($CURUSER['categorie_icon'] == 4 ? " selected='selected'" : "") . ">{$lang['usercp_tt_typ4']}</option>
     </select>", $CURUSER['categorie_icon']);
    $HTMLOUT.= tr($lang['usercp_tor_perpage'], "<input type='text' size='10' name='torrentsperpage' value='{$CURUSER['torrentsperpage']}' /> {$lang['usercp_default']}", 1);
    $HTMLOUT.= "<tr><td align='center' colspan='2'><input class='btn btn-primary' type='submit' value='{$lang['usercp_sign_sub']}' style='height: 40px' /></td></tr>";
$HTMLOUT.="</table>";