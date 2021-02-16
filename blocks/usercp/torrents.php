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
$HTMLOUT.= "<input type='hidden' name='action' value='torrents'>
<div class='card'>
<div class='card-divider'>{$lang['usercp_tt_opt']}</div>
<div class='card-section'>";
    //==cats
    $categories = '';
    ($r = sql_query("SELECT id, image, name FROM categories ORDER BY name")) || sqlerr(__FILE__, __LINE__);
    if ($r->num_rows > 0) {
        $categories.= "<table><tr>";
        $i = 0;
        while ($a = $r->fetch_assoc()) {
            $categories.= ($i && $i % 3 == 0) ? "</tr><tr>" : "";
            $categories.= "<td>
                <input name='cat{$a['id']}' type='checkbox' " . (strpos($CURUSER['notifs'], (string) "[cat{$a['id']}]") !== false ? " checked='checked'" : "") . " value='yes'>
                <a class='catlink' href='browse.php?cat={$a['id']}'>
                <img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/" . htmlspecialchars($a['image']) . "' alt='" . htmlspecialchars($a['name']) . "' title='" . htmlspecialchars($a['name']) . "'></a>&nbsp;" . htmlspecialchars($a["name"]) . "
            </td>";
            ++$i;
        }
        $categories.= "</tr></table>";
    }
    $HTMLOUT.="<ul class='accordion' data-accordion data-allow-all-closed='true'>
    <li class='accordion-item is-closed' data-accordion-item>
        <a href='#' class='accordion-title'>{$lang['usercp_browse']}</a>
        <div class='accordion-content' data-tab-content>
        " . $categories . "
        </div>
        </li>
    </ul>";
    $HTMLOUT.= '
<div class="grid-x grid-margin-x small-up-2 medium-up-3 large-up-4">
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>'.$lang['usercp_tt_split'].'</strong></div>
            <div class="card-section float-center">
                <div class="switch large">
                    <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="browse_split" name="browse_split" value="yes"' . (((curuser::$blocks['browse_page'] & block_browse::SPLIT) !== 0) ? ' checked="checked"' : '') . '>
                    <label class="switch-paddle" for="browse_split">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                    </label>
                </div>         
            </div>
        </div>
    </div>
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>' . $lang['usercp_icons'] . '</strong></div>
            <div class="card-section float-center">
                <div class="switch large">
                    <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="browse_icons" name="browse_icons" value="yes"' . (((curuser::$blocks['browse_page'] & block_browse::ICONS) !== 0) ? ' checked="checked"' : '') . '>
                    <label class="switch-paddle" for="browse_icons">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                    </label>
                </div>         
            </div>
        </div>
    </div>
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>' . $lang['usercp_scloud'] . '</strong></div>
            <div class="card-section float-center">
                <div class="switch large">
                    <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="browse_viewscloud" name="browse_viewscloud" value="yes"' . (((curuser::$blocks['browse_page'] & block_browse::VIEWSCLOUD) !== 0) ? ' checked="checked"' : '') . '>
                    <label class="switch-paddle" for="browse_viewscloud">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                    </label>
                </div>         
            </div>
        </div>
    </div>
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>Slider</strong></div>
            <div class="card-section float-center">
                <div class="switch large">
                    <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="browse_slider" name="browse_slider" value="yes"' . (((curuser::$blocks['browse_page'] & block_browse::SLIDER) !== 0) ? ' checked="checked"' : '') . '>
                    <label class="switch-paddle" for="browse_slider">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                    </label>
                </div>         
            </div>
        </div>
    </div>
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>' . $lang['usercp_clearnewtagmanually'] . '</strong></div>
            <div class="card-section float-center">
                <div class="switch large">
                    <input onchange="this.form.submit()" class="switch-input" type="checkbox" id="browse_clear_tags" name="browse_clear_tags" value="yes"' . (((curuser::$blocks['browse_page'] & block_browse::CLEAR_NEW_TAG_MANUALLY) !== 0) ? ' checked="checked"' : '') . '>
                    <label class="switch-paddle" for="browse_clear_tags">
                        <span class="switch-active" aria-hidden="true">Yes</span>
                        <span class="switch-inactive" aria-hidden="true">No</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>' . $lang['usercp_cats_sets'] . '</strong></div>
            <div class="card-section float-center">
                <div class="input-group">
                    <select class="input-group-field" onchange="this.form.submit()" name="categorie_icon">
                        <option value="1"' . ($CURUSER['categorie_icon'] == 1 ? " selected='selected'" : "") . '>' . $lang['usercp_tt_typ1'] .'</option>
                        <option value="2"' . ($CURUSER['categorie_icon'] == 2 ? " selected='selected'" : "") . '>' . $lang['usercp_tt_typ2'] . '</option>
                        <option value="3"' . ($CURUSER['categorie_icon'] == 3 ? " selected='selected'" : "") . '>' . $lang['usercp_tt_typ3'] . '</option>
                        <option value="4"' . ($CURUSER['categorie_icon'] == 4 ? " selected='selected'" : "") . '>' . $lang['usercp_tt_typ4'] . '</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="cell"> 
        <div class="card">
            <div class="card-divider"><strong>' . $lang['usercp_tor_perpage'] . '</strong></div>
            <div class="card-section float-center">
                <div class="input-group">
                    <input class="input-group-field" type="text" size="10" name="torrentsperpage" value="' . $CURUSER['torrentsperpage'] .'">
                    <div class="input-group-button">
                        <input class="button" type="submit" value="'.$lang['usercp_sign_sub'] . '">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div></div>';
