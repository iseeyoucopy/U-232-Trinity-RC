<?php
$HTMLOUT .="<ul class='tabs' data-tabs id='details-tabs'>
	<li class='tabs-title is-active'><a href='#details_panel1' aria-selected='true'>{$lang['details_add_men1']}</a></li>
	<li class='tabs-title'><a data-tabs-target='details_panel2' href='#details_panel2'>{$lang['details_add_men2']}</a></li>
	<li class='tabs-title'><a data-tabs-target='details_panel3' href='#details_panel3'>{$lang['details_add_men3']}</a></li>
    <li class='tabs-title'><a data-tabs-target='details_panel4' href='#details_panel4'>{$lang['details_add_men4']}</a></li>
    ";
 if ($CURUSER['class'] >= UC_POWER_USER) {
    $HTMLOUT .= "<li class='tabs-title'><a data-tabs-target='details_panel5' href='#details_panel5'>{$lang['details_add_men5']}</a></li>";
 }
 $HTMLOUT .= "</ul>";
;
?>