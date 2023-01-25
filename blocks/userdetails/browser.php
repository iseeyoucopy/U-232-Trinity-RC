<?php
$browser = $user['browser'] != '' ? htmlspecialchars($user['browser'] ?? '') : $lang['userdetails_nobrowser'];
$HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_user_browser']}</td><td align='left'>{$browser}</td></tr>";
//==end
// End Class
// End File
