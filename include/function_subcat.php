<?php

function genrelist2()
{
    global $cache, $TRINITY20, $cache_keys;
    if (!($cats = $cache->get($cache_keys['categories']))) {
        $row = sql_query("SELECT id, name, image, parent_id, tabletype, min_class FROM categories ORDER BY name");
        while ($mysqlcats = $row->fetch_assoc()) {
            $allcats[] = $mysqlcats;
        }
        $allcats2 = $allcats;
        $i = 0;
        foreach ($allcats as $cat) {
            if ($cat['parent_id'] == -1) {
                $cats[] = $cat;
                $j = 0;
                $cats[$i]['categories'] = '';
                foreach ($allcats2 as $subcat) {
                    if ($cat['id'] == $subcat['parent_id']) {
                        //Subcategories
                        $cats[$i]['subcategory'][] = $subcat;
                        //Subcategories add parenttabletype
                        $cats[$i]['subcategory'][$j]['parenttabletype'] = $cat['tabletype'];
                        //Subcategories add idtabletype
                        $cats[$i]['subcategory'][$j]['idtabletype'] = $subcat['id'].$subcat['tabletype'];
                        //Subcategories description
                        $cats[$i]['subcategory'][$j]['description'] = $cat['name']."->".$subcat['name'];
                        //All link array for cats
                        $cats[$i]['categories'] .= "cats$cat[tabletype][]=$subcat[id]&amp;";
                        $j++;
                    }
                }
                //All link for cats
                $cats[$i]['categories'] = substr($cats[$i]['categories'], 0, -5);
                $i++;
            }
        }
        $cache->set($cache_keys['categories'], $cats, $TRINITY20['expires']['genrelist2']);
    }
    return $cats;
}

function categories_table($cats, $wherecatina, $linkpage = '', $display = 'block')
{
    global $lang, $CURUSER, $TRINITY20;
    $html = "";
    $html .= "<div id=\"cats\" style=\"display: {$display};\"><table><tbody align=\"left\"><tr>";
    $i = 0;
    $ncats = is_countable($cats) ? count($cats) : 0;
    $catsperrow = $TRINITY20['catperrow'];
    if (!empty($ncats)) {
        ;
    }
    foreach ($cats as $cat) {
        $html .= ($i && $i % $catsperrow == 0) ? "</tr><tr>" : "";
        $html .= "<td>
    <input id=\"checkAll{$cat['tabletype']}\" type=\"checkbox\" onclick=\"checkAllFields(1,{$cat['tabletype']});\" ".(isset($cat['checked']) && $cat['checked'] ? "checked='checked'" : "")." />
    <a href=\"javascript: ShowHideMainSubCats({$cat['tabletype']},{$ncats})\">
    <img border=\"0\" src=\"pic/aff_tick.gif\" id=\"pic{$cat['tabletype']}\" alt=\"Show/Hide\" />&nbsp;".htmlsafechars($cat['name'])."</a>&nbsp;".(($linkpage != '') ? "<a class=\"catlink\" href=\"{$linkpage}?{$cat['categories']}\">(All)</a>" : "")."</td>\n";
        $i++;
    }
    $nrows = ceil($ncats / $catsperrow);
    $lastrowcols = $ncats % $catsperrow;
    if ($lastrowcols != 0) {
        if ($catsperrow - $lastrowcols != 1) {
            $html .= "<td>&nbsp;</td>";
        } else {
            $html .= "<td>&nbsp;</td>";
        }
    }
    $html .= "</tr></tbody></table></div>";
    if ((is_countable($cats) ? count($cats) : 0) > 0) {
        ;
    }
    foreach ($cats as $cat) {
        $subcats = isset($cat['subcategory']) && is_array($cat['subcategory']) ? $cat['subcategory'] : [];
        if (count($subcats) > 0) {
            $html .= subcategories_table($cat, $wherecatina, $linkpage, $ncats);
        }
    }
    return $html;
}

function subcategories_table($cats, $wherecatina = [], $linkpage = '', $ncats)
{
    global $lang, $CURUSER, $TRINITY20;
    $html = "";
    $html .= "<div id=\"tabletype{$cats['tabletype']}\" style=\"display: none;\">";
    $subcats = $cats['subcategory'];
    $html .= "<table>";
    $html .= "<tbody align=\"left\"><tr>";
    $catsperrow = $TRINITY20['catperrow'];
    $i = 0;
    if ((is_countable($subcats) ? count($subcats) : 0) > 0) {
        foreach ($subcats as $cat) {
            $html .= ($i && $i % $catsperrow == 0) ? "</tr><tr>" : "";
            $html .= " ".(in_array($cat['id'],
                    $wherecatina) ? "checked='checked'" : "")." />
    ".(($linkpage != '') ? "<a href=\"{$linkpage}?cats{$cats['tabletype']}[]={$cat['id']}\"><img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($cat['image'])."' alt='".htmlsafechars($cat['name'])."' title='".htmlsafechars($cat['name'])."' /></a>" : htmlsafechars($cat['name']))."</td>\n";
            $i++;
        }
    }
    $nsubcats = is_countable($subcats) ? count($subcats) : 0;
    $nrows = ceil($nsubcats / $catsperrow);
    $lastrowcols = $nsubcats % $catsperrow;
    if ($lastrowcols != 0) {
        if ($catsperrow - $lastrowcols != 1) {
            $html .= "<td class=\"one\" rowspan=\"".($catsperrow - $lastrowcols)."\">&nbsp;</td>";
        } else {
            $html .= "<td class=\"one\">&nbsp;</td>";
        }
    }
    return $html."</tr></tbody></table></div>";
}

function validsubcat($subcatid, $cats)
{
    //Find Category with subcat
    $i = 0;
    if ((is_countable($cats) ? count($cats) : 0) > 0) {
    }
    foreach ($cats as $cat) {
        $subcats = $cat['subcategory'];
        if ((is_countable($subcats) ? count($subcats) : 0) > 0) {
            foreach ($subcats as $subcat) {
                if ($subcat['id'] == $subcatid) {
                    return true;
                }
            }
        }
    }
    return false;
}

?>
