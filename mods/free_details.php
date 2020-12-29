<?php
if (!defined('TBVERSION')) exit('No direct script access allowed');
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
/** free mod for TBDev 09 by pdq **/
/** free addon start **/
$is = $fl = '';
$isfree['yep'] = $isfree['expires'] = 0;
$freeimg = '<img src="' . $TRINITY20['pic_base_url'] . 'freedownload.gif" border="0" alt="' . $lang['mod_free_down'] .'" title="' . $lang['mod_free_down'] .'" />';
$silverimg = '<img src="' . $TRINITY20['pic_base_url'] . 'silverdownload.gif" border="0" alt="' . $lang['mod_free_tsilver'] .'" title="' . $lang['mod_free_tsilver'] .'" />';
if (isset($free)) {
    foreach ($free as $fl) {
        switch ($fl['modifier']) {
        case 1:
            $mode = $lang['mod_free_alltfree'];
            break;

        case 2:
            $mode = $lang['mod_free_dupload'];
            break;

        case 3:
            $mode = $lang['mod_free_tfree_dupload'];
            break;

        case 4:
            $mode = $lang['mod_free_all_tsilver'];
            break;

        default:
            $mode = 0;
        }
        $isfree['yep'] = ($fl['modifier'] != 0) && ($fl['expires'] > TIME_NOW || $fl['expires'] == 1);
        $isfree['expires'] = $fl['expires'];
    }
}
$HTMLOUT.= (($torrents['free'] != 0 || $torrents['silver'] != 0 || $CURUSER['free_switch'] != 0 || $isfree['yep']) ? '' . '' . $lang['mod_free_fstatus'] . ($torrents['free'] != 0 ? $freeimg . '' . $lang['mod_free_torfree'] . ($torrents['free'] > 1 ? $lang['mod_free_expires'] . get_date($torrents['free'], 'DATE') . ' 
(' . mkprettytime($torrents['free'] - TIME_NOW) . $lang['mod_free_togo'] . ')' : $lang['mod_free_unlimited']) . '' : '') : '') . ($torrents['silver'] != 0 ? '' . $silverimg . $lang['mod_free_torsilver'] . ($torrents['silver'] > 1 ? $lang['mod_free_expires'] . get_date($torrents['silver'], 'DATE') . mkprettytime($torrents['silver'] - TIME_NOW) . $lang['mod_free_togo'] : $lang['mod_free_unlimited'] . '' ) : '') . '' . ($CURUSER['free_switch'] != 0 ? $freeimg . $lang['mod_free_pstatus'] . ($CURUSER['free_switch'] > 1 ? $lang['mod_free_expires'] . get_date($CURUSER['free_switch'], 'DATE') . '' . mkprettytime($CURUSER['free_switch'] - TIME_NOW) . $lang['mod_free_togo'] : '' . $lang['mod_free_unlimited']. '') : '') . ($isfree['yep'] ? $freeimg . $mode . ($isfree['expires'] != 1 ? $lang['mod_free_expires'] . get_date($isfree['expires'], 'DATE') . mkprettytime($isfree['expires'] - TIME_NOW) . $lang['mod_free_togo'] : $lang['mod_free_unlimited']) : '') . (($torrents['free'] != 0 || $torrents['silver'] != 0 || $CURUSER['free_switch'] != 0 || $isfree['yep']) ? '' : '') . '';
?>
