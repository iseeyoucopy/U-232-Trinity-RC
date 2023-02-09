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
//=== new pager... count is total number, perpage is duh!, url is whatever it's goint too \o <=== and that's me waving to pdq, just saying "hi there"
function pager_new($count, $perpage, $page, $url, $page_link = false)
{
    global $TRINITY20;
    $pages = ceil($count / $perpage);
    if ($pages * $perpage < $count) {
        ++$pages;
    }
    //=== lets make php happy
    $page_num = '';
    $page = (max ($page, 1));
    $page = (min ($page, $pages));
    //=== lets add the ... if too many pages
    if ($pages < 11) {
        for ($i = 1; $i <= $pages; ++$i) {
            $page_num .= ($i == $page ? ' ' . $i . ' ' : '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>');
        }
    } elseif ($page < 5 || $page > ($pages - 3)) {
        for ($i = 1; $i < 5; ++$i) {
            $page_num .= ($i == $page ? ' ' . $i . ' ' : '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>');
        }
        $page_num .= '<li class="ellipsis" aria-hidden="true"></li>';
        $math = round ($pages / 2);
        for ($i = ($math - 1); $i <= ($math + 1); ++$i) {
            $page_num .= '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>';
        }
        $page_num .= '<li class="ellipsis" aria-hidden="true"></li>';
        for ($i = ($pages - 2); $i <= $pages; ++$i) {
            $page_num .= ($i == $page ? ' ' . $i . ' ' : '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>');
        }
    } elseif ($page < ($pages - 2)) {
        for ($i = 1; $i < 5; ++$i) {
            $page_num .= '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>';
        }
        $page_num .= '<li class="ellipsis" aria-hidden="true"></li>';
        for ($i = ($page - 1); $i <= ($page + 1); ++$i) {
            $page_num .= ($i == $page ? ' ' . $i . ' ' : '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>');
        }
        $page_num .= '<li class="ellipsis" aria-hidden="true"></li>';
        for ($i = ($pages - 2); $i <= $pages; ++$i) {
            $page_num .= '<li><a href="' . $url . '&amp;page=' . $i . $page_link . '" aria-label="'. $i .'">' . $i . '</a></li>';
        }
    }
    $menu = '<ul class="pagination" role="navigation" aria-label="Pagination">' . 
    ($page == 1 ? '<li class="pagination-previous disabled">Previous <span class="show-for-sr">page</span></li>' : '<ul class="pagination"><li class="pagination-previous"><a href="'.$url.'&amp;page='.($page - 1).$page_link.'" aria-label="Previous page">Previous <span class="show-for-sr">page</span></a></li>') . $page_num . ($page == $pages ? '<li class="pagination-next disabled">Next <span class="show-for-sr">page</span></li>' : '<li class="pagination-next"><a href="'.$url.'&amp;page='.($page + 1).$page_link.'" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>') . '</ul>';
    $offset = ($page * $perpage) - $perpage;
    $LIMIT = ($count > 0 ? "LIMIT $offset,$perpage" : '');
    return [
        $menu,
        $LIMIT,
    ];
} //=== end pager function

