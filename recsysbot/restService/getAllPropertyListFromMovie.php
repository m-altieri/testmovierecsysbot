<?php
use GuzzleHttp\Client;
function getAllPropertyListFromMovie($movie_name) {
	
	// $client = new Client(['base_uri'=>'http://localhost:8080']);
	$client = new Client ( [ 
			'base_uri' => 'http://193.204.187.192:8080' 
	] );
	$movie_name = str_replace("🎥_", "", $movie_name);
	$stringGetRequest = '/movierecsysrestful/restService/movieDetail/getAllPropertyListFromMovie?movieName=' . urlencode ( $movie_name );
	$response = $client->request ( 'GET', $stringGetRequest );
	$bodyMsg = $response->getBody ()->getContents ();
	$data = json_decode ( $bodyMsg, true ); // true per trasformare in un array associativo
	
	file_put_contents ( "php://stderr", "http://193.204.187.192:8080" . $stringGetRequest . PHP_EOL );
	
	if ($data == "null") {
		file_put_contents ( "php://stderr", "ERROR - http://193.204.187.192:8080" . $stringGetRequest . PHP_EOL );
	}
	
	return $data;
}
