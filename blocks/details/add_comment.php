<?php
$HTMLOUT .= "<hr><br> 
<h2 class='text-center'>{$lang['details_comments']}<a href='details.php?id=$id'>".htmlsafechars($torrents["name"], ENT_QUOTES)."</a></h2>";
$HTMLOUT .= "<div class='container'><div class='row'>
<div class='col-md-2'></div>
<div class='col-md-8'>
<p><a name='startcomments'></a></p>
    <form name='comment' method='post' action='comment.php?action=add&amp;tid=$id'>
    <table align='center'>
    <tr>
    <td align='center'><b>{$lang['details_quick_comment']}</b></td></tr>
    <tr><td align='center'>
    <textarea name='body'></textarea>
    <input type='hidden' name='tid' value='".htmlsafechars($id)."'><br>
    <a href=\"javascript:SmileIT(':-)','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/smile1.gif' alt='Smile' title='Smile'></a> 
    <a href=\"javascript:SmileIT(':smile:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/smile2.gif' alt='Smiling' title='Smiling'></a> 
    <a href=\"javascript:SmileIT(':-D','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/grin.gif' alt='Grin' title='Grin'></a> 
    <a href=\"javascript:SmileIT(':lol:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/laugh.gif' alt='Laughing' title='Laughing'></a> 
    <a href=\"javascript:SmileIT(':w00t:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/w00t.gif' alt='W00t' title='W00t'></a> 
    <a href=\"javascript:SmileIT(':blum:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/blum.gif' alt='Rasp' title='Rasp'></a> 
    <a href=\"javascript:SmileIT(';-)','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/wink.gif' alt='Wink' title='Wink'></a> 
    <a href=\"javascript:SmileIT(':devil:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/devil.gif' alt='Devil' title='Devil'></a> 
    <a href=\"javascript:SmileIT(':yawn:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/yawn.gif' alt='Yawn' title='Yawn'></a> 
    <a href=\"javascript:SmileIT(':-/','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/confused.gif' alt='Confused' title='Confused'></a> 
    <a href=\"javascript:SmileIT(':o)','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/clown.gif' alt='Clown' title='Clown'></a> 
    <a href=\"javascript:SmileIT(':innocent:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/innocent.gif' alt='Innocent' title='innocent'></a> 
    <a href=\"javascript:SmileIT(':whistle:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/whistle.gif' alt='Whistle' title='Whistle'></a> 
    <a href=\"javascript:SmileIT(':unsure:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/unsure.gif' alt='Unsure' title='Unsure'></a> 
    <a href=\"javascript:SmileIT(':blush:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/blush.gif' alt='Blush' title='Blush'></a> 
    <a href=\"javascript:SmileIT(':hmm:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/hmm.gif' alt='Hmm' title='Hmm'></a> 
    <a href=\"javascript:SmileIT(':hmmm:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/hmmm.gif' alt='Hmmm' title='Hmmm'></a> 
    <a href=\"javascript:SmileIT(':huh:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/huh.gif' alt='Huh' title='Huh'></a> 
    <a href=\"javascript:SmileIT(':look:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/look.gif' alt='Look' title='Look'></a> 
    <a href=\"javascript:SmileIT(':rolleyes:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/rolleyes.gif' alt='Roll Eyes' title='Roll Eyes'></a> 
    <a href=\"javascript:SmileIT(':kiss:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/kiss.gif' alt='Kiss' title='Kiss'></a> 
    <a href=\"javascript:SmileIT(':blink:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/blink.gif' alt='Blink' title='Blink'></a> 
    <a href=\"javascript:SmileIT(':baby:','comment','body')\"><img border='0' src='{$TRINITY20['pic_base_url']}smilies/baby.gif' alt='Baby' title='Baby'></a><br>
    <input class='btn btn-primary' type='submit' value='Submit'></td></tr></table></form></div><!-- closing col md 8 --></div><!-- closing row --></div><!-- closing container -->";
?>
