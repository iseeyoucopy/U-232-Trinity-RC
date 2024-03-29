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
//== category
$categorie = genrelist();
foreach ($categorie as $key => $value) {
    $change[$value['id']] = [
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
    ];
}
$HTMLOUT .= "<div class='card'>
	<div class='card-divider'>
		<label for='checkbox_4' class='text-left'>{$lang['req_off_label']}</label>
	</div>
	<div class='card-section'>";
//== Requests
$HTMLOUT .= "<div class='card'>
	<div class='card-divider'>
		<label class='text-left'>{$lang['req_off_unfld']}</label>
	</div>
	<div class='card-section'>";
$requests = [];
if (($requests = $cache->get($cache_keys['requests'])) === false) {
    ($res = sql_query("SELECT r.id AS request_id, r.request_name, r.category, r.comments, r.added, r.vote_yes_count, r.vote_no_count, r.filled_by_user_id, u.id, u.username, u.warned, u.suspended, u.enabled, u.donor, u.class, u.leechwarn, u.chatpost, u.pirate, u.king FROM requests AS r LEFT JOIN users AS u ON r.requested_by_user_id = u.id WHERE filled_by_user_id = '' ORDER BY added DESC LIMIT {$TRINITY20['requests']['req_limit']}")) || sqlerr(__FILE__,
        __LINE__);
    if ($res->num_rows) {
        while ($request = $res->fetch_assoc()) {
            $requests = (array)$requests;
            $requests[] = $request;
        }
        $cache->set($cache_keys['requests'], $requests, $TRINITY20['expires']['req_limit']);
    }
}
if (!empty($requests)) {
    $HTMLOUT .= "<table class='table table-striped table-bordered'>";
    $HTMLOUT .= " <thead><tr>
                <th class='col-md-1 text-left'><b>{$lang['req_off_cat']}</b></th>
                <th class='col-md-5 text-left'><b>{$lang['req_off_tit']}</b></th>
	        <th class='col-md-1 text-center'><b>{$lang['req_off_add']}</b></th>
        	<th class='col-md-1 text-center'>{$lang['req_off_com']}</th>  
        	<th class='col-md-1 text-center'>{$lang['req_off_vot']}</th>
        	<th class='col-md-1 text-center'>{$lang['req_off_reqby']}</th>
<th class='col-md-1 text-center'>{$lang['req_off_fild']}</th>

</tr></thead>\n";
    if ($requests) {
        foreach ($requests as $requestarr) {
            if (is_array($requestarr)) {  
                $torrname = htmlsafechars($requestarr['request_name']);
                $requestarr['cat_name'] = htmlsafechars($change[$requestarr['category']]['name']);
                $requestarr['cat_pic'] = htmlsafechars($change[$requestarr['category']]['image']);
                $request_f = ($requestarr['filled_by_user_id'] > 0 ? '<a href="details.php?id='.(int)$requestarr['filled_torrent_id'].'" title='.$lang['req_off_goto'].'><span style="color: limegreen;font-weight: bold;">'.$lang['req_off_yes1'].'</span></a>' : '<span style="color: red;font-weight: bold;">'.$lang['req_off_no1'].'</span>');
                if (strlen($torrname) > 50) {
                    $torrname = substr($torrname, 0, 50)."...";
                }
                $HTMLOUT .= " <tbody><tr>
                <td class='text-center'><img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($requestarr["cat_pic"])."' alt='".htmlsafechars($requestarr["cat_name"])."' title='".htmlsafechars($requestarr["cat_name"])."'></td>
                    <td class='text-left'><a href=\"{$TRINITY20['baseurl']}/requests.php?action=request_details&amp;id=".(int)$requestarr['request_id']."&amp;hit=1\" >{$torrname}</a></td>
            <td class='text-center'>".get_date($requestarr['added'], 'LONG')."</td>
                <td class='text-center'>".number_format($requestarr['comments'])."</td>  
                <td class='text-center'>{$lang['req_off_yes2']}".number_format($requestarr['vote_yes_count'])."<br>
                            {$lang['req_off_no2']}".number_format($requestarr['vote_no_count'])."</td> 
                <td class='text-center'>".print_user_stuff($requestarr)."</td>
            <td class='text-center'>".$request_f."</td>
    </tr></tbody>";
            }
        }
        $HTMLOUT .= "</table></div>";
    } elseif (empty($requests)) {
        $HTMLOUT .= "<tbody><tr><td class='text-left' colspan='5'>{$lang['req_off_noreq']}</td></tr></tbody></table></div>";
    }
}
//==End
$HTMLOUT .= "</div>";
//== Offers
$HTMLOUT .= "<div class='card'>
	<div class='card-divider'>
		<label class='text-left'>{$lang['req_off_offers']}</label>
	</div>
	<div class='card-section'>";
$offers = [];
if (($offers = $cache->get($cache_keys['offers'])) === false) {
    ($res = sql_query("SELECT o.id AS offer_id, o.offer_name, o.category, o.comments, o.added, o.filled_torrent_id, o.vote_yes_count, o.vote_no_count, o.status, u.id, u.username, u.warned, u.suspended, u.enabled, u.donor, u.class, u.leechwarn, u.chatpost, u.pirate, u.king FROM offers AS o LEFT JOIN users AS u ON o.offered_by_user_id = u.id WHERE filled_torrent_id = 0 ORDER BY added DESC LIMIT {$TRINITY20['offers']['off_limit']}")) || sqlerr(__FILE__,
        __LINE__);
    if ($res->num_rows) {
        while ($offer = $res->fetch_assoc()) {
            $offers = (array)$offers;
            $offers[] = $offer;
        }
        $cache->update_row($cache_keys['offers'], $offers, $TRINITY20['expires']['off_limit']);
    }
}
if ((is_countable($offers) ? count($offers) : 0) > 0) {
    $HTMLOUT .= "<table class='table table-striped table-bordered'>";
    $HTMLOUT .= " <thead><tr>
                <th class='col-md-1 text-left'><b>{$lang['req_off_cat']}</b></th>
                <th class='col-md-5 text-left'><b>{$lang['req_off_tit']}</b></th>
	        <th class='col-md-1 text-center'><b>{$lang['req_off_add']}</b></th>
        	<th class='col-md-1 text-center'>{$lang['req_off_com']}</th>  
        	<th class='col-md-1 text-center'>{$lang['req_off_vot']}</th>
        	<th class='col-md-1 text-center'>{$lang['req_off_offd']}</th>
                <th class='col-md-1 text-center'>{$lang['req_off_stat']}</th>
                </tr></thead>\n";
    if ($offers) {
        foreach ($offers as $offerarr) {
            if (is_array($offerarr)) {
                $torrname = htmlsafechars($offerarr['offer_name']);
                $offerarr['cat_name'] = htmlsafechars($change[$offerarr['category']]['name']);
                $offerarr['cat_pic'] = htmlsafechars($change[$offerarr['category']]['image']);
                $status = ($offerarr['status'] == 'approved' ? '<span style="color: limegreen;font-weight: bold;">'.$lang['req_off_app'].'</span>' : ($offerarr['status'] == 'pending' ? '<span style="color: skyblue;font-weight: bold;">'.$lang['req_off_pend'].'</span>' : '<span style="color: red;font-weight: bold;">'.$lang['req_off_den'].'</span>'));
                if (strlen($torrname) > 50) {
                    $torrname = substr($torrname, 0, 50)."...";
                }
                $HTMLOUT .= " <tbody><tr>
                <td class='text-center'><img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($offerarr["cat_pic"])."' alt='".htmlsafechars($offerarr["cat_name"])."' title='".htmlsafechars($offerarr["cat_name"])."'></td>
                    <td class='text-left'><a href=\"{$TRINITY20['baseurl']}/offers.php?action=offer_details&amp;id=".(int)$offerarr['offer_id']."&amp;hit=1\" >{$torrname}</a></td>
            <td class='text-center'>".get_date($offerarr['added'], 'LONG')."</td>
                <td class='text-center'>".number_format($offerarr['comments'])."</td>  
                <td class='text-center'>{$lang['req_off_yes2']}".number_format($offerarr['vote_yes_count'])."<br>
                            {$lang['req_off_no2']}".number_format($offerarr['vote_no_count'])."</td> 
                <td class='text-center'>".print_user_stuff($offerarr)."</td>
            <td class='text-center'>".$status."</td>
    </tr></tbody>";
            }
        }
        $HTMLOUT .= "</table></div>";
    } elseif (empty($offers)) {
        $HTMLOUT .= "<tbody><tr><td class='text-left' colspan='5'>{$lang['req_off_nooff']}</td></tr></tbody></table></div>";
    }
}
//==End
$HTMLOUT .= "</div></div></div>";
// End Class
// End File
