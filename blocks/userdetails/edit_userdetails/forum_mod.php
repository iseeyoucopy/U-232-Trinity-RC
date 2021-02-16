<?php
$HTMLOUT.= "<div class='cell medium-12'>
<fieldset class='fieldset'>
    <legend>{$lang['userdetails_forum_rights']}</legend>
	<input name='forum_post' value='yes' type='radio'".($user['forum_post'] == "yes" ? " checked='checked'" : "").">
	{$lang['userdetails_yes']}
	<input name='forum_post' value='no' type='radio'".($user['forum_post'] == "no" ? " checked='checked'" : "").">
	<br>{$lang['userdetails_forums_no']}
	</fieldset>
</div>";
$HTMLOUT .="<div class='cell medium-12'>
<fieldset class='fieldset'>
    <legend>Forum Moderator</legend>
	<input name='forum_mod' value='yes' type='radio' " . ($user["forum_mod"]=="yes" ? "checked='checked'" : "") . ">
Yes 
<input name='forum_mod' value='no' type='radio' " . ($user["forum_mod"]=="no" ? "checked='checked'" : "") . ">
No</div>";
  

($q = sql_query("SELECT o.id as oid, o.name as oname, f.id as fid, f.name as fname FROM `over_forums` as o LEFT JOIN forums as f ON f.forum_id = o.id ")) || sqlerr(__FILE__, __LINE__);
	if($q){
	while($a = $q->fetch_assoc()){
		$boo[$a['oname']][] = array($a['fid'],$a['fname']);
	$forum_list = "<ul class='accordion' data-accordion data-allow-all-closed='true' data-deep-link='true' data-update-history='true' data-deep-link-smudge='true' data-deep-link-smudge-delay='500' id='deeplinked-accordion'>";
	foreach($boo as $fo=>$foo) {
		$forum_list .="<li class='accordion-item' data-accordion-item>
		<a href='#deeplink". $fo ."' class='accordion-title'>".$fo."</a>";
			foreach($foo as $fooo)
				$forum_list .= "<div class='accordion-content' data-tab-content id='deeplink".$fooo."'>
					<label for='forum_".$fooo[0]."'>
						<span class='file' style='position:relative;width:200px;'>
							<b>".$fooo[1]."</b>
							<input type='checkbox' ".(stripos($user["forums_mod"], "[".$fooo[0]."]") !== false ? "checked='checked'" : "" )."style='right:0;top:0;position:absolute;' name='forums[]' id='forum_".$fooo[0]."' value='".$fooo[0]."'>
						</span>
					</label>
				</div>";
		$forum_list .= "</li>";	
	}
	$forum_list .= "</ul>";
  

    $HTMLOUT .="<div class='cell medium-8'>Forums List<br>".$forum_list."</div>";
    }
}
