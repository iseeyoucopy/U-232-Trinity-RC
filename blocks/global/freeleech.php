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
//== Free torrents begin
if ($CURUSER && (isset($free) && is_array($free) && (count($free) >= 1))) {
    foreach ($free as $fl) {
        $mode = match ($fl['modifier']) {
            1 => 'All Torrents Free',
            2 => 'All Double Upload',
            3 => 'All Torrents Free and Double Upload',
            4 => 'All Torrents Silver',
            default => 0,
        };
        $htmlout .= ($fl['modifier'] != 0 && $fl['expires'] > TIME_NOW ? '
     <li>
     <a class="sa-tooltip" href="#"><b class="button small">'.$lang['gl_freeleech'].'</b>
	 <span class="custom info alert alert-info"><em>'.$fl['title'].'</em>
     '.$mode.'<br />
     '.$fl['message'].' '.$lang['gl_freeleech_sb'].' '.$fl['setby'].'<br />'.($fl['expires'] != 1 ? ''.$lang['gl_freeleech_u'].' '.get_date($fl['expires'],
                    'DATE').' ('.mkprettytime($fl['expires'] - TIME_NOW).' '.$lang['gl_freeleech_tg'].')' : '').'  
     </span></a></li>' : '');
    }
}
//=== free addon end
// End Class
// End File
