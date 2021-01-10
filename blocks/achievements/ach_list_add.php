<?php
$HTMLOUT.= "<div class='card table-responsive'>
<div class='card-divider'>{$lang['achlst_add_an_ach_lst']}</div>
<div class='card-section'>
		<form method='post' action='achievementlist.php'>
		<table class='table table-bordered'>
		<tr>
		<td class='colhead'></td><td class='one'><input type='text' placeholder='{$lang['achlst_achievname']}' name='achievname' size='40' /></td>
		</tr>
      <tr>
		<td class='colhead'></td><td class='two'><textarea cols='60' rows='3' placeholder='{$lang['achlst_achievicon']}' name='clienticon'></textarea></td>
		</tr>
		<tr>
		<td class='colhead'></td><td class='one'><textarea cols='60' rows='6' placeholder='{$lang['achlst_description']}' name='notes'></textarea></td>
		</tr>
		<tr>
		<td colspan='2' align='center' class='two'><input type='submit' name='okay' value='{$lang['achlst_add_me']}!' class='btn' /></td>
		</tr>
		</table>
		</form>
		</div>
</div>";
?>