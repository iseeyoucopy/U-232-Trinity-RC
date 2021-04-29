<?php
if ((in_array($torrents['category'], $TRINITY20['movie_cats'])) && $torrents['url'] != '') {
	
	                $IMDB = new IMDB($torrents['url']);
	                $description = $IMDB->getDescription();
                    $genre = $IMDB->getGenre();
                    $genre = explode(",", $genre);
                    $plot = $IMDB->getPlot();
                    $poster = $IMDB->getPoster("big", true);
                    $release_date = $IMDB->getReleaseDate();
                    $poster = "/".$poster;
                    $poster = "imgw.php".$poster;
                    $runtime = $IMDB->getRuntime();
                    $title = $IMDB->getTitle();
                    $year = $IMDB->getYear();
                    $rating = $IMDB->getRating();
                    $writer = $IMDB->getWriter();
					
					
					//themaster tmdb mod////
					
            
	
	        $O_url = trim($torrents['url']);
            $thenumbers = ltrim(strrchr($O_url, 'tt'), 'tt');
            $thenumbers = ($thenumbers[strlen($thenumbers) - 1] == "/" ? substr($thenumbers, 0, strlen($thenumbers) - 1) : $thenumbers);
            $thenumbers = preg_replace("[^A-Za-z0-9]", "", $thenumbers);
            $id_imdb = $thenumbers;
	
	      $movie_id=null;
		  //Get your api key from tmdb website//
	      $apikey = "516adf1e1567058f8ecbf30bf2eb9378";
		  
	    $imdbContent1 = file_get_contents("https://api.themoviedb.org/3/find/tt$id_imdb?api_key=$apikey&language=en&external_source=imdb_id");
        $imdbContent1 = json_decode($imdbContent1, true);
        $movie_id = $imdbContent1['movie_results']['0']["id"];
        
		
		
		if ($movie_id == true) {
			
            //trailer
           $json = file_get_contents("https://api.themoviedb.org/3/movie/$movie_id/videos?api_key=$apikey");
           $tmdb = json_decode($json, true);
           $yt = $tmdb['results']['0']["key"];
		   
		   if ($yt == true) {
            $autodata = "<iframe width='550' height='300' src='https://www.youtube.com/embed/" . $yt . "' frameborder='0' allowfullscreen></iframe>";
                            } 
			           else 
			                {
            $autodata = "";
                            }

		   //trailer
		   
		   
		   $imdbContent3 = file_get_contents("https://api.themoviedb.org/3/movie/$movie_id?api_key=$apikey&language=en&append_to_response=images&include_image_language=en,null");
           $imdbContent3 = json_decode($imdbContent3, true);
		   $movie_poster = $imdbContent3["poster_path"];
           $mposter = $imdbContent3["backdrop_path"];
           $movie_plot = $imdbContent3["overview"];
           
		   
		$curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.themoviedb.org/3/movie/$movie_id/credits?api_key=$apikey&language=en",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
                         ));

    $response2 = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $moviecast = json_decode($response2);
		   
		   
		   
		    if (empty($torrents['poster'])) {
        sql_query('UPDATE torrents SET poster = '.sqlesc('https://www.themoviedb.org/t/p/w600_and_h900_bestv2'.$movie_poster.'').' WHERE id = '.$torrents['id']) || sqlerr(__FILE__, __LINE__);
    }

    //==The torrent cache
    $cache->update_row($keys['torrent_details'].$torrents['id'], [
        'poster' => 'https://www.themoviedb.org/t/p/w600_and_h900_bestv2/'.$movie_poster.'',
    ], 0);

    $imdb = '';
    $imdb .= "<div class='card-section'>";
    $imdb .= "<h4><strong>".$title." (".$year.")</strong>
        <strong><i class='far fa-star dandelion'></i>".$rating."</strong>/10
        </h4>
    <hr class='margin-0'>";
    $imdb .= ''.$runtime.'<strong> | </strong>'.$release_date.'<strong> | </strong> ';
    foreach ($genre as $gen) {
        $imdb .= $gen.'';
    }
    $imdb .= "<hr class='margin-0'>
         
    <hr class='margin-0'> 
    ".strip_tags($movie_plot)."<br>
	".$autodata."
    <div class='grid-x grid-margin-x'>";
    
	
	$i=0;
	foreach($moviecast->cast as $cst) {
		$photo = $cst->profile_path == true ? "<img class='user-image' src=https://image.tmdb.org/t/p/w132_and_h132_bestv2/" . $cst->profile_path . " style='width:130px;height:130px'>" : "<img class='user-image' src=/imdb/cast/not-found.jpg style='width:130px;height:130px'>";
         if ($i++ > 5) break;
        $imdb .= "<div class='card-user-container'>
        <div class='cell auto'>
        <div class='card-user-avatar'>
            $photo<br><center><b>" . $cst->name . "</b><br>as<br>" . $cst->character . "</center></div>
            </div></div>";
    }
	
	
    $imdb .= "</div></div>";
    $HTMLOUT .= $imdb;		
		}
		elseif ($movie_id == false) 
		{
			
			//==The torrent cache
    $cache->update_row($keys['torrent_details'].$torrents['id'], [
        'poster' => $poster,
    ], 0);

    $imdb = '';
    $imdb .= "<div class='card-section'>";
    $imdb .= "<h4><strong>".$title." (".$year.")</strong>
        <strong><i class='far fa-star dandelion'></i>".$rating."</strong>/10
        </h4>
    <hr class='margin-0'>";
    $imdb .= ''.$runtime.'<strong> | </strong>'.$release_date.'<strong> | </strong> ';
    foreach ($genre as $gen) {
        $imdb .= $gen.'';
    }
    $imdb .= "<hr class='margin-0'>
         
    <hr class='margin-0'> 
    ".strip_tags($plot)."
    <div class='grid-x grid-margin-x'>";
    foreach($cast_images as $val) {
        $imdb .= "<div class='card-user-container'>
        <div class='cell auto'>
        <div class='card-user-avatar'>
            <img src='".$val."' class='user-image'></div>
            </div></div>";
    }
    $imdb .= "</div></div>";
    $HTMLOUT .= $imdb;
		}
} 
if (empty($imdb) && (in_array($torrents['category'], $TRINITY20['movie_cats']))){
    $HTMLOUT .= "No Imdb data";
}
